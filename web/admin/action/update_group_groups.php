<?php 
session_start();
include ("../admin_functions.php");

$id = $_POST['id'];                                 // hidden row id taken from FORM category.php
$chek = $_POST['chek'];                             // chekbox  on/off
$name = $_POST['name'];    							// category name
$sort = $_POST['sort'];    
$action = $_POST['action'];                         // action  update/delete/add_new_row

$slug = preg_replace("/[@#$%^&*\\{\\}]/", "", $name); // remove all special chars
$slug = preg_replace('/ {2,}/',' ',$slug); // remove all double space and tabs from string
$slug = str_replace(' ', '-', $slug); // change all space and tabs from string to -

mysql_connect(HOST, MYSQL_USER, MYSQL_PASS) or die("error with database connect");  //****  OPEN NEW CONNECT TO DB in ACTION file *************
mysql_select_db(DATABASE) or die("cannot select db");

//if ($chek == 'on'){
    if ($action == 'update_gr_cat') {
		$r=mysql_query("UPDATE `fsh_groupofgroups` SET name = '$name', sort='$sort', slug='$slug' WHERE id = '$id'");
        mysql_close();  //******************* CLOSE CONNECT TO DB
        header ('Location: /admin/?view=category');
    }
    if ($action == 'delete' AND $chek == 'on') {
        $r=mysql_query("DELETE FROM `fsh_groupofgroups` WHERE id = '$id'");
        mysql_close();  //******************* CLOSE CONNECT TO DB
        header ('Location: /admin/?view=category');
    }
    if ($action == 'add_new_row' AND $chek == 'on') {
        $r=mysql_query("INSERT INTO fsh_groupofgroups (name, slug) VALUES ('новая группа', 'novaya-gruppa')");
        mysql_close();  //******************* CLOSE CONNECT TO DB
        header ('Location: /admin/?view=category');
    }
    if ($action == 'update_icon') {
        mysql_close();  //******************* CLOSE CONNECT TO DB
        print '
    <form enctype="multipart/form-data" action="upload_topGr_icon.php" method="POST">
        <input type="hidden" name="cat_id" value='.$id.'>
        <p>Иконка должна быть заранее подготовленна, желательно без фона, формата png. соотношение сторон изображения должно быть 1 к 1. Размер иконки должен быть не больше 100px на 100px</p>
        Выбор иконки: <input name="iconImage" type="file" />
        <input type="submit" value="Загрузить Иконку" />
    </form>';
    }
    if (($action == 'delete' or $action == 'add_new_row') AND $chek != 'on') {
        header ('Location: /admin/?view=category');
    }
//}


?>