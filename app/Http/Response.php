<?php

namespace App\Http;

class Response 
{
    /**
     * Código do status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Cabeçalho do response
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteúdo de retorno
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteúdo do Response
     * @var mixed
     */
    private $content;
    
    /**
     * Método responsável por iniciar a classe e definir os valores
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html') 
    {
        $this->httpCode = $httpCode;
        $this->content  = $content;
        $this->setContentType($contentType);
    }
    
    /**
     * Método responsável por alterar o content type do response
     * @param string $contentType
     */
    public function setContentType($contentType) 
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Método responsável por adicionar um registro no cabeçalho HTTP
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value) 
    {
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por enviar headers para o navegador
     */
    // private function sendHeaders() 
    //{
    //     //Status
    //     http_response_code($this->httpCode);

    //     //Enviar headers
    //     foreach ($this->headers as $key => $value) {
    //         header($key. ': '. $value);
    //     }
    // }

    /**
     * Método responsável por enviar a resposta ao usuário
     */
    public function sendResponse() 
    {
        // $this->sendHeaders();
        
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        }
    }
}