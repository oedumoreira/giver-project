<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page 
{

    /**
     * Método responsável por renderizar o topo da página
     * @return string
     */
    private static function getHeader() 
    {
        return View::render('pages/header');
    }

    /**
     * Método responsável por renderizar o loader
     * @return string
     */
    private static function getLoading() 
    {
        return View::render('pages/loading');
    }

    /**
     * Método responsável por renderizar o loader
     * @return string
     */
    private static function getModalImport() 
    {
        return View::render('pages/import-modal');
    }

    /**
     * Método responsável por renderizar o modal insights
     * @return string
     */
    private static function getModalInsights() 
    {
        return View::render('pages/import-insights');
    }

    /**
     * Método responsável por renderizar o modal insights
     * @return string
     */
    private static function getModalAbout() 
    {
        return View::render('pages/import-about');
    }

    /**
     * Método responsável por renderizar o final da página
     * @return string
     */
    private static function getFooter() 
    {
        return View::render('pages/footer');
    }

    /**
     *  Método responsável por retornar o conteúdo (view) da nossa página genérica
     * @return string
     */
    public static function getPage($title, $content) 
    {
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter(),
            'modal-import' => self::getModalImport(),
            'modal-insights' => self::getModalInsights(),
            'modal-about' => self::getModalAbout(),
            'loading' => self::getLoading(),
        ]);
    }

    /**
     * Método responsável por retornar um link da paginação
     * @param array $queryParams
     * @param array $page
     * @param string $url
     */
    private static function getPaginationLink($queryParams, $page, $url, $label = null) 
    {
        $queryParams['page'] = $page['page'];

        $link = $url .'?'. http_build_query($queryParams);

        return View::render('pages/pagination/link', [
            'page' => $label ?? $page['page'],
            'num' => $page['page'],
            'link' => $link,
            'active' => $page['current'] ? 'active' : ''
        ]);
    }
    /**
     * Método responsável por renderizar o layout de paginação
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination) 
    {
        $pages = $obPagination->getPages();

        //VERIFICA QUANTIDADE DE PÁGINAS
        if (count($pages) <= 1 ) return '';

        //LINKS
        $links = '';

        //URL ATUAL SEM GET
        $url = $request->getRouter()->getCurrentUrl();

        //GET
        $queryParams = $request->getQueryParams();

        //PÁGINA ATUAL
        $currentPage = $queryParams['page'] ?? 1;

        //LIMITE DE PÁGINAS
        $limit = getenv('PAGINATION_LIMIT');

        //MEIO DA PAGINAÇÃO
        $middle = ceil($limit/2);

        //INICIO DA PAGINAÇÃO
        $start = $middle > $currentPage ? 0 : $currentPage - $middle;

        //AJUSTA FINAL
        $limit = $limit + $start;

        //AJUSTA O INICIO DA PAGINAÇÃO
        if ($limit > count($pages)) {
            $diff = $limit - count($pages);
            $start -= $diff;
        }

        //LINK INICIAL
        if ($start > 0) {
            $links .= self::getPaginationLink($queryParams, reset($pages), $url, '<<');
        }

        //RENDERIZA OS LINKS
        foreach ($pages as $page) {
            //VERIFICA O START
            if ($page['page'] <= $start) continue;

            //VERIFICA O LIMITE DE PAGINAÇÃO
            if ($page['page'] > $limit) {
                $links .= self::getPaginationLink($queryParams, end($pages), $url, '>>');
                break;
            }

            $links .= self::getPaginationLink($queryParams, $page, $url);
        }

        return View::render('pages/pagination/box', [
            'links' => $links
        ]);
    }
}
