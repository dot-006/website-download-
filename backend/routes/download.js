const express = require('express');
const router = express.Router();
const downloadController = require('../controllers/downloadController');

// POST /download
router.post('/', downloadController.handleDownload);

module.exports = router;
