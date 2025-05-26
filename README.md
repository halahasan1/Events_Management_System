#  Events Management API

A robust Laravel RESTful API for managing events, reservations, users, and media. Built with scalability, modularity, and clean code architecture in mind.

---

##  Features

- Event creation, updating, deletion
- Location and Event Type management
- Reservation handling
- User-based access control and permissions
- Image uploading (via polymorphic relationships)
- Modular Service & Resource layers
- Clean error logging & transaction safety

---

##  Requirements

- **PHP >= 8.1**
- **Laravel >= 10.x**
- **MySQL or MariaDB**
- **Composer**
- **Node.js + npm (for front-end assets, optional)**
- **Postman (for testing APIs)**

---

| Table Name                   | Description                                 |
| ---------------------------- | ------------------------------------------- |
| **users**                    | Stores user credentials and roles           |
| **events**                   | Event details (title, time, type, location) |
| **event\_types**             | Lookup table for event categories           |
| **locations**                | Event location details                      |
| **images**                   | Polymorphic images for events/locations     |
| **reservations**             | Stores event attendance reservations        |
| **password\_resets**         | Laravel's default password reset            |
| **personal\_access\_tokens** | Sanctum tokens                              |
| **roles**                    | User roles for permission system            |
| **permissions**              | Specific access permissions                 |
| **model\_has\_roles**        | Role assignments                            |
| **role\_has\_permissions**   | Role/permission mapping                     |

##  Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/halahasan1/Events_Management_System.git
cd events-api
````

### 2. Install Dependencies

```bash
composer install
```

### 3. Copy & Configure Environment

```bash
cp .env.example .env
```

Edit `.env` with your database and mail credentials:

```
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=
```

Then generate app key:

```bash
php artisan key:generate
```

### 4. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

### 5. Storage Linking (for Images)

```bash
php artisan storage:link
```

### 6. Serve the Application

```bash
php artisan serve
```

## Postman API Collection

To test all endpoints, import this Postman collection:

👉 **[Postman Collection Link](https://www.postman.com/research-geoscientist-78470583/workspace/my-workspace/collection/39063412-e3893aa1-ba58-4a61-8d50-e3a0d0aac684?action=share&creator=39063412)**

It includes:

* Auth (login/register)
* Event CRUD
* Location CRUD
* Reservation CRUD
* Event Types CRUD
* Users (list/delete)

