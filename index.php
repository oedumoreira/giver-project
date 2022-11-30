<?php 

require __DIR__ . '/bootstrap/app.php';

use \App\Http\Router;

//Inicia o router
$ob = new Router(URL);

//Inclui as rotas de páginas
include __DIR__.'/routes/pages.php';

//Imprime o response da rota
$ob->run()->sendResponse();