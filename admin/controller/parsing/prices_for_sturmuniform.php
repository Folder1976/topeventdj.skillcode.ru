<?php

set_time_limit(20);
//define("GETCONTENTVIAPROXY", 1);
//define("GETCONTENTVIANAON", 1);
include 'constants.php';
include 'simple_html_dom/simple_html_dom.php';

$find = array('Код товара:', 'Цена:', 'руб.', 'qty:', '+', ' ');
$rep = array('','','','','','');

$AND = ' AND ( url like "%sturmuniform.ru%")';
//$AND = ' AND ( url like "%allmulticam.ru%")';
$UND = ' AND url not like "%sturmuniform.ru%" ';

$sql = 'SELECT count(product_id) AS count FROM tbl_tovar_links WHERE updated="0" '.$AND.' ORDER BY links_id ASC';
$r = $folder->query($sql);
$tmp = $r->fetch_assoc();
echo '<h3>Будет проверено - '.$tmp['count'].'</h3>';

$sql = 'SELECT count(product_id) AS count FROM tbl_tovar_links WHERE updated="1" '.$AND.' ORDER BY links_id ASC';
$r = $folder->query($sql);
$tmp = $r->fetch_assoc();
echo '<h3>Проверено - '.$tmp['count'].'</h3>';


$sql = 'SELECT product_id, url, postav_id, links_id FROM tbl_tovar_links WHERE updated="0" '.$AND.' GROUP BY url ORDER BY links_id ASC LIMIT 1';
//$sql = 'SELECT product_id, url, postav_id, links_id FROM tbl_tovar_links WHERE url like "%http://allmulticam.ru/collection/Benchmade/product/Тактический-складной-нож-909-BK-Mini-Stryker-Benchmade%" LIMIT 1';
//$sql = 'SELECT product_id, url, postav_id, links_id FROM tbl_tovar_links WHERE url="http://sturmuniform.ru/phutbolka-mother-russia-morskoj-phlot-zelenaja-novaja.html"';
//echo $sql;

$r = $folder->query($sql);
$count = $r->num_rows;
$products = array();
while($tmp = $r->fetch_assoc()){
	$products[$tmp['links_id']] = $tmp;
}

$sql = 'SELECT currency_id, currency_ex FROM tbl_currency';
$r = $folder->query($sql);

$valuta = array();
while($tmp = $r->fetch_assoc()){
	$valuta[$tmp['currency_id']] = $tmp['currency_ex'];
}

$dell = 0;
$minus = 0;
if(isset($_GET['minus'])) $minus = $_GET['minus'];

$magelan = 0;
$splav = 0;
$sturmuniform = 0;
$allmulticam = 0;
$old_url = '';
foreach($products as $links_id => $value){
	
	$url = $value['url'];
	$product_id = $value['product_id'];
	
	//Сраные москальские урл выпарсиваем! Ну нахуя такие урл назначать которые не парсятся и не передаются нормально!!! Сука!!!!!!!!!!!!!!!!!
	$s = $url;
	$i = parse_url($s); 
	$p = ''; 
	foreach(explode('/',trim($i['path'],'/')) as $v) {$p .= '/'.rawurlencode($v);} 
	$url_tmp = $i['scheme'].'://'.$i['host'].$p; 
	echo '<br> => '.my_url_decode($url);
	
	//$url_tmp = "http://allmulticam.ru/collection/Benchmade/product/Тактический-складной-нож-909-BK-Mini-Stryker-Benchmade";
	//$url_tmp = urldecode($url_tmp);
	
	//$url_tmp = my_url_decode($url);
	
	//$url_tmp=iconv("Windows-1251","UTF-8", $s);
	
	$opts = array(
					'http'=>array(
					  'method'=>"GET",
					  'header'=>"Content-Type: text/html; charset=utf-8"
					)
			  );

	$context = stream_context_create($opts);
	
	
	//Только если новый урл... если урл тот же - проставляем все одно и тоже
	//Для долбанного СТИЧа - нужен отделый блок
	if($old_url != $url){
		
		//echo '<br>'.$url;
		$zakup = 0;
		$price = 0;
		$qty = 0;
		$postav_id = $value['postav_id'];
		$zakup_kur = 1;
		
		//Получаем новые цены и количество
		
		//=======================
		if(strpos($url, 'sturmuniform.ru') !== false){
			$sturmuniform++;
			
			$html = file_get_html($url_tmp);
			if(!$html AND !defined("GETCONTENTVIANAON")){
				$html = file_get_html($url_tmp);
			}
			
			if($html){
				
				$sizes = array();
				
				foreach($html->find('.ProductInfoRight p') as $p){
					$str_tmp = $p->innertext();
			
					if(strpos($str_tmp, 'Цена') !== false){
						$tmp_html = str_get_html($str_tmp);
						$str_tmp = $tmp_html->find('span', 0)->innertext();
						$str_tmp = str_replace($find, $rep, $str_tmp);
						$price = (int)str_replace('.', '', $str_tmp);
						$zakup = ($price * 0.75);
					}elseif(strpos($str_tmp, 'qty')){
						$qty = str_replace($find, $rep, $str_tmp);
						//echo ' - '.$qty;
					}elseif(strpos($str_tmp, 'id[1]')){
						
						$tmp_html = str_get_html($str_tmp);
						$str_tmp = $tmp_html->find('option');
						foreach($str_tmp as $option){
							if($option->innertext() != '' AND $option->innertext() != 'ВЫБРАТЬ РАЗМЕР'){
								$tmp = trim($option->innertext());
								$tmp = translitArtkl($tmp);
								$sizes[] = $tmp;
							}
						}
							
					}
					
				}
				
				
			}
			echo "<br><b>Товар: , Цена: $price, Количество: $qty</b>";
		}
		
		//=======================
		$old_url = $url;
	}
	
	//Тут нужно взять из базы альтернативные размеры и заменить их
	//foreach($sizes as $size){}
	
	
	
	//Получим список товаров
	$sql = "SELECT product_id, tovar_artkl
				FROM tbl_tovar_links
				LEFT JOIN tbl_tovar ON tovar_id = product_id	
				WHERE url = '$url';";
	$r = $folder->query($sql);
	
	
	$product_sizes = array();
	$product_artkl = '';
	$products_ids = array();
	
	while($row = $r->fetch_assoc()){
	
		$products_ids[$row['tovar_artkl']] = $row['product_id'];
	
		if(strpos($row['tovar_artkl'], '#') !== false){
			$tmp = explode('#', $row['tovar_artkl']);
			$product_sizes[$tmp[1]] = $tmp[1];
			$products_ids[$tmp[1]] = $row['product_id'];
			
			$product_artkl = $tmp[0];
		}else{
			$product_artkl = $row['tovar_artkl'];
			$products_ids[] = $row['product_id'];
		}
	
	}
	
	//Обнуляем остатки
	$sql = "UPDATE tbl_tovar_suppliers_items SET items = 0 WHERE postav_id='3' AND tovar_id IN (".implode(',', $products_ids).");";
	$r = $mysqli->query($sql) or die($sql);

	
	//Если продукт без размерный
	if(count($product_sizes) == 0 AND count($products_ids)){
		
		$sql = "UPDATE tbl_tovar_suppliers_items SET
						items = $qty,
						zakup = '$zakup',
						zakup_curr = '$zakup_kur'
					WHERE
						tovar_id = '".array_shift($products_ids)."' AND
						postav_id = '$postav_id'
						;";
					//echo '<br>'.$sql;
		$folder->query($sql) or die('<br>'.$sql);
		
		//Закрываем урл
		$sql = "UPDATE tbl_tovar_links SET updated = '1'
					WHERE
					url = '$url';";
		//echo $sql.'<br>';					
		$folder->query($sql) or die('<br>'.$sql);
		
		continue;
	}
	
	?>
	<h3>Список размеров в базе:</h3>
	<ul>
		<?php foreach($product_sizes as $size){ ?>
			<li><?php echo $size; ?></li>
		<?php } ?>
	</ul>
	<h3>Нашел следующие размеры у поставщика:</h3>
	<ul>
		<?php foreach($sizes as $size){ ?>
			<li><?php echo $size; ?></li>
		<?php } ?>
	</ul>
	
	<h3>Соответствие:</h3>
	<table>
		<tr>
			<th>Поставщик</th>
			<th>База</th>
		</tr>
		<?php foreach($sizes as $size){ ?>
			<tr>
				<td><?php echo $size; ?></td>
				<td><?php
						if(isset($product_sizes[$size])){
							echo $product_sizes[$size];
							
							//Сразу и добавим количество в базу
							$sql = "UPDATE tbl_tovar_suppliers_items SET
								items = 1,
								zakup = '$zakup',
								zakup_curr = '$zakup_kur'
							WHERE
								tovar_id = '".$products_ids[$size]."' AND
								postav_id = '$postav_id'
								;";
							//echo '<br>'.$sql;
							$folder->query($sql) or die('<br>'.$sql);
							
						}else{
							echo 'Нет соответствия!';
							//$_SESSION['sizes'][$product_artkl.'#'.$size] = $product_artkl.'#'.$size;
							
							$sql = 'INSERT INTO tbl_parser_sturmuniform_sizes SET
										`artkl` = "'.$product_artkl.'",
										`url` = "'.$url.'",
										`size_name` = "'.$size.'"
										ON DUPLICATE KEY UPDATE `size_name` = "'.$size.'";';
							$folder->query($sql) or die('<br>'.$sql);			
						}
					?></td>
			</tr>

		<?php } ?>
	</table>
	<?php if(isset($_SESSION['sizes'])){ ?>
		<h3>Список новых/не найденых размеров:</h3>
		<ul>
			<?php //foreach($_SESSION['sizes'] as $size){ ?>
				<li><?php //echo $size; ?></li>
			<?php //} ?>
		</ul>
	<?php } ?>
	
	<?php
	
		//Закрываем урл
		$sql = "UPDATE tbl_tovar_links SET updated = '1'
					WHERE
					url = '$url';";
//echo $sql.'<br>';					
		$folder->query($sql) or die('<br>'.$sql);
	
	
	
}
	
//}
echo '<hr>';

?>

<?php if($count > 0){ ?>
<script>
	$(document).ready(function(){
		setTimeout(reload, 5000);						   
	}
	);
	
	function reload() {
        location.reload();
    }
</script>
<?php } ?>

<?php
function my_url_encode($s){ 
$s= strtr ($s, array ("а"=>"%D0%B0", "А"=>"%D0%90","б"=>"%D0%B1", "Б"=>"%D0%91", "в"=>"%D0%B2", "В"=>"%D0%92", "г"=>"%D0%B3", "Г"=>"%D0%93", "д"=>"%D0%B4", "Д"=>"%D0%94", "е"=>"%D0%B5", "Е"=>"%D0%95", "ё"=>"%D1%91", "Ё"=>"%D0%81", "ж"=>"%D0%B6", "Ж"=>"%D0%96", "з"=>"%D0%B7", "З"=>"%D0%97", "и"=>"%D0%B8", "И"=>"%D0%98", "й"=>"%D0%B9", "Й"=>"%D0%99", "к"=>"%D0%BA", "К"=>"%D0%9A", "л"=>"%D0%BB", "Л"=>"%D0%9B", "м"=>"%D0%BC", "М"=>"%D0%9C", "н"=>"%D0%BD", "Н"=>"%D0%9D", "о"=>"%D0%BE", "О"=>"%D0%9E", "п"=>"%D0%BF", "П"=>"%D0%9F", "р"=>"%D1%80", "Р"=>"%D0%A0", "с"=>"%D1%81", "С"=>"%D0%A1", "т"=>"%D1%82", "Т"=>"%D0%A2", "у"=>"%D1%83", "У"=>"%D0%A3", "ф"=>"%D1%84", "Ф"=>"%D0%A4", "х"=>"%D1%85", "Х"=>"%D0%A5", "ц"=>"%D1%86", "Ц"=>"%D0%A6", "ч"=>"%D1%87", "Ч"=>"%D0%A7", "ш"=>"%D1%88", "Ш"=>"%D0%A8", "щ"=>"%D1%89", "Щ"=>"%D0%A9", "ъ"=>"%D1%8A", "Ъ"=>"%D0%AA", "ы"=>"%D1%8B", "Ы"=>"%D0%AB", "ь"=>"%D1%8C", "Ь"=>"%D0%AC", "э"=>"%D1%8D", "Э"=>"%D0%AD", "ю"=>"%D1%8E", "Ю"=>"%D0%AE", "я"=>"%D1%8F", "Я"=>"%D0%AF")); 
return $s; 
} 
    // функция раскодирует строку из URL 
function my_url_decode($s){ 
$s= strtr ($s, array ("%20"=>" ", "%D0%B0"=>"а", "%D0%90"=>"А", "%D0%B1"=>"б", "%D0%91"=>"Б", "%D0%B2"=>"в", "%D0%92"=>"В", "%D0%B3"=>"г", "%D0%93"=>"Г", "%D0%B4"=>"д", "%D0%94"=>"Д", "%D0%B5"=>"е", "%D0%95"=>"Е", "%D1%91"=>"ё", "%D0%81"=>"Ё", "%D0%B6"=>"ж", "%D0%96"=>"Ж", "%D0%B7"=>"з", "%D0%97"=>"З", "%D0%B8"=>"и", "%D0%98"=>"И", "%D0%B9"=>"й", "%D0%99"=>"Й", "%D0%BA"=>"к", "%D0%9A"=>"К", "%D0%BB"=>"л", "%D0%9B"=>"Л", "%D0%BC"=>"м", "%D0%9C"=>"М", "%D0%BD"=>"н", "%D0%9D"=>"Н", "%D0%BE"=>"о", "%D0%9E"=>"О", "%D0%BF"=>"п", "%D0%9F"=>"П", "%D1%80"=>"р", "%D0%A0"=>"Р", "%D1%81"=>"с", "%D0%A1"=>"С", "%D1%82"=>"т", "%D0%A2"=>"Т", "%D1%83"=>"у", "%D0%A3"=>"У", "%D1%84"=>"ф", "%D0%A4"=>"Ф", "%D1%85"=>"х", "%D0%A5"=>"Х", "%D1%86"=>"ц", "%D0%A6"=>"Ц", "%D1%87"=>"ч", "%D0%A7"=>"Ч", "%D1%88"=>"ш", "%D0%A8"=>"Ш", "%D1%89"=>"щ", "%D0%A9"=>"Щ", "%D1%8A"=>"ъ", "%D0%AA"=>"Ъ", "%D1%8B"=>"ы", "%D0%AB"=>"Ы", "%D1%8C"=>"ь", "%D0%AC"=>"Ь", "%D1%8D"=>"э", "%D0%AD"=>"Э", "%D1%8E"=>"ю", "%D0%AE"=>"Ю", "%D1%8F"=>"я", "%D0%AF"=>"Я")); 
return $s; 
} 


  function translitArtkl($str) {
    $rus = array('и','і','є','Є','ї','А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('u','i','e','E','i','A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
   return str_replace($rus, $lat, $str);
  }

?>