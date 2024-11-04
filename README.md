
# News Aggregator API
# Docker Setup Instructions:
1. Install Docker and Docker Compose : Download and install Docker from https://www.docker.com/products/docker-desktop/ and Docker Compose installation from https://docs.docker.com/compose/install/.
2. Copy .env.example to .env : cp .env.example .env
3. Edit the .env file to set up your database credentials :
   DB_CONNECTION=mysql
   DB_HOST=db      
   DB_PORT=3306
   DB_DATABASE=news_aggregator
   DB_USERNAME=root
   DB_PASSWORD=root
4. Command to Build and Start the Docker Containers : docker-compose up -d
5. To install the PHP Dependencies : docker-compose exec app composer install
6. To Generate Application Key : docker-compose exec app php artisan key:generate
7. Once the containers are running, the API should be accessible at : http://localhost:8000/api/.
8. Run migrations to set up the database schema : docker-compose exec app php artisan migrate
9. To stop all running containers, use: docker-compose down
10. If you make changes to the Dockerfile or docker-compose.yml - docker-compose up -d --build

## API Documentation
1. Access of API documentation is  at the following URL: http://localhost:8000/api/documentation.

### How to Access the Documentation
1. Command to generate the Swagger documentation : php artisan l5-swagger:generate
2.  Make sure your Laravel application is running.
3.  Open your web browser and navigate to the link above.

## Additional Notes on Implementation
1.User Authentication
- Laravel Sanctum: User registration and login endpoints are implemented using Laravel Sanctum, which provides a simple way to authenticate Single Page Applications (SPAs) and mobile applications using API tokens.
- Endpoints:
  - User Registration: Allows new users to create an account.
  - User Login: Authenticates users and provides them with a token for further requests.
  - User Logout: Allows users to log out, which invalidates their current authentication token.
  - Password Reset: Provides functionality for users to reset their password via email.

2. Article Management
- Fetching Articles: Endpoints have been created to fetch articles with support for pagination, allowing users to easily navigate through large sets of articles.
- Search Functionality: Implemented search capabilities that allow users to filter articles by:
  - Keyword: Search for articles containing specific words.
  - Date: Filter articles by publication date.
  - Category: Browse articles by their respective categories.
  - Source: View articles from specific news sources.
- Single Article Details: An endpoint is available to retrieve detailed information about a single article, enhancing user engagement with the content.

3. User Preferences
- User Preferences Endpoints: Users can manage their news preferences through dedicated endpoints:
  - Set Preferences: Users can specify their preferred news sources, categories, and authors.
  - Retrieve Preferences: Allows users to view their current preferences.
- Personalized Feed: An endpoint generates a personalized news feed based on user preferences, ensuring that users receive content that aligns with their interests.
  
4. Docker Implementation
- The application is containerized using Docker to ensure consistency across development environments. This setup includes a PHP application container, a MySQL database container, and any necessary services.
- A docker-compose.yml file adapts the setup, allowing easy management of services.
- The application can be easily built and deployed using the Docker command line, making it straightforward for other developers to set up the environment.
  
5.Testing
- Use Docker to run tests (docker-compose exec app php artisan test) to ensure compatibility.

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
>>>>>>> 1392513 (Initial commit: News-Aggregator)
