# Book Library API

Проект представляет собой RESTful API для управления библиотекой книг с возможностью загрузки обложек, реализованный на Symfony с использованием Docker.

## Основные возможности

- Управление авторами (CRUD)
- Управление книгами (CRUD)
- Загрузка обложек для книг
- Валидация данных
- Обработка ошибок

## Технологии

- PHP 8.2
- Symfony 6.4
- MySQL 8.0
- Nginx
- Docker


## Docker Submodule

Проект использует Docker submodule для контейнеризации. Основные сервисы:

- `app`: Symfony приложение (PHP-FPM)
- `nginx`: Веб-сервер
- `db`: MySQL база данных
- `phpmyadmin`: Администрирование БД

### Конфигурация Docker

- Порт Nginx: 8080
- Порт phpMyAdmin: 8090
- Объемы данных сохраняются между перезапусками

## Загрузка обложек книг

Реализована возможность загрузки изображений обложек через API:

1. Поддерживаемые форматы: JPG, PNG
2. Изображения сохраняются в `/public/uploads/covers/`
3. Автоматическое создание уникального имени файла

Пример запроса:
POST /api/books
Content-Type: multipart/form-data

form-data:
- data: {"title":"Новая книга","authorId":1,...}
- coverImage: [файл изображения]

# Использование API
## Доступные эндпоинты:

GET /api/authors - Список авторов

POST /api/authors - Создать автора

GET /api/authors/{id} - Получить автора

PUT /api/authors/{id} - Обновить автора

DELETE /api/authors/{id} - Удалить автора

Книги
GET /api/books - Список книг

POST /api/books - Создать книгу (с обложкой)

GET /api/books/{id} - Получить книгу

PUT /api/books/{id} - Обновить книгу

DELETE /api/books/{id} - Удалить книгу

## Тестирование проведено в Postman
https://documenter.getpostman.com/view/43517403/2sB2qUnjZR

## Ссылка на мой сабмодуль 
git@github.com:DanyaErezer/docker-starter.git