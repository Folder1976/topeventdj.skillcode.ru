<?php

class ControllerDataexchangeExportOrders extends Controller {

    public function index(){
		
		//echo phpinfo();
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/attribute');
		$this->load->model('customer/customer');
        $this->load->model('sale/order');

	//die('111');	
        $output = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $output .= '<Orders xmlns="http://mynamespace.ua" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . "\n";

        $orders = $this->_getOrders('', 'product');

		
		
//die('<br>tetetet');
        foreach ($orders as $order) {
				
			$totals = $this->_getOrderTotals($order['order_id']);
			
			$products = $this->_getOrderProducts($order['order_id']);
	
			if($products){
				$commentData = array();
				$output .= ' <Order>' . "\n";
				$output .= '   <OrderId>'. $order['order_id'] .'</OrderId>' . "\n";
				$output .= '   <Date>' . date('c', strtotime($order['date_added'])) . '</Date>' . "\n";
				$output .= '   <Shipping>' . $order['shipping_method'] . '</Shipping>' . "\n";
				$output .= '   <Total>' . "";
				foreach($totals as $total) {
						if($total['code'] == 'total'){
							$output .= $total['value'];
						}
					//$output .= '       <'.$total['code'].'>' . $total['value'] . '</'.$total['code'].'>' . "\n";
				}
				$output .= '   </Totals>' . "\n";            
				$output .= '   <Payment>' . $order['payment_method'] . '</Payment>' . "\n"; 
				$output .= '   <Address>' . $order['shipping_address_1'] . '</Address>' . "\n";
				$output .= '   <Address2>' . $order['shipping_address_1'] . '</Address2>' . "\n";
				$output .= '   <Comment>' . $order['comment'] . '</Comment>' . "\n";
				$output .= '   <Customer>' . "\n";            
				if ($order['customer_id']) {
					$customer = $this->db->query("
						SELECT * FROM " . DB_PREFIX . "customer c
						WHERE c.customer_id = {$order['customer_id']}
					");
				}

				if (isset($customer) AND $customer) {
				
					$output .= '    <CustomerId>' . $order['customer_id'] . '</CustomerId>' . "\n";
					$output .= '    <Name>' . $order['firstname'] . ' '. $order['lastname'] . '</Name>' . "\n";
					$output .= '    <Inn>' . (isset($order['inn']) ? $order['inn'] : "") . '</Inn>' . "\n";
					$output .= '    <Phone>' . $order['telephone'] . '</Phone>' . "\n";	
					$output .= '    <Email>' . $order['email'] . '</Email>' . "\n";	

				}
				
				$output .= '   </Customer>' . "\n";
				
				$products = $this->_getOrderProducts($order['order_id']);

				$output .= '    <Products>' . "\n";

				foreach ($products as $product) {
			
					$categories = $this->model_catalog_product->getProductCategories($product['product_id']);
			//die('11');				
					foreach($categories as $index => $category_id){
						$categories[$index] = $this->model_catalog_category->getCategory($category_id);
					}
					
					$output .= '    <Order_product>' . "\n";
					$output .= '      <Id1c>' . $product['1c_id'] . '</Id1c>' . "\n";
					$output .= '      <Product>' . htmlspecialchars($product['name'], ENT_QUOTES) . '</Product>' . "\n";
					$output .= '      <Model>' . $product['model_product'] . '</Model>' . "\n";
					$output .= '      <Sku>' . $product['sku'] . '</Sku>' . "\n";
					$output .= '      <Manufacturer>' . htmlspecialchars($product['manufacturer'], ENT_QUOTES) . '</Manufacturer>' . "\n";                
					$output .= '      <Price>' . round($product['price'], 2) . '</Price>' . "\n";
					$output .= '      <Quantity>' . $product['quantity'] . '</Quantity>' . "\n";
					$output .= '      <Total>' . round($product['total'], 2) . '</Total>' . "\n";
					//$output .= '      <Categories>' . "\n";
					foreach($categories as $category){
						//$output .= '          <Category>' . $category['path'] . '</Category>' . "\n";
					}
					//$output .= '      </Categories>' . "\n";                
				
					$output .= '    </Order_product>' . "\n";
				}
				$order_status_id = $this->getOrderStatusId($order['order_status_id']);
				$output .= '   </Products>' . "\n";
				$output .= '   <OrderStatus>'. $order_status_id .'</OrderStatus>' . "\n";	
				$output .= ' </Order>' . "\n";
			}
        }

        $output .= '</Orders>';
	
	

		
		//Продукты
		$output1 = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $output1 .= '<Products xmlns="http://mynamespace.ua" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . "\n";

		$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
		$manufacturers[0]['name'] = '';
		
		$attributes = $this->getAttributeNames();
		
        $products = $this->model_catalog_product->getProducts();
		
		foreach ($products as $product) {
			
				//$data['product_description'] = $this->getProductDescriptions($product_id);
				//$data['product_filter'] = $this->getProductFilters($product_id);
				//$data['product_image'] = $this->getProductImages($product_id);
				//$data['product_related'] = $this->getProductRelated($product_id);
				//$data['product_reward'] = $this->getProductRewards($product_id);
				//$data['product_special'] = $this->getProductSpecials($product_id);
				//$data['product_download'] = $this->getProductDownloads($product_id);
				//$data['product_recurrings'] = $this->getRecurrings($product_id);

				$product_id = $product['product_id'];
				$output1 .= ' <Product>' . "\n";
				$output1 .= '   <ProductId>'. $product['product_id'] .'</ProductId>' . "\n";
				$output1 .= '   <Model> '. $product['model'] .'</Model>' . "\n";
				$output1 .= '   <Sku> '. $product['sku'] .'</Sku>' . "\n";
				//$output1 .= '   <UnitId> '. $product['unit_id'] .'</UnitId>' . "\n";
				$output1 .= '   <StockStatusId> '. $product['stock_status_id'] .'</StockStatusId>' . "\n";
				$output1 .= '   <Image> '. $product['image'] .'</Image>' . "\n";
				$output1 .= '   <Images>' . "\n";
					$images = $this->model_catalog_product->getProductImages($product_id);
					foreach($images as $image){
						$output1 .= '       <Image>' . $image['image'] . '</Image>' . "\n";
					}
				$output1 .= '   </Images>' . "\n";
		
				//$output1 .= '   <Manufacturer>' . htmlspecialchars($manufacturers[$product['manufacturer_id'] ]['name'], ENT_QUOTES) . '</Manufacturer>' . "\n";
				$output1 .= '   <Shipping>' . $product['shipping'] . '</Shipping>' . "\n";
				$output1 .= '   <Price>' . $product['price'] . '</Price>' . "\n";
				$output1 .= '   <Weight>' . $product['weight'] . '</Weight>' . "\n";
				$output1 .= '   <Sort>' . $product['sort_order'] . '</Sort>' . "\n";
				$output1 .= '   <Status>' . $product['status'] . '</Status>' . "\n";
				$output1 .= '   <MainCategoryId>' . $this->getProductMainCategoryId($product_id) . '</MainCategoryId>' . "\n";
				$output1 .= '   <CategoryIds>' . "\n";
					$product_category = $this->model_catalog_product->getProductCategories($product_id);
					foreach($product_category as $category_id){
						$output1 .= '       <CategoryId>' . $category_id . '</CategoryId>' . "\n";
					}
				$output1 .= '   </CategoryIds>' . "\n";
				$output1 .= '   <Attributes>' . "\n";
					$attributes = $this->model_catalog_product->getProductAttributes($product_id);
					foreach($attributes as $arribute){
						//$output1 .= '       <Attribute>' . htmlspecialchars($attributes[$arribute['attribute_id']], ENT_QUOTES) . '</Attribute>' . "\n";
						//$output1 .= '       <AttributeValue>' . htmlspecialchars($arribute['product_attribute_description'], ENT_QUOTES) . '</AttributeValue>' . "\n";
					}
				$output1 .= '   </Attributes>' . "\n";
				
				$output1 .= '   <Options>' . "\n";
					$options = $this->model_catalog_product->getProductOptions($product_id);
					foreach($options as $option){
						$output1 .= '       <Option>' . htmlspecialchars(var_dump($option), ENT_QUOTES) . '</Option>' . "\n";
					}
				$output1 .= '   </Options>' . "\n";
				
				
				$output1 .= '   <Name>' . htmlspecialchars($product['name'], ENT_QUOTES) . '</Name>' . "\n";
				$output1 .= '   <Tag>' . htmlspecialchars($product['tag'], ENT_QUOTES) . '</Tag>' . "\n";
				$output1 .= '   <MetaTitle>' . htmlspecialchars($product['meta_title'], ENT_QUOTES) . '</MetaTitle>' . "\n";
				//$output1 .= '   <MetaH>' . htmlspecialchars($product['meta_h1'], ENT_QUOTES) . '</MetaH>' . "\n";
				$output1 .= '   <MetaDescription>' . htmlspecialchars($product['meta_description'], ENT_QUOTES) . '</MetaDescription>' . "\n";
				$output1 .= '   <MetaKeyword>' . htmlspecialchars($product['meta_keyword'], ENT_QUOTES) . '</MetaKeyword>' . "\n";
				$output1 .= '   <MetaKeyword>' . htmlspecialchars($product['meta_keyword'], ENT_QUOTES) . '</MetaKeyword>' . "\n";
				$output1 .= '   <Description>' . htmlspecialchars($product['description'], ENT_QUOTES) . '</Description>' . "\n";
				
				$output1 .= ' </Product>' . "\n";
		}
		
		$output1 .= '</Products>';
		
	
		//Пользователи
		$output2 = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $output2 .= '<Customers xmlns="http://mynamespace.ua" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . "\n";

        $customers = $this->model_customer_customer->getCustomers();
		
		foreach ($customers as $customer) {
	
			
				$customer_id = $customer['customer_id'];
				$output2 .= ' <Customer>' . "\n";
				$output2 .= '   <CustomerId>'. $customer['customer_id'] .'</CustomerId>' . "\n";
				$output2 .= '   <Group>'. $customer['customer_group'] .'</Group>' . "\n";
				$output2 .= '   <FirstName>'. htmlspecialchars($customer['firstname'], ENT_QUOTES) .'</FirstName>' . "\n";
				$output2 .= '   <LastName>'. htmlspecialchars($customer['lastname'], ENT_QUOTES) .'</LastName>' . "\n";
				$output2 .= '   <Name>'. htmlspecialchars($customer['name'], ENT_QUOTES) .'</Name>' . "\n";
				$output2 .= '   <Description>'. htmlspecialchars($customer['description'], ENT_QUOTES) .'</Description>' . "\n";
				$output2 .= '   <Email>'. $customer['email'] .'</Email>' . "\n";
				$output2 .= '   <Telephone>'. $customer['telephone'] .'</Telephone>' . "\n";
				$output2 .= '   <Ip>'. $customer['ip'] .'</Ip>' . "\n";
				$output2 .= '   <Addresses>' . "\n";
					$adresses = $this->model_customer_customer->getAddresses($customer_id);
					foreach($adresses as $adress){
						
						$output2 .= '       <Address>' . "\n";
				
						$output2 .= '           <AddressId>'. $adress['address_id'] .'</AddressId>' . "\n";
						$output2 .= '           <FirstName>'. $adress['firstname'] .'</FirstName>' . "\n";
						$output2 .= '           <LastName>'. $adress['lastname'] .'</LastName>' . "\n";
						$output2 .= '           <Company>'. $adress['company'] .'</Company>' . "\n";
						$output2 .= '           <Address1>'. $adress['address_1'] .'</Address1>' . "\n";
						$output2 .= '           <Address2>'. $adress['address_2'] .'</Address2>' . "\n";
						$output2 .= '           <PostCode>'. $adress['postcode'] .'</PostCode>' . "\n";
						$output2 .= '           <City>'. $adress['city'] .'</City>' . "\n";
						$output2 .= '           <ZoneId>'. $adress['zone_id'] .'</ZoneId>' . "\n";
						$output2 .= '           <Zone>'. htmlspecialchars($adress['zone'], ENT_QUOTES) .'</Zone>' . "\n";
						$output2 .= '           <ZoneCode>'. $adress['zone_code'] .'</ZoneCode>' . "\n";
						$output2 .= '           <Country>'. htmlspecialchars($adress['country'], ENT_QUOTES) .'</Country>' . "\n";
							
						$output2 .= '       </Address>' . "\n";
				
					}
				$output2 .= '   </Addresses>' . "\n";
				
				$output2 .= ' </Customer>' . "\n";
		}
		
		$output2 .= '</Customers>';
		
	
	//Пользователи
		$output3 = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
        $output3 .= '<Categories xmlns="http://mynamespace.ua" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . "\n";

        $categories = $this->model_catalog_category->getCategories();
		
		foreach ($categories as $category) {
	
				$category_id = $category['category_id'];
				$output3 .= ' <Category>' . "\n";
				$output3 .= '   <CategoryId>'. $category['category_id'] .'</CategoryId>' . "\n";
				$output3 .= '   <ParentId>'. $category['parent_id'] .'</ParentId>' . "\n";
				$output3 .= '   <Sort>'. $category['sort_order'] .'</Sort>' . "\n";
				//$output3 .= '   <Status>'. $category['status'] .'</Status>' . "\n";
				$output3 .= '   <Name>'. htmlspecialchars($category['name'], ENT_QUOTES) .'</Name>' . "\n";
				
				$output3 .= ' </Category>' . "\n";
		}
		
		$output3 .= '</Categories>';
		
	
		
		/*header('Content-Type: application/xml; charset=utf-8');
        header("Content-Disposition: attachment; filename=orders.xml");
        ob_end_clean();
        ob_start();
        echo $output;
        ob_end_flush();*/
			
        $fp = fopen(DIR_CATALOG . '../exchange1c/to_1c_orders.xml', "w+");
        fwrite($fp, $output);
        fclose($fp);
        chmod(DIR_CATALOG . '../exchange1c/to_1c_orders.xml', 0777);
		
		/*
		$fp = fopen(DIR_CATALOG . '../exchange1c/to_1c_products.xml', "w+");
        fwrite($fp, $output1);
        fclose($fp);
        chmod(DIR_APPLICATION . '../exchange1c/to_1c_products.xml', 0777);
		
		$fp = fopen(DIR_CATALOG . '../exchange1c/to_1c_customers.xml', "w+");
        fwrite($fp, $output2);
        fclose($fp);
        chmod(DIR_APPLICATION . '../exchange1c/to_1c_customers.xml', 0777);
		
		$fp = fopen(DIR_CATALOG . '../exchange1c/to_1c_categories.xml', "w+");
        fwrite($fp, $output3);
        fclose($fp);
        chmod(DIR_APPLICATION . '../exchange1c/to_1c_categories.xml', 0777);
		*/
		$this->createFile('exchange1c/to_1c_orders.xml');
		//$this->createFile('exchange1c/to_1c_products.xml');
		//$this->createFile('exchange1c/to_1c_customers.xml');
		//$this->createFile('exchange1c/to_1c_categories.xml');

       // $this->db->query('DELETE FROM ' . DB_PREFIX . 'setting WHERE `key`="start_date_export_orders";');

	  
        if(isset($this->request->get['link'])) {
            die('exchange1c/*.xml was created.');
        } else {
            if (!isset($this->request->get['cron'])) {
                $this->session->data['success'] = 'Файлы сохранены в папке exchange1c.';
                echo $this->session->data['success'];
				//$this->response->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }
		
		
    }
	
	protected function _getManufacturerCode($category){
		$query = $this->db->query("SELECT m.manufacturercode FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "category c ON (m.manufacturer_id = c.manufacturer_id) WHERE c.category_id = '". $this->db->escape($category) ."'");
		
		if(isset($query->row['manufacturercode'])){
			return $query->row['manufacturercode'];
		}else{
			return '';
		}
	}

    protected function _getOrderProducts($order_id) {
		
		$sql = "
          SELECT op.*, p.model AS model_product, p.sku, p.1c_id, m.name AS manufacturer
          FROM " . DB_PREFIX . "order_product op
          LEFT JOIN " . DB_PREFIX . "product p ON op.product_id = p.product_id
		  LEFT JOIN " . DB_PREFIX . "manufacturer m ON p.manufacturer_id = m.manufacturer_id
		  WHERE order_id = '" . (int)$order_id . "'";
		//die($sql);
        $query = $this->db->query($sql);


        return $query->rows;
    }

     protected function _getOrderTotals($order_id) {
        $query = $this->db->query("
          SELECT *
          FROM " . DB_PREFIX . "order_total
		  WHERE order_id = '" . (int)$order_id . "'"
        );

        return $query->rows;
    }

    protected function _getOrders($data = array(), $type)
    {
        $sql = "SELECT o.*, o.shipping_address_2, o.telephone,  o.customer_id, o.order_id, o.email, shipping_city,
		CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os
		WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

        if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
            $sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
        } else {
            $sql .= " WHERE o.order_id IS NOT NULL";
        }
		
		//$sql .= " AND o.order_type = '" . $this->db->escape($type) . "'";

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $sql .= " AND o.telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (!empty($data['filter_shipping_address_2'])) {
            $sql .= " AND o.shipping_address_2 LIKE '%" . $this->db->escape($data['filter_shipping_address_2']) . "%'";
        }
       
        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
        }

        $sort_data = array(
            'o.order_id',
            'o.telephone',
            'o.shipping_address_2',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY o.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        } else {
            $sql .= " LIMIT 0, 200";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }
	
	protected function getOrderStatusId($order_status_id)
	{
		$query = $this->db->query("SELECT DISTINCT os1c.order_status_1c_id FROM ".DB_PREFIX."order_status_1c os1c
								  LEFT JOIN ".DB_PREFIX."os_1c_to_os os1c2os
								  ON(os1c2os.order_status_1c_site_id = os1c.order_status_1c_site_id)
								  WHERE os1c2os.order_status_id = '". (int)$order_status_id ."'");
		
		if($query->row){
			return $query->row['order_status_1c_id'];
		}	
	}
	
	protected function getTypeId($type_id)
	{
		$query = $this->db->query("SELECT DISTINCT t1c.type_1c_id FROM ".DB_PREFIX."type_1c t1c LEFT JOIN ".DB_PREFIX."type_1c_to_type t1c2t ON(t1c2t.type_1c_site_id = t1c.type_1c_site_id) WHERE t1c2t.type_id = '". (int)$type_id ."'");
		
		if($query->row){
			return $query->row['type_1c_id'];
		}
	}
	
	public function createFile($file){
			$_file = str_replace(array('exchange1c/','.xml'), '', strtolower($file));			
			if(file_exists(DIR_APPLICATION . '../' . $file)){
				if(!file_exists(DIR_APPLICATION . '../exchange1c/archive/')){
					mkdir(DIR_APPLICATION . '../exchange1c/archive/', 0755);
				}
				@copy(DIR_APPLICATION . '../' . $file, DIR_APPLICATION . '../exchange1c/archive/' . $_file . '-' . date('Y-m-d-H-i-s') . '.xml');
			}
	}
	public function getAttributeNames() {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "attribute_description agd ORDER BY name";
		$query = $this->db->query($sql);
		
		$return = array();
		
		foreach($query->rows as $attribute){
			$return[$attribute['attribute_id']] = $attribute['name'];
		}
		
		return $return;
	}
	public function getProductMainCategoryId($product_id) {
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND
								  main_category = '1' LIMIT 1");

		return ($query->num_rows ? (int)$query->row['category_id'] : 0);
	}
	
}