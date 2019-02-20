<?php
class ControllerExtensionModuleSendpulse extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/sendpulse');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/module/sendpulse');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['sendpulse_count'] = 0;
			$this->model_setting_setting->editSetting('sendpulse', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module/sendpulse', 'token=' . $this->session->data['token'], true));
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}		
		$data['token'] = $this->session->data['token'];
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');		
		$data['entry_book'] = $this->language->get('entry_book');
		$data['entry_book_default'] = $this->language->get('entry_book_default');
		$data['entry_status_add'] = $this->language->get('entry_status_add');		
		$data['entry_sendpulse_secret'] = $this->language->get('entry_sendpulse_secret');
		$data['entry_sendpulse_id'] = $this->language->get('entry_sendpulse_id');
		$data['entry_export'] = $this->language->get('entry_export');
		$data['error_select_book'] = $this->language->get('error_select_book');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_export'] = $this->language->get('button_export');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/sendpulse', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/sendpulse', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension/module', 'token=' . $this->session->data['token'], true);
		
		if (isset($this->error['sendpulse_id'])) {
			$data['error_sendpulse_id'] = $this->error['sendpulse_id'];
		} else {
			$data['error_sendpulse_id'] = '';
		}
		if (isset($this->error['sendpulse_secret'])) {
			$data['error_sendpulse_secret'] = $this->error['sendpulse_secret'];
		} else {
			$data['error_sendpulse_secret'] = '';
		}
		$id = $this->config->get('sendpulse_id');
		$secret = $this->config->get('sendpulse_secret');
		
		if (isset($this->request->post['sendpulse_id'])) {
			$data['sendpulse_id'] = $this->request->post['sendpulse_id'];
		} else {
			$data['sendpulse_id'] = $id;
		}
		if (isset($this->request->post['sendpulse_secret'])) {
			$data['sendpulse_secret'] = $this->request->post['sendpulse_secret'];
		} else {
			$data['sendpulse_secret'] = $secret;
		}
		if (isset($this->request->post['sendpulse_auto_add'])) {
			$data['status'] = $this->request->post['sendpulse_auto_add'];
		} else {
			$data['status'] = $this->config->get('sendpulse_auto_add');
		}		
		if (isset($this->request->post['sendpulse_book_default'])) {
			$data['sendpulse_book_default'] = $this->request->post['sendpulse_book_default'];
		} else {
			$data['sendpulse_book_default'] = $this->config->get('sendpulse_book_default');
		}		
		if($id != '' && $secret != '') {
			$data['books'] = $this->model_extension_module_sendpulse->getBooks($id, $secret);
			if(!$data['books']) $this->error['warning'] = $this->language->get('error_connect');
		}
		else $data['books'] = false;
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/sendpulse', $data));
	}

	public function export() {
		$this->load->language('extension/module/sendpulse');

		$this->load->model('extension/module/sendpulse');
		
		$id = $this->config->get('sendpulse_id');
		$secret = $this->config->get('sendpulse_secret');

		if ($this->user->hasPermission('modify', 'extension/module/sendpulse')) {
			if($id != '' && $secret != '' && isset($this->request->post['book'])) {
				$json = $this->model_extension_module_sendpulse->export($id, $secret, $this->request->post['book']);		
			} else $json['error'] = $this->language->get('error_export');
		} else $json['error'] = $this->language->get('error_permission');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/sendpulse')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if ((utf8_strlen($this->request->post['sendpulse_id']) < 20) || (utf8_strlen($this->request->post['sendpulse_id']) > 64)) {
			$this->error['sendpulse_id'] = $this->language->get('error_api');
		}

		if ((utf8_strlen($this->request->post['sendpulse_secret']) < 20) || (utf8_strlen($this->request->post['sendpulse_secret']) > 64)) {
			$this->error['sendpulse_secret'] = $this->language->get('error_api');
		}
		return !$this->error;
	}
}