const express = require('express');
const cors = require('cors');
const path = require('path');
const downloadRoutes = require('./routes/download');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
const allowedOrigins = [
    'https://gethtml.wuaze.com',
    'http://gethtml.wuaze.com',
    'http://localhost:3000',
    'http://localhost:8080',
];
app.use(cors({
    origin: function (origin, callback) {
        // Allow requests with no origin (e.g. curl, Render health checks)
        if (!origin) return callback(null, true);
        if (allowedOrigins.includes(origin)) return callback(null, true);
        return callback(new Error(`CORS: Origin ${origin} not allowed`));
    },
    exposedHeaders: ['Content-Disposition']
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Simple health check route instead of serving the frontend
app.get('/', (req, res) => {
    res.json({ status: 'Website Downloader API is running' });
});

// Ensure dynamic directories exist
const tempDir = path.join(__dirname, 'temp');
const downloadsDir = path.join(__dirname, 'downloads');
if (!require('fs').existsSync(tempDir)) require('fs').mkdirSync(tempDir, { recursive: true });
if (!require('fs').existsSync(downloadsDir)) require('fs').mkdirSync(downloadsDir, { recursive: true });

// Routes
app.use('/download', downloadRoutes);

// Error Handling Middleware
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({ error: 'Internal server error' });
});

// Start Server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
