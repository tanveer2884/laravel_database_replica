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

COMPOSE_PROJECT_NAME= <br>
HTTP_PORT= <br>
HTTPS_PORT= <br>
MYSQL_PORT= <br>
MYSQL_PORT_REPLICA= <br>

MYSQL_DATABASE= <br>
MYSQL_ROOT_PASSWORD= <br>
REDIS_PASSWORD= <br>

# Setup db replica
in .docker -> docker-compose.yml add following lines. This will excute mysql service as replica

 '#MySQL reblica Service <br>
    db_replica: <br>
        image: mysql:5.7.32 <br>
        container_name: ${COMPOSE_PROJECT_NAME}_db_replica <br>
        restart: unless-stopped <br>
        tty: true <br>
        volumes: <br>
        - ./.mysql_replica:/var/lib/mysql <br>
        ports: <br>
        - "${MYSQL_PORT_REPLICA}:3306" <br>
        environment: <br>
        MYSQL_DATABASE: ${MYSQL_DATABASE} <br>
        MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD} <br>
        SERVICE_TAGS: dev <br>
        SERVICE_NAME: ${COMPOSE_PROJECT_NAME}_db_replica <br>
        networks: <br>
        - app-network <br>'

# setup .env varable

DB_CONNECTION=mysql <br>
DB_HOST=db <br>
DB_PORT=3306 <br>
DB_DATABASE=gfp <br>
DB_USERNAME=root <br>
DB_PASSWORD=root <br>

DB_HOST_REPLICA=db_replica <br>
DB_PORT_REPLICA=3306 <br>
DB_DATABASE_REPLICA=gfp <br>
DB_USERNAME_REPLICA=root <br>
DB_PASSWORD_REPLICA=root <br>

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
