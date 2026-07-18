# Website Downloader

A modern, responsive tool to download HTML pages or mirror complete static websites with a single click.

## Features
- **HTML Only Mode**: Quickly grabs just the `index.html` file.
- **Single Page Mode**: Downloads the page along with its assets (CSS, JS, images) and rewrites links for offline use.
- **Entire Website Mode**: Recursively mirrors a static website.
- Clean Dark UI with glassmorphism effects.
- Session history of recent downloads.
- Drag-and-drop URL support.
- Responsive design.

## Prerequisites

**Operating System:** Windows, Linux, or macOS.

> **Windows Users:** The backend relies on `wget` and `curl`. `curl` is usually pre-installed on Windows 10/11, but `wget` is not. 
> To use the "Single Page" and "Entire Website" modes on Windows, you must install `wget` and ensure it is added to your system's `PATH`. You can install it using a package manager like [Chocolatey](https://chocolatey.org/) (`choco install wget`) or [Scoop](https://scoop.sh/) (`scoop install wget`).

## Installation

1. Clone or download the repository.
2. Open your terminal in the project directory.
3. Run the following command to install the required Node.js dependencies:

```bash
npm install
```

## Running the Application

Start the server:

```bash
npm start
```

The application will run by default on `http://localhost:3000`.

## Architecture
- **Frontend**: Vanilla HTML5, CSS3, and JavaScript. No build steps required.
- **Backend**: Node.js and Express.
- **Downloading Logic**: Child processes orchestrating system-level `curl` and `wget` commands, subsequently zipped using `archiver` and piped directly to the client. Temporary directories are created with unique identifiers and automatically cleaned up upon request completion.

## Disclaimer
This tool is intended for downloading websites that you own or have permission to archive. Users are responsible for complying with applicable laws and website terms.
