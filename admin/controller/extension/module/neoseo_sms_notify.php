<?php

require_once( DIR_SYSTEM . "/engine/neoseo_controller.php");

class ControllerExtensionModuleNeoSeoSmsNotify extends NeoSeoController
{

	private $error = array();

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->_moduleSysName = "neoseo_sms_notify";
		$this->_logFile = $this->_moduleSysName . ".log";
		$this->debug = $this->config->get($this->_moduleSysName . "_debug") == 1;
	}

	public function index()
	{
		$this->checkLicense();
	
		$this->upgrade();

		$data = $this->language->load('extension/module/' . $this->_moduleSysName);

		$this->document->setTitle($this->language->get('heading_title_raw'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->_moduleSysName, $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($_GET["close"])) {
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->response->redirect($this->url->link('extension/module/' . $this->_moduleSysName, 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		$data = $this->initButtons($data);

		$data = $this->initBreadcrumbs(array(
			array("extension/module", "text_module"),
			array('extension/module/' . $this->_moduleSysName, "heading_title_raw")
		    ), $data);

		$this->load->model("extension/" . $this->_route . "/" . $this->_moduleSysName);
		$data = $this->initParamsListEx($this->{"model_extension_" . $this->_route . "_" . $this->_moduleSysName}->getParams(), $data);

		// Статусы
		$this->load->model('localisation/order_status');
		$this->load->model('localisation/language');
		$this->load->model('extension/module/neoseo_sms_notify');
		$languages = $this->model_localisation_language->getLanguages();

		$this->load->model('customer/customer_group');
		$items = $this->model_customer_customer_group->getCustomerGroups(array('sort' => 'cg.sort_order'));
		$customer_groups = array();
		foreach ($items as $item) {
			$customer_groups[$item['customer_group_id']] = $item['name'];
		}
		$data['customer_groups'] = $customer_groups;

		// Первичная инициализация с подгонкой под все имеющиеся языки и статусы,
		// чтобы потом не извращаться в .tpl
		$data[$this->_moduleSysName . '_templates'] = array();
		$orderStatuses = $this->model_localisation_order_status->getOrderStatuses();
		foreach ($orderStatuses as $id => $status) {
			$data[$this->_moduleSysName . '_templates'][$status["order_status_id"]]["name"] = $status["name"];
			$data[$this->_moduleSysName . '_templates'][$status["order_status_id"]][0]["subject"] = "";
			foreach ($languages as $language) {
				$data[$this->_moduleSysName . '_templates'][$status["order_status_id"]][$language['language_id']]["subject"] = "";
			}
			$data[$this->_moduleSysName . '_templates'][$status["order_status_id"]][0]["status"] = 0;
			foreach ($languages as $language) {
				$data[$this->_moduleSysName . '_templates'][$status["order_status_id"]][$language['language_id']]["status"] = 0;
			}
		}

		// А теперь восстанавливаем сохраненные данные
		$savedTemplates = $this->config->get($this->_moduleSysName . "_templates");
		if (!$savedTemplates) {
			$savedTemplates = $this->model_neoseo_sms_notify->defaultTemplates;
		}

		foreach ($savedTemplates as $id => $template) {
			if (isset($data[$this->_moduleSysName . '_templates'][$id])) {
				$data[$this->_moduleSysName . '_templates'][$id][0]["status"] = $template[0]["status"];
				foreach ($languages as $language) {
					if (isset($template[$language['language_id']]["status"])) {
						$data[$this->_moduleSysName . '_templates'][$id][$language['language_id']]["status"] = $template[$language['language_id']]["status"];
					}
				}

				$data[$this->_moduleSysName . '_templates'][$id][0]["subject"] = $template[0]["subject"];
				foreach ($languages as $language) {
					if (isset($template[$language['language_id']]["subject"])) {
						$data[$this->_moduleSysName . '_templates'][$id][$language['language_id']]["subject"] = $template[$language['language_id']]["subject"];
					}
				}
			}
		}

		$data['languages'] = $languages;

		ksort($data[$this->_moduleSysName . '_templates']);
		$sms_gatenames = array();

		$files = glob(DIR_SYSTEM . 'smsgate/*.php');

		foreach ($files as $file) {
			$sms_gatenames[basename($file, '.php')] = basename($file, '.php');
		}
		$data['sms_gatenames'] = $sms_gatenames;

		$data['fields'] = $this->getFields();
		$data['params'] = $data;
		$data['token'] = $this->session->data['token'];

		$data["logs"] = $this->getLogs();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/module/' . $this->_moduleSysName . '.tpl', $data));
	}

	public function check()
	{
		$gateName = trim($this->request->post["gate"]);
		$login = trim($this->request->post["login"]);
		$password = trim($this->request->post["password"]);
		$sender = trim($this->request->post["sender"]);
		$additional = trim($this->request->post["additional"]);
		$phone = trim($this->request->post["phone"]);
		$message = trim($this->request->post["message"]);
		$groups = trim($this->request->post["message"]);

		$this->load->model("tool/" . $this->_moduleSysName);
		$result = $this->model_tool_neoseo_sms_notify->sendSms($gateName, $login, $password, $sender, $additional, $phone, $message);

		$this->response->setOutput(json_encode(array("status" => ($result ? 1 : 0))));
	}

	protected function getFields()
	{
		$result = array();

		$result['order_status'] = $this->language->get("field_desc_order_status");
		$result['order_date'] = $this->language->get("field_desc_order_date");
		$result['date'] = $this->language->get("field_desc_date");
		$result['total'] = $this->language->get("field_desc_total");
		$result['sub_total'] = $this->language->get("field_desc_sub_total");
		$result['invoice_number'] = $this->language->get("field_desc_invoice_number");
		$result['comment'] = $this->language->get("field_desc_comment");
		$result['shipping_cost'] = $this->language->get("field_desc_shipping_cost");
		$result['tax_amount'] = $this->language->get("field_desc_tax_amount");
		$result['logo_url'] = $this->language->get("field_desc_logo_url");

		$sql = 'SHOW COLUMNS FROM `' . DB_PREFIX . 'order`';
		$query = $this->db->query($sql);
		foreach ($query->rows as $row) {
			if (!isset($result[$row['Field']])) {
				$field_name = $row['Field'];
				$field_desc = '';
				if ($this->language->get('field_desc_' . $field_name) != 'field_desc_' . $field_name) {
					$field_desc = $this->language->get('field_desc_' . $field_name);
				}
				$result[$field_name] = $field_desc;
			}
		}

		return $result;
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/module/' . $this->_moduleSysName)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

}

?>