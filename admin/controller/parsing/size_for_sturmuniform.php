<?php

set_time_limit(20);
//define("GETCONTENTVIAPROXY", 1);
//define("GETCONTENTVIANAON", 1);
include 'constants.php';


$sql = 'SELECT * FROM tbl_parser_sturmuniform_sizes ';

$r = $folder->query($sql);

?>
<table>
	<tr>
		<th>Артикл</th>
		<th>Размер</th>
		<th>Операция</th>
		<th>Название</th>
	</tr>

<?php while($row = $r->fetch_assoc()){	
	
	$sql = 'SELECT tovar_id, tovar_artkl, tovar_name_1 FROM tbl_tovar where tovar_artkl LIKE "'.$row['artkl'].'%"';
	$rt= $folder->query($sql);
	$product_id = '';?>
	
	<?php while($row1 = $rt->fetch_assoc()){ $product_id = $row1['tovar_id']; ?>
		<tr>
			<td><?php echo $row1['tovar_artkl']; ?></td>
			<td>+</td>
			<td><?php echo $row1['tovar_name_1']; ?></td>
			<td>&nbsp;</td>
		</tr>
	<?php } ?>



		<tr >
			<td><font color="red"><?php echo $row['artkl'].'#'.$row['size_name']; ?></font></td>
			<td><font color="red"><?php echo $row['size_name']; ?></font></td>
			<td><a href="javascript:;" class="add_product" id="<?php echo $product_id; ?>" data-artkl="<?php echo $row['artkl']; ?>" data-size="<?php echo $row['size_name']; ?>">Добавить</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<a href="/admin/edit_tovar.php?tovar_id=<?php echo $product_id; ?>" target="_blank">Редактировать</a></td>
			<td><font color="red">Не хватает размера</font></td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
	
<?php } ?>
</table>
<script>
	$(document).on('click', '.add_product', function(){
		
		var id = $(this).attr('id');
		var artkl = $(this).data('artkl');
		var size = $(this).data('size');
		var element = $(this);
		
		$.ajax({
			type: "GET",
			url: "parsing/ajax_size_for_sturmuniform.php",
			dataType: "text",
			data: "id="+id+"&artkl="+artkl+"&size="+size,
			beforeSend: function(){
			},
			success: function(msg){
				console.log( msg );
				element.parent('td').html(msg);
			}
		});
		
			
	});
	
	
</script>

