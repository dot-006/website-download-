const { validateUrl } = require('../utils/validation');
const downloadService = require('../services/downloadService');
const fs = require('fs');
const path = require('path');

async function handleDownload(req, res) {
    const { url, mode } = req.body;

    if (!url || !validateUrl(url)) {
        return res.status(400).json({ error: 'Invalid URL provided. Only http and https are supported.' });
    }

    const validModes = ['html', 'page', 'website', 'inlined'];
    if (!validModes.includes(mode)) {
        return res.status(400).json({ error: 'Invalid mode. Must be one of html, page, website, or inlined.' });
    }

    try {
        // Send a custom header or just rely on the zip stream
        // Setting content disposition for a download
        const ext = mode === 'inlined' ? 'html' : 'zip';
        const filename = `download-${Date.now()}.${ext}`;
        res.attachment(filename);
        
        await downloadService.processDownload(url, mode, res);

    } catch (error) {
        console.error('Download error:', error);
        if (!res.headersSent) {
            res.status(500).json({ error: 'Failed to process the download: ' + error.message });
        } else {
            res.end(); // End the response if headers are already sent
        }
    }
}

module.exports = {
    handleDownload
};
