<?php

header('content-type: application/json');
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$lista = $update['lista'];
if(empty($lista)){
    echo '{"success":false, "Message":"Please Enter Valid CC"}';
    exit();
}
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
$key = $update['key'];
if(empty($cc) || empty($mes) || empty($ano) || empty($cvv)){
    echo '{"success":false, "Message":"Please Enter Valid CC"}';
    exit();
}
if(empty($key)){
    echo '{"success":false, "Message":"Please Enter The Adyen Key"}';
    exit();

}
$lista = "$cc|$mes|$ano|$cvv";
$encrypt = shell_exec("CC=$cc MES=$mes ANO=$ano CVV=$cvv NAME='Juan Perez' KEY='$key' node encrypt/index.js");

if(empty($encrypt)){
echo '{"success":false, "Message":"Encrypt Failed"}';
}else{
    echo $encrypt;
}