<?php
class ControllerExtensionModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$data['language_id'] = (int)$this->config->get('config_language_id');
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');
		$this->load->model('catalog/category');

		$this->load->model('tool/image');

		$data['rand'] = array();
		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			
			if(isset($this->request->get['path']) AND $this->request->get['path'] == 60){
				$r = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE category_id=60");
			}else{
				$r = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE category_id=59");	
			}
			
			foreach($r->rows as $row){
			
			//foreach ($products as $product_id) {
				$product_id = $row['product_id'];
			
				$product_info = $this->model_catalog_product->getProduct($product_id);
	
				if ($product_info) {
					
					$image_first = $this->model_catalog_product->getProductImageFirst($product_id);
					if ($image_first) {
						$image = $this->model_tool_image->resize($image_first['image'], $setting['width'], $setting['height']);
					}elseif ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					$image2 = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
					
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$category_id = $this->model_catalog_product->getCategory($product_info['product_id']);
					$category_path = $this->model_catalog_category->getCategoryPath($category_id);
					
					//array_pop($category_path);
					$data['rand'][$product_info['product_id']] = $product_info['product_id'];
					
					$data['products'][$product_info['product_id']] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'image'       => $image2,
						'name'        => $product_info['name'],
						//'is_special'        => $product_info['is_special'],
						//'is_recommended'        => $product_info['is_recommended'],
						//'on_image'        => $product_info['on_image'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'path=' . implode('_', $category_path) . '&product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		$products = $data['products'];
		unset($data['products']);
		while(count($products)){
			$id = array_rand($products, 1);
			$data['products'][$id] = $products[$id];
			unset($products[$id]);
		}

		
		if ($data['products']) {
			
			if(isset($this->request->get['path']) AND $this->request->get['path'] == 60){
				return $this->load->view('extension/module/featured_news', $data);
			}else{
				return $this->load->view('extension/module/featured', $data);
			}
		}
	}
}