# DecifraCaptcha

### Decrição
A classe DecifraCaptcha permite que você, de maneira fácil, possa resolver captchas e recaptchas v2 com php.

É a maneira mais fácil de decifrar captchas e recaptchas v1 ou v2

Entre no site http://decifracaptcha.com.br para você receber seu token

### Resolvendo reCaptcha V2
```php
<?php 
require_once("../DecifraCaptcha.php");

//cadastre-se no site http://decifracaptcha.com.br e coloque seu token aqui
$token = "coloqueseutokenaqui"; 

//instânciando a classe DecifraCaptcha
$DecifraCaptcha = new DecifraCaptcha();  

//url onde se encontra o reCaptcha v2 que você quer decifrar
$url = "http://decifracaptcha.com.br/Sandbox/recaptcha"; 

/* 
Para pegar o data_sitekey, entre na página onde está o reCaptcha e procure por "data_sitekey". 
Pegue então o valor que está neste atributo e coloque abaixo
*/
$data_sitekey = "6LfkmBIUAAAAAEyzJEjtl8x07609j5bQssB90Mhm"; 

//Chamando o método para decifrarRecaptcha v2
$resposta = $DecifraCaptcha->decifrarRecaptcha($token, $url, $data_sitekey);

//Imprimindo a resposta
echo "<pre>";
print_r($resposta);
echo "</pre><br>";

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
?>
```

Se tudo ocorrer bem, você terá obterá

```php
stdClass Object
(
    [sucesso] => 1
    [message] => Captcha resolvido com sucesso
    [status] => OK
    [captcha_id] => 250513
    [captcha_texto] => 03AHJ_Vuu7BLEyfA1e9m4lGGqv1c60E4FJhsw...
)
Parabéns. Você conseguiu passar pelo recaptcha v2
```
A resposta vem com os seguintes parametros
* sucesso: retorna 0 (se falhou) ou 1 (se obteve sucesso)
* message: mensagem retornada pela api
* status: o status da sua transação, retornará OK se tudo der certo
* captcha_id: o id do seu captcha no nosso sistema
* captcha_texto: a informação que você usará para passar pelo reCaptcha v2

### Resolvendo captcha ou reCaptcha V1 (captchas em formato de texto ou número)
```php
<?php 
require_once("../DecifraCaptcha.php");

//cadastre-se no site http://decifracaptcha.com.br e coloque seu token aqui
$token = 'coloqueseutokenaqui'; 

//aqui vai o seu arquivo com o caminho correspondente
//obs: se você quer resolver um captcha de um site, você deve fazer o download dele primeiro para enviar
$arquivo = "../captchas/captcha.jpg"; 

//Instanciando a classe DecifraCaptcha
$DecifraCaptcha = new DecifraCaptcha();

//Chamando o método para captchas e reCaptcha v1
$resposta = $DecifraCaptcha->decifrarCaptcha($token, $arquivo);

//Imprimindo a resposta
echo "<pre>";
print_r($resposta);
echo "</pre><br>";

?>
```
Se tudo ocorrer bem, você terá obterá

```php
stdClass Object
(
    [sucesso] => 1
    [message] => Captcha resolvido com sucesso
    [status] => OK
    [captcha_id] => 250513
    [captcha_texto] => 03AHJ_Vuu7BLEyfA1e9m4lGGqv1c60E4FJhsw...
)
Parabéns. Você conseguiu passar pelo recaptcha v2
```
A resposta vem com os seguintes parametros
* sucesso: retorna 0 (se falhou) ou 1 (se obteve sucesso)
* message: mensagem retornada pela api
* status: o status da sua transação, retornará OK se tudo der certo
* captcha_id: o id do seu captcha no nosso sistema
* captcha_texto: a informação que você usará para passar pelo reCaptcha v2
