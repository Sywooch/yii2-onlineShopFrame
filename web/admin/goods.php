<?php 
$cat_id = empty($_GET['cat_id']) ? 1 : $_GET['cat_id']; // take cat_id from 13 row this code and put into function get_goods on 38 row
$id_edit_goods = $_GET['edit_goods']; // take parametr named edit_goods from form action udpate_goods.php and equal it with id goods those was edited
$group_id = empty($_GET['group_id']) ? '' : $_GET['group_id'];
?>


<div>
<div id="current_gr"><?php echo get_group_name ($group_id)?></div>
<ul class="group_menu">
<?php 
$topGr=get_group_groups();
while ($row=mysql_fetch_array($topGr)) {
        print "<li><a href=\"#\">$row[name]</a></li>";
        $gr=get_group_category($row[id]);
        
        print "<ul class=\"under_group\">";
        while ($row2=mysql_fetch_array($gr)){
            print "<li><a href=\"?view=goods&edit_goods=$id_edit_goods&group_id=$row2[id]\">$row2[name]</a></li>";
        }
        print "</ul>";
             	
}
print "<li><a href=\"?view=goods&edit_goods=$id_edit_goods\">ВСЕ КАТЕГОРИИ</a></li>
	<li><a href=\"?view=goods&edit_goods=$id_edit_goods&group_id=NULL\">ВНЕ ГРУПП</a></li>";
?>
</ul>
</div>


<div class="catalog_menu">
    <ul>
<?php
$r=get_category($id_edit_goods, $group_id);
if ($id_edit_goods==''){
	print "<li><b>кат. id || название кат.</b></li>";
	while ($row=mysql_fetch_array($r)) {
		print "<li>id=$row[id] || <a href=\"?view=goods&cat_id=$row[id]&group_id=$group_id\">$row[name]</a></li>";}
}else{
	print "<form method = \"post\" action=\"action/update_goods.php\"><input type=\"hidden\" name=\"id\" value=\"$id_edit_goods\"><input type=\"hidden\" name=\"group_id\" value=\"$group_id\">";
	print "<li><b>кат. id || название кат.</b></li>";
	while ($row=mysql_fetch_array($r)) {
		if($row[cat_flag]=='1'){$checked='checked'; $color='red';}
		if($row[cat_flag]=='0'){$checked=''; $color='black';}
		print "<li><input class=\"cat_switch\" type=\"checkbox\" name=\"cat_$row[id]\" value=\"on\" $checked><span style=\"color:$color;\"> id=$row[id] </span> || <a href=\"?view=goods&cat_id=$row[id]&group_id=$group_id\">$row[name]</a></li>";}
	print  "<button style= \"display:none;\" name=\"action\" value=\"update_prod_cat\"><img src=\"img/upd.png\"></button>
			</form>"; 
}

?>
<li>нет ID || <a href="?view=goods&cat_id=none">Вне категорий</a></li>
    </ul>
</div>


<div class="goods_field">
<?php 
switch ($id_edit_goods){
    case null: // this mode when we looking goods same catalog, view depends from selected category
    print "<table border=\"1\">
        <tr><th>ID</th><th>фото</th><th>название</th><th>артикул</th><th>цена</th></tr>";
       
    $r=get_goods($cat_id); 
    while ($row = mysql_fetch_array($r)) {
        print 
                "<tr><form method = \"post\" action=\"action/update_goods.php\">
				<input type=\"hidden\" name=\"group_id\" value=\"$group_id\">
                <td><input type=\"hidden\" name=\"id\" value=\"$row[id]\">$row[id]</td>";
		$img=mysql_fetch_array(get_main_image($row[id]));		// тут ранбше была crop_img
		print	"<td><img style=\"width:60px\"src=\"../images/".$img[img]."\"></td> 
				<td><textarea rows =\"2\" cols =\"12\" name=\"goods_name\">".$row[name]."</textarea></td>
                <td><textarea rows =\"2\" cols =\"10\" name=\"articul\">".$row[articul]."</textarea></td>
                <td><input type=\"text\" size =\"5\" name=\"price\" value=\"".$row[price]."\"></td>
                <td style=\"width:40px;\"><button name=\"action\" value=\"update\"><img src=\"img/upd.png\"></button></td>
                <td style=\"width:40px;\"><button name=\"action\" value=\"href_good_edit\"><img src=\"img/ed.png\"></button></td>
                <td style=\"width:40px;\"><button name=\"action\" value=\"copy\"><img src=\"img/copy.png\"></button></td>";
        if ($row[show_flag] == 1) {
            print "<td style=\"width:40px;\"><button class=\"lamp\" name=\"action\" value=\"show_off\"><img src=\"img/on.png\"></button></td>";
        }
        else {
            print "<td style=\"width:40px;\"><button class=\"lamp\" name=\"action\" value=\"show_on\"><img src=\"img/off.png\"></button></td>";
        }
        print  "</form></tr>"; // print table row for each goods
    }
    // save cat_id in session before close if. This Session need for be taken into action file (update_goods.php) for be used in header function, which  after finished action code  redirect back to page which action was called.    
    $_SESSION['cat_id'] = $cat_id;
        
    print "</table>";
    break; //finish case (edit_goods is null).

// now draw form for add new goods 
    case 0:
		print "<table border=\"1\">
				<tr><th>ID</th><th>название</th><th>брэнд</th><th>арт.</th><th>цена</th><th>размер</th><th>тэги</th></tr>";
		print 
                "<form method = \"post\" action=\"action/update_goods.php\"><tr>
                <td></td>
                <td><textarea rows =\"2\" cols =\"15\"name=\"goods_name\">название</textarea></td>
                <td><input type=\"text\" size =\"8\" name=\"brand_name\" value=\"NULL\"></td>
				<td><input type=\"text\" size =\"5\" name=\"articul\" value=\"NULL\"></td>
                <td><input type=\"text\" size =\"5\" name=\"price\" value=\"150.00\"></td>
                <td><input type=\"text\" size =\"3\" name=\"size\" value=\"M\"></td>
                <td><textarea rows =\"2\" cols = \"10\" name=\"tags\">NULL</textarea></td>
                <td><button name=\"action\" value=\"add_new_goods\"><img src=\"img/plus.png\"></button></td><tr>";
        print "</table>";
    break;
    
// now draw form for edit goods. This is form in html part same to the above form 
    default:
    $goods = get_goods_info($id_edit_goods);
    while ($row = mysql_fetch_array($goods)){
        print "<form method = \"post\" action=\"action/update_goods.php\"><table border=\"1\">
				<tr><th>ID</th><th>название</th><th>брэнд</th><th>арт.</th><th>цена</th><th>ед.измер</th><th>тэги</th></tr>";
        print 
                "<tr>
                <td><input type=\"hidden\" name=\"id\" value=\"$row[id]\">$row[id]</td>
				<td><textarea rows =\"2\" cols =\"15\"name=\"goods_name\">".$row[name]."</textarea></td>
                <td><input type=\"text\" size =\"8\" name=\"brand_name\" value=\"".$row[brand_name]."\"></td>
				<td><input type=\"text\" size =\"5\" name=\"articul\" value=\"".$row[articul]."\"></td>
                <td><input type=\"text\" size =\"5\" name=\"price\" value=\"".$row[price]."\"></td>
                <td><input type=\"text\" size =\"5\" name=\"size\" value=\"".$row[size]."\"></td>
				<td><textarea rows =\"2\" cols =\"10\" name=\"tags\">".$row[tags]."</textarea></td>
                <td><button name=\"action\" value=\"full_goods_edit\"><img src=\"img/done.png\"></button></td><tr>";
        print "</table><br>";
		
		// add related part
		print "<table border=\"1\">
				<tr><th>Номера ID, сопутсвующих товаров</th></tr>";
        print 
                "<tr>
                <td><input type=\"text\" size =\"5\" name=\"related_product\" value=\"".$row[related_product]."\"></td>   
                <td><button name=\"action\" value=\"full_goods_edit\"><img src=\"img/done.png\"></button></td><tr>";
        print "</table><br>";
		
		// add descriptions part
		print "<table border=\"1\">
				<tr><th>описание</th></tr>";
        print 
                "<tr>
				<td><textarea rows =\"20\" cols =\"50\"name=\"full_dscr\">".$row[dscr]."</textarea></td>                
                <td><button name=\"action\" value=\"full_goods_edit\"><img src=\"img/done.png\"></button></td><tr>";
        print "</table></form><br>";
		
		
		/*
		// category table 
		$cat = get_prod_cat($row[id]);
		while ($r=mysql_fetch_array($cat)){
			print "<table border=\"1\" style = \"float:left; width:40px; \">
					 <tr><td><input type=\"text\" name=\"cat_id\" value=\"".$r[cat_id]."\"></td></tr>
					 <tr><td><button name=\"action\" value=\"del_cat.$r[cat_id]\"><img src=\"img/del.png\"></button><br><input type=\"checkbox\" name=\"chek\" value=\"on\"></td></tr>
					 </table>";				
		}
		print "<button name=\"action\" value=\"add_new_cat_id.00\"><img style=\"width:50px\" src=\"img/plus.png\"></button>";			
		*/
		
        // image table
        print "<table style=\"width: auto;\"border=\"1\">";
        print "<tr><th>Текущая, главная картинка</th><th>предосмотр миниатюры</th></tr>";
        $img=mysql_fetch_array(get_main_image($row[id]));  
        print "<tr>
				<td><img style=\"max-width: 300px;\" src=\"../images/".$img[img]."\"></td>
				<td><div id=\"preview\"></div></td>	
				</tr>";		
		print "</table><br>"; 
 
		print "<table border=\"1\">";
		print   "<tr><th>картинка во временной памяти</th></tr>
				<tr><td><img id=\"targetImg\" src=\"../images/prod/temp/".$_SESSION['file']."\"></td></tr>";
        
		print "</table>"; 
		
		print "<form action =\"action/action_images.php\" enctype=\"multipart/form-data\" method=\"post\"><input type=\"hidden\" name=\"prod_id\" value=\"$row[id]\">
				<div style=\"float:left; margin:5px 0;\"><input type=\"file\" name=\"filename\" accept=\"image/*\"></div><div style=\"margin:10px 0;\"><button name=\"action\" value=\"upload_img.00\"><img src=\"img/upd.png\"></button></div>";
		$imgage=get_images($row[id]);  // 175 string    befor crop_img
		while ($r=mysql_fetch_array($imgage)){
			print "<table border=\"1\" style = \"float:left; width:200px; height: 100px;\">
					<tr><th>img</th><th colspan=\"2\">img_id=".$r[id]."</th></tr>
					<tr><td rowspan=\"2\"><img style=\"max-width: 80px;\" src=\"../images/".$r[img]."\"></td>
						<td colspan=\"2\"><button name=\"action\" value=\"image_update.$r[id]\"><img src=\"img/upd.png\">принять картинку</button></td>
					<tr>";
			if ($r[main]==1){print "<td><button name=\"action\" value=\"main_img.$r[id]\"><img src=\"img/on.png\"></button></td>";}
			else			{print "<td><button name=\"action\" value=\"main_img.$r[id]\"><img src=\"img/off.png\"></button></td>";}			
			print "<td><button name=\"action\" value=\"del_img.$r[id]\"><img src=\"img/del.png\"></button><br><input type=\"checkbox\" name=\"chek\" value=\"on\"></td></tr>";
			
			print "</table>"; 
		}
		print "<button name=\"action\" value=\"add_new_img.00\"><img style=\"width:50px\" src=\"img/plus.png\"></button>";
		print "	<input type=\"hidden\" name=\"x1\" value=\"\" />
				<input type=\"hidden\" name=\"y1\" value=\"\" />
				<input type=\"hidden\" name=\"w\" value=\"\" />
				<input type=\"hidden\" name=\"h\" value=\"\" />
				</form>"; 
    }
}
?>
    
</div>

<!--
 while ($row = mysql_fetch_array($r)){
        print "<div><h1>$row[name]<br>$row[item_name]</h1><img src=\"../images/$row[img]\"><h2>$main_info<br><span><br></span>FOB Price:
		<b>$row[price] THB/pcs</b><br>gross weight: $row[weight]<br>min. order: $row[min_order]<br>packing: $row[pack]</h2><br> 
		<div>$row[description]</div></div>";} -->
