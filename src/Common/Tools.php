<?php

namespace NFePHP\NFSeTrans\Common;

/**
 * Auxiar Tools Class for comunications with NFSe webserver in Nacional Standard
 *
 * @category  NFePHP
 * @package   NFePHP\NFSeTrans
 * @copyright NFePHP Copyright (c) 2008-2018
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse-etransparencia for the canonical source repository
 */

use NFePHP\NFSeTrans\RpsInterface;
use NFePHP\Common\DOMImproved as Dom;
use NFePHP\NFSeTrans\Common\Soap\SoapInterface;
use NFePHP\NFSeTrans\Common\Soap\SoapCurl;

class Tools
{
    public $lastRequest;
    
    protected $config;
    protected $prestador;
    protected $wsobj;
    protected $soap;
    protected $environment;
    protected $storage;
    protected $ws = [
        1 => 'webservice',
        2 => 'webservice_v2'
    ];

    /**
     * Constructor
     * @param string $config
     * @param Certificate $cert
     */
    public function __construct($config)
    {
        $this->config = json_decode($config);
        $this->storage = realpath(
            __DIR__ . '/../../storage/'
        );
        $urls = json_decode(file_get_contents($this->storage . '/municipios_conam.json'), true);
        if (empty($urls[$this->config->cmun])) {
            throw new \Exception("O municipio [{$this->config->cmun}] não consta da lista dos atendidos.");
        }
        if (empty($this->config->webservice)) {
            $this->config->webservice = 1;
        }
        $this->wsobj = json_decode(json_encode($urls[$this->config->cmun]));
        $this->wsobj->homologacao = "https://nfehomologacao.etransparencia.com.br"
            . "/{$this->wsobj->uri}/{$this->ws[$this->config->webservice]}/aws_nfe.aspx";
        $this->wsobj->producao = "https://nfe.etransparencia.com.br"
            . "/{$this->wsobj->uri}/{$this->ws[$this->config->webservice]}/aws_nfe.aspx";
        $this->environment = 'homologacao';
        if ($this->config->tpamb === 1) {
            $this->environment = 'producao';
        }
    }
    
    /**
     * SOAP communication dependency injection
     * @param SoapInterface $soap
     */
    public function loadSoapClass(SoapInterface $soap)
    {
        $this->soap = $soap;
    }
    
    /**
     * Send message to webservice
     * @param string $message
     * @param string $operation
     * @return string XML response from webservice
     */
    public function send($message, $operation)
    {
        $action = "NFeaction/AWS_NFE.$operation";
        $url = $this->wsobj->homologacao;
        if ($this->environment === 'producao') {
            $url = $this->wsobj->producao;
        }
        $request = $this->createSoapRequest($message, $operation);
        $this->lastRequest = $request;
        if (empty($this->soap)) {
            $this->soap = new SoapCurl();
        }
        $msgSize = strlen($request);
        $parameters = [
            "Content-Type: text/xml;charset=UTF-8",
            "SOAPAction: \"$action\"",
            "Content-length: $msgSize"
        ];
        $response = (string) $this->soap->send(
            $operation,
            $url,
            $action,
            $request,
            $parameters
        );
        return $response;
    }
    
    protected function cdtata($content)
    {
        if ($this->config->webservice == 1) {
            return $content;
        }
        $message = htmlentities($content, ENT_NOQUOTES, 'UTF-8', false);
        return "<nfe:Xml_entrada>$message</nfe:Xml_entrada>";
        
        /*
        $dom = new \DOMDocument();
        $node = $dom->appendChild($dom->createElement('nfe:Xml_entrada'));
        $node->appendChild($dom->createCDATASection($content));
        $content = $dom->saveXML($dom->documentElement);
        return $content;
         */
    }

    /**
     * Build SOAP request
     * @param string $message
     * @param string $operation
     * @return string XML SOAP request
     */
    protected function createSoapRequest($message, $operation)
    {
        $env = "<soapenv:Envelope "
            . "xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" "
            . "xmlns:nfe=\"NFe\">"
            . "<soapenv:Header/>"
            . "<soapenv:Body>"
            . "<nfe:ws_nfe.$operation>"
            . $this->cdtata($message)
            . "</nfe:ws_nfe.$operation>"
            . "</soapenv:Body>"
            . "</soapenv:Envelope>";
        
        return $env;
    }
}
