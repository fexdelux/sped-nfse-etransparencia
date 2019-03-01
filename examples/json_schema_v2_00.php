<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$version = '2_00';

$jsonSchema = '{
    "title": "RPS",
    "type": "object",
    "properties": {
        "tipo": {
            "required": true,
            "type": "string",
            "pattern": "^(RPS|RPC)$"
        },
        "numero": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 999999999
        },
        "serie": {
            "required": true,
            "type": "string",
            "pattern": "^.{1,3}$"
        },
        "dtemi": {
            "required": true,
            "type": "string",
            "pattern": "^(2[0-9]{3}-(((0[13578]|1[02])-([0-2]{1}[0-9]{1}|3[01]))|(02-([0-2]{1}[0-9]{1}))|((0[469]|11)-([0-2]{1}[0-9]{1}|30))))$"
        },
        "retfonte": {
            "required": true,
            "type": "string",
            "pattern": "^(SIM|NAO)$"
        },
        "codsrv": {
            "required": true,
            "type": "string",
            "pattern": "^.{1,10}$"
        },
        "discrsrv": {
            "required": true,
            "type": "string",
            "pattern": "^.{1,4000}$"
        },
        "vlnfs": {
            "required": true,
            "type": "number"
        },
        "vlded": {
            "required": true,
            "type": "number"
        },
        "discrded": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^.{1,4000}$"
        },
        "alqiss": {
            "required": true,
            "type": "number"
        },
        "vlissret": {
            "required": true,
            "type": "number"
        },
        "tomador": {
            "required": true,
            "type": "object",
            "properties": {
                "cpfcnpj": {
                    "required": true,
                    "type": "string",
                    "pattern": "^([0-9]{11,14}|CONSUMIDOR|EXTERIOR)$"
                },
                "razsoc": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,60}$"
                },
                "tipolog": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^(RUA|AVENIDA|PRAÇA|ALAMEDA)$"
                },
                "log": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,60}$"
                },
                "numend": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,10}$"
                },
                "complend": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,60}$"
                },
                "bairro": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,60}$"
                },
                "mun": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,60}$"
                },
                "siglauf": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{2}$"
                },
                "cep": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{8}$"
                },
                "telefone": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{6,10}$"
                },
                "inscricaomunicipal": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{5,20}$"
                },
                "email1": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{2,120}$"
                },
                "email2": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{2,120}$"
                },
                "email3": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{2,120}$"
                },
                "localprestacao": {
                    "required": false,
                    "type": ["object","null"],
                    "properties": {
                        "tipolog": {
                            "required": true,
                            "type": "string",
                            "pattern": "^(RUA|AVENIDA|PRAÇA|ALAMEDA)$"
                        },
                        "log": {
                            "required": true,
                            "type": "string",
                            "pattern": "^.{1,60}$"
                        },
                        "numend": {
                            "required": true,
                            "type": "string",
                            "pattern": "^.{1,10}$"
                        },
                        "complend": {
                            "required": false,
                            "type": ["string","null"],
                            "pattern": "^.{1,60}$"
                        },
                        "bairro": {
                            "required": true,
                            "type": "string",
                            "pattern": "^.{1,60}$"
                        },
                        "mun": {
                            "required": true,
                            "type": "string",
                            "pattern": "^.{1,60}$"
                        },
                        "siglauf": {
                            "required": true,
                            "type": "string",
                            "pattern": "^.{2}$"
                        },
                        "cep": {
                            "required": true,
                            "type": "string",
                            "pattern": "^[0-9]{8}$"
                        }
                    }
                }
            }
        },
        "tributos": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "maxItems": 10,
            "items": {
                "type": "object",
                "properties": {
                    "sigla": {
                        "required": true,
                        "type": "string",
                        "pattern": "^.{1,10}$"
                    },
                    "aliquota": {
                        "required": true,
                        "type": "number"
                    },
                    "valor": {
                        "required": true,
                        "type": "number"
                    }
                }
            }    
        }
    }
}';


$std = new \stdClass();
$std->tipo = "RPS"; //Tipo de NFS 'RPS' e 'RPC'.
$std->numero = 12; //9 numericos
$std->serie = "A"; //string 3
$std->dtemi = "10/11/2018"; //dd/mm/yyyy
$std->retfonte = "SIM"; //retenção na fonte SIM ou NAO.
$std->codsrv = "16.38"; //codigo do serviço string até 10
$std->discrsrv = 'Discriminação da natureza do serviço prestado \\ indica quebra de linha'; //Discriminação da natureza do serviço prestado \\ indica quebra de linha
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
$std->alqiss = 1.00; //Numérico 5,2
//calcular no factory $std->vliss = 111.11; //Numérico 16,2 Obrigatório se <RetFonte> = 'NAO' Valor igual a 0 (zero) se <RetFonte> = 'SIM’
$std->vlissret = 34.55; //Numérico 16,2 Obrigatório se <RetFonte> = 'SIM' Valor igual a 0 (zero) se <RetFonte> = 'NAO'



$std->tomador = new stdClass();
$std->tomador->cpfcnpj = "12345678901234"; //caracter 11 ou 14 | CONSUMIDOR | EXTERIOR
$std->tomador->razsoc = "Razao Social do tomador"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado
$std->tomador->tipolog = "RUA"; //1. RUA 2. AVENIDA 3. PRAÇA 4. ALAMEDA
$std->tomador->log = "Amendoin"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado 
$std->tomador->numend = "11A";//Caracter 10 Obrigatorio de CPF ou CNPJ for informado 
$std->tomador->complend = "sobreloja";//Caracter 60 Obrigatorio de CPF ou CNPJ for informado 
$std->tomador->bairro = "Bolo ingles"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado 
$std->tomador->mun = "Docelandia"; //Caracter 60 Obrigatorio de CPF ou CNPJ for informado Informar “EXTERIOR” para operações com o exterior
$std->tomador->siglauf = "SP"; //Caracter 2 Obrigatorio de CPF ou CNPJ for informado  Informar “EX” para operações com o exterior
$std->tomador->cep = "01154700"; // Obrigatorio de CPF ou CNPJ for informado 
$std->tomador->telefone = "99999999"; // numerico 10 Telefone do Tomador
$std->tomador->inscricaomunicipal = "1234567899"; //Caracter 20 Não Informar quando Tomador é Consumidor Final ou Pessoa do Exterior.
$std->tomador->email1 = "ciclano@mail.com"; //Caracter 120 Quando o Tomador é pessoa Externa ou consumidor final, o campo poderá ser usado como um endereço de E-mail para envio da NFE
$std->tomador->email2 = "beltrano@mail.com"; //Deve ser diferente do campo <Email1> e só deve ser informado se a nota deve ser enviada para mais de um endereço de email
$std->tomador->email3 = "fulano@mail.com"; //Deve ser diferente dos campos <Email1> e <Email2>e só deve ser informado se a nota deve ser enviada para mais de um endereço de email


$std->tomador->localprestacao = new stdClass();
$std->tomador->localprestacao->tipolog = "ALAMEDA"; //Caracter 10 Tipo do Logradouro do local de Prestação de Serviços Informar somente se Local de Prestação de Serviços
                        //diferente do Endereço do Tomador 
                        //Informar segundo a tabela que segue:
                        //1. RUA
                        //2. AVENIDA
                        //3. PRAÇA
                        //4. ALAMEDA
                        //5. Tomador Consumidor Final não pode ter local de prestação de serviços
$std->tomador->localprestacao->log = "Maracatins"; //Caracter 60 Logradouro do Local de Prestação de Serviços
                        //Regras:
                        //1. Obrigatório Somente se o campo <TipoLogLocPre> foi
                        //informado. Poderá ser informado endereço de prestação no
                        //exterior também. Neste caso a UF deve ser igual a EX e o
                        //município = EXTERIOR.
$std->tomador->localprestacao->numend = "1874"; //Caracter 10) Número do Endereço do Local de Prestação de Serviços
                        //1. Obrigatório Somente se o campo <TipoLogLocPre> foi informado
//$std->tomador->localprestacao->complend = ""; //Caracter 60
$std->tomador->localprestacao->bairro = "Moema"; //Caracter 60
$std->tomador->localprestacao->mun = "Sao Paulo"; //Caracter 60 Informar “EXTERIOR” para serviços prestados no Exterior
$std->tomador->localprestacao->siglauf = "SP"; //Caracter 2 Informar “EX” para operações com o exterior
$std->tomador->localprestacao->cep = "02145000"; //numerico 8 Se <SiglaUFLocpre> = 'EX' campo do CEP deve vir zerado


$std->tributos[0] = new stdClass(); 
$std->tributos[0]->sigla = "COFINS"; //Caracter 10 Siglas de tributos permitidas:
                                    // COFINS
                                    // CSLL
                                    // INSS
                                    // IR
                                    // PIS
                                    // Caso tenha algum que não esteja na lista deve verificar com a prefeitura.
$std->tributos[0]->aliquota = 0.98; //numerico 5,2 
$std->tributos[0]->valor = 14.98; //Numérico 10,2



// Schema must be decoded before it can be used for validation
$jsonSchemaObject = json_decode($jsonSchema);
if (empty($jsonSchemaObject)) {
    echo "<h2>Erro de digitação no schema ! Revise</h2>";
    echo "<pre>";
    print_r($jsonSchema);
    echo "</pre>";
    die();
}
// The SchemaStorage can resolve references, loading additional schemas from file as needed, etc.
$schemaStorage = new SchemaStorage();
// This does two things:
// 1) Mutates $jsonSchemaObject to normalize the references (to file://mySchema#/definitions/integerData, etc)
// 2) Tells $schemaStorage that references to file://mySchema... should be resolved by looking in $jsonSchemaObject
$schemaStorage->addSchema('file://mySchema', $jsonSchemaObject);
// Provide $schemaStorage to the Validator so that references can be resolved during validation
$jsonValidator = new Validator(new Factory($schemaStorage));
// Do validation (use isValid() and getErrors() to check the result)
$jsonValidator->validate(
    $std,
    $jsonSchemaObject,
    Constraint::CHECK_MODE_COERCE_TYPES  //tenta converter o dado no tipo indicado no schema
);

if ($jsonValidator->isValid()) {
    echo "The supplied JSON validates against the schema.<br/>";
} else {
    echo "Dados não validados. Violações:<br/>";
    foreach ($jsonValidator->getErrors() as $error) {
        echo sprintf("[%s] %s<br/>", $error['property'], $error['message']);
    }
    die;
}
//salva se sucesso
file_put_contents("../storage/jsonSchemes/rps.schema", $jsonSchema);