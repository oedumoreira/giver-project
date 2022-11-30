<?php 

require __DIR__ . '/../vendor/autoload.php';

use \App\Utils\view;
use \App\Utils\Environment;
use \App\Database\Database;

Environment::load(__DIR__. '/../');
define('URL', getenv('URL'));

//Define as configurações do banco de dados
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

//Define o valor padrão das variáveis
View::init([
    'URL' => URL
]);