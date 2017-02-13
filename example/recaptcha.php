<?php 
require_once("../DecifraCaptcha.php");

//$token = "d41d8cd98f00b204e9800998ecf8427e";
$token = "4431f462d526d9ceaae1e377f90e3255";
//$token = 'coloqueseueutokenaqui'; //aqui vai seu token
$arquivo = "../captchas/captcha.jpg"; //aqui vai o seu arquivo com o caminho correspondente



$DecifraCaptcha = new DecifraCaptcha();

$url = "http://decifracaptcha.com.br/Sandbox/recaptcha";
$google_data_sitekey = "6LfkmBIUAAAAAEyzJEjtl8x07609j5bQssB90Mhm";

$DecifraCaptcha = new DecifraCaptcha();
$resposta = $DecifraCaptcha->decifrarRecaptcha($token, $url, $google_data_sitekey);

//echo $resposta;
/*	
	Você insere $resposta->captcha_texto
	no textarea id="g-recaptcha-response"
	ou envia este parametro como post
*/
	//Enviando via post

			//$post = "url=".$url; //tipo de decodificação de captcha
			//$post .= "&token=".$token; //seu token disponível no seu profile
			//$post .= "&data_sitekey=".trim($data_sitekey); // imagem já convertida para base64 e urlencode

$posts = "enviado=1";
$posts .= "&g-recaptcha-response=".$resposta->captcha_texto.";";
$curl_options = array(
    CURLOPT_RETURNTRANSFER => true, //retorna o conteúdo da requisição via curl
    CURLOPT_POST => true, // tipo de requisição post
    CURLOPT_URL => $url, //url para iniciar a decodificação do captcha
    CURLOPT_POSTFIELDS => $posts, // parâmetros do post
    CURLOPT_HEADER => false, // não retornar o cabeçalho da requisição
);

$ch      = curl_init();
curl_setopt_array($ch, $curl_options);
$resp    = curl_exec($ch);
if(!empty(curl_error($ch))){
	echo curl_error($ch);
}
echo $resp;
curl_close($ch);




?>