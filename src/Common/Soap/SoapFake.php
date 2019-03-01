<?php

namespace NFePHP\NFSeTrans\Common\Soap;

/**
 * Soap fake class used for development only
 *
 * @category  NFePHP
 * @package   NFePHP\NFSeTrans
 * @copyright NFePHP Copyright (c) 2017
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse-etransparencia for the canonical source repository
 */
use NFePHP\NFSeTrans\Common\Soap\SoapBase;
use NFePHP\NFSeTrans\Common\Soap\SoapInterface;
use NFePHP\Common\Exception\SoapException;
use NFePHP\Common\Certificate;
use Psr\Log\LoggerInterface;

class SoapFake extends SoapBase implements SoapInterface
{

    private $mock = false;

    /**
     * Constructor
     * @param Certificate $certificate
     * @param LoggerInterface $logger
     */
    public function __construct(
        Certificate $certificate = null,
        LoggerInterface $logger = null
    ) {
        parent::__construct($certificate, $logger);
    }

    public function mockResponse($bool)
    {
        $this->mock = $bool;
    }

    public function send(
        $operation,
        $url,
        $action,
        $envelope,
        $parameters
    ) {
        $requestHead = implode("\n", $parameters);
        $requestBody = $envelope;

        if ($this->mock) {
            return $this->getMock($operation);
        }

        return json_encode([
            'url'        => $url,
            'operation'  => $operation,
            'action'     => $action,
            'soapver'    => '1.2',
            'parameters' => $parameters,
            'header'     => $requestHead,
            'namespaces' => [],
            'body'       => $requestBody
            ], JSON_PRETTY_PRINT);
    }

    private function getMock($operation)
    {
        switch ($operation) {
            case 'IMPRESSAOLINKNFSE':
                return $this->implink();
                break;
            case 'VERFICARPS':
                break;
            case 'PROCESSARPS':
                break;
            case 'CONSULTANOTASPROTOCOLO':
                break;
            case 'CONSULTAPROTOCOLO':
                break;
            case 'CANCELANOTAELETRONICA':
                break;
            default:
                '';
        }
    }
    
    private function verrps()
    {
    }

    private function implink()
    {
        $content = "
        <SDT_IMPRESSAO_OUT xmlns=\"NFe\">
            <Versao>string</Versao>
            <Sucesso>boolean</Sucesso>
            <Lista_Notas>
                <Nota>
                    <LinkImpressao>URL</LinkImpressao>
                    <TipoNf>string</TipoNf>
                    <NumNf>Number</NumNf>
                    <SerNf>String</SerNf>
                    <DtEmiNf>String</DtEmiNf>
                    <DtHrGerNf>String</DtHrGerNf>
                    <CodVernf>String</CodVernf>
                    <NumRps>Number</NumRps>
                    <SerRps>String</SerRps>
                    <DtEmiRps>String</DtEmiRps>
                    <TipoCpfCnpjPre>String</TipoCpfCnpjPre>
                    <CpfCnpjPre>String</CpfCnpjPre>
                    <RazSocPre>String</RazSocPre>
                    <LogPre>String</LogPre>
                    <NumEndPre>String</NumEndPre>
                    <ComplEndPre>String</ComplEndPre>
                    <BairroPre>String</BairroPre>
                    <MunPre>String</MunPre>
                    <SiglaUFPre>String</SiglaUFPre>
                    <CepPre>Number</CepPre>
                    <EmailPre>String</EmailPre>
                    <TipoTribPre>String</TipoTribPre>
                    <DtAdeSN>String</DtAdeSN>
                    <AlqIssSN>Number</AlqIssSN>
                    <SitNf>Number</SitNf><DataCncNf>String</DataCncNf>
                    <MotivoCncNf>String<MotivoCncNf>
                    <TipoCpfCnpjTom>String</TipoCpfCnpjTom>
                    <CpfCnpjTom>String</CpfCnpjTom>
                    <RazSocTom>String</RazSocTom>
                    <LogTom>String</LogTom>
                    <NumEndTom>String</NumEndTom>
                    <ComplEndTom>String</ComplEndTom>
                    <BairroTom>String</BairroTom>
                    <MunTom>String</MunTom>
                    <SiglaUFTom>String</SiglaUFTom>
                    <CepTom>Number</CepTom>
                    <EMailTom>String</EMailTom>
                    <LogLocPre>String</LogLocPre>
                    <NumEndLocPre>String</NumEndLocPre>
                    <ComplEndLocPre>String</ComplEndLocPre>
                    <BairroLocPre>String</BairroLocPre>
                    <MunLocPre>String</MunLocPre>
                    <SiglaUFLocpre>String</SiglaUFLocpre>
                    <CepLocPre>Number</CepLocPre>
                    <CodSrv>String</CodSrv>
                    <DiscrSrv>String</DiscrSrv>
                    <VlNFS>Number</VlNFS>
                    <VlDed>Number</VlDed>
                    <DiscrDed>String</DiscrDed>
                    <VlBasCalc>Number</VlBasCalc>
                    <AlqIss>Number</AlqIss>
                    <VlIss>Number</VlIss>
                    <VlIssRet>Number</VlIssRet>
                    <Reg30>
                        <Reg30Item>
                            <TributoSigla>String</TributoSigla>
                            <TributoAliquota>Number</TributoAliquota>
                            <TributoValor>Number</TributoValor>
                        </Reg30Item>
                    </Reg30>
                </Nota>
            </Lista_Notas>
        <Message>
            <Id>String<Id>
            <Description>String<Id>
        </Message>
    </SDT_IMPRESSAO_OUT>";
        
        return $content;
    }
}
