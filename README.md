# WebDev_Library

WebDev_Library is a PHP and MariaDB library web application created for the Web Development 2 module. It allows visitors to view recommended books, search the catalogue, filter books by category, create accounts, reserve books, and manage account details.

## Features

- Home page with featured recommendations for *The Da Vinci Code*, *Tara Road*, and *Shooting History*.
- User registration with profile details and password validation.
- Login and logout using PHP sessions and a `SessionID` cookie.
- Book catalogue browsing with:
  - Title and author search.
  - Category filtering.
  - Five-book pagination.
  - Availability and reservation status.
- Book reservations for logged-in users.
- Account area for:
  - Viewing personal details.
  - Updating profile information.
  - Viewing current reservations.
  - Returning reserved books.
  - Deleting an account.
- Responsive visual styling based on TUDublin-inspired fonts, colours, imagery, navigation bars, tables, forms, and recommendation cards.

## Technology stack

- **Backend:** PHP 8.2+
- **Database:** MariaDB/MySQL
- **Database access:** PHP `mysqli` extension with prepared statements in the main search, registration, reservation, and account flows.
- **Frontend:** HTML, CSS, and small inline JavaScript validation helpers.
- **Containerisation:** Docker and Docker Compose for local development and service orchestration.
- **Assets:** JPEG and PNG images stored in `media/`.

## Project structure

```text
.
├── README.md                  Project documentation
├── LICENSE                    MIT License
├── library.php                MariaDB connection configuration
├── library.sql                Database schema and sample data
├── Dockerfile                 PHP + Apache container image
├── docker-compose.yml         Web and database service orchestration
├── .dockerignore              Docker build exclusions
├── reserve.php                Creates a reservation for a book
├── returnBook.php             Removes a reservation and marks a book available
├── pages/
│   ├── Index.php              Home page and recommendations
│   ├── Browse.php             Search, category filtering, and pagination
│   ├── Login.php              User authentication
│   ├── Register.php           New account registration
│   ├── MyAccount.php          Details, updates, reservations, and deletion links
│   ├── Delete.php             Account deletion confirmation
│   └── Logout.php             Logout confirmation and session cleanup
├── libraryCSS/
│   ├── styles.css             Shared application styles
│   └── background.css         Shared background image styling
└── media/
    ├── TUDlibraryBackground.jpeg
    ├── accountIcon.png
    ├── headerIcon.png
    ├── icon.png
    ├── daVinciCode.jpg
    ├── shootingHistory.jpg
    └── taraRoad.jpg
```

## Database design

The application uses a database named `library`. The supplied `library.sql` dump creates four tables:

- `books` stores ISBNs, titles, authors, editions, publication years, categories, and reservation status.
- `category` stores the available catalogue categories, including Health, Business, Biography, Technology, Travel, Self-Help, Cookery, and Fiction.
- `reservations` links a user to a reserved book and records the reservation date.
- `users` stores account and contact details.

Foreign keys connect books to categories and reservations to both books and users. The SQL dump also includes sample books, users, categories, and a reservation so the application can be tested after setup.

## Setup

### 1. Requirements

Install or enable:

- Docker and Docker Compose
- Or, for local non-Docker use: PHP 8.2 or later, MariaDB or MySQL, and the PHP `mysqli` extension.
- A web server capable of executing PHP, such as Apache, or PHP's development server.

### 2. Get the project files and start Docker

Clone the repository, move into the project directory, and start the Docker services:

```bash
git clone https://github.com/DorianDzigumovic/WebDev_Library.git
cd WebDev_Library
docker compose up --build
```

The `git clone` command downloads the repository files to your computer. During `docker compose up --build`, the `Dockerfile` copies those files into the web container with:

```dockerfile
COPY . /var/www/html/
```

If the repository has already been cloned, run the following from its root directory instead:

```bash
docker compose up --build
```

The `--build` option rebuilds the PHP/Apache image and ensures the latest repository files are included. To run the containers in the background, use:

```bash
docker compose up --build -d
```

This starts:

- the PHP/Apache application on `http://localhost:8080`
- a MariaDB service named `db`
- a database called `library` with the schema loaded from `library.sql`

The application reads its database settings from environment variables in `docker-compose.yml`:

```text
DB_HOST=db
DB_NAME=library
DB_USER=library_user
DB_PASSWORD=library_password
```

Open the application at:

```text
http://localhost:8080/pages/Index.php
```

To stop the services, run:

```bash
docker compose down
```

To stop the services and remove the persisted MariaDB data as well, run:

```bash
docker compose down -v
```

### 3. Local database setup (without Docker)

Create the `library` database and import the supplied SQL dump. For example, from a MariaDB/MySQL command line:

```bash
mysql -u root -p -e "CREATE DATABASE library;"
mysql -u root -p library < library.sql
```

The included `library.php` connection file expects a local database with these settings:

```text
Host:     localhost
Database: library
User:     root
Password: empty
```

If your local database uses different credentials, update `library.php` before starting the application. Do not commit production credentials to the repository.

### 4. Start the application locally

From the repository root, start PHP's development server:

```bash
php -S localhost:8000
```

Then open the application home page at:

```text
http://localhost:8000/pages/Index.php
```

If the project is placed under an Apache document root, open the equivalent `/pages/Index.php` URL through that server instead.

## Typical user flow

1. Open `pages/Index.php` and select **Browse Books**.
2. Search by title or author, or select a category.
3. Log in or register if a book is available for reservation.
4. Select **Available** to reserve the book.
5. Open **My Account** and choose **My Reservations** to view or return the reservation.
6. Use **Logout** when finished.

## Notes for development

- Shared database connection setup is kept in `library.php` and included by the PHP pages and action scripts.
- The project is configured for Docker compatibility via `Dockerfile`, `docker-compose.yml`, and environment variables in `library.php`.
- The catalogue query in `pages/Browse.php` uses prepared statements for search, category, count, limit, and offset values.
- Reservation actions are handled by the root-level `reserve.php` and `returnBook.php` scripts, which update both `reservations` and the `books.Reserved` flag.
- The interface uses shared styles from `libraryCSS/styles.css` and the fixed background defined in `libraryCSS/background.css`.
- The repository does not currently include an automated test suite, dependency manifest, or build script.

## Security considerations

This project is intended as an educational application.

## Licence

This project is licensed under the [MIT License](LICENSE).

Copyright (c) 2026 Dorian Džigumović.
