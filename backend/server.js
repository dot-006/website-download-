const express = require('express');
const cors = require('cors');
const path = require('path');
const downloadRoutes = require('./routes/download');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors({
    exposedHeaders: ['Content-Disposition']
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, '../frontend')));

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
