<?php
class ControllerextensionModulelastviewed extends Controller {
 public function index($setting) {
  $this->load->language('module/lastviewed');
  
  $data['heading_title']   = $this->language->get('heading_title');
  $data['text_tax']        = $this->language->get('text_tax');
  $data['button_cart']     = $this->language->get('button_cart');
  $data['button_wishlist'] = $this->language->get('button_wishlist');
  $data['button_compare']  = $this->language->get('button_compare');
  
  $this->load->model('catalog/product');
  $this->load->model('tool/image');
  
  $data['products'] = array();  
  $product_data     = array();
  
  if(isset($setting['limit']) && $setting['limit']!=''){
	 $setting['limit'] = $setting['limit'];
  }
  else{
	   $setting['limit'] = 4;
  }
  //$setting['limit'] = 4;
  	 
				
			$array_ids_a_mostrar = array();	  
	  $checagem_status = array();
	  $array_checagem = array();
  
  if(isset($_COOKIE['IdProduto'])){	  
	  $array_checagem = explode(',',substr($_COOKIE['IdProduto'],0,-1));    //Tira a última vírgula da string armazenada
	  $contprods = 0;
	  foreach($array_checagem as $id_a_checar){
		$checagem_status = $this->model_catalog_product->getProduct($id_a_checar);
		if($checagem_status['status']){
			$array_ids_a_mostrar[$contprods++] = $id_a_checar;
		}
	  }
	  if($setting['limit']>count($array_ids_a_mostrar)){           //Se o número de produtos visitados válidos é menor do que o número de produtos que o módulo
		$setting['limit'] = count($array_ids_a_mostrar);           //vai mostrar, então reconfigura o número de produtos que vai mostrar
	  }	  
  }
	
	$ultimos_finalizado = array_reverse($array_ids_a_mostrar);
	
    $count = $setting['limit'];
    foreach($ultimos_finalizado as $in => $row){
        if($count-- < 1){
            unset($ultimos_finalizado[$in]);
        }
    }
    
    //array_splice($ultimos_finalizado,$setting['limit'], 0);

  foreach ($ultimos_finalizado as $result) {
   $product_data[$result] = $this->model_catalog_product->getProduct($result);
  } 	  
		  
  $results = $product_data;

		
  if ($results) {
  foreach ($results as $result) {
   if ($result['image']) {
    $image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
   } else {
    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
   }
   
   
   if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
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
    $rating = $result['rating'];
   } else {
    $rating = false;
   }
       
   $data['products'][] = array(
    'product_id'   => $result['product_id'],
    'thumb'        => $image,
    'name'         => $result['name'],
				'upc'        => $result['upc'],
				'quantity'  => $result['quantity'],
    'description'  => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
    'price'        => $price,
    'special'      => $special,
    'tax'          => $tax,
    'rating'       => $rating,
    'href'         => $this->url->link('product/product', 'product_id=' . $result['product_id']),
   );
  }

	
		
		return $this->load->view('extension/module/lastviewed', $data);
		

     }
 }
}