const CACHE_NAME = "brkhub-v1";

const FILES = [
    "/",
    "/index.php",
    "/assets/css/style.css",
    "/assets/js/app.js"
];

self.addEventListener("install", event => {

    event.waitUntil(

        caches.open(CACHE_NAME)

        .then(cache => cache.addAll(FILES))

    );

});

self.addEventListener("fetch", event => {

    event.respondWith(

        caches.match(event.request)

        .then(response => {

            return response || fetch(event.request);

        })

    );

});