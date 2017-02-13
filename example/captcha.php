<?php 
require_once("../DecifraCaptcha.php");

$token = "d41d8cd98f00b204e9800998ecf8427e";
//$token = "4431f462d526d9ceaae1e377f90e3255";
//$token = 'coloqueseueutokenaqui'; //aqui vai seu token
$arquivo = "../captchas/captcha.jpg"; //aqui vai o seu arquivo com o caminho correspondente



$DecifraCaptcha = new DecifraCaptcha();

$resposta = $DecifraCaptcha->decifrarCaptcha($token, $arquivo);

echo "<br>Resposta<br>";
echo "<pre>";
print_r($resposta);
echo "</pre>";

?>