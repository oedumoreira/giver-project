<?php 

namespace App\Model\Entity;

use \App\Database\Database;

class Cliente 
{

    /**
     * ID do registro na base
     * @var integer
     */
    public int $id;

    public string $nome;

    public string $sobrenome;

    public string $email;

    public string $genero;

    public string $endereco_ip;

    public string $empresa;

    public string $cidade;

    public string $cargo;

    public string $site;

    public function cadastrar() 
    {
        $this->id = (new Database('clientes'))->insert([
            'first_name' => $this->nome,
            'last_name'  => $this->sobrenome,
            'email'      => $this->email,
            'gender'     => $this->genero,
            'ip_address' => $this->endereco_ip,
            'company'    => $this->empresa,
            'city'       => $this->cidade,
            'title'      => $this->cargo,
            'website'    => $this->site,
        ]);

        return true;
    }

    /**
     * @return PDOStatment
     */
    public static function getClientes($where = null, $order = null, $limit = null, $fields = '*') 
    {
        return (new Database('clientes'))->select($where, $order, $limit, $fields);
    }
}