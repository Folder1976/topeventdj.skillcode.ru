<?php

class ControllerDataexchangeImport1c extends Controller {

  public function index() {
    if (isset($this->request->get['filename'])) {
        $uploadFiles = array('exchange1c/' . $this->request->get['filename'] . '.xml');
    } else {
        $uploadFiles = array(
          'exchange1c/from_1c_categories.xml',
          'exchange1c/from_1c_customers.xml',
          'exchange1c/from_1c_products.xml',
          'exchange1c/from_1c_order.xml1',
        );
    }
    
    
    // ===========================================================
    $sql = "SELECT *  FROM information_schema.columns 
							WHERE table_schema = '".DB_DATABASE."'
							  AND table_name   = '" . DB_PREFIX . "category'
							  AND column_name  = 'Id1c'";
	$r = $this->db->query($sql);
    
    if($r->num_rows == 0){
        $sql = "ALTER TABLE " . DB_PREFIX . "category ADD COLUMN Id1c varchar(20) DEFAULT NULL AFTER sort_order;";
        $this->db->query($sql);
    }
    // ===========================================================
    $sql = "DROP TABLE IF EXISTS `" . DB_PREFIX . "product_document`;";
    //$this->db->query($sql);
    
    $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_document` (
                        `product_id` int(11) NOT NULL,
                        `document` varchar(512) COLLATE utf8_bin NOT NULL
                     ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
    $this->db->query($sql);                
    // -------------------------------------------------------
    $sql = "SELECT *  FROM information_schema.columns 
							WHERE table_schema = '".DB_DATABASE."'
							  AND table_name   = '" . DB_PREFIX . "product'
							  AND column_name  = 'Id1c'";
	$r = $this->db->query($sql);
    
    if($r->num_rows == 0){
        $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN Id1c varchar(20) DEFAULT NULL AFTER `model`;";
        $this->db->query($sql);
    }
    // -------------------------------------------------------
    /*
    $sql = "SELECT *  FROM information_schema.columns 
							WHERE table_schema = '".DB_DATABASE."'
							  AND table_name   = '" . DB_PREFIX . "product'
							  AND column_name  = 'condition'";
	$r = $this->db->query($sql);
    
    if($r->num_rows == 0){
        $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN `condition` varchar(20) DEFAULT NULL AFTER `model`;";
        $this->db->query($sql);
    }
   // -------------------------------------------------------
    $sql = "SELECT *  FROM information_schema.columns 
							WHERE table_schema = '".DB_DATABASE."'
							  AND table_name   = '" . DB_PREFIX . "product'
							  AND column_name  = 'condition'";
	$r = $this->db->query($sql);
    
    if($r->num_rows == 0){
        $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN `unit` varchar(10) DEFAULT NULL AFTER `model`;";
        $this->db->query($sql);
    }
    */
    // ===========================================================
    
    
    foreach($uploadFiles as $file){
      
        $_file = str_replace( array( 'exchange1c/', '.xml' ), '', strtolower( $file ) );

        if(!file_exists(DIR_APPLICATION . '../' . $file )) continue;
        
        
        //Продукт
        if ( $_file == 'from_1c_categories' ) {
            
            $filetime = filemtime( DIR_APPLICATION . '../' . $file );
            $is_new_file = $this->checkDate( $filetime );
            $count = $this->checkCount();
    
            if ( $is_new_file || $count['active'] ) {
                if ( !$count['value'] ) {
                  $this->createFile( $file );
                }
      
                $data  = $this->_xml2array( file_get_contents( $file ) );
                
                $this->importCategory( $data, $count['value'], $filetime );
      
      
                $total = count($data['categories']['category']);
                echo "category import count: {$count['value']} from $total <br>";
            }
    
        //Продукт
        }elseif ( $_file == 'from_1c_customers') {
          $filetime = filemtime( DIR_APPLICATION . '../' . $file );
          $is_new_file = $this->checkDate( $filetime );
          $count = $this->checkCount();
  
          if ( $is_new_file || $count['active'] ) {
            if ( !$count['value'] ) {
              $this->createFile( $file );
            }
  
            $data  = $this->_xml2array( file_get_contents( $file ) );
            
            $this->importCustomers( $data, $count['value'], $filetime );
  
            $total = count($data['from_1c_customers']['customers']);
            echo "product import count: {$count['value']} from $total <br>";
          }
   
        //Кустомерс
        }elseif ( $_file == 'from_1c_products' OR $_file == 'from_1c_products_test') {
          $filetime = filemtime( DIR_APPLICATION . '../' . $file );
          $is_new_file = $this->checkDate( $filetime );
          $count = $this->checkCount();
  
          if ( $is_new_file || $count['active'] ) {
            if ( !$count['value'] ) {
              $this->createFile( $file );
            }
  
            $data  = $this->_xml2array( file_get_contents( $file ) );
            
            $this->importPrices( $data, $count['value'], $filetime );
  
            $total = count($data['products']['product']);
            echo "product import count: {$count['value']} from $total <br>";
          }
   
        //Заказы
        }elseif ( $_file == 'from_1c_order' ) {
          $filetime = filemtime( DIR_APPLICATION . '../' . $file );
          $is_new_file = $this->checkDate( $filetime );
          $count = $this->checkCount();
  
          if ( $is_new_file || $count['active'] ) {
            if ( !$count['value'] ) {
              $this->createFile( $file );
            }
  
            $data  = $this->_xml2array( file_get_contents( $file ) );
            
            $this->importOrders( $data, $count['value'], $filetime );
  
            $total = count($data['orders']);
            echo "product import count: {$count['value']} from $total <br>";
          }
   
        //Другие файлы
        }else{
          
        }
      
    }
    
    
    /*
    $log = date('Y-m-d H:i:s').' - '.implode(',', $uploadFiles) .' == ' .implode(',', $_GET);
    
    $this->load->model('dataexchange/import1c');
    if ($this->model_dataexchange_import1c->import($uploadFiles)) {
       echo $log ."- ok";
       file_put_contents('import_log.txt', $log . " - ok\n", FILE_APPEND);
    } else {
       echo $log ."- error";
       file_put_contents('import_log.txt', $log . " - error\n", FILE_APPEND);
    }
    */
    $this->cache->delete('product');
    return;
  }


  //import prices and products
  public function importOrders( $data, $count, $date = '' ) {
    
    if ( isset( $data['orders']['order'] ) && is_array( $data['orders']['order'] ) ) {
      $orderXml = $data['orders']['order'];

      $i = (int) $count;
      $n = (int) $count + 2000;
      $m = count( $orderXml );
   
      for ( $i; $i < $n && $i < $m; $i++ ) {
        $this->_updateInsertOrder( $orderXml[$i] );
      }

      echo '<br>Проверено заказов '.$i;
    }
  }

  
    public function importCustomers( $data, $count, $date = '' ) {
        if ( isset( $data['from_1c_customers']['customers']['customer'] ) && is_array( $data['from_1c_customers']['customers']['customer'] ) ) {
            
            foreach($data['from_1c_customers']['customers']['customer'] as $customer){
            
                $group_id = $this->getOrAddCustomerGroupId($customer['pricegroupname']);
            
                if($group_id){
                    
                    $data = array(
                                  'customer_group_id' => $group_id,
                                  'phone' => is_array($customer['phone']) ? '' : $customer['phone'],
                                  'ur' => $customer['ur'],
                                  'id' => $customer['id'],
                                  'name' => $customer['name'],
                                  );
                    
                    $customer_id = $this->getOrAddCustomerId($data);
                    
                    
                }
            }
        }
    }
  
    public function getOrAddCustomerId($data){
        $r = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$data['id'] . "' LIMIT 1");
        
        if($r->num_rows > 0){
            
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET
                                    customer_group_id = '" . (int)$data['customer_group_id'] . "',
                                    language_id = '2',
                                    firstname = '" . $this->db->escape($data['name']) . "',
                                    lastname = '" . $this->db->escape($data['ur']) . "',
                                    telephone = '" . $this->db->escape($data['phone']) . "'
                                    WHERE customer_id = '" . (int)$data['id'] . "'
                                    ");
            
        }else{
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer SET
                                    customer_id = '" . (int)$data['id'] . "',
                                    customer_group_id = '" . (int)$data['customer_group_id'] . "',
                                    language_id = '2',
                                    firstname = '" . $this->db->escape($data['name']) . "',
                                    lastname = '" . $this->db->escape($data['ur']) . "',
                                    telephone = '" . $this->db->escape($data['phone']) . "'
                                    ");
   
        }
    
       
		return $customer_id = $this->db->getLastId();
    }
  
    public function getOrAddCustomerGroupId($name){
        $r = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE LOWER(`name`) LIKE '" . mb_strtolower($name) . "' LIMIT 1");
        
        if($r->num_rows > 0){
            return (int)$r->row['customer_group_id'];
        }
    
        $this->db->query("INSERT INTO " . DB_PREFIX . "customer_group SET approval='0', sort_order = '0'");

		$customer_group_id = $this->db->getLastId();

		for($x=1;$x<=2;$x++){ 
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET
                                    customer_group_id = '" . (int)$customer_group_id . "',
                                    language_id = '" . (int)$x . "',
                                    name = '" . $this->db->escape($name) . "',
                                    description = '" . $this->db->escape($name) . "'
                                    ");
		}

		return $customer_group_id;
    }
  
  //import prices and products
  public function importCategory( $data, $count, $date = '' ) {
        if ( isset( $data['categories']['category'] ) && is_array( $data['categories']['category'] ) ) {
            
            foreach($data['categories']['category'] as $category){
                
                $parent_id = isset($category['parentid']) ? $category['parentid'] : 0;
                $category_id = 0;
                $sort_order = (int)$category['sort'];
                $name = trim($category['name']);
                
                $r = $this->db->query( "SELECT category_id FROM `" . DB_PREFIX . "category` WHERE Id1c='".$category['categoryid']."'");
                if($r->num_rows){
                    $category_id = $r->row['category_id'];
                }
                $r = $this->db->query( "SELECT category_id FROM `" . DB_PREFIX . "category` WHERE Id1c='".$parent_id."'");
                if($r->num_rows){
                    $parent_id = $r->row['category_id'];
                }
               
                $image = '';
                if(isset($category['image'])){
                    copy(DIR_APPLICATION.'../exchange1c/Pictures/'.$category['image'], DIR_IMAGE.'catalog/category/'.$category['image']);
                    $image = 'catalog/category/'.$category['image'];
                }
                
                $data = array(
                              'parent_id' => $parent_id,
                              'column' => '0',
                              'sort_order' => $sort_order,
                              'image' => $image,
                              'status' => '1',
                              'keyword' => strtolower($this->translitArtkl($name)),
                              'category_description' => array(
                                    '1' => array(
                                                 'name' => $name,
                                                 'description' => '',
                                                 'meta_title' => $name,
                                                 'meta_description' => $name,
                                                 'meta_keyword' => $name,
                                                 ),
                                    '2' => array(
                                                 'name' => $name,
                                                 'description' => '',
                                                 'meta_title' => $name,
                                                 'meta_description' => $name,
                                                 'meta_keyword' => $name,
                                                 ),
                              ),
                        );
                
                
                
                if($category_id == 0){
                    $category_id = $this->addCategory($data);
                }else{
                    $this->editCategory($category_id, $data);
                }
                
                $this->db->query( "UPDATE `" . DB_PREFIX . "category` SET Id1c='".$category['categoryid']."' WHERE category_id='".$category_id."'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '0'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '0', layout_id = '1'");
            }
            
        }
        
  }
  
   //import prices and products
  public function importPrices( $data, $count, $date = '' ) {
    if ( isset( $data['products']['product'] ) && is_array( $data['products']['product'] ) ) {
      $productsXml = $data['products']['product'];

      $i = (int) $count;
      $n = (int) $count + 2000;
      $m = count( $productsXml );

      if ( $i == 0 ) {
        $this->db->query( "UPDATE `" . DB_PREFIX . "setting` SET value = '1' WHERE `key` = 'import_active' AND `code` = 'import'" );
      }

      for ( $i; $i < $n && $i < $m; $i++ ) {
        $this->_updateInsertProduct( $productsXml[$i] );
      }

      if ( $m < $n ) {
        $this->db->query( "UPDATE `" . DB_PREFIX . "setting` SET value = '0' WHERE `key` = 'import_active' AND `code` = 'import'" );
        $this->db->query( "UPDATE `" . DB_PREFIX . "setting` SET value = '0' WHERE `key` = 'import_count' AND `code` = 'import'" );
        $this->db->query( "UPDATE `" . DB_PREFIX . "setting` SET value = '" . $date . "' WHERE `key` = 'import_date' AND `code` = 'import'" );
        
        //$this->setNullPrices();
        
      } else {
        $this->db->query( "UPDATE `" . DB_PREFIX . "setting` SET value = '" . $n . "' WHERE `key` = 'import_count' AND `code` = 'import'" );
      }
    }
  }

  protected function _updateInsertOrder( $data ) {
    
    $order_id = $data['orderid'];
    $product = $data['products'];
    
    $this->db->query( "DELETE FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '".$order_id."'" );
    
    $total = 0;
     
    if(isset($product['order_product']['model'])){
      $products = array($product['order_product']);
    }else{
      $products = $product['order_product'];
    }
      
    foreach($products as $product){
  
        $sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "product WHERE model = '" . $product['model'] . "' LIMIT 1;";
        $product_info = $this->db->query( $sql );
    
        if($product_info->num_rows){
          
          $product_id = $product_info->row['product_id'];
          
          $sql = "INSERT INTO " . DB_PREFIX . "order_product SET
                        order_id = '" . (int)$order_id . "',
                        product_id = '" . (int)$product_id . "',
                        name = '" . $product['product'] . "',
                        model = '" . $product['model'] . "',
                        quantity = '" . (int)$product['quantity'] . "',
                        unit_id = '0',
                       price = '" . (float)$product['price'] . "',
                       total = '" . (float)$product['total'] . "',
                       tax = '0',
                       reward = '0'
                        ;";
          $this->db->query( $sql );
          
          $total += (float)$product['total'];
          
        }
      
    }
    
    $sql = "UPDATE " . DB_PREFIX . "order_total SET
                            value = '" . (float)$total . "'
                            WHERE order_id = '" . $order_id . "' AND code = 'total';";
    $this->db->query( $sql );
    
    if(isset($data['totals']) AND count($data['totals']) > 0){
      
      foreach($data['totals'] as $code => $value){
        
            $sql = "UPDATE " . DB_PREFIX . "order_total SET
                            value = '" . (float)$total . "'
                            WHERE order_id = '" . $order_id . "' AND code = '".$code."';";
            $this->db->query( $sql );
        
      }
      
      
      $products = array($product['order_product']);
    }
    
    
  }
  protected function _updateInsertProduct( $data ) {
    
    if ( empty( $data['model'] ) AND empty( $data['Id1c'] )) {
      return FALSE;
    }

    
    $data['model'] = addslashes( $data['model'] );

    
    if ( !empty( $data['manufacturer'] ) ) {
      $manufacturer_name = $data['manufacturer'];
    } else {
      $manufacturer_name = 0;
    }
      
    //$sql = "SELECT DISTINCT * FROM {$this->DB_PREFIX}product WHERE 1c_id = '" . $data['id1c'] . "';";
    //$issetProduct = $this->db->query( $sql );
    
    $_SESSION['stop'] = true;
    
    $sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "product WHERE Id1c = '" . (isset($data['id1c']) ? $data['id1c'] : '$#%') . "';";
   
    $issetProduct = $this->db->query( $sql );
    
    if($issetProduct->num_rows == 0){
        $sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "product WHERE model = '" . $data['model'] . "';";
        $issetProduct = $this->db->query( $sql );
    }
    
        
    $manufacturer_id = $this->_updateInsertManufacturer( $manufacturer_name );

    
    //header('Content-Type: text/html; charset=utf-8');
   // echo '<pre>';print(var_dump($data['analoggroup']));
    //die();
    if (!isset($data['keyword'])) {
        $data['keyword'] = strtolower($this->translitArtkl($data['model'].'-'.$data['model']));
    }
    
    if($issetProduct->num_rows){
      
        $product_id = $issetProduct->row['product_id'];
    
        $sql = "UPDATE " . DB_PREFIX . "product SET ";
        
                          if(isset($data['sku']) ) $sql .= "sku = '" . $this->db->escape($data['sku']) . "', ";
                          if(isset($data['upc']) ) $sql .= "upc = '" . $this->db->escape($data['upc']) . "', ";
                          if(isset($data['analoggroup']) AND is_string($data['analoggroup'])) $sql .= "analoggroup = '" . $data['analoggroup'] . "', ";
                          if(isset($data['jan']) ) $sql .= "jan = '" . $this->db->escape($data['jan']) . "', ";
                          if(isset($data['isbn']) ) $sql .= "isbn = '" . $this->db->escape($data['isbn']) . "', ";
                          if(isset($data['location']) ) $sql .= "location = '" . $this->db->escape($data['location']) . "', ";
                          if(isset($data['quantity']) ) $sql .= "quantity = '" . (int)$data['quantity'] . "', ";
                          if(isset($data['minimum']) ) $sql .= "minimum = '" . (int)$data['minimum'] . "', ";
                          if(isset($data['subtract']) ) $sql .= "subtract = '" . $this->db->escape($data['subtract']) . "', ";
                          if(isset($data['stockstatusid']) ) $sql .= "stock_status_id = '" . (int)$data['stockstatusid'] . "', ";
                          if(isset($data['shipping']) ) $sql .= "shipping = '" . (int)$data['shipping'] . "', ";
                          if(isset($data['price']) ) $sql .= "price = '" . (float)$data['price'] . "', ";
                          if(isset($data['weight']) ) $sql .= "weight = '" . str_replace(',','.',$this->db->escape($data['weight'])) . "', ";
                          if(isset($data['height']) ) $sql .= "height = '" . $this->db->escape($data['height']) . "', ";
                          if(isset($data['status']) ) $sql .= "status = '" . $this->db->escape($data['status']) . "', ";
                          if(isset($data['sortorder']) ) $sql .= "sort_order = '" . $this->db->escape($data['sort_order']) . "', ";
                          if(isset($data['length']) ) $sql .= "length = '" . $this->db->escape($data['length']) . "', ";
                        if(isset($data['condition']) ) $sql .= "`condition` = '" . $this->db->escape($data['condition']) . "', ";

                          $sql .= "manufacturer_id = '" . (int)$manufacturer_id . "', date_modified = NOW()
                          WHERE product_id = '" . (int)$product_id . "'";
     
        $this->db->query($sql);
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' "); 
        for($x=1;$x<=2;$x++){                  
            $sql = "INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "',
                          name = '" . $this->db->escape($data['name']) . "', ";
                          $sql .= "description = '" . str_replace('#', '<br>', $this->db->escape($data['description'])) . "',";
                          $sql .= "tag = '" . $this->db->escape(isset($data['tag']) ? $data['tag'] : $data['name']) . "',";
                          $sql .= "meta_title = '" . $this->db->escape(isset($data['meta_title']) ? $data['meta_title'] : $data['name']) . "',";
                          $sql .= "short_description = '" . $this->db->escape(isset($data['shortdescription']) ? $data['shortdescription'] : ''). "',";
                          $sql .= "meta_description = '" . $this->db->escape(isset($data['meta_description']) ? $data['meta_description'] : $data['name']) . "',";
                          $sql .= "meta_keyword = '" . $this->db->escape(isset($data['meta_keyword']) ? $data['meta_keyword'] : $data['name']) . "', ";
                          $sql .= " language_id = '".$x."' ON DUPLICATE KEY UPDATE product_id = '" . (int)$product_id . "'";
           //echo '<ht>'.$sql;               
            $this->db->query($sql);
        }
        
        //echo '<br>'.$sql;
        $sql = str_replace("language_id = '1'","language_id = '2'",$sql);                          
        $this->db->query($sql);
        
        $store_id = 0;
        $layout_id = 0;
    	
        if (isset($data['maincategoryid'])) {
            
            $r = $this->db->query( "SELECT category_id FROM `" . DB_PREFIX . "category` WHERE Id1c='".$data['maincategoryid']."'");
            if($r->num_rows){
                $maincategoryid = $r->row['category_id'];
                $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$maincategoryid . "', main_category = 1");
            }
        }
        if (isset($data['productcategory'])) {
          foreach ($data['productcategory'] as $category_id) {
            $r = $this->db->query( "SELECT category_id FROM `" . DB_PREFIX . "category` WHERE Id1c='".$category_id."'");
            if($r->num_rows){
                $maincategoryid = $r->row['category_id'];
                $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = 0");
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$maincategoryid . "'");
            }
          }
        }

        if (isset($data['keyword'])) {
          $this->db->query("UPDATE " . DB_PREFIX . "url_alias SET keyword = '" . $this->db->escape($data['keyword']) . "' WHERE query = 'product_id=" . (int)$product_id . "'");
        }

        if (isset($data['priceopt'])) {
        
          $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '2'");
            
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "',
                         customer_group_id = '2',
                         quantity = '1', priority = '1', price = '" . (float)$data['priceopt'] . "',
                         date_start = '', date_end = ''");
        }
        
        /*
        <Price1>1</Price1> <!--name="НАЛ розничная"-->
        <Price2>2</Price2> <!--name="НАЛ со склада"-->
        <Price3>3</Price3> <!--name="НАЛ опт  заказ"-->
        <Price4>4</Price4> <!--name="НАЛ мелкий опт"-->
        <Price5>5</Price5> <!--name="БЕЗНАЛ со склада"-->
        <Price6>6</Price6> <!--name="БЕЗНАЛ опт  заказ"-->
        <Price7>7</Price7> <!--name="БЕЗНАЛ мелкий  заказ"-->
        <Price8>8</Price8> <!--name="Юр. лицо"-->
        */
        
        $prices_ids = array(
                            1=>1,
                            2=>8,
                            3=>5,
                            4=>4,
                            5=>9,
                            6=>6,
                            7=>7,
                            8=>3,
                            );
        
        foreach($prices_ids as $from => $to){
          if (isset($data['prices']['price'.$from])) {
          
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '".$to."'");
              
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "',
                           customer_group_id = '".$to."',
                           quantity = '1', priority = '1', price = '" . (float)$data['prices']['price'.$from] . "',
                           date_start = '', date_end = ''");
          }
        }
        
    
    }else{
      
        $sql = "INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', ";
        
                          if(isset($data['sku']) ) $sql .= "sku = '" . $this->db->escape($data['sku']) . "', ";
                          if(isset($data['upc']) ) $sql .= "upc = '" . $this->db->escape($data['upc']) . "', ";
                          if(isset($data['analoggroup']) AND is_string($data['analoggroup'])) $sql .= "analoggroup = '" . $data['analoggroup'] . "', ";
                          if(isset($data['jan']) ) $sql .= "jan = '" . $this->db->escape($data['jan']) . "', ";
                          if(isset($data['isbn']) ) $sql .= "isbn = '" . $this->db->escape($data['isbn']) . "', ";
                          if(isset($data['location']) ) $sql .= "location = '" . $this->db->escape($data['location']) . "', ";
                          if(isset($data['quantity']) ) $sql .= "quantity = '" . (int)$data['quantity'] . "', ";
                          if(isset($data['minimum']) ) $sql .= "minimum = '" . (int)$data['minimum'] . "', ";
                          if(isset($data['subtract']) ) $sql .= "subtract = '" . $this->db->escape($data['subtract']) . "', ";
                          if(isset($data['stockstatusid']) ) $sql .= "stock_status_id = '" . (int)$data['stockstatusid'] . "', ";
                          if(isset($data['shipping']) ) $sql .= "shipping = '" . (int)$data['shipping'] . "', ";
                          if(isset($data['price']) ) $sql .= "price = '" . (int)$data['price'] . "', ";
                          if(isset($data['weight']) ) $sql .= "weight = '" . $this->db->escape($data['weight']) . "', ";
                          if(isset($data['height']) ) $sql .= "height = '" . $this->db->escape($data['height']) . "', ";
                          if(isset($data['status']) ) $sql .= "status = '" . $this->db->escape($data['status']) . "', ";
                          if(isset($data['sortorder']) ) $sql .= "sort_order = '" . $this->db->escape($data['sort_order']) . "', ";
                          if(isset($data['length']) ) $sql .= "length = '" . $this->db->escape($data['length']) . "', ";

                          $sql .= "manufacturer_id = '" . (int)$manufacturer_id . "', date_added = NOW(), date_modified = NOW()";
      
        $this->db->query($sql);
                          
        $product_id = $this->db->getLastId();

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' ");
        for($x=1;$x<=2;$x++){ 
            $sql = "INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "',
                          name = '" . $this->db->escape($data['name']) . "', ";
                        
                        $sql .= "description = '" . str_replace('#', '<br>', $this->db->escape($data['description'])) . "',";
                          $sql .= "tag = '" . $this->db->escape(isset($data['tag']) ? $data['tag'] : $data['name']) . "',";
                          $sql .= "meta_title = '" . $this->db->escape(isset($data['meta_title']) ? $data['meta_title'] : $data['name']) . "',";
                          $sql .= "short_description = '" . $this->db->escape(isset($data['shortdescription']) ? $data['shortdescription'] : ''). "',";
                          //$sql .= "meta_h1 = '" . $this->db->escape(isset($data['meta_h1']) ? $data['meta_h1'] : $data['name']). "',";
                          $sql .= "meta_description = '" . $this->db->escape(isset($data['meta_description']) ? $data['meta_description'] : $data['name']) . "',";
                          $sql .= "meta_keyword = '" . $this->db->escape(isset($data['meta_keyword']) ? $data['meta_keyword'] : $data['name']) . "', ";
                          
                          $sql .= " language_id = '".$x."'";
                          
            $this->db->query($sql);
        }
        
        $store_id = 0;
        $layout_id = 0;
    		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
		

        if (isset($data['maincategoryid'])) {
            
            $r = $this->db->query( "SELECT category_id FROM `" . DB_PREFIX . "category` WHERE Id1c='".$data['maincategoryid']."'");
            if($r->num_rows){
                $maincategoryid = $r->row['category_id'];
                $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = '1'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$maincategoryid . "', main_category = 1");
            }
        }
        if (isset($data['productcategory'])) {
          foreach ($data['productcategory'] as $category_id) {
            $r = $this->db->query( "SELECT category_id FROM `" . DB_PREFIX . "category` WHERE Id1c='".$category_id."'");
            if($r->num_rows){
                $maincategoryid = $r->row['category_id'];
                $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND main_category = 0");
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$maincategoryid . "'");
            }
          }
        }
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
        
        if ($data['keyword']) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }else{
          $keyword = $this->translitArtkl($data['name']);
          $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "'");
        }

        if (isset($data['priceopt'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "',
                         customer_group_id = '6',
                         quantity = '1', priority = '1', price = '" . (float)$data['priceopt'] . "',
                         date_start = '', date_end = ''");
        }
        
        $_SESSION['stop'] = false;
      
    }
    
    
    //$this->db->query("DELETE FROM  " . DB_PREFIX . "product_special WHERE product_id='".$product_id."'");
    $this->db->query("DELETE FROM  " . DB_PREFIX . "product_discount WHERE product_id='".$product_id."'");
    if(isset($data['prices']['price'])){
        foreach($data['prices']['price'] as $price){
            
            $group_id = $this->getOrAddCustomerGroupId($price['pricename']);
            
            $sql = "INSERT INTO " . DB_PREFIX . "product_discount SET
                        product_id = '" . (int)$product_id . "',
                        customer_group_id = '".$group_id."',
                        quantity = '".(($price['quantity'] > 0) ? $price['quantity'] : 1)."',
                        priority = '1',
                        price = '" . (float)str_replace(',','.',$price['price']) . "',
                        date_start = '', date_end = ''";
            $this->db->query($sql);
        
        }
    }
    
    if(isset($data['image'])){
        
        if(!is_array($data['image'])){
            $this->db->query("UPDATE " . DB_PREFIX . "product SET `image`='catalog/products/".trim($data['image'])."' WHERE product_id='".$product_id."'");  
            copy(DIR_APPLICATION.'../exchange1c/Pictures/'.$data['image'], DIR_IMAGE.'catalog/products/'.$data['image']);
        }
      
    }
    
    $this->db->query("DELETE FROM  " . DB_PREFIX . "product_document WHERE product_id='".$product_id."'");
    if(isset($data['documentation'])){
        
        foreach($data['documentation'] as $documentation){
      
            if(!is_array($documentation)){
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_document SET `document`='documentation/".trim($documentation)."', product_id='".$product_id."'");  
                copy(DIR_APPLICATION.'../exchange1c/Pictures/'.$documentation, DIR_IMAGE.'../documentation/'.$documentation);
            }else{
                foreach($documentation as $row){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_document SET `image`='documentation/".trim($row)."', product_id='".$product_id."'");  
                    copy(DIR_APPLICATION.'../exchange1c/Pictures/'.$row, DIR_IMAGE.'../documentation/'.$row);
                }
            }
        }      
    }
    
    /*
    if((int)$data['model'] == 81102){
        echo '<br>'  .$data['model'];
        echo '<ht><pre>';print(var_dump($data['images']));echo '</pre>';
    }
    */
    
    $this->db->query("DELETE FROM  " . DB_PREFIX . "product_image WHERE product_id='".$product_id."'");  
    if(isset($data['images']) AND is_array($data['images']) AND count($data['images'])){
    
        foreach($data['images'] as $image){
    
            if(!is_array($image)){
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET `image`='catalog/products/".trim($image)."', product_id='".$product_id."'");  
                copy(DIR_APPLICATION.'../exchange1c/Pictures/'.$image, DIR_IMAGE.'catalog/products/'.$image);
            }else{
                foreach($image as $row){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET `image`='catalog/products/".trim($row)."', product_id='".$product_id."'");  
                    copy(DIR_APPLICATION.'../exchange1c/Pictures/'.$row, DIR_IMAGE.'catalog/products/'.$row);
                }
            }
        
        }
        
    }
    
    //Реокмендуемые
    $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id='".$product_id."'");
    if(isset($data['related']) AND !empty($data['related'])){
        
        $related = $data['related']['model']; //explode(';', $data['related']);
        
        foreach($related as $related_model){
           
            $prod_id = $this->getProductIdOnModel(trim($related_model));
            if($prod_id > 0){
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id='".(int)$product_id."', related_id='".(int)$prod_id."' ");
            }
        }
              
    }
    
    //Аналоги
    $this->db->query("DELETE FROM " . DB_PREFIX . "product_analog WHERE product_id='".$product_id."'");
    if(isset($data['related']) AND !empty($data['related'])){
        
        $related = $data['related']['model']; //explode(';', $data['related']);
        
        foreach($related as $related_model){
           
            $prod_id = $this->getProductIdOnModel(trim($related_model));
            if($prod_id > 0){
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_analog SET product_id='".(int)$product_id."', analog_id='".(int)$prod_id."' ");
            }
        }
              
    }
    
    // Атрибуты
    $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id='".$product_id."'");
    
    if(isset($data['attributes'])){
        
        foreach($data['attributes']['attribute'] as $attribute){
            
            if(isset($attribute['value']) AND !is_array($attribute['value'])){              
                $attribute_id = $this->getAttributeFromName($attribute['name']);
            
                for($x=1;$x<=2;$x++){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET 
                                product_id='".$product_id."',
                                attribute_id='".$attribute_id."',
                                language_id='".$x."',
                                text='".trim($attribute['value'])."'
                             ");
                }
            }
        }
    }
    
    if(isset($data['condition'])){
        
            $attribute_id = $this->getAttributeFromName('Состояние');
            
            for($x=1;$x<=2;$x++){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET 
                                product_id='".$product_id."',
                                attribute_id='".$attribute_id."',
                                language_id='".$x."',
                                text='".trim($data['condition'])."'
                             ");
            }
      
    }
    
    if(isset($data['unit'])){
        
            $attribute_id = $this->getAttributeFromName('Ед. измерения');
            
            for($x=1;$x<=2;$x++){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET 
                                product_id='".$product_id."',
                                attribute_id='".$attribute_id."',
                                language_id='".$x."',
                                text='".trim($data['unit'])."'
                             ");
            }
      
    }
    
    
      
    $this->cache->delete('product');
    
    return $product_id;
    
  
  }
  
  public function getProductIdOnModel($model){
    
        $sql = "SELECT DISTINCT product_id FROM " . DB_PREFIX . "product WHERE model = '" . $model . "' LIMIT 1;";
        $product = $this->db->query( $sql );
        
        if($product->num_rows){
            return (int)$product->row['product_id'];
        }
        
        return 0;
        
    }
    
  
  
  public function getAttributeFromName($name){
        $r = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE LOWER(`name`) LIKE '" . mb_strtolower($name) . "' LIMIT 1");
        
        if($r->num_rows > 0){
            return (int)$r->row['attribute_id'];
        }
    
        $r = $this->db->query("SELECT attribute_group_id FROM " . DB_PREFIX . "attribute_group LIMIT 1");
        $attribute_group_id = (int)$r->row['attribute_group_id'];
    
        $this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$attribute_group_id . "', sort_order = '0'");

		$attribute_id = $this->db->getLastId();

		for($x=1;$x<=2;$x++){ 
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$x . "', name = '" . $this->db->escape($name) . "'");
		}

		return $attribute_id;
  }
  
  public function translitArtkl($str) {
			$rus = array('и','і','є','Є','ї','\"','\'','.',' ','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
			$lat = array('u','i','e','E','i','','','','-','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
		return str_replace($rus, $lat, $str);
	}

  
  protected function _updateInsertManufacturer( $name ) {
    $language_id = 1;
    
    $issetManufacturer = $this->db->query( "SELECT DISTINCT * FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $name . "'" );

    if($issetManufacturer->num_rows){
      
      return (int)$issetManufacturer->row['manufacturer_id'];
      
    }
   
		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($name) . "', sort_order = '0'");

		$manufacturer_id = $this->db->getLastId();

        for ( $language_id=1; $language_id <= 2; $language_id++ ) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET
                     manufacturer_id = '" . (int)$manufacturer_id . "',
                     language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($name) . "'");
        }
                     
		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '".$manufacturer_id."', store_id = '0'");
			}
		}

    $keyword = $this->translitArtkl($name);
    
		$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "',
                     keyword = '" . $this->db->escape($keyword) . "'");
	
		$this->cache->delete('manufacturer');
    
    return $manufacturer_id;

  }
  
  public function checkDate( $date ) {
    $query = $this->db->query( "SELECT DISTINCT value FROM `" . DB_PREFIX . "setting` WHERE `code` = 'import' AND `key` = 'import_date'" );
    if ( !$query->row ) {
      $this->db->query( "INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'import', `key` = 'import_date', value = ''" );
      return true;
    } else {
      if ( $query->row['value'] == $date ) {
        return false;
      } else {
        return true;
      }
    }
  }

  public function checkCount() {
    $data  = array();

    $query = $this->db->query( "SELECT DISTINCT value FROM `" . DB_PREFIX . "setting` WHERE `code` = 'import' AND `key` = 'import_active'" );
    if ( !$query->row ) {
      $this->db->query( "INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'import', `key` = 'import_active', value = '1'" );
      $data['active'] = 1;
    } else {
      $data['active'] = $query->row['value'];
    }

    $query = $this->db->query( "SELECT DISTINCT value FROM `" . DB_PREFIX . "setting` WHERE `code` = 'import' AND `key` = 'import_count'" );
    if ( !$query->row ) {
      $this->db->query( "INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'import', `key` = 'import_count', value = '0'" );
      $data['value'] = 0;
    } else {
      $data['value'] = $query->row['value'];
    }

    return $data;
  }

   public function createFile( $file ) {
    $_file = str_replace( array( 'exchange1c/', '.xml' ), '', strtolower( $file ) );
    if ( file_exists( DIR_APPLICATION . '../' . $file ) ) {
      if ( !file_exists( DIR_APPLICATION . '../exchange1c/archive/' ) ) {
        //mkdir( DIR_APPLICATION . '../exchange1c/archive/', 0755 );
      }
      //@copy( DIR_APPLICATION . '../' . $file, DIR_APPLICATION . '../exchange1c/archive/' . $_file . '-' . date( 'Y-m-d-H-i-s' ) . '.xml' );
    }
  }

  
   protected function _xml2array( $contents, $get_attributes = 1, $priority = 'tag' ) {
    if ( !$contents ) {
      return array();
    }
    if ( !function_exists( 'xml_parser_create' ) ) {
      return array();
    }

    $parser = xml_parser_create( '' );
    xml_parser_set_option( $parser, XML_OPTION_TARGET_ENCODING, "UTF-8" );
    xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
    xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
    xml_parse_into_struct( $parser, trim( $contents ), $xml_values );
    xml_parser_free( $parser );

    if ( !$xml_values ) {
      return;
    }

    $xml_array   = array();
    $parents     = array();
    $opened_tags = array();
    $arr         = array();

    $current = &$xml_array;

    $repeated_tag_index = array();

    foreach ( $xml_values as $data ) {
      unset( $attributes, $value );
      extract( $data );

      $result          = array();
      $attributes_data = array();

      if ( isset( $value ) ) {
        if ( $priority == 'tag' ) {
          $result = $value;
        } else {
          $result['value'] = $value;
        }
      }

      if ( isset( $attributes ) and $get_attributes ) {
        foreach ( $attributes as $attr => $val ) {
          if ( $priority == 'tag' ) {
            $attributes_data[$attr] = $val;
          } else {
            $result['attr'][$attr] = $val;
          }
        }
      }

      $tag = strtolower( $tag );
      if ( $type == "open" ) {

        $parent[$level - 1] = &$current;
        if ( !is_array( $current ) or ( !in_array( $tag, array_keys( $current ) )) ) {
          $current[$tag] = $result;
          if ( $attributes_data ) {
            $current[$tag . '_attr'] = $attributes_data;
          }
          $repeated_tag_index[$tag . '_' . $level] = 1;

          $current = &$current[$tag];
        } else {

          if ( isset( $current[$tag][0] ) ) {
            $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
            $repeated_tag_index[$tag . '_' . $level] ++;
          } else {
            $current[$tag]                           = array( $current[$tag], $result );
            $repeated_tag_index[$tag . '_' . $level] = 2;

            if ( isset( $current[$tag . '_attr'] ) ) {
              $current[$tag]['0_attr'] = $current[$tag . '_attr'];
              unset( $current[$tag . '_attr'] );
            }
          }
          $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
          $current         = &$current[$tag][$last_item_index];
        }
      } elseif ( $type == "complete" ) {

        if ( !isset( $current[$tag] ) ) {
          $current[$tag]                           = $result;
          $repeated_tag_index[$tag . '_' . $level] = 1;
          if ( $priority == 'tag' and $attributes_data ) {
            $current[$tag . '_attr'] = $attributes_data;
          }
        } else {
          if ( isset( $current[$tag][0] ) and is_array( $current[$tag] ) ) {
            $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

            if ( $priority == 'tag' and $get_attributes and $attributes_data ) {
              $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
            }
            $repeated_tag_index[$tag . '_' . $level] ++;
          } else {
            $current[$tag]                           = array( $current[$tag], $result );
            $repeated_tag_index[$tag . '_' . $level] = 1;

            if ( $priority == 'tag' and $get_attributes ) {
              if ( isset( $current[$tag . '_attr'] ) ) {
                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                unset( $current[$tag . '_attr'] );
              }
              if ( $attributes_data ) {
                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
              }
            }
            $repeated_tag_index[$tag . '_' . $level] ++;
          }
        }
      } elseif ( $type == 'close' ) {
        $current = &$parent[$level - 1];
      }
    }
    return ($xml_array);
  }
  
  	public function addCategory($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$category_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('category');

		return $category_id;
	}

	public function editCategory($category_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('category');
	}


  
}
