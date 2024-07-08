# Laravel Kanye Quotes API

This is a Laravel application that connects to the Kanye West quotes API, retrieves quotes, and provides endpoints to get and refresh quotes. 

The application uses an MVC structure and includes caching (via SQLite, which is the default for new Laravel installs) for performance improvements.

As caching is done via the Cache facade, it can be easily switched to an external datastore.

## Features

- REST API that shows 5 random Kanye West quotes
- Endpoint to refresh the quotes and fetch the next 5 random quotes
- Authentication using an API token (without any package)
- Feature and Unit tests for the implemented functionalities
- Uses caching for quick third-party API response

## Requirements

- PHP 8.2
- Composer
- Laravel 11
- [Kanye.rest](https://kanye.rest/) API

## Installation

### Step 1: Clone the repository

```bash
git clone https://github.com/marcoleary/laravel-kanye-quotes-api.git
cd laravel-kanye-quotes-api
```

### Step 2: Install dependencies

```bash
composer install
```

### Step 3: Copy the .env.example file to .env

```bash
cp .env.example .env
```
### Step 4: Add an APP_API_KEY token to the .env file

```bash
echo 'APP_API_KEY=<Your API key goes here>' >> .env
```

### Step 5: Generate an application key

```bash
php artisan key:generate
```

### Step 6: Initialise the cache

```bash
php artisan make:cache-table
php artisan migrate
```

### Step 7: Start the web server

```bash
php artisan serve
```

## Feature and Unit Testing

Feature and Unit tests can be run with `php artisan test`. 

## Testing the API

The following end points will be made available for GET requests: `/api/quotes`, `/api/refresh`

These can be tested with cURL, Postman or any other suitable application. Make sure you use the API key that you added to the .env in the `x-api-key` HTTP header. For best results, you should also use `Accept: application/json`. This will inform the API of the format you're expecting in return.

The following is an example request made by cURL:

```curl
curl -v -H 'x-api-key: b53ddfd16aa12ec13c02263935b74547' -H 'Accept: application/json' localhost:8000/api/quotes
* Host localhost:8000 was resolved.
* IPv6: ::1
* IPv4: 127.0.0.1
*   Trying [::1]:8000...
* connect to ::1 port 8000 from ::1 port 62296 failed: Connection refused
*   Trying 127.0.0.1:8000...
* Connected to localhost (127.0.0.1) port 8000
> GET /api/quotes HTTP/1.1
> Host: localhost:8000
> User-Agent: curl/8.6.0
> x-api-key: b53ddfd16aa12ec13c02263935b74547
> Accept: application/json
>
< HTTP/1.1 200 OK
< Host: localhost:8000
< Connection: close
< X-Powered-By: PHP/8.3.8
< Cache-Control: no-cache, private
< Date: Mon, 08 Jul 2024 22:34:30 GMT
< Content-Type: application/json
< Access-Control-Allow-Origin: *
<
* Closing connection
{"data":["I was just speaking with someone that told me their life story and they used to be homeless.","I'm the new Moses","The media tries to kill our heroes one at a time","Two years ago we had 50 million people subscribed to music streaming services around the world. Today we have 400 million.","We will be recognized"]}
```
