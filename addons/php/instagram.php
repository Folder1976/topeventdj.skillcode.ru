<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}
           		$sobfeedback = new sobfeedback($this->registry);
$data['sobfeedback_id36'] = $sobfeedback->initFeedback(36);
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
	
	
	//http://ls-web.ru/kak-poluchit-access-token-v-instagram/
	//$token = '8014471697.9cab776.efdf8bb1e17f4519b5c2899767df75b7';
	$token = '8014471697.123d055.5bc297c0d15e414dbf4b8fc29bb2b64c';
	$user_id = '8014471697';
	$instagram_cnct = curl_init(); // инициализация cURL подключения
	curl_setopt( $instagram_cnct, CURLOPT_URL, "https://api.instagram.com/v1/users/" . $user_id . "/media/recent?access_token=" . $token ); // подключаемся
	curl_setopt( $instagram_cnct, CURLOPT_RETURNTRANSFER, 1 ); // просим вернуть результат
	curl_setopt( $instagram_cnct, CURLOPT_TIMEOUT, 15 );
	$media = json_decode( curl_exec( $instagram_cnct ) ); // получаем и декодируем данные из JSON
	curl_close( $instagram_cnct ); // закрываем соединение

	
	//echo '<pre>'; printf(var_dump($media));
	
	if(isset($media->data)){
		$data['media'] = $media->data;
	}
	
		$this->response->setOutput($this->load->view('common/home', $data));
	}
}

