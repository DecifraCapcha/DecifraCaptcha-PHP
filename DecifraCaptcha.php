<?php 

	class DecifraCaptcha{
		var $url_base = "http://decifracaptcha.com.br";


		public $tentativas = 20; // Numero de tentativas de 2 em 2 segundos

		public function setTentativas($tentativas){
			$this->tentativas = $tentativas;
		}

		public function base64encode($meu_captcha){
			//$meu_captcha = "diretorio/captcha.jpg"; //url do captcha
			$img = @file_get_contents( $meu_captcha );

			$img_64 = base64_encode( $img ); //codifica a imagem para a base64
			$img_64 = urlencode($img_64); // codifica a imagem para ser passada via url

			return $img_64;
		}

		public function decifrarCaptcha($token, $arquivo){
			$resultado = array(); //irá receber a resposta do decifra captcha

			$img_64 = $this->base64encode($arquivo);
			$url_iniciar = $this->url_base."/api/iniciarCaptcha"; // nesta url você inicia a decodificaçao do captcha
			$post = "tipo=base64"; //tipo de decodificação de captcha
			$post .= "&token=".$token; //seu token disponível no seu profile
			$post .= "&conteudo=".trim($img_64); // imagem já convertida para base64 e urlencode

		    $options = array(
		        CURLOPT_RETURNTRANSFER => true, //retorna o conteúdo da requisição via curl
		        CURLOPT_POST => true, // tipo de requisição post
		        CURLOPT_URL => $url_iniciar, //url para iniciar a decodificação do captcha
		        CURLOPT_POSTFIELDS => $post, // parâmetros do post
		        CURLOPT_HEADER => false, // não retornar o cabeçalho da requisição
		    );

		    $resultado = $resposta_iniciar = $this->curlExec($options);

			if(!empty($resposta_iniciar)){
				$resposta = null;
				$resultado = $resposta = json_decode($resposta_iniciar);
			}

			if(!empty($resposta->captcha_id)){
				sleep(12);//tempo mínimo de resposta
				$i = $this->tentativas; // número máximo de tentativas
				do{
					sleep(2); // espera 2 segundos para a próxima tentativa

					$url_pegar = $this->url_base."/api/pegarCaptcha/".$token."/".$resposta->captcha_id;
				    $options = array(
				        CURLOPT_RETURNTRANSFER => true, //retorna o conteúdo da requisição via curl
				        CURLOPT_POST => true, // tipo de requisição post
				        CURLOPT_URL => $url_pegar, //url para iniciar a decodificação do captcha
				        CURLOPT_POSTFIELDS => null, // parâmetros do post
				        CURLOPT_HEADER => false, // não retornar o cabeçalho da requisição
				    );

				    $resposta_json_get = $this->curlExec($options);

					if(!empty($resposta_json_get)){
						$resultado = $resposta_captcha = json_decode($resposta_json_get);
						if(!empty($resposta_captcha->status) && $resposta_captcha->status == "OK"){
							$resposta_captcha->captcha_id = $resposta->captcha_id;
							return $resposta_captcha;
						}
						return $resultado;
					}
					$i--;
				} while($i > 0);
			}
			return $resultado;
		}

		public function reportarErroCaptcha($token, $captcha_id){
			$url = $this->url_base."/api/reportarErroCaptcha/".$token."/".$captcha_id;
		    $options = array(
		        CURLOPT_RETURNTRANSFER => true, //retorna o conteúdo da requisição via curl
		        CURLOPT_POST => true, // tipo de requisição post
		        CURLOPT_URL => $url, //url para relatar o erro
		        CURLOPT_POSTFIELDS => null, // parâmetros do post
		        CURLOPT_HEADER => false, // não retornar o cabeçalho da requisição
		    );
		    $resposta_json_get = $this->curlExec($options);
		    return $resposta_json_get;
		}


		public function decifrarRecaptcha($token, $url, $data_sitekey){
			$url_iniciar = $this->url_base."/api/getRecaptcha2?token=".$token."&url=".urlencode($url)."&data_sitekey=".$data_sitekey; // nesta url você inicia a decodificaçao do captcha

		    $options = array(
		        CURLOPT_RETURNTRANSFER => true, //retorna o conteúdo da requisição via curl
		        CURLOPT_POST => true, // tipo de requisição post
		        CURLOPT_URL => $url_iniciar, //url para iniciar a decodificação do captcha
		        CURLOPT_POSTFIELDS => false, // parâmetros do post
		        CURLOPT_HEADER => false, // não retornar o cabeçalho da requisição
		    );

		    $resposta = $this->curlExec($options);
		    $resposta_json_get = json_decode($resposta);
		    return $resposta_json_get;
		}



        public function curlExec($options){
            $ch      = curl_init();
            curl_setopt_array($ch, $options);
            $resp    = curl_exec($ch);
            if(!empty(curl_error($ch))){
            	"<br>CURL ERROR: <br>";
	            echo "<pre>";
	            print_r(curl_error($ch));
	            echo "</pre>";
            }

            curl_close($ch);
            return $resp;
        }


	}

?>