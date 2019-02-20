<?php
class ControllerProductCategory extends Controller {

				// attribute_filter * * * Start
				public function getProductFilterOptions($product_id, $option_ids){
					
					if(count($option_ids) == 0){
						return array();
					}
					
					if(!$product_id){
						$sql = "SELECT *, '0' as is_product FROM " . DB_PREFIX . "option_value ov
							LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON ov.option_value_id = ovd.option_value_id
							WHERE ov.option_id IN (".implode(',', $option_ids).") ORDER BY sort_order";
							
					}else{
						$sql = "SELECT * FROM " . DB_PREFIX . "product_option_value pov
							LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON pov.option_value_id = ovd.option_value_id
							WHERE quantity > 0 AND product_id=".(int)$product_id." AND pov.option_id IN (".implode(',', $option_ids).")";
					}
				
					$r = $this->db->query($sql);
				
					$return = array();
				
					if($r->num_rows){
						foreach($r->rows as $row){
							$return[$row['option_value_id']] = $row;
						}
					}
					
					return $return;
				
				}
				public function getOptionAsFilter(){
					
					$sql = "SELECT * FROM " . DB_PREFIX . "option o LEFT JOIN " . DB_PREFIX . "option_description op ON o.option_id = op.option_id
							WHERE isfilter = 1 ORDER BY sort_order_filter";
					
					$r = $this->db->query($sql);
					
					$return = array();
					
					if($r->num_rows){
						foreach($r->rows as $row){
							$return[$row['option_id']] = $row;
							$return[$row['option_id']]['values'] = $this->getProductFilterOptions(false, array($row['option_id']));
							
						}
					}
					
					return $return;
					
				}
				public function filterOptionIds($product_ids, $ofilter){
		
					if(count($ofilter) == 0){
						return $product_ids;
					}
					
					$return_ids = array();
					
					$value_ids = array();
					
					foreach($ofilter as $option_id => $option_value_ids){
						$value_ids = array_merge($option_value_ids);
					}
					
				$sql = "SELECT product_id FROM " . DB_PREFIX . "product_option_value pov 
					WHERE product_id IN (".implode(',', $product_ids).") AND option_value_id IN (".implode(',', $value_ids).") AND quantity > 0";
			
					$r = $this->db->query($sql);
					
					foreach($r->rows as $row){
						$return_ids[] = $row['product_id'];
					}
					
					return $return_ids;
					
				}
				// attribute_filter * * * End
				
	public function index() {

			// Product edit link on front * * * Start
			if (isset($this->session->data['token'])) {
				$data['token'] = $this->session->data['token'];
			}
			// Product edit link on front * * * End
					  
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

				// attribute_filter * * * Start
				if (isset($this->request->get['ofilter'])) {
					foreach($this->request->get['ofilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ofilter['.$index.'][]')).'='.$row;
						}
					}
				}
	
				if (isset($this->request->get['ffilter'])) {
					foreach($this->request->get['ffilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ffilter['.$index.'][]')).'='.$row;
						}
					}
				}
				// attribute_filter * * * End
				

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];

			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

			$url = '';

				// attribute_filter * * * Start
				if (isset($this->request->get['ofilter'])) {
					foreach($this->request->get['ofilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ofilter['.$index.'][]')).'='.$row;
						}
					}
				}
	
				if (isset($this->request->get['ffilter'])) {
					foreach($this->request->get['ffilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ffilter['.$index.'][]')).'='.$row;
						}
					}
				}
				// attribute_filter * * * End
				

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}
				
				$filter_data = array(
					'filter_category_id' => $result['category_id'],
					'not_null'      => true,
					'filter_sub_category' => true,
				);
	
				$prices = $this->model_catalog_product->getMinMaxPriceProducts($filter_data);
				
				if ((int)$prices['min_price'] > 0) {
					$price = $this->currency->format($this->tax->calculate($prices['min_price'], 0, $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				$data['categories'][] = array(
					'thumb' => $image,
					'price' => $price,
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
				);
			}
// =============================================================================

			$data['lates_products'] = array();
			$results = array();
			
			if(isset($_COOKIE['IdProduto'])){
			$ids = explode(',', $_COOKIE['IdProduto']);
			foreach($ids as $id){
				if((int)$id > 0){
					$results[] = $this->model_catalog_product->getProduct($id);
				}
			}
			}
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				if ($result['quantity'] <= 0) {
					$stock = $result['stock_status'];
				} else {
					$stock = $this->language->get('text_instock');
				}
				
				$data['lates_products'][] = array(
					'product_id'  => $result['product_id'],
					'quantity'  => $result['quantity'],
					'thumb'       => $image,
					'stock'       => $stock,
					'attributes'  => $this->model_catalog_product->getProductAttributes($result['product_id']),
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
				);
			}
//=============================
			$data['products'] = array();

			$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'filter_sub_category' => true,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			
				// attribute_filter * * * Start
				$this->load->model('catalog/manufacturer');
				
				$products = $this->model_catalog_product->getTotalProductIds($filter_data);
				$prices = $this->model_catalog_product->getMinMaxPriceProducts($filter_data);
				
				$product_ids = array(); //Тут отфильтрованные ИД продукта
				$product_array = array(); //Тут все по продуктам отфильтрованное
				$filter_manufactures = array();
				$filter_attributes = array();
				$black_list = array();
				
				$option_ids = array();
				
				
				$data['filter_attribute_groups'] = $filter_attribute_groups = $this->model_catalog_product->getAttributeAsFilter();
				$data['filter_options'] = $this->getOptionAsFilter();
				
				foreach($data['filter_options']  as $row){
					$option_ids[] = $row['option_id'];
				}
				
				$ffilter= array();
				if(isset($this->request->get['ffilter'])){
					$ffilter = $this->request->get['ffilter'];
				}
		
				$ofilter= array();
				if(isset($this->request->get['ofilter'])){
					$ofilter = $this->request->get['ofilter'];
				}
		
				
				$size_filter = array(
					 'xxs' => 1000,
					 'xs' => 2000,
					 's' => 3000,
					 'm' => 4000,
					 'l' => 5000,
					 'xl' => 6000,
					 'xxl' => 7000,
					 '2xl' => 8000,
					 'xxxl' => 9000,
					 '3xl' => 10000,
					 'xxxxl' => 11000,
					 '4xl' => 12000,
					 
					 );

				 
				
				$products_attribures = array();
				
				//Бренды
				foreach($products as $index => $row){
					
					
					$options = $this->getProductFilterOptions($row['product_id'], $option_ids);
					
					foreach($options as $option){
						$data['filter_options'][$option['option_id']]['values'][$option['option_value_id']]['is_product'] = 1;
					}
					
					$filter_manufactures[$row['manufacturer_id']] = array();
					
					$attributes = array();
					$attributes = explode(';', mb_strtolower($row['text']));
					
					if(!isset($this->request->get['ffilter'])){
						$product_array[$index] = $row;
						$product_ids[$row['product_id']] = $row['product_id'];
					}else{
						$products_attribures[$row['product_id']][$row['attribute_id']] = $row['attribute_id'];
					}

					if(isset($this->request->get['manufacturer_id'])){
						if(!in_array((int)$row['manufacturer_id'], $this->request->get['manufacturer_id'])){
							
							$black_list[(int)$row['product_id']] = (int)$row['product_id'];
							continue;
						}
					}

				
					
					
					if(isset($filter_attribute_groups[$row['attribute_id']])){
					
						foreach($attributes as $a_id => $a_text){
							$a_text = mb_strtolower(trim($a_text));
							$attributes[$a_id] = $a_text;
							
							if(isset($size_filter[$a_text])){
								$filter_attributes[$row['attribute_id']][$a_text] = (int)$size_filter[$a_text];	
							}else{
								$filter_attributes[$row['attribute_id']][$a_text] = $a_text;	
							}
								
						}
					}else{
						continue;
					}
				
					if(!in_array((int)$row['product_id'], $black_list)){
						
						if(isset($ffilter[$row['attribute_id']]) ){
							
							$row_attribute = explode(';', mb_strtolower($row['text']));
							
							$find = false;
							
							foreach($ffilter[$row['attribute_id']] as $ffilter_row){
								if(in_array($ffilter_row, $row_attribute)){
									$find = true;
									break;
								}
							}
						
							if(!$find){
								$black_list[(int)$row['product_id']] = (int)$row['product_id'];
								continue;
							}
						}
						
						$product_array[$index] = $row;
						$product_ids[$row['product_id']] = $row['product_id'];
	
					}
				}
			
				foreach($black_list as $prod_id){
					unset($product_ids[$prod_id]);
					unset($products_attribures[$prod_id]);
					
				}
			
				//Чистим продукты у которых вообще нет таких атрибутов
				if(isset($this->request->get['ffilter'])){
					
					$ffilter_ids = array();
					
					foreach($ffilter as $id => $attr){
						$ffilter_ids[] = $id;
					}
					
					foreach($products_attribures as $prod_id => $attributes){
						$find = false;
				
						foreach($attributes as $attribute_id){
				
								if(in_array($attribute_id, $ffilter_ids)){
								
								$find = true;
								break;
							}
						}
						
						if(!$find){
							unset($product_ids[$prod_id]);
						}
					}
				}
			
				if(count($product_ids) == 0) $product_ids = array(-1976);
			
			
				unset($filter_manufactures[0]);
				foreach($filter_manufactures as $manufacturer_id => $row){
					
					$filter_manufactures[$manufacturer_id] = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
					
				}
			
				foreach($filter_attributes as $index => $row){
					asort($filter_attributes[$index]);
					
					foreach($filter_attributes[$index] as $iindex => $rrow){
						$filter_attributes[$index][$iindex] = $iindex;
					}
			
				}
			
				//Чистим опции от пустышек
				foreach($data['filter_options'] as $index1 => $option){
					foreach($option['values'] as $index2 => $value){
					
						if((int)$value['is_product'] == 0){
							unset($data['filter_options'][$index1]['values'][$index2]);
						}
					}
				}
			
				//если есть фильтр по опциям - отсеем и по нему ИД
				if(count($ofilter > 0)){
					
					$product_ids = $this->filterOptionIds($product_ids, $ofilter);
					
				}

				$product_total = count($product_ids);
				$data['filter_manufactures'] = $filter_manufactures;
				$data['filter_attributes'] = $filter_attributes;
				$data['prices'] = $prices;
				$this->session->data['filter_options'] = $data['filter_options'];
				$this->session->data['filter_manufactures'] = $data['filter_manufactures'];
				$this->session->data['filter_attributes'] = $data['filter_attributes'];
				$this->session->data['filter_attribute_groups'] = $data['filter_attribute_groups'];
				$this->session->data['prices'] = $data['prices'];
		
 				// attribute_filter * * * End

					  

			
				// attribute_filter * * * Start
				$results = $this->model_catalog_product->getProducts($filter_data, $product_ids);
				// attribute_filter * * * End
				

			foreach ($results as $result) {
				/*
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}
				*/

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				
				$image_first = $this->model_catalog_product->getProductImageFirst($result['product_id']);
				if ($image_first) {
					$image = $this->model_tool_image->resize($image_first['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}elseif ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				}

				$image2 = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
				

				if ($result['quantity'] <= 0) {
					$stock = $result['stock_status'];
				} else {
					$stock = $this->language->get('text_instock');
				}
				
				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'quantity'  => $result['quantity'],
					'image'       => $image2,
					'thumb'       => $image,
					'stock'       => $stock,
					'attributes'  => $this->model_catalog_product->getProductAttributes($result['product_id']),
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
				);
			}

			
			//die('--'.$category_id);
			
			if($category_id == 59){
				$products = $data['products'];
				unset($data['products']);
				while(count($products)){
					$id = array_rand($products, 1);
					$data['products'][$id] = $products[$id];
					unset($products[$id]);
				}
			}

			
			$url = '';

				// attribute_filter * * * Start
				if (isset($this->request->get['ofilter'])) {
					foreach($this->request->get['ofilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ofilter['.$index.'][]')).'='.$row;
						}
					}
				}
	
				if (isset($this->request->get['ffilter'])) {
					foreach($this->request->get['ffilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ffilter['.$index.'][]')).'='.$row;
						}
					}
				}
				// attribute_filter * * * End
				

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

				// attribute_filter * * * Start
				if (isset($this->request->get['ofilter'])) {
					foreach($this->request->get['ofilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ofilter['.$index.'][]')).'='.$row;
						}
					}
				}
	
				if (isset($this->request->get['ffilter'])) {
					foreach($this->request->get['ffilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ffilter['.$index.'][]')).'='.$row;
						}
					}
				}
				// attribute_filter * * * End
				

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

				// attribute_filter * * * Start
				if (isset($this->request->get['ofilter'])) {
					foreach($this->request->get['ofilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ofilter['.$index.'][]')).'='.$row;
						}
					}
				}
	
				if (isset($this->request->get['ffilter'])) {
					foreach($this->request->get['ffilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ffilter['.$index.'][]')).'='.$row;
						}
					}
				}
				// attribute_filter * * * End
				

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page='. ($page + 1), true), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			//Если будут возникать пробелмы с категори ИД - нужно будет добавить ее автоопределение	
			if($category_info['category_id'] == 60){
				$this->response->setOutput($this->load->view('product/category_news', $data));	
			}else{
				$this->response->setOutput($this->load->view('product/category', $data));
			}
			
				
		} else {
			$url = '';

				// attribute_filter * * * Start
				if (isset($this->request->get['ofilter'])) {
					foreach($this->request->get['ofilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ofilter['.$index.'][]')).'='.$row;
						}
					}
				}
	
				if (isset($this->request->get['ffilter'])) {
					foreach($this->request->get['ffilter'] as $index => $rows){
						foreach($rows as $row){
							$url .= '&'.htmlentities(urlencode('ffilter['.$index.'][]')).'='.$row;
						}
					}
				}
				// attribute_filter * * * End
				

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}

