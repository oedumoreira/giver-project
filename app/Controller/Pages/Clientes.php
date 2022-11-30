<?php

namespace App\Controller\Pages;

use \App\Model\Entity\Cliente as EntityCliente;
use \App\Utils\Pagination;

class Clientes extends Page
{
    /**
     * Método responsável por obter a renderização dos itens de clientes para a página
     * @param Request $request
     * @param Pagination $obPagination 
     * @return string
     */
    private static function getClienteItems($request, &$obPagination) 
    {

        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;
        $qntdTotal = EntityCliente::getClientes(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

        $obPagination = new Pagination($qntdTotal, $paginaAtual, 10);
        $itens = [];

        //Qntd total de registros
        $results = EntityCliente::getClientes(null, 'id DESC', $obPagination->getLimit());

        while ($obCliente = $results->fetchObject(EntityCliente::class)) {

            array_push($itens,
                [
                    'id'          => $obCliente->id,
                    'nome'        => $obCliente->first_name,
                    'sobrenome'   => $obCliente->last_name,
                    'email'       => $obCliente->email,
                    'genero'      => $obCliente->gender,
                    'endereco_ip' => $obCliente->ip_address,
                    'empresa'     => $obCliente->company,
                    'cidade'      => $obCliente->city,
                    'cargo'       => $obCliente->title,
                    'site'        => substr($obCliente->website, 0, 35) . "...",
                    'data'        => date('d/m/Y H:i:s', strtotime($obCliente->create_at))
                ]
            );
        }

        return $itens;
    }

    /**
     *  Método responsável por retornar o conteúdo (view) de clientes
     * @param Request $request
     * @return string
     */
    public static function getClientes($request) 
    {
        echo json_encode(
            [
                'itens' => self::getClienteItems($request, $obPagination),
                'pagination' => parent::getPagination($request, $obPagination)
            ]
        );
    }

    /**
     * Método responsável por retornar o número de clientes sem sobrenome
     */
    public static function getClientesSemSobrenome() 
    {
        $qntd = EntityCliente::getClientes('last_name = ""', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        echo json_encode($qntd);
    }

    /**
     * Método responsável por retornar o número de clientes sem gênero
     */
    public static function getClientesSemGenero() 
    {
        $qntd = EntityCliente::getClientes('gender = ""', null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        echo json_encode($qntd);
    }

    /**
     * Método responsável por retornar o número de clientes sem e-mail válido
     */
    public static function getClientesSemEmailValido() {
        $count = 0;
        $results = EntityCliente::getClientes(null, null, null, 'email');

        while ($obCliente = $results->fetchObject(EntityCliente::class)) {
            !filter_var($obCliente->email, FILTER_VALIDATE_EMAIL) ? $count++ : '';
        }

        echo json_encode($count);
    }

    /**
     * Método responsável por importar um arquivo .csv para a base
     * @param Request $request
     * @return string
     */
    public static function importCsv($request)
    {
        $file_vars = $request->getFileVars();

        $file_mimes = [
            'text/x-comma-separated-values',
            'text/comma-separated-values',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.msexcel',
            'text/plain'
        ];

        if (empty($file_vars['filecsv']['name']) && !in_array($file_vars['filecsv']['type'], $file_mimes)) {
            return false;
        }

        $csv_file = fopen($file_vars['filecsv']['tmp_name'], 'r');
        fgetcsv($csv_file);
        
        while (($data = fgetcsv($csv_file, 10000, ",")) !== FALSE) {
            $obCliente = new EntityCliente;
            $obCliente->nome        = $data[1]; 
            $obCliente->sobrenome   = $data[2]; 
            $obCliente->email       = $data[3]; 
            $obCliente->genero      = $data[4]; 
            $obCliente->endereco_ip = $data[5]; 
            $obCliente->empresa     = $data[6]; 
            $obCliente->cidade      = $data[7]; 
            $obCliente->cargo       = $data[8]; 
            $obCliente->site        = $data[9]; 
            $obCliente->cadastrar();
        }

        fclose($csv_file);
    }
}
