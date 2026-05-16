require("./bootstrap");

// resources/js/app.js

// 1. Register the Service Worker file
navigator.serviceWorker.register("/sw.js");

async function subscribeUserToPush() {
    const registration = await navigator.serviceWorker.ready;

    // 2. Request user authorization
    const permission = await Notification.requestPermission();
    if (permission !== "granted") return;

    // 3. Generate credentials using your server's VAPID Key
    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: "YOUR_VAPID_PUBLIC_KEY_FROM_ENV",
    });

    // 4. Send subscription array payload to the Laravel backend controller
    await fetch("/push-subscriptions", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
        body: JSON.stringify(subscription),
    });
}
