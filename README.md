# Seprate read and write opration in laravel 

Benefits of Splitting Read and Write Operations: 

1. Load Distribution:
2. Concurrency Handling
3. Horizontal Scaling
4. Elasticity
5. Failover and Redundancy
6. Failover and Redundancy
7. Geographically Distributed Read Replicas
8. Specialized Hardware
9. Offload Backups

# Setup project

copy docker folder as .docker 
`cp -r docker .dokcer`
change .env file in .docker. add your project name, and other ports 

COMPOSE_PROJECT_NAME=
HTTP_PORT=
HTTPS_PORT=
MYSQL_PORT=
MYSQL_PORT_REPLICA=

MYSQL_DATABASE=
MYSQL_ROOT_PASSWORD=
REDIS_PASSWORD=

# Setup db replica
in .docker -> docker-compose.yml add following lines. This will excute mysql service as replica

 #MySQL reblica Service <br>
  db_replica:
    image: mysql:5.7.32
    container_name: ${COMPOSE_PROJECT_NAME}_db_replica
    restart: unless-stopped
    tty: true
    volumes:
      - ./.mysql_replica:/var/lib/mysql
    ports:
      - "${MYSQL_PORT_REPLICA}:3306"
    environment:
      MYSQL_DATABASE: ${MYSQL_DATABASE}
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      SERVICE_TAGS: dev
      SERVICE_NAME: ${COMPOSE_PROJECT_NAME}_db_replica
    networks:
      - app-network

# setup .env varable

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=gfp
DB_USERNAME=root
DB_PASSWORD=root

DB_HOST_REPLICA=db_replica
DB_PORT_REPLICA=3306
DB_DATABASE_REPLICA=gfp
DB_USERNAME_REPLICA=root
DB_PASSWORD_REPLICA=root

# Setup database config

    'mysql' => [
        'driver' => 'mysql',
        'read' => [
            'host' => env('DB_HOST_REPLICA', '127.0.0.1'),
        ],
        'write' => [
            'host' => env('DB_HOST', '127.0.0.1'),
        ],
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],

    'mysql_replica' => [
        'driver' => 'mysql',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST_REPLICA', '127.0.0.1'),
        'port' => env('DB_PORT_REPLICA', '3306'),
        'database' => env('DB_DATABASE_REPLICA', 'forge'),
        'username' => env('DB_USERNAME_REPLICA', 'forge'),
        'password' => env('DB_PASSWORD_REPLICA', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],

# Run docker command

`cd .docker`
`docker compose build`
`docker compose up -d`

# login docker container 
`docker exec -u docker_app_user -it 'NAME OF COMPOSE PROJECT (set in .docker.env)'_php_service bash`
like
`docker exec -u docker_app_user -it laravelApp_php_service bash`

run artisan command in docker terminal
`php artisan key:generate`
`php artisan storage:link`
`php artisan migrate`
`php artisan optimize:clear`

After successfully build and migrate now you can able to read and write data from different table. 
