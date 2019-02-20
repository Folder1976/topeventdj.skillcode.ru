<?php

set_time_limit(20);
//define("GETCONTENTVIAPROXY", 1);
//define("GETCONTENTVIANAON", 1);
include 'constants.php';
include 'simple_html_dom/simple_html_dom.php';

$find = array('Код товара:', 'Цена:', 'руб.', 'qty:', '+', ' ');
$rep = array('','','','','','');

$AND = ' AND ( url like "%magellanrus.ru%" OR url like "%allmulticam.ru%" /*OR url like "%sturmuniform.ru%"*/ /*OR url like "%splav.ru%" */)';
//$AND = ' AND ( url like "%allmulticam.ru%")';
$UND = ' AND url not like "%magellanrus.ru%" AND url not like "%allmulticam.ru%" /*AND url not like "%sturmuniform.ru%"*/ /*AND url not like "%splav.ru%" */';

$sql = 'SELECT count(product_id) AS count FROM tbl_tovar_links WHERE updated="0" '.$AND.' ORDER BY links_id ASC';
$r = $folder->query($sql);
$tmp = $r->fetch_assoc();
echo '<h3>Будет проверено - '.$tmp['count'].'</h3>';

$sql = 'SELECT count(product_id) AS count FROM tbl_tovar_links WHERE updated="1" '.$AND.' ORDER BY links_id ASC';
$r = $folder->query($sql);
$tmp = $r->fetch_assoc();
echo '<h3>Проверено - '.$tmp['count'].'</h3>';


$sql = 'SELECT product_id, url, postav_id, links_id FROM tbl_tovar_links WHERE updated="0" '.$AND.' GROUP BY url ORDER BY links_id ASC LIMIT 3';
//$sql = 'SELECT product_id, url, postav_id, links_id FROM tbl_tovar_links WHERE url like "%http://allmulticam.ru/collection/Benchmade/product/Тактический-складной-нож-909-BK-Mini-Stryker-Benchmade%" LIMIT 1';
//$sql = 'SELECT product_id, url, postav_id, links_id FROM tbl_tovar_links WHERE url="http://sturmuniform.ru/phutbolka-mother-russia-morskoj-phlot-zelenaja-novaja.html"';


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
	
	
	//Говносайт stich.su = пока не парсим.
	if(strpos($url, 'stich.su') !== false){
			continue;
	}		
	
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
					}
					
				}
			}
			echo "<br><b>Товар: , Цена: $price, Количество: $qty</b>";
		}
		
		//=======================
		if(strpos($url, 'magellanrus.ru') !== false){
			$magelan++;
			
			//continue;	
			if($html = file_get_html($url_tmp)){
				$blok = $html->find('.product-info', 0);
				if($tmp_html = str_get_html($blok)){
					$str_tmp = $tmp_html->find('.product-price span', 0)->innertext();
					$price = (int)str_replace($find, $rep, $str_tmp);
					$qty = 1;
					$zakup = ($price * 0.75);
				}else{
					$price = 0;
					$qty = 0;
					echo '<br>1 Нет цены - '.$url;
				}
			}
		
		}
		
			//=======================
			if(strpos($url, 'splav.ru') !== false){
				$splav++;
				
				if($html2 = file_get_html($url)){
					
					$tmp = explode('xmlRs', $html2);
					$gods_det = explode('good id', $tmp[1]);
			
					$count = 0;
					foreach($gods_det as $Text){
						if(strpos($Text, 'version=') === false){
							
							$Text = str_replace('\\\'','@@@', $Text);
							$Text = str_replace("'",'"', $Text);
							
							if (preg_match_all('#\s+([^=\s]+)\s*=\s*((?(?="|\') (?:"|\')([^"\']+)(?:"|\') | ([^\s]+)))#isx', $Text, $matches)) {
								
								if($matches[0][0] != '' AND $matches[0][0] != 'name'){
									
									$price = 0;
									foreach($matches[0] as $tmp){
										if(strpos($tmp,'price1=') !== false){
										$price = trim(trim(str_replace('price1=','',$tmp),'"'));
										$price = (int)trim(trim($price, '"'));
										
										$zakup = $price * 0.95;
										$price = $price * 1.15;
										$qty = 1; 
										break;
										}
									}
									
								}
							}
						}
					}
				}else{
					$price = 0;
					$qty = 0;
					$zakup = 0;
					echo '<br>2 Нет цены - '.$url;
				}
			}
			
		}
		
		//=======================
		
		if(strpos($url, 'allmulticam.ru') !== false){
			
			$allmulticam++;
			
			$html = file_get_html($url_tmp);
			if(!$html AND !defined("GETCONTENTVIANAON")){
				$html = file_get_html($url_tmp);
			}
			if(!$html){
				$html = file_get_html(my_url_decode($url_tmp), false, $context);
			}
			if(!$html){
				$url_tmp = urldecode($url_tmp);
				$html = file_get_html($url_tmp);
			}
			
			if($html){
			//continue;
			//if($html = file_get_html($url_tmp, false, $context)){
				$str_tmp = $html->find('#price-field', 0)->innertext();
			
				$price = (int)str_replace($find, $rep, $str_tmp);
				$zakup = ($price * 0.75);
				$qty = 1;
			}
			
			echo "<br><b>Товар: , Цена: $price, Количество: $qty</b>";
		
		}
		
				
		$old_url = $url;
	
	
	
	if(strpos($url, 'magellanrus.ru') !== false OR
		strpos($url, 'sturmuniform.ru') !== false OR
			strpos($url, 'allmulticam.ru') !== false){
		
		//Для Магелана отключим исправление остатков
		$ostatki = '';
		if(strpos($url, 'magellanrus.ru') === false){
			$ostatki = " items = '$qty',";
		   
		}
		//echo '<br>Кол: '.$qty;
		//echo ' Цена: '.$price;
		//echo ' Урл: '.$url;
		$sql = "SELECT product_id FROM tbl_tovar_links WHERE url = '$url';";
		$links = $folder->query($sql);
		
		while($lin = $links->fetch_assoc()){
			if($price > 0){
				$sql = "INSERT INTO tbl_tovar_suppliers_items SET
						tovar_id = '".$lin['product_id']."',
						postav_id = '$postav_id',
						$ostatki
						zakup = '$zakup',
						price_1 = '" . ($price - $minus). "',
						zakup_curr = '$zakup_kur'
					ON DUPLICATE KEY UPDATE
						$ostatki
						zakup = '$zakup',
						price_1 = '" . ($price - $minus). "',
						zakup_curr = '$zakup_kur';";
			}else{
				$sql = "INSERT INTO tbl_tovar_suppliers_items SET
						tovar_id = '".$lin['product_id']."',
						postav_id = '$postav_id',
						$ostatki
						zakup = '$zakup',
						zakup_curr = '$zakup_kur'
					ON DUPLICATE KEY UPDATE
						$ostatki
						zakup = '$zakup',
						zakup_curr = '$zakup_kur';";
			}
//echo '<br><br>'./*$sql.*/'<br>';					
			$folder->query($sql) or die('<br>Ошибка запроса: '.$sql);
		}
		$sql = "UPDATE tbl_tovar_links SET updated = '1'
					WHERE
					url = '$url';";
//echo $sql.'<br>';					
		$folder->query($sql) or die('<br>'.$sql);
	
	
	}
	
}
	
//}
echo '<hr>';
echo '<br>splav.ru - <b>'.$splav.'</b>';
echo '<br>sturmuniform.ru - <b>'.$sturmuniform.'</b>';
echo '<br>magellanrus.ru - <b>'.$magelan.'</b>';
echo '<br>allmulticam.ru - <b>'.$allmulticam.'</b>';

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

?>