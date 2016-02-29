<?php
session_start();
include ("../admin_functions.php");

$id = $_POST['id'];                                 // hidden row id taken from FORM category.php
$chek = $_POST['chek'];                             // chekbox  on/off
$catalog_row_name = $_POST['catalog_row_name'];     // category name
$group_id = $_POST['group_id'];   
$sort = $_POST['sort'];    
$action = $_POST['action'];                         // action  update/delete/add_new_row


$slug = preg_replace("/[@#$%^&*\\{\\}]/", "", $catalog_row_name); // remove all special chars
$slug = preg_replace('/ {2,}/',' ',$slug); // remove all double space and tabs from string
$slug = str_replace(' ', '-', $slug); // change all space and tabs from string to -

mysql_connect(HOST, MYSQL_USER, MYSQL_PASS) or die("error with database connect");  //****  OPEN NEW CONNECT TO DB in ACTION file *************
mysql_select_db(DATABASE) or die("cannot select db");

//if ($chek == 'on'){
    if ($action == 'update_cat') {
		if ($group_id==''){
			$r=mysql_query("UPDATE `fsh_catalog` SET name = '$catalog_row_name', group_id = NULL, sort='$sort', slug = '$slug' WHERE id = '$id'");
		}else{
			$r=mysql_query("UPDATE `fsh_catalog` SET name = '$catalog_row_name', group_id = '$group_id', sort='$sort', slug = '$slug' WHERE id = '$id'");
		}				
    }
    if ($action == 'delete' and $chek == 'on') {
        $r=mysql_query("DELETE FROM `fsh_catalog` WHERE id = '$id'");
    }
    if ($action == 'add_new_row' and $chek == 'on') {
        $r=mysql_query("INSERT INTO fsh_catalog (name, slug) VALUES ('новая категория', 'novaya-categoriya')"); 
    }
//}

mysql_close();  //******************* CLOSE CONNECT TO DB
header ('Location: /admin/?view=category');
?>