<?php 
require_once("../DecifraCaptcha.php");


$token = 'coloqueseueutokenaqui'; //aqui vai seu token
$arquivo = "../captchas/captcha.jpg"; //aqui vai o seu arquivo com o caminho correspondente



$DecifraCaptcha = new DecifraCaptcha();

$resposta = $DecifraCaptcha->decifrarCaptcha($token, $arquivo);

echo "<br>Resposta<br>";
echo "<pre>";
print_r($resposta);
echo "</pre>";

?>