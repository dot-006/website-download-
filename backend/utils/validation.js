/**
 * Validates a given URL to ensure it's a valid HTTP or HTTPS URL.
 * Also helps prevent command injection by ensuring the string doesn't contain shell metacharacters.
 */
function validateUrl(inputUrl) {
    if (!inputUrl) return false;
    
    try {
        const parsedUrl = new URL(inputUrl);
        if (parsedUrl.protocol !== 'http:' && parsedUrl.protocol !== 'https:') {
            return false;
        }

        // Additional basic check for shell injection characters just in case
        // The URL constructor usually handles this, but it's good for defense in depth
        const dangerousChars = /[&|;$`\\'"]/g;
        if (dangerousChars.test(inputUrl)) {
            return false;
        }

        return true;
    } catch (err) {
        return false;
    }
}

module.exports = {
    validateUrl
};
