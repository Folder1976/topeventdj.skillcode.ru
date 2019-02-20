<?php
class ControllerAccountUniversalform extends Controller {
	private $error = array();

	public function index() {
        
        //echo '<pre>'; printf(var_dump($this->request->post));
        //die();
        
        $this->load->model('account/customer');
        
        //Если пользователь залогинен
        $customer_info = array(
                               'firstname' => 'Не',
                               'lastname' => 'залогинен',
                               );
        
		if ($this->customer->isLogged()) {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->isLogged());
		}else{
            if(isset($this->request->post['email'])){
                $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
            }
        }
        
        if(!$customer_info){
            $customer_info = array(
                               'firstname' => 'Не',
                               'lastname' => 'найден',
                               );
        }

        
        
        $json = array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
            
			$subject = 'Заказ мероприятия - ';
			if(isset($this->request->post['dj-name']) AND $this->request->post['dj-name']){
				$subject .= $this->request->post['dj-name'];
			}
			
            $message = '<h1>'.$subject.'</h1>';
            
            foreach($this->request->post as $name => $value){
                $message  .= $name .': <b>'.$value. "</b><br>";
            }
       
			$this->config->set('config_email', 'folder.list@gmail.com');
		
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
            
			if(isset($this->request->post['dj-email']) AND $this->request->post['dj-email']){
				$mail->setTo($this->request->post['dj-email']);
				$mail->send();
			}
			
            $json['success'] = 'Форма успешно отправлена. Мы скоро свяжемся с Вами.';
		}else{
            $json['error'] = $this->error;
        }

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	protected function validate() {
     
		if (!isset($this->request->post['email']) OR $this->request->post['email'] == '') {
			$this->error = 'Укажите Ваш email для обратной связи';
        }
        
		if (!isset($this->request->post['comment']) OR $this->request->post['comment'] == '') {
			$this->error = 'Укажите Ваше сообщение';
        }
        
		if (!isset($this->request->post['name']) OR $this->request->post['name'] == '') {
			$this->error = 'Укажите Ваше имя';
        }


		return !$this->error;
	}
}
