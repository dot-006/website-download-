<?php
/*
Template Name: Website Downloader
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Downloader - Download HTML & Mirror Sites Free</title>
    <link rel="canonical" href="https://gethtml.wuaze.com/">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php wp_head(); ?>
    <style>
:root {
    /* Dark Mode (Default) - Vercel/Stripe Minimal Aesthetic */
    --bg-main: #000000;
    --bg-card: #0a0a0a;
    --bg-input: #111111;
    --border-color: #333333;
    --text-primary: #ededed;
    --text-secondary: #a1a1aa;
    
    --color-primary: #0070f3;
    --color-primary-hover: #3291ff;
    --color-success: #17c964;
    --color-error: #e00;
    --color-warning: #f5a623;
    
    --glass-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.39);
    --glass-backdrop: blur(0px); /* Removed heavy blur for crispness */
    
    --transition: all 0.3s ease;
}

body.light-mode {
    --bg-main: #ffffff;
    --bg-card: #fafafa;
    --bg-input: #ffffff;
    --border-color: #eaeaea;
    --text-primary: #000000;
    --text-secondary: #666666;
    
    --glass-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.1);
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--bg-main);
    color: var(--text-primary);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    position: relative;
    overflow-x: hidden;
    transition: background-color 0.4s ease, color 0.4s ease;
}

/* Background Shapes Removed for Minimalism */
.bg-shape {
    display: none;
}

.app-container {
    width: 100%;
    max-width: 900px;
    padding: 2rem 2rem 0 2rem;
    display: flex;
    flex-direction: column;
}

/* Header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
    font-weight: 700;
}

.logo i {
    color: var(--color-primary);
    font-size: 1.5rem;
}

.icon-btn {
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 1.25rem;
    cursor: pointer;
    transition: var(--transition);
    padding: 0.5rem;
    border-radius: 50%;
}

.icon-btn:hover {
    background: var(--border-color);
}

/* Hero */
.hero {
    text-align: center;
    margin-bottom: 3rem;
}

h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: linear-gradient(to right, #fff, var(--text-secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

body.light-mode h1 {
    background: linear-gradient(to right, #0f172a, var(--color-primary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero p {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.input-group {
    display: flex;
    align-items: center;
    background: var(--bg-input);
    border: 1px solid var(--border-color);
    border-radius: 8px; /* Sharper Vercel style */
    padding: 0.5rem 1rem;
    box-shadow: var(--glass-shadow);
    transition: var(--transition);
}

.input-group:focus-within {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

.input-icon {
    color: var(--text-secondary);
    margin-right: 0.75rem;
}

input[type="url"] {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 1.1rem;
    padding: 0.75rem 0;
    outline: none;
}

input[type="url"]::placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

.error-message {
    color: var(--color-error);
    font-size: 0.9rem;
    margin-top: 0.75rem;
    display: none;
}

/* Modes Grid */
.modes-section {
    margin-bottom: 3rem;
}

.modes-section h2 {
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.modes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.mode-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.mode-card:hover {
    transform: translateY(-5px);
    border-color: var(--color-primary);
}

.mode-card.active {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 1px var(--color-primary);
    background: rgba(59, 130, 246, 0.1);
}

.mode-card.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--color-primary);
}

.mode-icon {
    font-size: 2rem;
    color: var(--color-primary);
    margin-bottom: 1rem;
}

.mode-card h3 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.mode-card p {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.4;
}

.badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(34, 197, 94, 0.2);
    color: var(--color-success);
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
    font-weight: 600;
}

.badge-warning {
    background: rgba(245, 158, 11, 0.2);
    color: var(--color-warning);
}

/* Action Section */
.action-section {
    text-align: center;
    margin-bottom: 3rem;
}

.primary-btn {
    background: var(--color-primary);
    color: white;
    border: none;
    border-radius: 2rem;
    padding: 1rem 3rem;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: var(--transition);
    box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.4);
}

.primary-btn:hover {
    background: var(--color-primary-hover);
    transform: scale(1.05);
}

.primary-btn:disabled {
    background: var(--text-secondary);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Progress */
#progress-container {
    margin-top: 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
}

#progress-container.hidden {
    display: none;
}

.status-text {
    font-weight: 500;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 1rem;
}

body.light-mode .progress-bar {
    background: rgba(0, 0, 0, 0.1);
}

.progress-fill {
    height: 100%;
    width: 0%;
    background: var(--color-primary);
    border-radius: 4px;
    transition: width 0.3s ease;
    /* Indeterminate animation setup */
}

.progress-fill.loading {
    animation: indeterminate 2s infinite linear;
    width: 50%;
}

@keyframes indeterminate {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(200%); }
}

/* History Section */
.history-section {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 3rem;
}

.history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.text-btn {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 0.9rem;
}

.text-btn:hover {
    color: var(--color-primary);
}

.history-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.empty-history {
    color: var(--text-secondary);
    font-size: 0.9rem;
    text-align: center;
    padding: 1rem 0;
}

.history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 0.5rem;
    font-size: 0.9rem;
}

body.light-mode .history-item {
    background: rgba(0, 0, 0, 0.05);
}

.history-url {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 60%;
}

.history-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: var(--text-secondary);
}

.copy-btn {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    transition: var(--transition);
}

.copy-btn:hover {
    color: var(--text-primary);
}

/* Footer */
footer {
    margin-top: auto;
    text-align: center;
    border-top: 1px solid var(--border-color);
    padding-top: 1.5rem;
}

.footer-links {
    margin-bottom: 1rem;
    display: flex;
    justify-content: center;
    gap: 1.5rem;
}

.footer-links a {
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.9rem;
    transition: var(--transition);
}

.footer-links a:hover {
    color: var(--text-primary);
}

.disclaimer {
    font-size: 0.8rem;
    color: var(--text-secondary);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.5;
}

/* SEO Content Section */
.seo-content {
    max-width: 900px;
    margin: 0 auto 4rem auto;
    padding: 2rem;
    color: var(--text-secondary);
    line-height: 1.7;
    font-size: 1.05rem;
}

.seo-content h2, .seo-content h3 {
    color: var(--text-primary);
    margin-top: 2.5rem;
    margin-bottom: 1rem;
}

.seo-content h2 {
    font-size: 2rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.5rem;
}

.seo-content h3 {
    font-size: 1.4rem;
}

.seo-content p {
    margin-bottom: 1.25rem;
}

.seo-content ul {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.seo-content li {
    margin-bottom: 0.5rem;
}

.seo-content .comparison-table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    text-align: left;
}

.seo-content .comparison-table th, .seo-content .comparison-table td {
    border: 1px solid var(--border-color);
    padding: 1rem;
}

.seo-content .comparison-table th {
    background: var(--bg-card);
    color: var(--text-primary);
    font-weight: 600;
}

.seo-content a {
    color: var(--color-primary);
    text-decoration: none;
}

.seo-content a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    h1 { font-size: 2.25rem; }
    .primary-btn { width: 100%; justify-content: center; }
}

    </style>
</head>
<body class="dark-mode">

<!-- Background Elements -->
    <div class="bg-shape shape1"></div>
    <div class="bg-shape shape2"></div>

    <div class="app-container">
        <!-- Header -->
        <header>
            <div class="logo">
                <i class="fa-solid fa-cloud-arrow-down"></i>
                <span>Website Downloader</span>
            </div>
            <button id="theme-toggle" class="icon-btn" aria-label="Toggle light/dark mode">
                <i class="fa-solid fa-sun"></i>
            </button>
        </header>

        <!-- Main Content -->
        <main>
            <section class="hero">
                <h1>Archive The Web</h1>
                <p>Download HTML pages or mirror complete static websites with a single click.</p>

                <div class="input-group" id="drop-zone">
                    <i class="fa-solid fa-link input-icon"></i>
                    <input type="url" id="url-input" placeholder="https://example.com" required autocomplete="off">
                    <button id="paste-btn" class="icon-btn" title="Paste URL"><i class="fa-solid fa-paste"></i></button>
                </div>
                <div class="error-message" id="url-error">Please enter a valid HTTP/HTTPS URL.</div>
            </section>

            <section class="modes-section">
                <h2>Select Mode</h2>
                <div class="modes-grid">
                    <!-- HTML Only Card -->
                    <div class="mode-card active" data-mode="html">
                        <div class="mode-icon"><i class="fa-brands fa-html5"></i></div>
                        <h3>HTML Only</h3>
                        <p>Downloads just the bare HTML file.</p>
                        <span class="badge">Fast</span>
                    </div>

                    <!-- Single Page Card -->
                    <div class="mode-card" data-mode="page">
                        <div class="mode-icon"><i class="fa-regular fa-file-code"></i></div>
                        <h3>Single Page</h3>
                        <p>Downloads HTML, CSS, JS, and images. Rewrites links for offline use.</p>
                    </div>

                    <!-- Entire Website Card -->
                    <div class="mode-card" data-mode="website">
                        <div class="mode-icon"><i class="fa-solid fa-sitemap"></i></div>
                        <h3>Entire Website</h3>
                        <p>Mirrors the complete static website recursively.</p>
                        <span class="badge badge-warning">Slower</span>
                    </div>
                </div>
            </section>

            <section class="action-section">
                <button id="download-btn" class="primary-btn">
                    <span class="btn-text">Start Download</span>
                    <i class="fa-solid fa-download"></i>
                </button>

                <div id="progress-container" class="hidden">
                    <div class="status-text" id="status-text">Preparing...</div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                    <div class="progress-stats">
                        <span id="speed-text"></span>
                        <span id="time-text"></span>
                    </div>
                </div>
            </section>

            <!-- History Section -->
            <section class="history-section">
                <div class="history-header">
                    <h2>Recent Downloads</h2>
                    <button id="clear-history-btn" class="text-btn">Clear</button>
                </div>
                <ul class="history-list" id="history-list">
                    <!-- History items will be injected here -->
                    <li class="empty-history">No recent downloads in this session.</li>
                </ul>
            </section>
        </main>

        <section id="seo-content" class="seo-content">
            <h2>The Ultimate Website Downloader Online</h2>
            <p>Welcome to the most efficient and reliable <strong>website downloader online</strong>. Whether you are a
                web developer, a digital archivist, a student, or a QA engineer, our free tool allows you to easily
                <strong>download HTML pages</strong>, <strong>download complete webpages with CSS and
                    JavaScript</strong>, and even <strong>mirror entire static websites</strong> directly to your local
                machine.</p>
            <p>Our powerful website copier engine bridges the gap between complex command-line tools like
                <code>wget</code> or <code>HTTrack</code> and an intuitive, easy-to-use graphical interface. No software
                installation is required. Simply paste your URL, select your mode, and save the webpage offline in
                seconds.</p>

            <h3>Why You Need a Website Archiver</h3>
            <p>In today’s fast-paced digital ecosystem, having offline access to web content is crucial. Websites go
                offline, domains expire, and valuable research data can disappear overnight. Using an <strong>offline
                    website downloader</strong> acts as an insurance policy for your digital assets. It serves multiple
                purposes, from creating a robust <strong>website backup tool</strong> to enabling offline presentations
                and archiving competitor designs.</p>

            <h2>Features of Our Free Website Copier</h2>
            <p>Our downloader is packed with features designed to handle everything from a single HTML file to complex
                static websites. Here’s why professionals choose us:</p>
            <ul>
                <li><strong>Download HTML Only:</strong> Quickly extract the raw HTML structure of any page without
                    downloading heavy assets. Perfect for SEO professionals analyzing DOM structures.</li>
                <li><strong>Download Webpage with CSS and JS:</strong> Our "Single Page" mode fetches all linked
                    stylesheets, scripts, and images, automatically rewriting the internal links so the page looks and
                    functions perfectly when viewed offline.</li>
                <li><strong>Mirror Static Websites:</strong> Recursively crawl and download an entire static website.
                    Our <strong>website cloning tool</strong> maps the directory structure locally, making it an
                    excellent <strong>website archive</strong> solution.</li>
                <li><strong>Zero Installation:</strong> Operate entirely from your browser. We run the intensive
                    background processes, delivering a clean ZIP file to your device.</li>
                <li><strong>Smart Link Rewriting:</strong> Unlike basic browser "Save Page As" functions, our tool
                    intelligently converts absolute URLs to relative local paths.</li>
            </ul>

            <h3>Supported Download Modes</h3>
            <p>We offer three distinct modes tailored to different use cases. Understanding which to use will optimize
                your download times and storage requirements.</p>

            <h4>1. HTML Only Mode</h4>
            <p>This is the fastest option available. It initiates a rapid fetch of the target URL, ignoring all external
                resources like images, CSS, or JavaScript files. Use this mode when you solely need to <strong>download
                    HTML online</strong> for text extraction, metadata auditing, or quick code inspection.</p>

            <h4>2. Single Page Mode</h4>
            <p>When you need to <strong>download webpage resources</strong> fully, this mode is ideal. It grabs the HTML
                and recursively fetches every asset required to render the page visually. This includes stylesheets, web
                fonts, JavaScript bundles, and high-resolution images. It’s perfect for web designers wanting to inspect
                a competitor's layout offline.</p>

            <h4>3. Entire Website Mode</h4>
            <p>This is a complete <strong>static website mirror</strong>. The tool starts at your provided URL and
                follows every internal link belonging to the same domain, downloading all interconnected pages and
                assets. It preserves the site's folder hierarchy, allowing you to browse the site offline exactly as it
                appears online.</p>

            <h2>Common Use Cases: Who is this Tool For?</h2>
            <p>Our platform is trusted by a wide variety of professionals across the tech and digital marketing sectors.
            </p>
            <ul>
                <li><strong>Web Developers & Designers:</strong> Download inspiring landing pages to analyze CSS grid
                    structures, animation logic, and responsive breakpoints offline.</li>
                <li><strong>SEO Professionals:</strong> Download HTML to inspect semantic structures, heading
                    hierarchies, schema markup, and keyword density without triggering analytics trackers.</li>
                <li><strong>QA Engineers:</strong> Capture a snapshot of a staging environment or production page to
                    document bugs or UI inconsistencies.</li>
                <li><strong>Students & Researchers:</strong> Save essential articles, research papers, and educational
                    materials for offline study, ensuring access even without an internet connection.</li>
                <li><strong>Digital Archivists:</strong> Preserve legacy websites before they are shut down,
                    contributing to historical web archives.</li>
            </ul>

            <h2>Website Downloader vs Browser "Save Page As" vs HTTrack</h2>
            <p>You might wonder why you should use a dedicated <strong>website downloader online</strong> rather than
                built-in browser features or legacy desktop software. Here is a breakdown:</p>
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>Our Downloader</th>
                        <th>Browser "Save Page"</th>
                        <th>wget / HTTrack</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Ease of Use</strong></td>
                        <td>High (One-click UI)</td>
                        <td>High</td>
                        <td>Low (Command-line / Dated UI)</td>
                    </tr>
                    <tr>
                        <td><strong>Link Rewriting</strong></td>
                        <td>Yes (Intelligent relative paths)</td>
                        <td>Poor (Often breaks assets)</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td><strong>Recursive Mirroring</strong></td>
                        <td>Yes (Entire site mode)</td>
                        <td>No (Single page only)</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td><strong>Installation Required</strong></td>
                        <td>No (Fully Online)</td>
                        <td>No</td>
                        <td>Yes (Local installation)</td>
                    </tr>
                    <tr>
                        <td><strong>Output Format</strong></td>
                        <td>Clean ZIP archive</td>
                        <td>Messy folder + HTML file</td>
                        <td>Local directory tree</td>
                    </tr>
                </tbody>
            </table>

            <h2>Best Practices for Archiving Websites</h2>
            <p>To get the best results when you <strong>download complete websites</strong>, follow these expert best
                practices:</p>
            <ul>
                <li><strong>Check robots.txt:</strong> Respect the website owner's crawling guidelines. If a directory
                    is disallowed, it is best practice not to aggressively mirror it.</li>
                <li><strong>Start at the Root:</strong> When mirroring an entire site, always start at the homepage
                    (e.g., <code>https://example.com/</code>) to ensure the crawler discovers all interconnected
                    internal links.</li>
                <li><strong>Be Mindful of Dynamic Content:</strong> Modern Single Page Applications (SPAs) built with
                    React or Angular heavily rely on client-side rendering. While our tool downloads the compiled JS,
                    the offline functionality may be limited if the app requires backend API connections.</li>
            </ul>

            <h2>Limitations & Technical Constraints</h2>
            <p>While our tool is highly capable, transparency regarding its limitations ensures a better user
                experience. Please be aware of the following:</p>
            <ul>
                <li><strong>Anti-Bot Protection (Cloudflare):</strong> Websites utilizing aggressive DDoS protection or
                    CAPTCHAs (like Cloudflare's "Under Attack" mode) will block automated downloaders, returning a 403
                    Forbidden error.</li>
                <li><strong>Login-Protected Pages:</strong> The downloader operates anonymously and does not share your
                    browser's session cookies. It cannot download pages behind a login wall, paywall, or intranet
                    authentication.</li>
                <li><strong>Copyright & Intellectual Property:</strong> This tool must only be used to download websites
                    you own or have explicit permission to archive. Do not use this service to clone copyrighted
                    material or duplicate content for malicious purposes.</li>
            </ul>

            <h2>Frequently Asked Questions (FAQ)</h2>

            <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 itemprop="name">How do I download a website to view offline?</h3>
                <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <p itemprop="text">Simply paste the URL of the website into our input field above, select the
                        "Entire Website" mode, and click "Start Download". We will compress the site into a ZIP file.
                        Once downloaded, extract the ZIP and open the `index.html` file in your browser to view it
                        offline.</p>
                </div>
            </div>

            <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 itemprop="name">Can I download HTML, CSS, and JS together?</h3>
                <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <p itemprop="text">Yes. By using the "Single Page" mode, our tool automatically fetches the HTML
                        document along with all referenced CSS stylesheets and JavaScript files, rewriting the links to
                        ensure they load properly from your local drive.</p>
                </div>
            </div>

            <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 itemprop="name">Is this website downloader free?</h3>
                <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <p itemprop="text">Absolutely. Our core downloading features, including HTML extraction and static
                        site mirroring, are 100% free to use directly from your browser.</p>
                </div>
            </div>

            <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 itemprop="name">Why did my download fail with a 403 error?</h3>
                <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <p itemprop="text">A 403 error typically indicates that the target website is utilizing anti-bot
                        protection mechanisms (such as Cloudflare). These security layers actively block automated
                        scripts and downloaders from accessing their content.</p>
                </div>
            </div>

            <h2>Ready to Archive the Web?</h2>
            <p>Stop relying on messy browser saves or complicated command-line utilities. Experience the fastest,
                cleanest way to <strong>save websites offline</strong>. Scroll up to the top of the page, paste your
                URL, and let our tool handle the heavy lifting for you.</p>
        </section>


        <!-- Footer -->
        <footer>
            <div class="footer-links">
                <a href="#">About</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
            <p class="disclaimer">
                <strong>Disclaimer:</strong> This tool is intended for downloading websites that you own or have
                permission to archive. Users are responsible for complying with applicable laws and website terms.
            </p>
        </footer>
    </div>

    <script src="script.js"></script>

<script>
// Set this to your Render backend URL when deploying the frontend separately!
const API_BASE_URL = 'https://website-download.onrender.com';

document.addEventListener('DOMContentLoaded', () => {
    // Theme Toggle
    const themeToggleBtn = document.getElementById('theme-toggle');
    const body = document.body;

    // Load theme from localStorage
    if (localStorage.getItem('theme') === 'light') {
        body.classList.remove('dark-mode');
        body.classList.add('light-mode');
        themeToggleBtn.innerHTML = '<i class="fa-solid fa-moon"></i>';
    }

    themeToggleBtn.addEventListener('click', () => {
        if (body.classList.contains('light-mode')) {
            body.classList.remove('light-mode');
            body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
            themeToggleBtn.innerHTML = '<i class="fa-solid fa-sun"></i>';
        } else {
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
            localStorage.setItem('theme', 'light');
            themeToggleBtn.innerHTML = '<i class="fa-solid fa-moon"></i>';
        }
    });

    // Mode Selection
    const modeCards = document.querySelectorAll('.mode-card');
    let currentMode = 'html';

    modeCards.forEach(card => {
        card.addEventListener('click', () => {
            modeCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            currentMode = card.getAttribute('data-mode');
        });
        
        // Keyboard accessibility for cards
        card.setAttribute('tabindex', '0');
        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                card.click();
            }
        });
    });

    // Paste Button
    const pasteBtn = document.getElementById('paste-btn');
    const urlInput = document.getElementById('url-input');

    pasteBtn.addEventListener('click', async () => {
        try {
            const text = await navigator.clipboard.readText();
            urlInput.value = text;
        } catch (err) {
            console.error('Failed to read clipboard contents: ', err);
        }
    });

    // Download Button
    const downloadBtn = document.getElementById('download-btn');
    const urlError = document.getElementById('url-error');
    const progressContainer = document.getElementById('progress-container');
    const statusText = document.getElementById('status-text');
    const progressFill = document.getElementById('progress-fill');

    function isValidUrl(string) {
        try {
            const url = new URL(string);
            return url.protocol === "http:" || url.protocol === "https:";
        } catch (_) {
            return false;  
        }
    }

    downloadBtn.addEventListener('click', async () => {
        const url = urlInput.value.trim();

        if (!url || !isValidUrl(url)) {
            urlError.style.display = 'block';
            return;
        }
        urlError.style.display = 'none';

        // Start download process UI
        downloadBtn.disabled = true;
        progressContainer.classList.remove('hidden');
        statusText.innerText = 'Downloading and Compressing...';
        progressFill.classList.add('loading');
        
        try {
            const response = await fetch(`${API_BASE_URL}/download`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ url, mode: currentMode })
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.error || `Server responded with ${response.status}`);
            }

            // Extract filename from headers if possible
            let filename = `download-${Date.now()}.zip`;
            const disposition = response.headers.get('content-disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                const matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) { 
                    filename = matches[1].replace(/['"]/g, '');
                }
            } else if (currentMode === 'html') {
                // If it's just HTML, maybe it's returning HTML? 
                // But our backend always returns a zip according to the spec, to keep things consistent.
                filename = `download-${Date.now()}.zip`;
            }

            const blob = await response.blob();
            const downloadUrl = window.URL.createObjectURL(blob);
            
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = downloadUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            
            setTimeout(() => {
                a.remove();
                window.URL.revokeObjectURL(downloadUrl);
            }, 15000); // Wait 15 seconds before cleanup

            // Add to history
            addToHistory(url, currentMode);
            statusText.innerText = 'Ready!';
        } catch (error) {
            console.error('Download error:', error);
            statusText.innerText = `Error: ${error.message}`;
            statusText.style.color = 'var(--color-error)';
        } finally {
            downloadBtn.disabled = false;
            progressFill.classList.remove('loading');
            progressFill.style.width = '100%';
            
            setTimeout(() => {
                if (statusText.innerText === 'Ready!') {
                    progressContainer.classList.add('hidden');
                    progressFill.style.width = '0%';
                }
            }, 3000);
        }
    });

    // Drag and Drop URL Support
    const dropZone = document.getElementById('drop-zone');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'var(--color-primary)';
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'var(--border-color)';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'var(--border-color)';
        const text = e.dataTransfer.getData('text');
        if (text) {
            urlInput.value = text;
        }
    });

    // Keyboard Shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + Enter to trigger download
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            if (!downloadBtn.disabled) {
                downloadBtn.click();
            }
        }
    });

    // History Logic
    const historyList = document.getElementById('history-list');
    const clearHistoryBtn = document.getElementById('clear-history-btn');
    let sessionHistory = [];

    function renderHistory() {
        historyList.innerHTML = '';
        if (sessionHistory.length === 0) {
            historyList.innerHTML = '<li class="empty-history">No recent downloads in this session.</li>';
            return;
        }

        sessionHistory.forEach((item, index) => {
            const li = document.createElement('li');
            li.className = 'history-item';
            
            const urlSpan = document.createElement('span');
            urlSpan.className = 'history-url';
            urlSpan.textContent = item.url;
            urlSpan.title = item.url;

            const metaDiv = document.createElement('div');
            metaDiv.className = 'history-meta';
            
            const modeSpan = document.createElement('span');
            modeSpan.textContent = item.mode.toUpperCase();
            
            const copyBtn = document.createElement('button');
            copyBtn.className = 'copy-btn';
            copyBtn.title = 'Copy Original URL';
            copyBtn.innerHTML = '<i class="fa-regular fa-copy"></i>';
            copyBtn.addEventListener('click', () => {
                navigator.clipboard.writeText(item.url);
                copyBtn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fa-regular fa-copy"></i>';
                }, 2000);
            });

            metaDiv.appendChild(modeSpan);
            metaDiv.appendChild(copyBtn);

            li.appendChild(urlSpan);
            li.appendChild(metaDiv);
            historyList.appendChild(li);
        });
    }

    function addToHistory(url, mode) {
        sessionHistory.unshift({ url, mode, timestamp: Date.now() });
        // Keep only last 5 items
        if (sessionHistory.length > 5) {
            sessionHistory.pop();
        }
        renderHistory();
    }

    clearHistoryBtn.addEventListener('click', () => {
        sessionHistory = [];
        renderHistory();
    });
});

</script>
<?php wp_footer(); ?>
</body>
</html>
