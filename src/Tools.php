<?php

namespace NFePHP\NFSeTrans;

/**
 * Class for comunications with NFSe webserver in Nacional Standard
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

use NFePHP\NFSeTrans\Common\Tools as BaseTools;
use NFePHP\NFSeTrans\RpsInterface;
use NFePHP\Common\Certificate;
use DateTime;

class Tools extends BaseTools
{
    protected $reg20 = '';
    protected $reg90 = '';
    
    public function __construct($config, Certificate $cert = null)
    {
        parent::__construct($config, $cert);
    }
    
    /**
     * Solicita cancelamento de NFSe
     * @param string $cancelarGuia S ou N
     * @param float $valor
     * @param string $motivo
     * @param string $numeronfse
     * @param string $serienfse
     * @param string $serierps
     * @param string $numerorps
     * @return string
     */
    public function cancelarNfse(
        $cancelarGuia,
        $valor,
        $motivo,
        $numeronfse = null,
        $serienfse = null,
        $serierps = null,
        $numerorps = null
    ) {
        $operation = "CANCELANOTAELETRONICA";
        $content = "<nfe:Sdt_cancelanfe>"
            . $this->login()
            . "<nfe:Nota>";
        if (!empty($numeronfse)) {
            $content .= "<nfe:SerieNota>$serienfse</nfe:SerieNota>"
            . "<nfe:NumeroNota>$numeronfse</nfe:NumeroNota>";
        } else {
            if (isset($serierps)) {
                $content .= "<nfe:SerieRPS>$serierps</nfe:SerieRPS>";
            }
            $content .= "<nfe:NumeroRps>$numerorps</nfe:NumeroRps>";
        }
        $content .= "<nfe:ValorNota>" . number_format($valor, 2, ',', '') ."</nfe:ValorNota>"
            . "<nfe:MotivoCancelamento>$motivo</nfe:MotivoCancelamento>"
            . "<nfe:PodeCancelarGuia>$cancelarGuia</nfe:PodeCancelarGuia>"
            . "</nfe:Nota>"
            . "</nfe:Sdt_cancelanfe>";
        
        return $this->send($content, $operation);
    }
   
    /**
     * Consulta de NFSe
     * @param string $protocolo
     * @return string
     */
    public function consultarNfse($protocolo)
    {
        $operation = "CONSULTAPROTOCOLO";
        
        $content = "<nfe:Sdt_consultaprotocoloin>"
            . "<nfe:Protocolo>$protocolo</nfe:Protocolo>"
            . $this->login()
            . "</nfe:Sdt_consultaprotocoloin>";
        return $this->send($content, $operation);
    }
    
    /**
     * Consulta de Lote
     * @param string $protocolo
     * @return string
     */
    public function consultarLoteRps($protocolo)
    {
        $operation = "CONSULTANOTASPROTOCOLO";
        $content = "<nfe:Sdt_consultanotasprotocoloin>"
            . "<nfe:Protocolo>$protocolo</nfe:Protocolo>"
            . $this->login()
            . "</nfe:Sdt_consultanotasprotocoloin>";
        
        return $this->send($content, $operation);
    }
    
    /**
     * Envio de NFSe em lote
     * @param array $arps
     * @return string
     */
    public function enviarLoteRps($arps)
    {
        $operation = 'PROCESSARPS';
        $tot = new \stdClass();
        $tot->ano = '';
        $tot->mes = '';
        $tot->dtini = '';
        $tot->dtfin = '';
        
        $tot = $this->totalize($arps);
        $content = "<nfe:Sdt_processarpsin>"
            . $this->login()
            . "<nfe:SDTRPS>"
            . "<nfe:Ano>{$tot->ano}</nfe:Ano>"
            . "<nfe:Mes>{$tot->mes}</nfe:Mes>"
            . "<nfe:CPFCNPJ>{$this->config->cnpj}</nfe:CPFCNPJ>"
            . "<nfe:DTIni>{$tot->dtini}</nfe:DTIni>"
            . "<nfe:DTFin>{$tot->dtfin}</nfe:DTFin>"
            . "<nfe:TipoTrib>{$this->config->tipotrib}</nfe:TipoTrib>"
            . "<nfe:DtAdeSN>{$this->config->dtadesn}</nfe:DtAdeSN>"
            . "<nfe:AlqIssSN_IP>{$this->config->alqisssn}</nfe:AlqIssSN_IP>"
            . "<nfe:Versao>{$this->wsobj->versao}</nfe:Versao>"
            . "<nfe:Reg20>"
            . $this->reg20
            . "</nfe:Reg20>"
            . $this->reg90
            . "</nfe:SDTRPS>"
            . "</nfe:Sdt_processarpsin>";
        return $this->send($content, $operation);
    }
    
    /**
     * Validação de NFSe
     * @param array $arps
     * @return string
     */
    public function validarLoteRps($arps)
    {
        $operation = "VERFICARPS";
        $tot = $this->totalize($arps);
        $content = "<nfe:Sdt_processarpsin>"
            . $this->login()
            . "<nfe:SDTRPS>"
            . "<nfe:Ano>{$tot->ano}</nfe:Ano>"
            . "<nfe:Mes>{$tot->mes}</nfe:Mes>"
            . "<nfe:CPFCNPJ>{$this->config->cnpj}</nfe:CPFCNPJ>"
            . "<nfe:DTIni>{$tot->dtini}</nfe:DTIni>"
            . "<nfe:DTFin>{$tot->dtfin}</nfe:DTFin>"
            . "<nfe:TipoTrib>{$this->config->tipotrib}</nfe:TipoTrib>"
            . "<nfe:DtAdeSN>{$this->config->dtadesn}</nfe:DtAdeSN>"
            . "<nfe:AlqIssSN_IP>{$this->config->alqisssn}</nfe:AlqIssSN_IP>"
            . "<nfe:Versao>{$this->wsobj->versao}</nfe:Versao>"
            . "<nfe:Reg20>"
            . $this->reg20
            . "</nfe:Reg20>"
            . $this->reg90
            . "</nfe:SDTRPS>"
            . "</nfe:Sdt_processarpsin>";
        return $this->send($content, $operation);
    }
    
    /**
     * Build tag login
     * @return string
     */
    protected function login()
    {
        return "<nfe:Login>"
            . "<nfe:CodigoUsuario>{$this->config->usuario}</nfe:CodigoUsuario>"
            . "<nfe:CodigoContribuinte>{$this->config->contribuinte}</nfe:CodigoContribuinte>"
            . "</nfe:Login>";
    }
    
    /**
     * Totalizador
     * @param array $arps
     * @return string
     */
    protected function totalize($arps)
    {
        $QtdRegNormal = 0;
        $ValorNFS = 0;
        $ValorISS = 0;
        $ValorDed = 0;
        $ValorIssRetTom = 0;
        $QtdReg30 = 0;
        $ValorTributos = 0;
        $dtIni = null;
        $dtFim = null;
        foreach ($arps as $rps) {
            if ($rps->std->tipo == 'RPS') {
                if (empty($dtIni)) {
                    $dtIni = new Datetime($rps->std->dtemi);
                    $dtFim = new Datetime($rps->std->dtemi);
                } else {
                    $dtIni = $this->smaller($dtIni, new Datetime($rps->std->dtemi));
                    $dtFim = $this->bigger($dtFim, new Datetime($rps->std->dtemi));
                }
                $QtdRegNormal++;
                $ValorNFS += $rps->std->vlnfs;
                $ValorISS += round(($rps->std->alqiss/100)*$rps->std->vlnfs, 2);
                $ValorDed += $rps->std->vlded;
                $ValorIssRetTom += $rps->std->vlissret;
                $QtdReg30 += count($rps->std->tributos);
                foreach ($rps->std->tributos as $trib) {
                    $ValorTributos += $trib->valor;
                }
            }
            $this->reg20 .= $rps->render();
        }
        
        $this->reg90 = "<nfe:Reg90>"
            . "<nfe:QtdRegNormal>$QtdRegNormal</nfe:QtdRegNormal>"
            . "<nfe:ValorNFS>".number_format($ValorNFS, 2, ',', '')."</nfe:ValorNFS>"
            . "<nfe:ValorISS>".number_format($ValorISS, 2, ',', '')."</nfe:ValorISS>"
            . "<nfe:ValorDed>".number_format($ValorDed, 2, ',', '')."</nfe:ValorDed>"
            . "<nfe:ValorIssRetTom>".number_format($ValorIssRetTom, 2, ',', '')."</nfe:ValorIssRetTom>"
            . "<nfe:QtdReg30>$QtdReg30</nfe:QtdReg30>"
            . "<nfe:ValorTributos>".number_format($ValorTributos, 2, ',', '')."</nfe:ValorTributos>"
            . "</nfe:Reg90>";
        
        $tot = [
            'ano' => $dtFim->format('Y'),
            'mes' => $dtFim->format('m'),
            'dtini' => $dtIni->format('d/m/Y'), //extraido dos RPS
            'dtfin' => $dtFim->format('d/m/Y') //extraido dos RPS
        ];
        return json_decode(json_encode($tot));
    }
    
    /**
     * Compare dates
     * @param \Datetime $dt1
     * @param \DateTime $dt2
     * @return \Datetime|DateTime
     */
    protected function bigger(DateTime $dt1, DateTime $dt2)
    {
        if ($dt1 > $dt2) {
            return $dt1;
        } else {
            return $dt2;
        }
    }
    
    /**
     * Compare dates
     * @param \Datetime $dt1
     * @param \DateTime $dt2
     * @return \Datetime
     */
    protected function smaller(DateTime $dt1, DateTime $dt2)
    {
        if ($dt1 < $dt2) {
            return $dt1;
        } else {
            return $dt2;
        }
    }
}
