<p align="center">
  <img src="https://raw.githubusercontent.com/benjamimWalker/fade/master/assets/logo.png" alt="Project logo" />
</p>
<p align="center">
  <a href="https://github.com/benjamimWalker/fade/actions/workflows/tests.yml">
    <img src="https://github.com/benjamimWalker/fade/actions/workflows/tests.yml/badge.svg" alt="Tests" />
  </a>
</p>


## Overview

Fade is a Laravel 12 project focused on secure, ephemeral note sharing. It allows users to create encrypted notes that self-destruct after a specified time or view limit. The system is performant, queue-aware, and ideal for scenarios requiring secure, temporary data sharing (e.g., sharing credentials, confidential snippets, or sensitive tokens).

Notes are encrypted with AES-256-CBC using Laravel’s Crypt facade (OpenSSL under the hood), ensuring that contents are never stored in plaintext. Every note can be accessed only through a unique URL and can be configured to expire based on time or view count.
## Technology

Key Technologies used:

* PHP
* Laravel 12
* MySQL
* Nginx
* Redis as queue driver for jobs
* Docker + Docker Compose
* PestPHP
* OpenSSL encryption via Laravel's Crypt facade

## Getting started

> [!IMPORTANT]  
> You must have Docker and Docker Compose installed on your machine.

* Clone the repository:
```sh
git clone https://github.com/benjamimWalker/fade.git
```

* Go to the project folder:
```sh
cd fade
```

* Prepare environment files:
```sh
cp .env.example .env
```

* Build the containers:
```sh
docker compose up -d
```

* Install composer dependencies:
```sh
docker compose exec app composer install
```

* Run the migrations:
```sh
docker compose exec app php artisan migrate
```

* You can now execute the tests:
```sh
docker compose exec app php artisan test
```

* And access the documentation at:
```sh
http://localhost/docs/api
```

## How to use

### 1 - Create a note

Send a `POST` request to `/api/notes` with a body and optional ttl or view_limit (Can also be done in the try of the docs):

![Content creation image](https://raw.githubusercontent.com/benjamimWalker/fade/master/assets/create_note.png)

### 2 - Access a note
#### Hit the `access_url` and the app will:

* Decrypt the note using AES-256-CBC

* Decrement the view limit (if set)

* Delete the note if it expired or view limit is exhausted

![Content creation image](https://raw.githubusercontent.com/benjamimWalker/fade/master/assets/retrieving_note.png)
![Content creation image](https://raw.githubusercontent.com/benjamimWalker/fade/master/assets/get_note_expired.png)

## Features

The main features of the application are:
- AES-256-CBC encryption, Redis queue driver, and smart rate limits.
- Background job processing for deleting expired notes.
- Notes can expire after a TTL or after being viewed n times.
- Full test coverage with PestPHP.
- API documentation
- Clean, maintainable Laravel 12 code with proper architecture.

[Benjamim] - [benjamim.sousamelo@gmail.com]<br>
Github: <a href="https://github.com/benjamimWalker">@benjamimWalker</a>

