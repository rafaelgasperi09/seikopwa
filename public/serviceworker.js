var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/assets/css/style.css?t=123',
    '/assets/js/lib/jquery-3.4.1.min.js',
    '/assets/js/toast.js',
    '/assets/js/lib/popper.min.js',
    '/assets/js/lib/bootstrap.min.js',
    '/assets/js/plugins/owl-carousel/owl.carousel.min.js',
    '/assets/js/plugins/jquery-circle-progress/circle-progress.min.js',
    '/assets/js/base.js',
    '/images/icons/icon-72x72.png',
    '/images/icons/icon-96x96.png',
    '/images/icons/icon-128x128.png',
    '/images/icons/icon-144x144.png',
    '/images/icons/icon-152x152.png',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-384x384.png',
    '/images/icons/icon-512x512.png',
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            }),
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});

self.addEventListener('push', function (e) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        //notifications aren't supported or permission not granted!
        return;
    }

    if (e.data) {
        var msg = e.data.json();
        console.log(e.data.json());
        console.log("url data "+msg.data.url);
        e.waitUntil(self.registration.showNotification(msg.title, {
            body: msg.body,
            icon: msg.icon,
            actions: msg.actions,
            data:msg.data
        }));
    }
});

self.addEventListener('notificationclick', function(event) {
    console.log('On notification click: ', event);

    if (Notification.prototype.hasOwnProperty('data')) {
        console.log(event.notification.data);
        var url = event.notification.data.url;
        event.waitUntil(clients.openWindow(url));
    }
});
