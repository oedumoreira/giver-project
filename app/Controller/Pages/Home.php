<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Page 
{

    /**
     *  Método responsável por retornar o conteúdo (view) da nossa home
     * @return string
     */

    public static function getHome() 
    {

        $content = View::render('pages/home');

        return parent::getPage('Início', $content);
    }
}
