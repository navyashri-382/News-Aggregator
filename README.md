<<<<<<< HEAD
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
=======
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
>>>>>>> 1392513 (Initial commit: News-Aggregator)
