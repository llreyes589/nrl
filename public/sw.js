// public/sw.js

self.addEventListener("push", function (event) {
    if (!event.data) return;

    // Parse the payload delivered from App\Notifications\AccountAlert
    const payload = event.data.json();

    const options = {
        body: payload.body,
        icon: payload.icon || "/default-icon.png",
        data: payload.data, // Contains our back-link mapping (/dashboard)
    };

    event.waitUntil(self.registration.showNotification(payload.title, options));
});

// Route user to the destination path when they click the browser alert box
self.addEventListener("notificationclick", function (event) {
    event.notification.close();

    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(clients.openWindow(event.notification.data.url));
    }
});
