
<?php
set_time_limit(10000);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'constants.php';



$postav_id = 1; //Folder
$pars_key = 'tiande';
$pars_table = 'tbl_parsing_tiande';
$add_row_key = 'catalog/';
$http = 'http://tiande.ru/';
$http2 = 'http://www.tiande.ru/';
$pausa = 10;
$currency = 1;
$kurs = 1;
$skidka = 0.58;
$nacenka = 1; //Наценка на розницу! не на закуп!!!
$brand_id = 1; //Гарсинг
//$category_id = 407;
//define('UPLOAD_DIR', '/tiande-brovary.com/www/images/stories/virtuemart/product/');
define('UPLOAD_DIR', '../../images/stories/virtuemart/product/');

//$nacenka110 = 1.10; //Наценка на розницу! не на закуп!!!
//$nacenka135 = 1.35; //Наценка на розницу! не на закуп!!!
//$nacenka110 = $nacenka110 * 1.04; //наценили еще 4%
//$nacenka135 = $nacenka135 * 1.04;

function sort_by_len($f,$s)
{
	if(strlen($f)<strlen($s)) return true;
	else return false;
}

?>

<style>
	.error{
		display: block;
		position: absolute;
		left: 10px;
		top: 10px;
		background-color: #FFCCC9;
		border: 2px solid gray;
		border-radius: 3px;
		padding: 10px;
	}
	.key_close{
		cursor: pointer;
	}
	#container{
		display: none;
		position: absolute;
		left: 10px;
		top: 80px;
		width: 600px;
		background-color: #C9F7FF;
		border: 2px solid gray;
		border-radius: 3px;
		padding-right: 10px;
	}
	
	.tree{
		margin-left: 15px;
	}
	.tree_ul{
		margin-left: 0px;
	}
	.handle {
		background: transparent url(images/tree-handle.png) no-repeat left top;
		display:block;
		float:left;
		width:15px;
		height:17px;
		cursor:pointer;
	}
	    .product-type-edit  li {
        list-style-type: none; 
    }
       
    .product-type-edit ul {
        margin-top: 15px;
        margin-left: 20px; /* Отступ слева в браузере IE и Opera */
        padding-left: 0; /* Отступ слева в браузере Firefox, Safari, Chrome */
    }
	li {
        padding-top: 3px;
        padding-bottom: 4px;
        list-style-type: none; 
    }
	.closed { background-position: left 2px; }
	.opened { background-position: left -13px; }
</style>
  <div class="msg_back"></div>
  
  <style>
	.msg_back{width: 100%;height: 100%;opacity: 0.7;display: none;position: absolute;background-color: gray;top:0;left:0;}
  </style>
<?php
//=================================================================================================================================


?>
<br><br>
<a href="?func=add_products&supplier=<?php echo $pars_key;?>&resetlinks">Обнулить сайтмап (заново)</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="?func=add_products&supplier=<?php echo $pars_key;?>&links"><b><font color="blue">Продолжить парсить</font></b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="?func=add_products&supplier=<?php echo $pars_key;?>&links&unset"><b><font color="orange">Пропустить товар</font></b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<?php

//просто парсим ссылки
if(isset($_GET['resetlinks'])){
	
	$sql = 'UPDATE '.$pars_table.' SET view = \'0\' WHERE view = "1";';
	$mysqli->query($sql) or die('==' . $sql);

}
		$sql = 'DELETE FROM '.$pars_table.' WHERE url LIKE \'%/en/catalog/%\' OR  url LIKE \'%/cz/catalog/%\' OR  url LIKE \'%/pl/catalog/%\';';
		$url = $mysqli->query($sql) or die('==' . $sql);
		
if(isset($_GET['links'])){
		include 'simple_html_dom/simple_html_dom.php';

		//тупо посчитаем все запись
		$sql = 'SELECT count(id) AS id FROM '.$pars_table.';';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$all = $tmp['id'];

		$sql = 'SELECT count(id) AS id FROM '.$pars_table.' WHERE view = \'0\';';
		$tmp = $mysqli->query($sql) or die('==' . $sql);
		$tmp = $tmp->fetch_assoc();
		$none = $tmp['id'];
echo '<b>Всего ликов - '.$all.'. Пропарсено - '.($all - $none).'. Осталось - '.$none.'.</b>';
		
	   
	if(isset($_GET['unset'])){
		$sql = 'SELECT id FROM '.$pars_table.' WHERE view = \'0\' LIMIT 1;';
		$url = $mysqli->query($sql) or die('==' . $sql);
		$list = $url->fetch_assoc();
		$sql = 'UPDATE '.$pars_table.' SET view = \'2\' WHERE id = "'.$list['id'].'";';
		$url = $mysqli->query($sql) or die('==' . $sql);
		
		?>
			<script>
				function reload() {
					location.href() = '?func=add_products&supplier=<?php echo $pars_key; ?>&links';
				}
			</script>
		<?php
	}
	    
		$sql = 'SELECT * FROM '.$pars_table.' WHERE view = \'0\' LIMIT 1;';
		$url = $mysqli->query($sql) or die('==' . $sql);
	

	
	
	if($url->num_rows > 0){
				$list = $url->fetch_assoc();

echo ' <b>Урл ID - '.$list['id'].'. </b>'; 
			
			if(isset($_GET['url'])){
				$list['url'] = $_GET['url'];
			}
				$brand = '';
				$price = '';
				$view = '';
				//$size = '';
				
				//Если попадаются кривые линки!!!!!!!
				if(strpos($list['url'], $http)){
					$list['url'] = str_replace($http,'',$list['url']);
					
					$sql = 'UPDATE '.$pars_table.' SET `url` = \''.$list['url'].'\' WHERE `id` = \''.$list['id'].'\';';
					$mysqli->query($sql) or die('==' . $sql);
		
					echo '<h3 style="color:orange;">url - '.$list['url'].'</h3>';
				}else{
					echo '<h3 style="color:green;">url - '.$list['url'].'</h3>';
				}
	
				//Для тестов
//$list['url'] = 'http://tiande.ru/catalog/dlya_volos/sredstva_ne_trebuyuschie_smyvaniya/1252112/';
//$list['url'] = 'http://wht.ru/shop/catalog/wear/ARXUS_CLOTHES/MICROCLIMATE/SWAETER_MICRO/21439.php';
//echo 	$list['url']; die();

				$html = @file_get_html($list['url']);
				
				$sql = 'UPDATE '.$pars_table.' SET view = "1" WHERE `url` = \''.$list['url'].'\';';
				//echo $sql; die();
				//$folder->query($sql) or die('==' . $sql);
				
				//Если это кривой линк - возможно удаленный товар
				if(!$html){
					header("Content-Type: text/html; charset=UTF-8");
					echo "<pre>";  print_r(var_dump( $list['url'] )); echo "</pre>";
					?>
					<h3>Ошибка 404</h3>
						<script>
							$(document).ready(function(){
								<?php if($error == 0 OR $name == 'catalogue'){ 
									echo 'setTimeout(reload, '.$pausa.'000);';
								 } ?>
							}
							);
							
							function reload() {
								location.reload();
							}
						</script>
					<?php
					return false;
				}
				
				//Хлебная крошка
				$tmp = $html->find('.breadcrumbs',0);
				if($tmp){
					$breadcrumbs_html = $tmp->innertext();
					$html_tmp = str_get_html($breadcrumbs_html);
					$breadcrumbs_html = $html_tmp->find('li a');
					$breadcrumbs = array();
					foreach($breadcrumbs_html as $tt){
						$breadcrumbs[$tt->innertext()] = $tt->innertext();
					}
					echo 'Крошки родителя ($breadcrumbs_txt => $category_id)-> <br><b>'.implode('>',$breadcrumbs).'</b><br>';
					$breadcrumbs_txt = implode('>',$breadcrumbs);
				}else{
					$breadcrumbs_txt = '';
				}
				
				//Массив ссылок
				$str_tmp = $html->find('a');
				
				$artkl = '';
/* =================================================================================================================================
 * =================================================================================================================================
 * =================================================================================================================================
											//Это товар
 * =================================================================================================================================
 * =================================================================================================================================
 */ 
				//if($html->find('.product-text h1',0)){
				if($html->find('.product-right',0)){
					//============================================================================================
					$name = $html->find('.product-text h1',0)->innertext();
					echo '<br>Название ($name) -> <b><font color="red">'.$name.'</font></b>';
					//============================================================================================
					$artkl = $html->find('.code-p',0)->innertext();
					$artkl = trim($artkl);
					$artkl = str_replace('Код:', '', $artkl);
					$artkl = trim($artkl);
					echo '<br>Артикл ($artkl) -> <b><font color="green">'.$artkl.'</font></b>';
					//============================================================================================
					$size_original = $html->find('.obyom-p',0)->innertext();
					$size = trim($size_original);
					$size = preg_replace("/\D/","",$size);
					$size = trim($size, ',');
					echo '<br>Обьем/Вес ($size) -> <b><font color="orange">'.$size_original. ' => '.$size.'</font></b>';
					//============================================================================================
					$price = $html->find('.now-price',0)->innertext();
					$price = trim($price);
					$price = preg_replace("/\D/","",$price);
					$price = $price / 100;
					echo '<br>Цена ($price) -> <b><font color="blue">'.$price.'</font></b>';
					//============================================================================================
					$bonus_original = $html->find('.bonus',0)->innertext();
					$bonus = trim($bonus_original);
					$bonus = preg_replace("/\D/","",$bonus);
					$bonus = $bonus / 100;
					echo '<br>Бонус ($bonus) -> <b><font color="blue">'.$bonus_original. ' => '.$bonus.'</font></b>';
					//============================================================================================
					//============================================================================================
					
					$image = array();
					$image_small = array();
					$t = $html->find('#single_image');
					foreach($t as $tt){
						
						$tmp = ''.$tt->href;
						
						$tmp = $http.''.$tmp;$tmp = str_replace('//', '/', $tmp);
								$tmp = str_replace('http:/', 'http://', $tmp);
						$image[] = $tmp;
						$image_small[] = $tmp;
						/*
						if(strpos($tmp, 'upload/resize_cache/') !== false){
							if(strpos($tmp, $pars_key) === false){
								$tmp = $http.''.$tmp;
								$tmp = str_replace('//', '/', $tmp);
								$tmp = str_replace('http:/', 'http://', $tmp);
							}
								$tmp = str_replace('400_400_1/','',$tmp);
								$tmp = str_replace('resize_cache/','',$tmp);
							$image[] = $tmp;
						
							$image_small[] = $tmp;
						}*/
						
					}
						
					echo '<br>';
					foreach($image_small as $index => $img){
						if(is_string($img)){
							echo '<img width="150" src="'.$img.'">';	
						}
					}
					//============================================================================================
					//============================================================================================
					$tmp = $html->find('.description',0);
					$memo = '';
					if($tmp){
						$memo = $tmp->outertext();
						$memo_s = $tmp->plaintext;
						$memo_s = trim($memo_s);
						$memo_s = str_replace('ОПИСАНИЕ:', '', $memo_s);
						$memo_s = trim($memo_s);
						
						$tmp = $html->find('.instruction',0);
					}
					if($tmp){
						$memo .= $tmp->outertext();
					}
					//$memo = htmlspecialchars($memo, ENT_QUOTES);
					$memo = '<ul><li>Балы: '.$bonus_original.'</li><li>Вес/Обьем: '.trim(str_replace(',','', $size_original)).'</li></ul>'.$memo;
					$memo = str_replace('"', "'", $memo);
					
					//$memo_s = str_replace('"', "'", $memo);
					echo '<br>Описание ($memo) -> '.$memo.'';
					//============================================================================================
				
					$view = '0'; //Тоже в ноль его пока полное добавление не пройдет
				}else{
					$view = '0';
					$name = 'category';
				}
				
				echo '<h4>Найдены линки новые линки</h4>';
				$view = '0';
				foreach($str_tmp as $option){
				
					$href = str_get_html($option->href);
					if(strpos($href, $http) === false AND strpos($href, $http2) === false AND strpos($href, $add_row_key) !== false){
						
						$href = $http.$href;
						$href = str_replace('//', '/', $href);
						$href = str_replace('//', '/', $href);
						$href = str_replace('https:/', 'https://', $href);
						$href = str_replace('http:/', 'http://', $href);
					}
					
					//Отсекаем мусор
					if(strpos($href, "'") === false){
						$sql = 'SELECT id FROM '.$pars_table.' WHERE url = \''.$href.'\';';
						$t = $mysqli->query($sql) or die('==' . $sql);
						
						if($t->num_rows == 0){
							if(strpos($href, $add_row_key) !== false){
								if(strpos($href, '/cz/catalog/') === false
										AND strpos($href, '/en/catalog/') === false
											AND strpos($href, '/pl/catalog/') === false
												AND strpos($href, 'change_country') === false
													AND strpos($href, 'change_lang') === false
														AND strpos($href, 'VIEW=') === false
									){
									$sql = 'INSERT INTO '.$pars_table.' SET
												 `url` = \''.$href.'\',
												 `key` = "'.$name.'",
												 `view` = \''.$view.'\',
												 `date` = \''.date('Y-m-d H:i:s').'\',
												 `breadcrumbs` = \'\';';
									$mysqli->query($sql) or die('==' . $sql);
									echo ''.$href.'<br>';
								}
							}else{
								//echo '<font color=red>'.$href.'</font><br>';
							}
							
						}
					}
					
	 
				}
				
				
				$sql = 'UPDATE '.$pars_table.' SET view = \'0\',
												`breadcrumbs` = \''.$breadcrumbs_txt.'\',
												`artkl` = \''.$artkl.'\',
												`brand` = \''.$brand.'\',
												`price` = \''.$price.'\',
												`size` = \'\'
												
												WHERE url = \''.$list['url'].'\';';
				$mysqli->query($sql) or die('==' . $sql);
				
				//=============================================================
				//Опеределяем данные
				$error = 0;
				
				//Категория
				//Если только категория не назначена для всего сайта	
				if(!isset($category_id)){
					$sql = 'SELECT category_id FROM tbl_category_alternative WHERE breadcrumbs = "'.$breadcrumbs_txt.'" AND postav_id = "'.$postav_id.'";';
					$br = $mysqli->query($sql) or die('==' . $sql);
					if($br->num_rows > 0){
						$tmp = $br->fetch_assoc();
						$category_id = $tmp['category_id'];
					}else{
						$error = 1;
					}
				}	

	 
	 
		if($error AND $name != 'category'){
			echo '<div class="error">';
			if(!isset($brand_id)){
				
				include 'class/class_localisation.php';

				$Localisation = new Localisation($mysqli);
				$country = $Localisation->getCountry();
				
				include 'class/class_brand.php';
				$Brand = new Brand($mysqli);
				$brands = $Brand->getBrands();
				?>
				<br>Не удалось определить бренд ( <b> <?php echo $brand; ?> </b> ) -
						<select class="brand" style="width: 200px;">
							<option value="0">Состыкуй бренд</option>
						<?php foreach($brands as $value){?>
							<option value="<?php echo $value['brand_id'];?>"><?php echo $value['brand_name'];?></option>
						<?php } ?>
						
						</select> ===>
						<br><a href="javascript:" id="add_brand" target="_blank"><b>Добавить <?php echo $brand; ?></b></a>
						<select name="country0" class="brand_country" data-id="0" style="width:200px;">
							<?php foreach($country as $id => $value){
								  echo '<option value="'.$id.'">'.$value.'</option>';	    
							} ?>
						  </select>
						<a href="edit_brands.php?brand_id=297" target="_blank"> редактор брендов тут</a>
						<header>
							<title>*** STOP!</title>
						</header>
						<hr>
				<?php
			}else{
				?>
					<header>
						<title>Scan tiande.ru</title>
					</header>
				<?php
			}
			
			if(!isset($category_id)){
				  $sql = "SELECT
							category_child_id AS product_type_id,
							category_parent_id AS product_parent_id,
							category_name AS product_type_name
						FROM  jos_virtuemart_category_categories VCC
						LEFT JOIN jos_virtuemart_categories_ru_ru VCRR ON VCRR.virtuemart_category_id = VCC.category_child_id
							WHERE category_parent_id = '0'
							ORDER BY category_name ASC;";
					$rs = $mysqli->query($sql) or die ("Get product type list ".$sql);
					
					$body = "
							<div id=\"container\" class = \"product-type-tree\">
							<div class='key_close'>Закрыть [x]</div>
							<input type='hidden' id='selected_menu' value=''>
							<ul  id=\"celebTree\"><li><span id=\"span_0\"><a class = \"tree\" href=\"javascript:\" id=\"0\">Категории</a></span><ul>";
					while ($Type = mysqli_fetch_assoc($rs)) {
						if($Type['product_parent_id'] == 0){
							$body .=  "<li><span id=\"span_".$Type['product_type_id']."\"> <a class = \"tree\" href=\"javascript:\" id=\"".$Type['product_type_id']."\">".$Type['product_type_name']."</a>";
							$body .= "</span>".readTree($Type['product_type_id'],$mysqli);
							$body .= "</li>";
						}
					}
					$body .= "</ul>
						</li></ul></div>";
						
				echo $body;
				echo '<hr>Категория? <b>'.$breadcrumbs_txt.'</b> - <a href="javascript:" class="breadcrumbs">Назначить этим крошкам</a>
							<!-- &nbsp;&nbsp;&nbsp;&nbsp;===>&nbsp;&nbsp;
							<a href="edit_inet_parent_table.php" target="_blank"> редактор категорий тут</a-->';
				?>
					<header>
						<title>*** STOP!</title>
					</header>
				
				<?php
			}else{
				?>
					<header>
						<title>Scan tiande.ru</title>
					</header>
				<?php
			}
		}
		//============================================================================================================
		
		//header("Content-Type: text/html; charset=UTF-8");
		//echo "<pre>";  print_r(var_dump( $size )); echo "</pre>";die();
		
		//============================================================================================================
		//============================== З А П И С Ь =================================================================
		//============================================================================================================
		//============================================================================================================
		if($name != 'category'){	
			if($error == 1 ){
				echo '<h2>НЕ ХВАТАЕТ ДАННЫХ!</h2></div>';
				$view = 0;
			}else{
				
				$date = date('Y-m-d H:i:s');
				
				$sql = 'SELECT virtuemart_product_id FROM jos_virtuemart_products WHERE product_sku = "'.$artkl.'" LIMIT 0, 1;';
				$r = $mysqli->query($sql);
				
				//Значит продукт есть - обновим цены
				if($r->num_rows > 0){
					
					$tmp = $r->fetch_assoc();
					
					$product_id = $tmp['virtuemart_product_id'];
					
					//Продукта нет - добавим
				}else{
					
					$sql = 'INSERT INTO	`jos_virtuemart_products` SET
							`virtuemart_vendor_id`="1",
							`product_parent_id`="0",
							`product_sku`="'.$artkl.'",
							`product_gtin`="",
							`product_mpn`="",
							`product_weight`="'.$size.'",
							`product_weight_uom`="г.",
							`product_length`=NULL,
							`product_width`=NULL,
							`product_height`=NULL,
							`product_lwh_uom`="M",
							`product_url`="",
							`product_in_stock`="1",
							`product_ordered`="0",
							`low_stock_notification`="1",
							`product_available_date`="'.$date.'",
							`product_availability`="",
							`product_special`="0",
							`product_sales`="0",
							`product_unit`="KG",
							`product_packaging`=NULL,
							`product_params`=\'min_order_level=""|max_order_level=""|step_order_level=""|product_box=""|\',
							`hits`=NULL,
							`intnotes`="",
							`metarobot`="",
							`metaauthor`="",
							`layout`="0",
							`published`="1",
							`pordering`="0",
							`created_on`="'.$date.'",
							`created_by`="62",
							`modified_on`="'.$date.'",
							`modified_by`="62",
							`locked_on`="0000-00-00 00:00:00",
							`locked_by`=""
							';
							
					$mysqli->query($sql) or die('Добавдение продукта '.$sql);
					
					$product_id = $mysqli->insert_id;
					
					$sql = 'INSERT INTO jos_virtuemart_products_ru_ru SET
							`virtuemart_product_id`="'.$product_id.'",
							`product_s_desc`="'.$memo_s.'",
							`product_desc`="'.$memo.'",
							`product_name`="'.$name.'",
							`metadesc`="'.$name.'",
							`metakey`="'.$name.'",
							`customtitle`="'.$name.'",
							`slug`="'.strtolower(translitArtkl($name.'-'.$artkl)).'"
					';
					$mysqli->query($sql) or die('Добавдение продукта '.$sql);
					
					
					$sql = 'INSERT INTO jos_virtuemart_product_categories SET
								virtuemart_product_id = "'.$product_id.'",
								virtuemart_category_id = "'.$category_id.'",
								ordering="0"
							';
					$mysqli->query($sql) or die('Добавдение продукта '.$sql);
					
					
					$sql = 'INSERT INTO jos_virtuemart_product_prices SET
							`virtuemart_product_id` = "'.$product_id.'",
							`virtuemart_shoppergroup_id` = "0",
							`product_price` = "'.$price.'",
							`override` = "0",
							`product_override_price` = "0", 
							`product_tax_id` = "0",
							`product_discount_id` = "0",
							`product_currency` = "199",
							`product_price_publish_up` = "0000-00-00 00:00:00",
							`product_price_publish_down` = "0000-00-00 00:00:00",
							`price_quantity_start` = "0",
							`price_quantity_end` = "0",
							`created_on` = "'.$date.'",
							`created_by` = "62",
							`modified_on` = "'.$date.'",
							`modified_by` = "62",
							`locked_on` = "0000-00-00 00:00:00",
							`locked_by` = ""
						';
					$mysqli->query($sql) or die('Добавдение продукта '.$sql);
					
				}
					
				
				//Обновляем другие поля
				//Цена 131 = RUB, 199 = UAH;
				
				$sql = 'UPDATE jos_virtuemart_products_ru_ru SET
						`product_s_desc`="'.$memo_s.'",
						`product_desc`="'.$memo.'",
						`product_name`="'.$name.'",
						`metadesc`="'.$name.'",
						`metakey`="'.$name.'",
						`customtitle`="'.$name.'",
						`slug`="'.strtolower(translitArtkl($name.'-'.$artkl)).'"
						WHERE
						`virtuemart_product_id`="'.$product_id.'"
				';
				$mysqli->query($sql) or die('Добавдение продукта '.$sql);
				
				
				$sql = 'UPDATE jos_virtuemart_product_prices SET
							`virtuemart_shoppergroup_id` = "0",
							`product_price` = "'.$price.'",
							`override` = "0",
							`product_override_price` = "0", 
							`product_tax_id` = "0",
							`product_discount_id` = "0",
							`product_price_publish_up` = "0000-00-00 00:00:00",
							`product_price_publish_down` = "0000-00-00 00:00:00",
							`price_quantity_start` = "0",
							`price_quantity_end` = "0",
							`modified_on` = "'.$date.'",
							`modified_by` = "62",
							`locked_on` = "0000-00-00 00:00:00",
							`locked_by` = ""
							WHERE
							`virtuemart_product_id` = "'.$product_id.'" AND `product_currency` = "199"
						';
				$mysqli->query($sql) or die('Добавдение прайса '.$sql);
			/*
				$sql = 'UPDATE `jos_virtuemart_products` SET
							`virtuemart_vendor_id`="'.$product_id.'"
							WHERE
							`virtuemart_product_id` = "'.$product_id.'"
							;';
				$mysqli->query($sql) or die('Добавдение вендора '.$sql);			
			*/	
				//Добавляем параметры. Обьем и Баллы			
				
				$mysqli->query('DELETE FROM jos_virtuemart_product_customfields WHERE `virtuemart_product_id` = "'.$product_id.'"') or die('Добавдение кустомфилдов '.$sql);	
				
				if($size > 0){
					
					$sql = 'UPDATE `jos_virtuemart_products` SET
							`product_weight`="'.$size.'"
							WHERE
							`virtuemart_product_id` = "'.$product_id.'";';
					$mysqli->query($sql) or die('Добавдение кустомфилдов 1 '.$sql);
					
					$sql = 'INSERT INTO `jos_virtuemart_product_customfields` SET
								`virtuemart_product_id` = "'.$product_id.'",
								`virtuemart_custom_id` = 4,
								`customfield_value` = "product_weight",
								`customfield_price` = NULL,
								`disabler` = 0,
								`override` = 0 ,
								`customfield_params` = \'round="1"|\',
								`product_sku` = NULL,
								`product_gtin` = NULL,
								`product_mpn` = NULL,
								`published` = 0,
								`created_on` = "'.$date.'",
								`created_by` = 63,
								`modified_on` = "'.$date.'",
								`modified_by` = 63,
								`locked_on` = \'0000-00-00 00:00:00\',
								`locked_by` = 0,
								`ordering` = 0;
							';
					$mysqli->query($sql) or die('Добавдение кустомфилдов 1 '.$sql);
				}
				
				$sql = 'INSERT INTO `jos_virtuemart_product_customfields` SET
							`virtuemart_product_id` = "'.$product_id.'",
							`virtuemart_custom_id` = 13,
							`customfield_value` = "'.$bonus.'",
							`customfield_price` = "'.$bonus.'",
							`disabler` = 0,
							`override` = 0 ,
							`customfield_params` = "", 
							`product_sku` = NULL,
							`product_gtin` = NULL,
							`product_mpn` = NULL,
							`published` = 0,
							`created_on` = "'.$date.'",
							`created_by` = 63,
							`modified_on` = "'.$date.'",
							`modified_by` = 63,
							`locked_on` = \'0000-00-00 00:00:00\',
							`locked_by` = 0,
							`ordering` = 0;
						';
				$mysqli->query($sql) or die('Добавдение кустомфилдов 2 '.$sql);

						
					//Загрузим фото - только если у товара нет фото
					include_once ('import/import_url_getfile.php');
					$noload = true;
					if(!file_exists(UPLOAD_DIR.$artkl.'.png')) {
						if(isset($image)){
							include_once 'init.class.upload_0.31.php';
							foreach($image as $str_img){
							
							
								$TdateCode = DownloadFile($str_img);
								
								$uploaddir = UPLOAD_DIR.$artkl.'.png';
							
								file_put_contents($uploaddir, $TdateCode);
							
							echo $str_img.'<br>';
							echo $uploaddir.'<br>';
								
								touch($uploaddir);
								
								$sql = 'DELETE FROM `jos_virtuemart_medias` WHERE virtuemart_vendor_id = "'.$product_id.'";';
								$mysqli->query($sql) or die('Чистка линка фотки '.$sql);
								
								$sql = 'DELETE FROM `jos_virtuemart_product_medias` WHERE virtuemart_product_id = "'.$product_id.'";';
								$mysqli->query($sql) or die('Чистка линка фотки '.$sql);
								
								
								$sql = 'INSERT INTO `jos_virtuemart_medias`
									( `virtuemart_vendor_id`, `file_title`, `file_description`, `file_meta`, `file_class`, `file_mimetype`, `file_type`, `file_url`, `file_url_thumb`, `file_is_product_image`, `file_is_downloadable`, `file_is_forSale`, `file_params`, `file_lang`, `shared`, `published`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`)
									VALUES
									("'.$product_id.'", "'.$artkl.'.png", "", "", "", "image/png", "product", "images/stories/virtuemart/product/'.$artkl.'.png", "", 0, 0, 0, "", "", 0, 1, "'.$date.'", 62, "'.$date.'", 62, "0000-00-00 00:00:00", 0)';
								$mysqli->query($sql) or die('Добавдение фотки '.$sql);
								
								$media_id = $mysqli->insert_id;
								
								$sql = 'INSERT INTO `jos_virtuemart_product_medias`
									( `virtuemart_product_id`, `virtuemart_media_id`, `ordering`)
									VALUES
									("'.$product_id.'", "'.$media_id.'", 1)';
								$mysqli->query($sql) or die('Прилинковка фотки '.$sql);
								
								
							}
						}else{
							echo '<br>Нет фото';    
						}
					
					}else{
						   //include '../import/import_url_getfile.php';
					}
				
					//Вот теперь когда все сделали - поставим этому линку статус вью 1
					$sql = 'UPDATE '.$pars_table.' SET `view` = \'1\' WHERE `url` = \''.$list['url'].'\';';
					$mysqli->query($sql) or die('==' . $sql);
					
				
			}
		}else{
			
			//Если это была категория
			//Вот теперь когда все сделали - поставим этому линку статус вью 1
			$sql = 'UPDATE '.$pars_table.' SET `view` = \'1\' WHERE `url` = \''.$list['url'].'\';';
			$mysqli->query($sql) or die('==' . $sql);
			
		}
		
		//return false;
	}
	 
				
			//}		
							
}else{
	die('<h2>ЗАКОНЧИЛ!</h2>');
}
//echo $sql;die();
//Рекурсия=================================================================
function readTree($parent,$mysqli){
   $sql = "SELECT
					category_child_id AS product_type_id,
					category_parent_id AS product_parent_id,
					category_name AS product_type_name
				FROM  jos_virtuemart_category_categories VCC
				LEFT JOIN jos_virtuemart_categories_ru_ru VCRR ON VCRR.virtuemart_category_id = VCC.category_child_id
				WHERE
					category_parent_id = '$parent' ORDER BY category_name ASC;";
					//echo $sql.'<br>';
    $rs1 = $mysqli->query($sql) or die ("Get product type list".$sql);

    $body = "";

     while ($Type = mysqli_fetch_assoc($rs1)) {
	    $body .=  "<li><span id=\"span_".$Type['product_type_id']."\"><a class = \"tree\" href=\"javascript:\" id=\"".$Type['product_type_id']."\">".$Type['product_type_name']."</a>";
	    $body .= "</span>".readTree($Type['product_type_id'],$mysqli);
	    $body .= "</li>";
    }
    if($body != "") $body = "<ul>$body</ul>";
    return $body;

}
function translitArtkl($str) {
    $rus = array('І','и','і','є','Є','ї','\"','\'','.',' ','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('I','u','i','e','E','i','','','','-','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
   return str_replace($rus, $lat, $str);
}
?>
<script>
	$(document).on('click', '.breadcrumbs', function(){
		$('#container').css('display', 'block');
		$('.msg_back').css('display', 'block');
		
	});

	$(document).on('click', '.msg_back', function(){
		$('#container').css('display', 'none');
		$('.msg_back').css('display', 'none');
	});
	
	
	
	$(document).on('change', '.brand', function(){
		
		var brand_id = $(this).val();
		var postav_id = "<?php echo $postav_id; ?>";
		var brand_name = "<?php echo $brand; ?>";
		$.ajax({
			type: "POST",
			url: "edit_alternative.php",
			dataType: "text",
			data: "brand_id="+brand_id+"&postav_id="+postav_id+"&brand_name="+brand_name+"&key=add_brand",
			beforeSend: function(){
			},
			success: function(msg){
				console.log( msg );
			}
		});
		
	});

	$(document).on('click', 'span', function(event){
		
		var id = event.target.id;
		var parent_id = $(this).children("a").first().attr('id');
		console.log(id);
	
		if (id) {
			switch(id){
				case "dell-carfit":
							 dellItem(parent_id);
				break;
				case "insert-carfit":
							  insertItem(parent_id);
				break;
				case "new_category":
							  //insertItem(parent_id);
				break;
				default:                  
					var category_id = id;
					var postav_id = "<?php echo $postav_id; ?>";
					var breadcrumbs = "<?php echo $breadcrumbs_txt; ?>";
					
					if(confirm('Назначить '+$('#'+id).html()+' ?')){

						$.ajax({
							type: "POST",
							url: "edit_alternative.php",
							dataType: "text",
							data: "category_id="+category_id+"&postav_id="+postav_id+"&breadcrumbs="+breadcrumbs+"&key=add_category",
							beforeSend: function(){
							},
							success: function(msg){
								$('.breadcrumbs').html('<b>'+$('#'+id).html()+'</b>');
								console.log($('#'+id).html());
								console.log( msg );
							}
						});
					}
			}
				}else{
					$(this).toggleClass('closed opened').nextAll('ul').toggle(300);
				}
				   
	});
	//==========Кнопка Закрыть окно редактирования
	$(".key_close").click(function(){
		$("#container").css("display","block").toggle('slow');
	});
	
	
$(document).ready(function(){
	
	//Скрипт дерева ========================
	$('#celebTree ul')
		.hide()
		.prev('span')
		.before('<span></span>')
		.prev()
		.addClass('handle closed')
		.click(function() {
		  // plus/minus handle click
				   // $(this).toggleClass('closed opened').nextAll('ul').toggle();
		});
	$('#celebTree ul')
		.prev('span')
		.children('a')
		.toggleClass('tree tree_ul')
		.click(function() {
		  // plus/minus handle click
				   // $(this).toggleClass('closed opened').nextAll('ul').toggle();
		});
		
	  //Развернем первый уровень
	$("#0").parent('span').parent('li').children('span').first().toggleClass('closed opened').nextAll('ul').toggle();
	console.log('ready');
	//setTimeout(reload, 10000);
});
	
	//Тут прописать - если пролетели без ошибок - валим дальше
	$(document).ready(function(){
		<?php if(($error == 0 OR $name == 'category') AND !isset($_GET['url'])){ 
			echo 'setTimeout(reload, '.($pausa * 1000).');';
		 } ?>
	}
	);
	
	function reload() {
        location.reload();
    }
	<?php
		$brand_code = translitArtkl($brand);
		$brand_code = str_replace('+', '-', $brand_code);
		$brand_code = str_replace(' ', '-', $brand_code);
		$brand_code = strtolower($brand_code);
	?>
	 
   $(document).on('click','#add_brand', function(){
		var brand_name = "<?php echo $brand; ?>";
		var brand_code = "<?php echo $brand_code; ?>";
		var country_id = $(".brand_country").val();
		
		brand_code = brand_code.replace('&', '@*@');
		brand_name = brand_name.replace('&', '@*@');
		
		$.ajax({
		type: "POST",
		url: "brand/ajax_edit_brand.php",
		dataType: "text",
		data: "brand_name="+brand_name+"&brand_code="+brand_code+"&country_id="+country_id+"&key=add",
		beforeSend: function(){
		},
		success: function(msg){
		  console.log(  msg );
		  alert('Добавил новый бренд.\n\rНекоторые спецсимволы модут быть заменены\n\rВозможно он не привяжется сам - привяжи через выбор');
		  location.reload();
		  //$('#msg').html('Изменил');
		  //setTimeout($('#msg').html(''), 1000);
		}
	  });
		
	});
	
//=======Новый добавляемый элемент
var menu = "&nbsp;&nbsp;<input type=\"text\" style=\"width:100px;\" id=\"new_category\" class=\"new_category\" value=\"\" placeholder=\"новая папка\"><a href=\"javascript:\" id=\"insert-carfit\" class=\"insert-carfit drop_key-carfit\" style=\"z-index:999;\">[вставить]</a>";

//=======Наводимся на элемент
$(document).on('mouseleave', '.new_category', function(){
	var parent_id = $(this).parent("span").attr('id');
	if ($(this).val() != '') {
		var span = $('#selected_menu').val();
		$('#'+span).children("a").last().remove();
		$('#'+span).children("input").last().remove();
		
        $('#selected_menu').val(parent_id);
    }
	
});
/*
$(document).on('mouseenter', '#container span', function(e){
            if (this.id) {
                if (this.id.indexOf('arent') > 0) {
                    
                }else{
					if($(this).attr('id') != $('#selected_menu').val()) {
						$(this).children("a").after(menu);       
					}
                }
            }});
//=======Уходим с элемента
$(document).on('mouseleave', '#container span', function(e){
            if (this.id) {
                if (this.id.indexOf('arent') > 0) {
                    
                }else{
					
					if ($(this).attr('id') != $('#selected_menu').val()) {
						$(this).children("a").last().remove();
						$(this).children("input").last().remove();
						//$(this).children("a").last().remove();
					}
                }
            }
              
});*/
//=========== Вставляем элемент
	function insertItem(id){
		
		var name = $('.new_category').val();
		var parent_id = id;
		
		console.log('Категория - '+ name + ' ' + parent_id);
			$.ajax({
				type: "POST",
				url: "parsing/ajax_edit_category.php",
				dataType: "json",
				data: "key=add&parent_id="+parent_id+"&name="+name,
				success: function(msg){
					//console.log(msg);
				
					var insert = "<li><span id=\"span_"+
								  msg.id+
								  "\"><a href=\"javascript:\" class=\"tree-carfit\" id=\""
								  +msg.id+"\">"+name+"</a></span></li>";
					//Если нет вложенного списка - создаем его
					if ($("#"+id).parent("span").parent("li").children("ul").html()) {
						$("#"+id).
							parent("span").
							parent("li").
							children("ul").
							children("li").
							last().after(insert);
					
					}else{
						$("#"+id).toggleClass('tree-carfit tree_ul-carfit');
						$("#"+id).parent("span").last("span").after("<ul style=\"display: block;\" class=\"new\"></ul>");
						$(".new").parent("li").children("span").before("<span class=\"handle-carfit opened-carfit\"></span>");
						$(".new").html(insert);
						$(".new").toggleClass('new old');
								 
					}
					 
					if ($("#"+id).
						parent("span").
						parent("li").
						children(".handle-carfit").hasClass("closed-carfit")) {
					  
						$("#"+id).parent("span").
								  parent("li").
								  children(".handle-carfit").
								  toggleClass('closed-carfit opened-carfit')
								  .nextAll('ul')
								  .toggle(300);;
					}
				
			  
			}
		}); 
	}

	//});
</script>
