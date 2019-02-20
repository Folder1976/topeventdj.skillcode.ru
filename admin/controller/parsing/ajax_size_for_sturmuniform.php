<?php

set_time_limit(20);
//define("GETCONTENTVIAPROXY", 1);
//define("GETCONTENTVIANAON", 1);
include '../config/config.php';

//Прилетел аякс
if(isset($_GET['artkl'])){
	
	$sql = 'CREATE TEMPORARY TABLE foo AS SELECT * FROM tbl_tovar WHERE tovar_id = "'.(int)$_GET['id'].'"';
	$folder->query($sql);
	$sql = 'UPDATE foo SET tovar_id=NULL, tovar_artkl="'.$_GET['artkl'].'#'.$_GET['size'].'"';
	$folder->query($sql);
	$sql = 'INSERT INTO tbl_tovar SELECT * FROM foo';
	$folder->query($sql);
	$product_id = $folder->insert_id;
	$sql = 'DROP TABLE foo';
	$folder->query($sql) or die('1 '.$sql);
	
/*
	$sql = 'CREATE TEMPORARY TABLE foo AS SELECT * FROM tbl_description WHERE description_tovar_id = "'.(int)$_GET['id'].'"';
	$folder->query($sql);
	$sql = 'UPDATE foo SET description_tovar_id="'.$product_id.'"';
	$folder->query($sql);
	$sql = 'INSERT INTO tbl_description SELECT * FROM foo';
	$folder->query($sql);
	$sql = 'DROP TABLE foo';
	$folder->query($sql) or die($sql);
*/	
	$sql = 'CREATE TEMPORARY TABLE foo AS SELECT * FROM tbl_tovar_links WHERE product_id = "'.(int)$_GET['id'].'" AND postav_id="3"';
	$folder->query($sql) or die('2 '.$sql);;
	$sql = 'UPDATE foo SET links_id=NULL, product_id="'.$product_id.'"';
	$folder->query($sql) or die('2 '.$sql);;
	$sql = 'INSERT INTO tbl_tovar_links SELECT * FROM foo';
	$folder->query($sql) or die('2 '.$sql);;
	$sql = 'DROP TABLE foo';
	$folder->query($sql) or die('2 '.$sql);
	
	$sql = 'CREATE TEMPORARY TABLE foo AS SELECT * FROM tbl_tovar_suppliers_items WHERE tovar_id = "'.(int)$_GET['id'].'" AND postav_id="3"';
	$folder->query($sql);
	$sql = 'UPDATE foo SET tovar_id="'.$product_id.'", items="1"';
	$folder->query($sql);
	$sql = 'INSERT INTO tbl_tovar_suppliers_items SELECT * FROM foo';
	$folder->query($sql);
	$sql = 'DROP TABLE foo';
	$folder->query($sql) or die('2 '.$sql);

	$sql = 'DELETE FROM tbl_parser_sturmuniform_sizes WHERE artkl="'.$_GET['artkl'].'" AND size_name="'.$_GET['size'].'"';
	$folder->query($sql) or die('3 '.$sql);

	
	echo 'OK - Клонировано'; die();
}
