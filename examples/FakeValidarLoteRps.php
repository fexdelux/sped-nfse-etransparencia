<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\NFSeTrans\Tools;
use NFePHP\NFSeTrans\Rps;
use NFePHP\NFSeTrans\Common\Soap\SoapFake;
use NFePHP\NFSeTrans\Common\FakePretty;

try {

    $config = [
        'cnpj'         => '99999999000191',
        'im'           => '1733160024',
        'cmun'         => '3506102', //ira determinar as urls e outros dados
        'razao'        => 'Empresa Test Ltda',
        'usuario'      => '1929292', //codigo do usuário usado no login
        'contribuinte' => 'ksjksjkjs', //codigo do contribunte usado no login
        'tipotrib'     => 4,
        'dtadesn'      => '01/01/2017',
        'alqisssn'     => 0,
        'tpamb'        => 2, //1-producao, 2-homologacao
        'webservice'   => 2
    ];

    $configJson = json_encode($config);
    $soap = new SoapFake();

    $tools = new Tools($configJson);
    $tools->loadSoapClass($soap);

    $arps = [];

    $std = new \stdClass();
    $std->tipo = "RPS"; //Tipo de NFS 'RPS' e 'RPC'.
    $std->numero = 12; //9 numericos
    $std->serie = "A"; //string 3
    $std->dtemi = "2018-11-10"; //dd/mm/yyyy
    $std->retfonte = "NAO"; //retenção na fonte SIM ou NAO.
    $std->codsrv = "16.38"; //codigo do serviço string até 10
    $std->discrsrv = 'Discriminação da natureza do serviço prestado \\\ indica quebra de linha'; //Discriminação da natureza do serviço prestado \\ indica quebra de linha
    $std->vlnfs = 1245.89; //Numérico 16,2
    $std->vlded = 123.64; //Numérico 16,2
    $std->discrded = "Obrigatório se Valor da dedução > 0"; //string 4000 Obrigatório se Valor da dedução > 0. Ele poderá também
            //poderá ser utilizado caso o operador necessite informar
            //retenções obrigatórias como IRPJ, PIS, COFINS, CSLL,
            //INSS etc., sem necessariamente ter um valor no campo
            //valor da dedução. O “\\” representa quebra de linha e assim
            //será considerado na impressão da nota gerada.
            //calcular no factory $std->VlBasCalc = ""; //Numérico 16,2 Deve ser igual ao informado no campo valor da nota menos
            //o informado no campo de valor de dedução
    $std->alqiss = 0.00; //Numérico 5,2
            //calcular no factory $std->vliss = 111.11; //Numérico 16,2 Obrigatório se <RetFonte> = 'NAO' Valor igual a 0 (zero) se <RetFonte> = 'SIM’
    //$std->vlissret = 34.55; //Numérico 16,2 Obrigatório se <RetFonte> = 'SIM' Valor igual a 0 (zero) se <RetFonte> = 'NAO'



    $std->tomador = new stdClass();
    $std->tomador->cpfcnpj = "12345678901234"; //caracter 11 ou 14 | CONSUMIDOR | EXTERIOR
    $std->tomador->razsoc = "Razao Social do tomador"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado
    $std->tomador->tipolog = "RUA"; //1. RUA 2. AVENIDA 3. PRAÇA 4. ALAMEDA
    $std->tomador->log = "Amendoin"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->numend = "11A"; //Caracter 10 Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->complend = "sobreloja"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->bairro = "Bolo ingles"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->mun = "Docelandia"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado Informar “EXTERIOR” para operações com o exterior
    $std->tomador->siglauf = "SP"; //Caracter 2 Obrigatorio de CPF ou CNPJ for informado  Informar “EX” para operações com o exterior
    $std->tomador->cep = "01154700"; // Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->telefone = "99999999"; // numerico 10 Telefone do Tomador
    $std->tomador->inscricaomunicipal = "1234567899"; //Caracter 20 Não Informar quando Tomador é Consumidor Final ou Pessoa do Exterior.
    $std->tomador->email1 = "ciclano@mail.com"; //Caracter 120 Quando o Tomador é pessoa Externa ou consumidor final, o campo poderá ser usado como um endereço de E-mail para envio da NFE
    $std->tomador->email2 = "beltrano@mail.com"; //Deve ser diferente do campo <Email1> e só deve ser informado se a nota deve ser enviada para mais de um endereço de email
    $std->tomador->email3 = "fulano@mail.com"; //Deve ser diferente dos campos <Email1> e <Email2>e só deve ser informado se a nota deve ser enviada para mais de um endereço de email


    //$std->tomador->localprestacao = new stdClass();
    //$std->tomador->localprestacao->tipolog = "ALAMEDA"; //Caracter 10 Tipo do Logradouro do local de Prestação de Serviços Informar somente se Local de Prestação de Serviços
        //diferente do Endereço do Tomador 
        //Informar segundo a tabela que segue:
        //1. RUA
        //2. AVENIDA
        //3. PRAÇA
        //4. ALAMEDA
        //5. Tomador Consumidor Final não pode ter local de prestação de serviços
    //$std->tomador->localprestacao->log = "Maracatins"; //Caracter 60 Logradouro do Local de Prestação de Serviços
        //Regras:
        //1. Obrigatório Somente se o campo <TipoLogLocPre> foi
        //informado. Poderá ser informado endereço de prestação no
        //exterior também. Neste caso a UF deve ser igual a EX e o
        //município = EXTERIOR.
    //$std->tomador->localprestacao->numend = "1874"; //Caracter 10) Número do Endereço do Local de Prestação de Serviços
        //1. Obrigatório Somente se o campo <TipoLogLocPre> foi informado
    //$std->tomador->localprestacao->complend = ""; //Caracter 60
    //$std->tomador->localprestacao->bairro = "Moema"; //Caracter 60
    //$std->tomador->localprestacao->mun = "Sao Paulo"; //Caracter 60 Informar “EXTERIOR” para serviços prestados no Exterior
    //$std->tomador->localprestacao->siglauf = "SP"; //Caracter 2 Informar “EX” para operações com o exterior
    //$std->tomador->localprestacao->cep = "02145000"; //numerico 8 Se <SiglaUFLocpre> = 'EX' campo do CEP deve vir zerado


    $std->tributos[0] = new stdClass();
    $std->tributos[0]->sigla = "COFINS"; //Caracter 10 Siglas de tributos permitidas:
        // COFINS
        // CSLL
        // INSS
        // IR
        // PIS
        // Caso tenha algum que não esteja na lista deve verificar com a prefeitura.
    $std->tributos[0]->aliquota = 10; //numerico 5,2 
    $std->tributos[0]->valor = 124.59; //Numérico 10,2
    
    $std->tributos[1] = new stdClass();
    $std->tributos[1]->sigla = "PIS"; //Caracter 10 Siglas de tributos permitidas:
    $std->tributos[1]->aliquota = 1; //numerico 5,2 
    $std->tributos[1]->valor = 12.46; //Numérico 10,2
    
    $arps[] = new Rps($std);

    
    $std = new \stdClass();
    $std->tipo = "RPS"; //Tipo de NFS 'RPS' e 'RPC'.
    $std->numero = 13; //9 numericos
    $std->serie = "A"; //string 3
    $std->dtemi = "2018-11-10"; //dd/mm/yyyy
    $std->retfonte = "NAO"; //retenção na fonte SIM ou NAO.
    $std->codsrv = "22.15"; //codigo do serviço string até 10
    $std->discrsrv = 'Outro serviço'; //Discriminação da natureza do serviço prestado \\ indica quebra de linha
    $std->vlnfs = 100.00; //Numérico 16,2
    //$std->vlded = 0; //Numérico 16,2
    //$std->discrded = "Obrigatório se Valor da dedução > 0"; //string 4000 Obrigatório se Valor da dedução > 0. Ele poderá também
        //poderá ser utilizado caso o operador necessite informar
        //retenções obrigatórias como IRPJ, PIS, COFINS, CSLL,
        //INSS etc., sem necessariamente ter um valor no campo
        //valor da dedução. O “\\” representa quebra de linha e assim
        //será considerado na impressão da nota gerada.
        //calcular no factory $std->VlBasCalc = ""; //Numérico 16,2 Deve ser igual ao informado no campo valor da nota menos
        //o informado no campo de valor de dedução
    $std->alqiss = 0.00; //Numérico 5,2
        //calcular no factory $std->vliss = 111.11; //Numérico 16,2 Obrigatório se <RetFonte> = 'NAO' Valor igual a 0 (zero) se <RetFonte> = 'SIM’
    //$std->vlissret = 34.55; //Numérico 16,2 Obrigatório se <RetFonte> = 'SIM' Valor igual a 0 (zero) se <RetFonte> = 'NAO'

    $std->tomador = new stdClass();
    $std->tomador->cpfcnpj = "12345678901234"; //caracter 11 ou 14 | CONSUMIDOR | EXTERIOR
    $std->tomador->razsoc = "Razao Social do tomador"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado
    $std->tomador->tipolog = "RUA"; //1. RUA 2. AVENIDA 3. PRAÇA 4. ALAMEDA
    $std->tomador->log = "Amendoin"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->numend = "11A"; //Caracter 10 Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->complend = "sobreloja"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->bairro = "Bolo ingles"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->mun = "Docelandia"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado Informar “EXTERIOR” para operações com o exterior
    $std->tomador->siglauf = "SP"; //Caracter 2 Obrigatorio de CPF ou CNPJ for informado  Informar “EX” para operações com o exterior
    $std->tomador->cep = "01154700"; // Obrigatorio de CPF ou CNPJ for informado 
    $std->tomador->telefone = "99999999"; // numerico 10 Telefone do Tomador
    $std->tomador->inscricaomunicipal = "1234567899"; //Caracter 20 Não Informar quando Tomador é Consumidor Final ou Pessoa do Exterior.
    $std->tomador->email1 = "ciclano@mail.com"; //Caracter 120 Quando o Tomador é pessoa Externa ou consumidor final, o campo poderá ser usado como um endereço de E-mail para envio da NFE
    $std->tomador->email2 = "beltrano@mail.com"; //Deve ser diferente do campo <Email1> e só deve ser informado se a nota deve ser enviada para mais de um endereço de email
    $std->tomador->email3 = "fulano@mail.com"; //Deve ser diferente dos campos <Email1> e <Email2>e só deve ser informado se a nota deve ser enviada para mais de um endereço de email


    //$std->tomador->localprestacao = new stdClass();
    //$std->tomador->localprestacao->tipolog = "ALAMEDA"; //Caracter 10 Tipo do Logradouro do local de Prestação de Serviços Informar somente se Local de Prestação de Serviços
        //diferente do Endereço do Tomador 
        //Informar segundo a tabela que segue:
        //1. RUA
        //2. AVENIDA
        //3. PRAÇA
        //4. ALAMEDA
        //5. Tomador Consumidor Final não pode ter local de prestação de serviços
    //$std->tomador->localprestacao->log = "Maracatins"; //Caracter 60 Logradouro do Local de Prestação de Serviços
        //Regras:
        //1. Obrigatório Somente se o campo <TipoLogLocPre> foi
        //informado. Poderá ser informado endereço de prestação no
        //exterior também. Neste caso a UF deve ser igual a EX e o
        //município = EXTERIOR.
    //$std->tomador->localprestacao->numend = "1874"; //Caracter 10) Número do Endereço do Local de Prestação de Serviços
        //1. Obrigatório Somente se o campo <TipoLogLocPre> foi informado
    //$std->tomador->localprestacao->complend = ""; //Caracter 60
    //$std->tomador->localprestacao->bairro = "Moema"; //Caracter 60
    //$std->tomador->localprestacao->mun = "Sao Paulo"; //Caracter 60 Informar “EXTERIOR” para serviços prestados no Exterior
    //$std->tomador->localprestacao->siglauf = "SP"; //Caracter 2 Informar “EX” para operações com o exterior
    //$std->tomador->localprestacao->cep = "02145000"; //numerico 8 Se <SiglaUFLocpre> = 'EX' campo do CEP deve vir zerado


    $std->tributos[0] = new stdClass();
    $std->tributos[0]->sigla = "COFINS"; //Caracter 10 Siglas de tributos permitidas:
        // COFINS
        // CSLL
        // INSS
        // IR
        // PIS
        // Caso tenha algum que não esteja na lista deve verificar com a prefeitura.
    $std->tributos[0]->aliquota = 10; //numerico 5,2 
    $std->tributos[0]->valor = 10.98; //Numérico 10,2
    
    $arps[] = new Rps($std);
        
    
    $response = $tools->validarLoteRps($arps);

    echo FakePretty::prettyPrint($response, '');
} catch (\Exception $e) {
    echo $e->getMessage();
}