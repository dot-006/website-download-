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
