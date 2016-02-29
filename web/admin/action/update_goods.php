<?php 
session_start();
include ("../admin_functions.php");
//$cat_name = $_GET['cat_name'];

$id = $_POST['id'];                                 // hidden goods id taken from FORM goods.php
$goods_name = $_POST['goods_name']; 
$brand_name = $_POST['brand_name'];   
$articul = $_POST['articul'];              
$price = $_POST['price'];
$size = $_POST['size'];
$dscr = $_POST['dscr'];
$tags = $_POST['tags'];
$related_product = $_POST['related_product'];
$group_id = $_POST['group_id'];

$action = $_POST['action'];                         // action  update/delete/add
$group_tag = $_POST['group_tag'];
$chek = $_POST['chek'];
$arr_group = $_POST['arr_group'];
//$category_switch = $_POST['category_switch'];

mysql_connect(HOST, MYSQL_USER, MYSQL_PASS) or die("error with database connect");  //****  OPEN NEW CONNECT TO DB in ACTION file *************
mysql_select_db(DATABASE) or die("cannot select db");

//if ($chek == 'on'){
switch ($action) {
    case 'copy':
        $time=time();
        $r=mysql_query("INSERT INTO products (name, brand_name, articul, price, size, dscr, tags, related_product)
        SELECT 'copy$id', `brand_name`, `articul`, `price`, `size`, `dscr`, `tags`, `related_product` FROM products WHERE id = '$id'")  or die (mysql_error());
        $r2=mysql_query("SELECT id FROM products WHERE name='copy$id'") or die (mysql_error());
        $row = mysql_fetch_array($r2);
        $id=$row['id'];
        $r3=mysql_query("UPDATE `products` SET created_at='$time', updated_at='$time' WHERE id = '$id'");
        mysql_close();  //******************* CLOSE CONNECT TO DB
        header ("Location: /admin/?view=goods&edit_goods=$id");
        break;
    
    case 'update':
        $time=time();
        $r=mysql_query("UPDATE `products` SET name='$goods_name', articul='$articul', price='$price', updated_at='$time' WHERE id = '$id'");
        mysql_close();  //******************* CLOSE CONNECT TO DB
        header ("Location: /admin/?view=goods&cat_id={$_SESSION['cat_id']}");

    
    case 'href_good_edit':
        mysql_close();  //******************* CLOSE CONNECT TO DB
        header ("Location: /admin/?view=goods&edit_goods=$id&group_id=$group_id");
    
    
    case 'full_goods_edit':
        $time=time();
        $r=mysql_query("UPDATE `products` SET name='$goods_name', brand_name='$brand_name', articul= '$articul', price='$price',  size='$size', dscr='$dscr', tags='$tags', related_product='$related_product', updated_at='$time'  WHERE id = '$id'");
        mysql_close();  //******************* CLOSE CONNECT TO DB
        header ("Location: /admin/?view=goods&edit_goods=$id");
    /*
    case 'descr_goods_edit':
        $r=mysql_query("UPDATE `products` SET full_dscr='$full_dscr' WHERE id = \"$id\"");
        mysql_close();  //******************* CLOSE CONNECT TO DB
        header ("Location: /admin/?view=goods&edit_goods=$id");
    */
    case 'add_new_goods':
        $time=time();
        $r=mysql_query("INSERT INTO products (name, brand_name, articul, price, size, tags, created_at, updated_at) VALUES ('$goods_name', '$brand_name', '$articul', '$price', '$size', '$tags', '$time', '$time')")  or die (mysql_error());
		$r2=mysql_query("SELECT id FROM products WHERE name='$goods_name' ORDER BY `id` DESC") or die (mysql_error());
        $row = mysql_fetch_array($r2);
        $id=$row['id'];
        mysql_close();  //******************* CLOSE CONNECT TO DB
        header ("Location: /admin/?view=goods&edit_goods=$id");
    
    case 'show_on':
        $r=mysql_query ("UPDATE  `products` SET  show_flag=1 WHERE  id ='$id'") or die (mysql_error());
        header ("Location: /admin/?view=goods&cat_name={$_SESSION['cat_name']}");
        echo "show_on";
        break;
    
    case 'show_off':
        $r=mysql_query ("UPDATE `products` SET show_flag=0 WHERE id='$id'") or die (mysql_error());
        header ("Location: /admin/?view=goods&cat_name={$_SESSION['cat_name']}");
        echo "show_off";
        break;
		
	case 'update_prod_cat';
		if ($group_id=='')
			$r=mysql_query("SELECT * FROM fsh_catalog") or die (mysql_error());
		if ($group_id!='' AND $group_id!='NULL')
			$r=mysql_query("SELECT * FROM `fsh_catalog` WHERE FIND_IN_SET ($group_id, group_id)") or die (mysql_error());
		if ($group_id=='NULL')
			$r=mysql_query("SELECT * FROM `fsh_catalog` group_id is NULL") or die (mysql_error());
		while ($row=mysql_fetch_array($r)) {
			$cat_id=$row['id'];
			$result = $_POST['cat_'.$cat_id]=='on' ?  'on' : 'off';
			//print "Id ".$id."=".$result."<br>";
			$r2=mysql_query("SELECT cat_id FROM fsh_prod_catalog WHERE prod_id='$id' AND cat_id='$cat_id'") or die (mysql_error());
			//print "SELECT cat_id FROM fsh_prod_catalog WHERE prod_id='$id' AND cat_id='$cat_id'";
			$row2 = mysql_fetch_array($r2);
			if ($row2['cat_id']!=''){
				if ($result=='off') mysql_query("DELETE FROM fsh_prod_catalog WHERE prod_id='$id' AND cat_id='$cat_id'") or die (mysql_error());
			}else{
				if ($result=='on') mysql_query("INSERT INTO fsh_prod_catalog (prod_id, cat_id) VALUES ('$id', '$cat_id')") or die (mysql_error());
			}
		}
		header ("Location: /admin/?view=goods&edit_goods=$id");
		break;
}
//}
?>