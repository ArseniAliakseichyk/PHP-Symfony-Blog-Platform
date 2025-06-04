# Symfony Blog Platform

This is a simple blog platform built with Symfony 7.2 and PostgreSQL.

## Features

### 🛡️ Authentication and Roles
- Register and login
- User roles: `ROLE_USER` and `ROLE_ADMIN`
- Voters to control access to edit/delete posts and comments

### ✍️ Content Management
- Create, edit, and delete blog posts
- Post categories: Technology, Lifestyle, News, Programming
- Comment system for posts
- Form validation

### 👑 Admin Panel
- View all users
- Delete users

### ⚙️ Console Commands
- Create a new user: `app:create-user`
- Change a user's password: `app:change-password`

## Tech Stack
- **Backend**: PHP 8.2, Symfony 7.2
- **Database**: PostgreSQL
- **Frontend**: Twig, Bootstrap 5
- **Extras**: Doctrine ORM, Symfony Forms, Security, Validator

## Installation

1. Clone the repository:
```bash
git clone https://github.com/your-username/symfony-blog-platform.git
cd symfony-blog-platform
```

2. Install dependencies:
```bash
composer install
```

3. Set up the database in the `.env` file:
```env
DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=16&charset=utf8"
```

4. Create the database and run migrations:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. (Optional) Load demo data:
```bash
php bin/console doctrine:fixtures:load
```

6. Start the server:
```bash
symfony server:start
```

Open in your browser: [http://localhost:8000](http://localhost:8000)

## Usage

### Console Commands
- Create a user:
```bash
php bin/console app:create-user email@example.com username password
```

- Change a user's password:
```bash
php bin/console app:change-password email@example.com new-password
```

### Roles
- **ROLE_USER**:
  - Create and edit own posts
  - Add comments
  - Edit/delete own comments

- **ROLE_ADMIN**:
  - All `ROLE_USER` actions
  - Access admin panel at `/admin/users`
  - Manage users
  - Edit/delete any posts and comments

---

Simple and functional blog platform for learning Symfony!
