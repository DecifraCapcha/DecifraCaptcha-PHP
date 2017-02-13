<?php 
require_once("../DecifraCaptcha.php");

//cadastre-se no site http://decifracaptcha.com.br e coloque seu token aqui
$token = 'coloqueseutokenaqui'; //aqui vai seu token

//instânciando a classe DecifraCaptcha
$DecifraCaptcha = new DecifraCaptcha();

//url onde se encontra o reCaptcha v2 que você quer decifrar
$url = "http://decifracaptcha.com.br/Sandbox/recaptcha";

/* 
Para pegar o data_sitekey, entre na página onde está o reCaptcha e procure por "data_sitekey". 
Pegue então o valor que está neste atributo e coloque abaixo
*/
$google_data_sitekey = "6LfkmBIUAAAAAEyzJEjtl8x07609j5bQssB90Mhm";

//Chamando o método para decifrarRecaptcha v2
$resposta = $DecifraCaptcha->decifrarRecaptcha($token, $url, $google_data_sitekey);


//Imprimindo a resposta
echo "<pre>";
print_r($resposta);
echo "</pre><br>";


if(!empty($resposta->captcha_texto)){
	//um parametro exigido pela página http://decifracaptcha.com.br/Sandbox/recaptcha para validar o captcha
	$posts = "enviado=1";

	/*	
	A resposta do reCaptcha v2 deve ser enviada como post com o parametro g-recaptcha-response
	Você insere $resposta->captcha_texto no textarea id="g-recaptcha-response" ou envia este parametro como post
	*/
	$posts .= "&g-recaptcha-response=".$resposta->captcha_texto.";";

	/*	
	Usamos o curl aqui, para enviar a resposta para o http://decifracaptcha.com.br/Sandbox/recaptcha
	mas você pode usar a ferramenta que quiser
	*/
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
} else {
	echo "Aconteceu um erro.";
}



?>