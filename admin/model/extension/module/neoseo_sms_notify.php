<?php

require_once( DIR_SYSTEM . "/engine/neoseo_model.php");

class ModelExtensionModuleNeoSeoSmsNotify extends NeoSeoModel {

	public $defaultTemplates = array();

	public function __construct($registry) {
		parent::__construct($registry);
		$this->_moduleSysName = "neoseo_sms_notify";
		$this->_logFile = $this->_moduleSysName . ".log";
		$this->debug = $this->config->get($this->_moduleSysName . "_debug");

		$this->defaultTemplates = array(
			1 => array(
				0 => array(
					"status" => 1,
					"subject" => "У вас новый заказ №{order_id}",
				),
				1 => array(
					"status" => 1,
					"subject" => "We've get your order #{order_id}",
				),
				2 => array(
					"status" => 1,
					"subject" => "Мы получили ваш заказ №{order_id}",
				),
				3 => array(
					"status" => 1,
					"subject" => "Ми отримали ваше замовлення №{order_id}",
				),
			),
			2 => array(
				0 => array(
					"status" => 0,
					"subject" => "Заказ №{order_id} отправлен в обработку",
				),
				1 => array(
					"status" => 1,
					"subject" => "We've process your order #{order_id}",
				),
				2 => array(
					"status" => 1,
					"subject" => "Мы обрабатываем ваш заказ №{order_id}",
				),
				3 => array(
					"status" => 1,
					"subject" => "Ми обробляємо ваше замовлення №{order_id}",
				),
			),
			3 => array(
				0 => array(
					"status" => 0,
					"subject" => "Заказ №{order_id} отправлен Новой Почтой",
				),
				1 => array(
					"status" => 1,
					"subject" => "We've sent your order #{order_id} via Nova Poshta",
				),
				2 => array(
					"status" => 1,
					"subject" => "Мы отправили ваш заказ №{order_id} Новой Почтой",
				),
				3 => array(
					"status" => 1,
					"subject" => "Ми вiдправили ваше замовлення №{order_id} Новою Поштою",
				),
			),
			4 => array(
				0 => array(
					"status" => 0,
					"subject" => "Заказ №{order_id} отправлен УкрПочтой",
				),
				1 => array(
					"status" => 1,
					"subject" => "We've sent your order #{order_id} via UkrPoshta",
				),
				2 => array(
					"status" => 1,
					"subject" => "Мы отправили ваш заказ №{order_id} УкрПочтой",
				),
				3 => array(
					"status" => 1,
					"subject" => "Ми вiдправили ваше замовлення №{order_id} УкрПоштою",
				),
			),
			5 => array(
				0 => array(
					"status" => 0,
					"subject" => "Заказ №{order_id} отправлен курьером",
				),
				1 => array(
					"status" => 1,
					"subject" => "We've sent your order #{order_id} via currier",
				),
				2 => array(
					"status" => 1,
					"subject" => "Мы отправили ваш заказ №{order_id} курьером",
				),
				3 => array(
					"status" => 1,
					"subject" => "Ми вiдправили ваше замовлення №{order_id} кур'єром",
				),
			),
			6 => array(
				0 => array(
					"status" => 0,
					"subject" => "Заказ №{order_id} готов к самовывозу",
				),
				1 => array(
					"status" => 1,
					"subject" => "We've prepared your order #{order_id} for pickup",
				),
				2 => array(
					"status" => 1,
					"subject" => "Ваш заказ №{order_id} ожидает самовывоза",
				),
				3 => array(
					"status" => 1,
					"subject" => "Ваше замовлення №{order_id} очiкує на самовивезення",
				),
			),
			7 => array(
				0 => array(
					"status" => 0,
					"subject" => "Заказ №{order_id} завершен",
				),
				1 => array(
					"status" => 1,
					"subject" => "We've complete your order #{order_id}",
				),
				2 => array(
					"status" => 1,
					"subject" => "Ваш заказ №{order_id} завершен",
				),
				3 => array(
					"status" => 1,
					"subject" => "Ваше замовлення №{order_id} виконане",
				),
			),
		);

		$this->params = array(
			"status" => 1,
			"recipients" => '',
			"align" => "38 000 000 00 00",
			"debug" => 0,
			"gate" => "smscabru",
			"sms_gatenames" => "smscabru",
			"gate_login" => "",
			"gate_password" => "",
			"gate_sender" => "",
			"gate_additional" => "",
			"templates" => $this->defaultTemplates,
			"customer_group" => array($this->config->get('config_customer_group_id')),
			"review_status" => 0,
			"review_notification_message" => "",
		);
	}

	public function install() {
		//Работает только в ocStore, да еще и не меньше 2.1, а потому не годится
		//$this->load->model('extension/event');
		//$this->model_extension_event->addEvent($this->_moduleSysName, 'pre.order.history.add', 'module/neoseo_sms_notify/handler_order_history_add');

		$this->initParams($this->params);

	}

	public function uninstall() {
		//Работает только в ocStore, да еще и не меньше 2.1, а потому не годится
		//$this->load->model('extension/event');
		//$this->model_extension_event->deleteEvent($this->_moduleSysName);
	}

	public function upgrade() {
		$this->initParams($this->params);
	}
}
