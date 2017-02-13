<?php 
require_once("../DecifraCaptcha.php");

//cadastre-se no site http://decifracaptcha.com.br e coloque seu token aqui
$token = 'coloqueseutokenaqui'; //aqui vai seu token

//aqui vai o seu arquivo com o caminho correspondente
//obs: se você quer resolver um captcha de um site, você deve fazer o download dele primeiro para enviar
$arquivo = "../captchas/captcha.jpg";

//Instanciando a classe DecifraCaptcha
$DecifraCaptcha = new DecifraCaptcha();

//Chamando o método para captchas e reCaptcha v1
$resposta = $DecifraCaptcha->decifrarCaptcha($token, $arquivo);

//Imprimindo a resposta
echo "<br>Resposta<br>";
echo "<pre>";
print_r($resposta);
echo "</pre>";

?>