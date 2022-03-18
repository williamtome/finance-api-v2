![Template](/public/img/thumb-finance-api.png?raw=true)

# Project summary

In this project was created a REST API with propose of manager the personal finances of the users using the programming language PHP and Laravel framework to build this API. It's working progress, but you can already to use for tests.

## Project features

Here is the Trello boards with the features implemented:

- [Board Sprint 1](https://trello.com/b/PGrRJkUx/challenge-backend-1)
- [Board Sprint 2](https://trello.com/b/NmrQ49bM/challenge-backend-2)
- [Board Sprint 3](https://trello.com/b/I5RRBmkT/challenge-backend-3)

## ✔️ Techs and Tools

- **Docker**
- **PHP 8**
- **Laravel 8**
- **Composer 2**
- **MySQL 8**
- **PHPStorm or Visual Studio Code**
- **PHP Best practices (PSR's and Clean Code)**

## 🔨 Instalation and configuration

1) Clone this repository and after move to the folder:

```
git clone https://github.com/williamtome/finance-api-v2.git
cd finance-api-v2
```

2) Get up the environment (obs.: you need the Docker installed in your operational system):
```
vendor/bin/sail up -d
```
3) Install the project and your dependencies:
```
vendor/bin/sail composer install
```
4) Copy `.env.example`, paste this file in root folder of project and rename file to `.env`

5) Generate project key:
```
vendor/bin/sail artisan key:generate
```

## Usage

Endpoint to use the API:
```
https://quiet-ocean-25469.herokuapp.com/
```
* You can consume this API with the REST HTTP Client as [Postman](https://www.postman.com/) or [Insomnia](https://insomnia.rest/) if you don't have a front-end application.

### Endpoints

* Revenues

| Verb | Path | Reason |
|------|------|--------|
| GET | `/revenue` | Show all the revenues |
| GET | `/revenue?descricao=xpto` | Show any revenue with a description |
| POST | `/revenue` | Create the revenue |
| GET | `/revenue/{id}` | Show the specific revenue |
| PUT | `/revenue/{id}` | Update an revenue |
| DELETE | `/revenue/{id}` | Delete the revenue |

* Expenses

| Verb | Path | Reason |
|------|------|--------|
| GET | `/expense` | Show all the expenses |
| POST | `/expense` | Create the expense |
| GET | `/expense/{id}` | Show the specific expense |
| PUT | `/expense/{id}` | Update an expense |
| DELETE | `/expense/{id}` | Delete the expense |
