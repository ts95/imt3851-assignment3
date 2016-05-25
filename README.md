# imt3851-assignment3
Assignment 3 in IMT3851 (Web programming II)

# Architecture

## Server side
The server purely acts like an API for the client side and will not do any
rendering whatsoever (except for rendering the initial HTML that the client
will mount).

All requests are propagated to `index.php`, which contains a router. The router
will return an almost empty HTML file for all routes that don't start with /a/.
This route will be mounted by the JS on the client side, which will handle all the
rendering.

All routes that start with `/a/` will return JSON. These routes will be called
with AJAX from the client side to retrieve information from the database and
so on.

## Client side
[Vue.js](http://vuejs.org/) (Reactive Components for Modern Web Interfaces) is
used for all the rendering. Vue.js will communicate with the server via AJAX in
order to retrieve information from and provide information to the database.

[Bootstrap Native](https://github.com/thednp/bootstrap.native) is used instead of
plain bootstrap since this project doesn't have (nor need) jQuery as a dependency.

# Setup
Make sure that you have [composer](https://getcomposer.org/) and
[npm](https://www.npmjs.com/) installed.

## Install the dependencies
Run `composer install`.
Run `npm install`.

## Configuration
Change the contents of the `database.json` file to match your database. The app
will generate a database automatically upon the first request so it's not
necessary to do this manually.

## Development
Run `npm start` to start browsersync and gulp (which will bundle all the ES6 files
and compile them to ES5).
The PHP files have to be served by a webserver. This can be done by using
something like MAMP or simply by running the dev server that comes with
PHP: `php -S 0.0.0.0:8000`.

## Production
Run `npm run build` (builds the files without starting browsersync).
