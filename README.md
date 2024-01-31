# File Verification API
### Introduction
This repository houses the implementation of a File Verification API. The API is built using Laravel and allows authenticated users to send a JSON file and receive a verification result as a response. Please refer to [this documentation](https://accredify.notion.site/Technical-Assessment-for-Software-Engineer-de808af21ca249ba8f4b2d8f1aaf2a66) for more information about the requirements of the API.

### Features
- Authentication system for users.
- Endpoint for verifying uploaded files with multiple verification logics. 
  - valid issuer
  - valid recipient
  - valid signature
- Scalable architecture to facilitate easy addition of new verification logics.

### Setup Guide
#### Pre-requisites
- git
- docker
  
#### Steps
1. Clone [this repository](https://github.com/SarahTeoh/file-verification-api).
    ```
    $ git clone https://github.com/SarahTeoh/file-verification-api.git
    ```
2. Navigate to the project directory
    ```
    $ cd file-verification-api
    ```
3. Create `.env` file
    ```
    $ cp .env.example .env
    ```
4. Update `.env` file content 
   * update the environment variables such as database credentials and port number.
   * the default database settings are as follows:
    ```
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=laravel
    ```
   * you may need to change the port number `3306` if any port number conflicts occur in your environment. In such case, you should also change the [port binding in docker-compose.yaml](https://github.com/SarahTeoh/file-verification-api/blob/main/docker-compose.yml#L35) accordingly.
5. Create the `.env.testing` file for testing environment
   ```
   $ cp .env .env.testing
   ```
6. Update the `.env.testing` file
    ```
    DB_CONNECTION=sqlite
    DB_DATABASE=:memory:
    ```
7. Build and run the application
    ```
    $ docker-compose up -d --build // build the docker containers
    $ docker-compose exec app bash // access the docker container
    $ php artisan serve
    ```
    At this point, you should see something like this in your terminal:
    ```
    INFO  Server running on [http://127.0.0.1:8080]. 
    ```
    This means that application server is running.

### Usage
1. access the docker container
  ```
  $ docker-compose exec app bash
  ```
2. Create user using artisan command
  ```
  $ php artisan users:create
  ```
  Follows the instructions. For example:
    ```
    root@575cd53c4ac7:/var/www/html# php artisan users:create

    Name of the new user:
    > user

    Email of the new user:
    > user@example.com

    Password of the new user:
    > ********

    User user@example.com created successfully
    ```

3. Access the API documentation `http://127.0.0.1:8080/api/v1/docs`
4. Connect to the API using POSTMAN as documented in the documentation

### API Endpoints
| HTTP Verbs | Endpoints | Action |
| --- | --- | --- |
| POST | /api/v1/login | To login and get API token |
| POST | /api/v1/verify | To verify a file |

Note: There is no `/signup` endpoint to prevent unauthorized signups. The first user can be created using command-line tools.

### Technologies Used
* [Laravel](https://laravel.com/) PHP Framework for web application
* [Pest](https://pestphp.com/) PHP testing framework
* [MySQL](https://www.mysql.com/) Databse
* [SQLite](https://www.sqlite.org/index.html) Database for testing.
* [Docker](https://www.docker.com/) This makes collaborative development easy.
* [Scribe](https://scribe.knuckles.wtf/laravel/) This helps generate API documentation for humans from Laravel codebase.