const http = require('http');
const https = require('https');
const crypto = require('crypto');
const path = require('path');
const fs = require('fs');
const url = require('url');
const archiver = require('archiver');
const cheerio = require('cheerio');

const MAX_RUNTIME_MS = 5 * 60 * 1000; // 5 minutes
const MAX_SIZE_BYTES = 500 * 1024 * 1024; // 500 MB

/**
 * Makes an HTTP/HTTPS GET request and returns the response body as a Buffer.
 * Follows up to 5 redirects.
 */
function fetchUrl(targetUrl, options = {}) {
    return new Promise((resolve, reject) => {
        const maxRedirects = options.maxRedirects !== undefined ? options.maxRedirects : 5;
        const parsedUrl = new URL(targetUrl);
        const lib = parsedUrl.protocol === 'https:' ? https : http;

        const reqOptions = {
            hostname: parsedUrl.hostname,
            port: parsedUrl.port,
            path: parsedUrl.pathname + parsedUrl.search,
            method: 'GET',
            timeout: 30000,
            headers: {
                'User-Agent': 'Mozilla/5.0 (compatible; WebsiteDownloader/1.0)',
                'Accept': '*/*',
            }
        };

        const req = lib.request(reqOptions, (response) => {
            // Handle redirects
            if ([301, 302, 303, 307, 308].includes(response.statusCode)) {
                if (maxRedirects <= 0) return reject(new Error('Too many redirects'));
                const redirectUrl = response.headers.location;
                if (!redirectUrl) return reject(new Error('Redirect with no location header'));
                const resolvedRedirect = new URL(redirectUrl, targetUrl).href;
                return resolve(fetchUrl(resolvedRedirect, { ...options, maxRedirects: maxRedirects - 1 }));
            }

            if (response.statusCode === 403) {
                return reject(new Error(`HTTP Error 403 for ${targetUrl}. The website is blocking automated downloads (e.g., anti-bot protection or Cloudflare).`));
            }
            if (response.statusCode < 200 || response.statusCode >= 400) {
                return reject(new Error(`HTTP Error ${response.statusCode} for ${targetUrl}`));
            }

            const chunks = [];
            let totalSize = 0;
            response.on('data', (chunk) => {
                totalSize += chunk.length;
                if (totalSize > MAX_SIZE_BYTES) {
                    req.destroy();
                    return reject(new Error('Download too large (over 500MB limit)'));
                }
                chunks.push(chunk);
            });
            response.on('end', () => resolve({ 
                body: Buffer.concat(chunks), 
                contentType: response.headers['content-type'] || '',
                finalUrl: targetUrl
            }));
            response.on('error', reject);
        });

        req.on('error', reject);
        req.on('timeout', () => { req.destroy(); reject(new Error('Request timed out')); });
        req.end();
    });
}

/**
 * Main entry point — decides mode and orchestrates the download.
 */
async function processDownload(targetUrl, mode, res) {
    const downloadId = crypto.randomUUID();
    const tempDir = path.join(__dirname, '..', 'temp', downloadId);

    try {
        fs.mkdirSync(tempDir, { recursive: true });

        if (mode === 'html') {
            await downloadHtmlOnly(targetUrl, tempDir);
        } else if (mode === 'page') {
            await downloadSinglePage(targetUrl, tempDir);
        } else if (mode === 'website') {
            await downloadWebsite(targetUrl, tempDir);
        }

        await streamZip(tempDir, res);

    } finally {
        // Always clean up temp files
        try {
            if (fs.existsSync(tempDir)) {
                fs.rmSync(tempDir, { recursive: true, force: true });
            }
        } catch (cleanupError) {
            console.error('Cleanup error:', cleanupError.message);
        }
    }
}

/**
 * MODE: HTML Only — download only the HTML of the page.
 */
async function downloadHtmlOnly(targetUrl, tempDir) {
    const result = await fetchUrl(targetUrl);
    fs.writeFileSync(path.join(tempDir, 'index.html'), result.body);
}

/**
 * Sanitize a URL path into a safe file path component.
 */
function sanitizeFilename(str) {
    return str.replace(/[^a-zA-Z0-9.\-_/]/g, '_').replace(/^\//, '');
}

/**
 * Resolves a relative URL against a base URL, returns null if invalid or cross-origin (optionally).
 */
function resolveAsset(assetHref, pageUrl) {
    try {
        return new URL(assetHref, pageUrl).href;
    } catch {
        return null;
    }
}

/**
 * Download an asset (CSS, JS, image, font) and save it to the temp dir.
 * Returns the local relative path.
 */
async function downloadAsset(assetUrl, tempDir, downloaded) {
    if (!assetUrl || downloaded.has(assetUrl)) return downloaded.get(assetUrl);

    // Mark as in-progress to prevent loops
    downloaded.set(assetUrl, null);

    try {
        const result = await fetchUrl(assetUrl);
        const parsed = new URL(assetUrl);
        let filePath = sanitizeFilename(parsed.pathname);
        if (!filePath || filePath.endsWith('/')) {
            filePath = filePath + 'index.html';
        }
        // Add hostname to avoid conflicts
        const fullPath = path.join(tempDir, parsed.hostname, filePath);
        fs.mkdirSync(path.dirname(fullPath), { recursive: true });
        fs.writeFileSync(fullPath, result.body);
        const relPath = path.join(parsed.hostname, filePath);
        downloaded.set(assetUrl, relPath);
        return relPath;
    } catch (e) {
        console.warn(`Failed to download asset: ${assetUrl} - ${e.message}`);
        downloaded.set(assetUrl, null);
        return null;
    }
}

/**
 * MODE: Single Page — download HTML + all linked assets (CSS, JS, images) and rewrite links.
 */
async function downloadSinglePage(targetUrl, tempDir) {
    const result = await fetchUrl(targetUrl);
    const $ = cheerio.load(result.body.toString('utf-8'));
    const downloaded = new Map();

    const assetSelectors = [
        { selector: 'link[rel="stylesheet"]', attr: 'href' },
        { selector: 'script[src]', attr: 'src' },
        { selector: 'img', attr: 'src' },
        { selector: 'source', attr: 'src' },
        { selector: 'link[rel="icon"]', attr: 'href' },
        { selector: 'link[rel="shortcut icon"]', attr: 'href' },
    ];

    // Collect all asset URLs
    const assetPromises = [];
    const elements = [];

    for (const { selector, attr } of assetSelectors) {
        $(selector).each((_, el) => {
            const href = $(el).attr(attr);
            if (!href || href.startsWith('data:')) return;
            const absUrl = resolveAsset(href, targetUrl);
            if (!absUrl) return;
            elements.push({ el, attr, absUrl });
            assetPromises.push(downloadAsset(absUrl, tempDir, downloaded));
        });
    }

    await Promise.allSettled(assetPromises);

    // Rewrite attributes to local paths
    for (const { el, attr, absUrl } of elements) {
        const localPath = downloaded.get(absUrl);
        if (localPath) {
            $(el).attr(attr, localPath.replace(/\\/g, '/'));
        }
    }

    fs.writeFileSync(path.join(tempDir, 'index.html'), $.html());
}

/**
 * MODE: Entire Website — recursively crawl and download all pages.
 * Limited to same hostname, max 100 pages to prevent runaway downloads.
 */
async function downloadWebsite(startUrl, tempDir) {
    const parsedStart = new URL(startUrl);
    const baseHost = parsedStart.hostname;
    const toVisit = [startUrl];
    const visited = new Set();
    const MAX_PAGES = 100;

    while (toVisit.length > 0 && visited.size < MAX_PAGES) {
        const currentUrl = toVisit.shift();
        if (visited.has(currentUrl)) continue;
        visited.add(currentUrl);

        let result;
        try {
            result = await fetchUrl(currentUrl);
        } catch (e) {
            console.warn(`Skipping ${currentUrl}: ${e.message}`);
            continue;
        }

        // Save the file
        const parsed = new URL(currentUrl);
        let filePath = sanitizeFilename(parsed.pathname);
        if (!filePath || filePath.endsWith('/')) filePath += 'index.html';
        if (!path.extname(filePath)) filePath += '.html';
        const fullPath = path.join(tempDir, filePath);
        fs.mkdirSync(path.dirname(fullPath), { recursive: true });
        fs.writeFileSync(fullPath, result.body);

        // If HTML, parse and queue same-host links
        if (result.contentType.includes('text/html')) {
            const $ = cheerio.load(result.body.toString('utf-8'));
            $('a[href]').each((_, el) => {
                const href = $(el).attr('href');
                if (!href) return;
                try {
                    const absUrl = new URL(href, currentUrl).href;
                    const parsedAbs = new URL(absUrl);
                    if (parsedAbs.hostname === baseHost && !visited.has(absUrl) && !toVisit.includes(absUrl)) {
                        toVisit.push(absUrl);
                    }
                } catch {}
            });
        }
    }
}

/**
 * Compresses the tempDir into a ZIP and pipes it directly to the HTTP response.
 */
function streamZip(sourceDir, res) {
    return new Promise((resolve, reject) => {
        const archive = archiver('zip', { zlib: { level: 9 } });

        // 'finish' fires on express response when piping is done
        res.on('finish', resolve);
        res.on('error', reject);

        archive.on('warning', (err) => {
            if (err.code !== 'ENOENT') reject(err);
        });
        archive.on('error', reject);

        archive.pipe(res);
        archive.directory(sourceDir, false);
        archive.finalize();
    });
}

module.exports = { processDownload };
