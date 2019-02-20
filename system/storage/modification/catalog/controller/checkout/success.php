<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		$this->load->language('checkout/success');

		if (isset($this->session->data['order_id'])) {

				// Folder autologin on checkout * * * Start
		
				$this->load->model('account/customer');
				$customer_id = false;
		if (!$this->customer->isLogged()) {
			
			if(isset($this->session->data['payment_address']['email']) AND $this->session->data['payment_address']['email'] != ''){
				
				$customer_info = $this->model_account_customer->getCustomerByEmail($this->session->data['payment_address']['email']);
			
				if($customer_info){
					
					$customer_id = $customer_info['customer_id'];
				
					// Unset guest
					unset($this->session->data['guest']);
					
					if (!$this->customer->login($this->session->data['payment_address']['email'], '', true)) {
						$this->error['warning'] = $this->language->get('error_login');
		
						$this->model_account_customer->addLoginAttempt($this->session->data['payment_address']['email']);
					} else {
						$this->model_account_customer->deleteLoginAttempts($this->session->data['payment_address']['email']);
					}
		
					$this->db->query("UPDATE " . DB_PREFIX . "customer SET language_id = '" . (int)$this->config->get('config_language_id') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

					
					$this->model_account_customer->addLoginAttempt($this->session->data['payment_address']['email']);
					
					
		
					// Default Shipping Address
					$this->load->model('account/address');
		
					if ($this->config->get('config_tax_customer') == 'payment') {
						$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
					}
		
					if ($this->config->get('config_tax_customer') == 'shipping') {
						$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
					}
		
					// Wishlist
					if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
						$this->load->model('account/wishlist');
		
						foreach ($this->session->data['wishlist'] as $key => $product_id) {
							$this->model_account_wishlist->addWishlist($product_id);
		
							unset($this->session->data['wishlist'][$key]);
						}
					}
		
					// Add to activity log
					if ($this->config->get('config_customer_activity')) {
						$this->load->model('account/activity');
		
						$activity_data = array(
							'customer_id' => $this->customer->getId(),
							'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
						);
		
						$this->model_account_activity->addActivity('login', $activity_data);
					}
					
					
				}else{
					
					//Тут регистрация			
					if(!isset($this->session->data['lastname'])){
						$name = explode(' ', $this->session->data['firstname']);
						$this->session->data['lastname'] = isset($name[1]) ? $name[1] : '';
						$this->session->data['firstname']  = $name[0];
					}
					$pass = explode('.',  $_SERVER['HTTP_HOST']);
					
					$register = array('firstname' => $this->session->data['firstname'],
									  'lastname' => $this->session->data['lastname'],
									  'email' => trim($this->session->data['payment_address']['email']),
									  'telephone' => trim(str_replace(array(' ','_','-','(',')','[',']'), '', $this->session->data['payment_address']['telephone'])),
									  'fax' => '',
									  'password' =>$pass[0],
									  'company' => '',
									  'address_1' => isset($this->session->data['payment_address']['address_1']) ? trim($this->session->data['payment_address']['address_1']) : '',
									  'address_2' => isset($this->session->data['payment_address']['address_2']) ? trim($this->session->data['payment_address']['address_2']) : '',
									  'city' => isset($this->session->data['payment_address']['city']) ? trim($this->session->data['payment_address']['city']) : '',
									  'postcode' => isset($this->session->data['payment_address']['postcode']) ? trim($this->session->data['payment_address']['postcode']) : '',
									  'country_id' => isset($this->session->data['payment_address']['country_id']) ? trim($this->session->data['payment_address']['country_id']) : '',
									  'zone_id' => isset($this->session->data['payment_address']['zone_id']) ? trim($this->session->data['payment_address']['zone_id']) : '',
									  );
					
					$customer_id = $this->model_account_customer->addCustomer($register);

					// Clear any previous login attempts for unregistered accounts.
					$this->model_account_customer->deleteLoginAttempts(trim($this->session->data['payment_address']['email']));
		
					$this->customer->login($register['email'], '', true);
		
					unset($this->session->data['guest']);
		
					// Add to activity log
					if ($this->config->get('config_customer_activity')) {
						$this->load->model('account/activity');
		
						$activity_data = array(
							'customer_id' => $customer_id,
							'name'        => $register['firstname'] . ' ' . $register['lastname']
						);
		
						$this->model_account_activity->addActivity('register', $activity_data);
					}
				}
			
			}
						
		}
		
		if ($customer_id AND isset($this->session->data['order_id'])) {
		
			$this->db->query("UPDATE " . DB_PREFIX . "order SET customer_id = '" . (int)$this->db->getLastId() . "' WHERE order_id=".$this->session->data['order_id']);
		
		}
		
				// Folder autologin on checkout * * * End
					  
			$this->cart->clear();

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				if ($this->customer->isLogged()) {
					$activity_data = array(
						'customer_id' => $this->customer->getId(),
						'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
						'order_id'    => $this->session->data['order_id']
					);

					$this->model_account_activity->addActivity('order_account', $activity_data);
				} else {
					$activity_data = array(
						'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
						'order_id' => $this->session->data['order_id']
					);

					$this->model_account_activity->addActivity('order_guest', $activity_data);
				}
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}