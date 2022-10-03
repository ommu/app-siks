'use strict';

const version = new URL(location).searchParams.get('v');
console.log(version);

var cacheVersion = 'v1';
console.log(cacheVersion);
if(version)
	cacheVersion = version;
console.log(version);

importScripts('https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js'),

workbox.googleAnalytics.initialize(),
workbox.precaching.cleanupOutdatedCaches(),

workbox.routing.registerRoute(
	/(.*(?:googleapis|gstatic)\.com\/.*)|(.*\.(?:woff|woff2|ttf|eot)(\?.*)?$)/,
	new workbox.strategies.StaleWhileRevalidate({
		cacheName: 'google-fonts-cache-'+cacheVersion,
		maxAgeSeconds: 7 * 24 * 60 * 60
	}), "GET"),

workbox.routing.registerRoute(
	/\.js$/,
	new workbox.strategies.CacheFirst({
		cacheName: 'js-cache-'+cacheVersion,
		plugins: [new workbox.cacheableResponse.Plugin({
			statuses: [200],
			headers: {
				"Content-Type": "application/javascript"
			},
			maxAgeSeconds: 7 * 24 * 60 * 60
		})]
	}), "GET"),

workbox.routing.registerRoute(
	/\.css$/,
	new workbox.strategies.CacheFirst({
		cacheName: 'css-cache-'+cacheVersion,
		plugins: [new workbox.cacheableResponse.Plugin({
			statuses: [200],
			headers: {
				"Content-Type": "text/css"
			},
			maxAgeSeconds: 7 * 24 * 60 * 60
		})]
	}), "GET");

// self.addEventListener('install', function(event) {
// 	event.waitUntil(
// 		caches.keys().then((keyList) => {
// 			return Promise.all(
// 				keyList
// 				.map((key) => {
// 					return caches.delete(key);
// 				})
// 			);
// 		})
// 	);
// });

self.addEventListener('activate', function(event) {
	event.waitUntil(
		caches.keys().then((keyList) => {
			return Promise.all(
				keyList
				.map((key) => {
					caches.delete(key);
				})
			);
		})
	);
});