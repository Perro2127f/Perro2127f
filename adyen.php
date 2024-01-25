<?php

header('content-type: application/json');
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$lista = $_GET['lista'];
if(empty($lista)){
    echo 'Message":"?lista=cc|mes|ano|cvv&key=';
    exit();
}
#$lista = '4728810541126442|12|2029|143';
$lista2 = $lista;
preg_match_all('/(\d{15,16})+?[^0-9]+?(\d{1,2})[\D]*?(\d{2,4})[^0-9]+?(\d{3,4})/', $lista, $lista);
$cc = $lista[1][0];
$mes = $lista[2][0];
$ano = $lista[3][0];
$ano = $lista[3][0];
 if (strlen($ano) != 4) {
    $ano = "20".$ano;
 }
$cvv = $lista[4][0];
$key = $_GET['key'];

if(empty($cc) || empty($mes) || empty($ano) || empty($cvv)){
    echo 'Message":"?lista=cc|mes|ano|cvv&key=';
    exit();
}
if(empty($key)){
    echo 'Message":"Adyen Key empty"';
    exit();

}
$lista = "$cc|$mes|$ano|$cvv";
$encrypt = shell_exec("CC=$cc MES=$mes ANO=$ano CVV=$cvv NAME='Jhin Vega' KEY='$key' node encrypt/index.js");

if(empty($encrypt)){
echo 'Encrypt Failed';
}else{
    echo $encrypt;
}
