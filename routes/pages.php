<?php

use \App\Http\Response;
use \App\Controller\Pages;

//ROTA HOME
$ob->get('/', [
    function() {
        return new Response(200, Pages\Home::getHome());
    }
]);

//ROTA PARA IMPORTAR CSV
$ob->post('/importcsv', [
    function($request) {
        return new Response(200, Pages\Clientes::importCsv($request));
    }
]);

//ROTA PARA OBTER DADOS DOS CLIENTES NA BASE
$ob->get('/clientes', [
    function($request) {
        return new Response(200, Pages\Clientes::getClientes($request));
    }
]);

//ROTA PARA OBTER QUANTIDADE DE CLIENTES SEM SOBRENOME
$ob->get('/clientes/semsobrenome', [
    function() {
        return new Response(200, Pages\Clientes::getClientesSemSobrenome());
    }
]);

//ROTA PARA OBTER QUANTIDADE DE CLIENTES SEM SOBRENOME
$ob->get('/clientes/semgenero', [
    function() {
        return new Response(200, Pages\Clientes::getClientesSemGenero());
    }
]);

//ROTA PARA OBTER QUANTIDADE DE CLIENTES SEM SOBRENOME
$ob->get('/clientes/semgenero', [
    function() {
        return new Response(200, Pages\Clientes::getClientesSemGenero());
    }
]);

//ROTA PARA OBTER QUANTIDADE DE CLIENTES SEM SOBRENOME
$ob->get('/clientes/sememailvalido', [
    function() {
        return new Response(200, Pages\Clientes::getClientesSemEmailValido());
    }
]);

//ROTA DINÂMICA
$ob->get('/pagina/{idPagina}/{acao}', [
    function($idPagina, $acao) {
        return new Response(200, 'Página '.$idPagina. ' - ' .$acao);
    }
]);
