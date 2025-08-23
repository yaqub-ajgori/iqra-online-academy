// Register the service worker for PWA support
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
            .then(reg => {
                // Registration successful
            })
            .catch(err => {
                // Registration failed
                console.error('ServiceWorker registration failed:', err);
            });
    });
}
