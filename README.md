# Laravel API Project

This is a Laravel API project that includes user authentication, post management, and tagging functionality using Laravel Sanctum and SQLite.

## Requirements

- Laravel 8 or higher
- PHP 7.4 or higher
- Composer
- SQLite Database

## Installation

1. **Clone the Repository**  
   Clone the project from the repository to your local machine:
   ```bash
   git clone https://github.com/rashaatta/posts-api.git
   cd posts-api
   ```

2. **Install Dependencies**  
   Run the following command to install all required dependencies:
   ```bash
   composer install
   ```

3. **Setup SQLite Database**  
   Create an SQLite database file:
   ```bash
   touch database/database.sqlite
   ```
   Update your `.env` file to use the SQLite connection:
   ```
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database/database.sqlite
   ```

4. **Run Migrations**  
   Run the database migrations to set up the required tables:
   ```bash
   php artisan migrate
   ```

5. **Generate Application Key**  
   Generate an application key for your Laravel app:
   ```bash
   php artisan key:generate
   ```

## Features

1. **Authentication System with Sanctum**
    - `/register`: Register a new user with name, phone number, and password.
    - `/login`: Login with phone number and password.
    - Verification code sent (logged) for registration.
    - Only verified users can log in.

2. **Tags API**
    - Authenticated users can view, create, update, and delete tags.
    - Tags must have unique names.

3. **Posts API**
    - Authenticated users can create, update, delete (soft delete), restore, and view their posts.
    - Posts can include a title, body, cover image, pinned status, and tags (many-to-many relationship).
    - Pinned posts are prioritized in display.

4. **Soft Delete Management**
    - Soft-deleted posts older than 30 days are permanently deleted via a daily job.

5. **HTTP Job**
    - A scheduled job runs every 6 hours, making an HTTP request to [Random User API](https://randomuser.me/api/) and logging the response.

6. **Stats API**
    - `/stats`: Returns statistics, including:
        - Total number of users
        - Total number of posts
        - Number of users without any posts
    - Data is cached and updated when relevant models change.

## Running Jobs

To run the scheduled jobs, make sure the Laravel scheduler is set up correctly. You can set it up with the following cron job:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## API Endpoints

### Authentication
- `POST /register` - Register a new user.
- `POST /login` - Login with phone number and password.

### Tags
- `GET /tags` - View all tags.
- `POST /tags` - Create a new tag.
- `PUT /tags/{id}` - Update a tag.
- `DELETE /tags/{id}` - Delete a tag.

### Posts
- `GET /posts` - View all your posts.
- `POST /posts` - Create a new post.
- `GET /posts/{id}` - View a single post.
- `PUT /posts/{id}` - Update a post.
- `DELETE /posts/{id}` - Soft delete a post.
- `GET /posts/deleted` - View deleted posts.
- `PUT /posts/restore/{id}` - Restore a deleted post.

### Stats
- `GET /stats` - Get platform statistics.


- API endpoints are secured with JWT authentication.
## Postman Collection

To test the API endpoints, you can import the Postman collection provided in the project. Follow the steps below:

1. Download the Postman collection file: [Postman Collection](posts.postman_collection.json)
2. Open Postman, go to "Collections" on the left-hand side, and click "Import."
3. Upload the `posts.postman_collection.json` file
