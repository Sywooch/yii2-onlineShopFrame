<?php
include ("../admin_functions.php");
include ("../classes.php");

$cat_id =$_POST['cat_id'];

mysql_connect(HOST, MYSQL_USER, MYSQL_PASS) or die("error with database connect");  //****  OPEN NEW CONNECT TO DB in ACTION file *************
mysql_select_db(DATABASE) or die("cannot select db");

if(is_uploaded_file($_FILES["iconImage"]["tmp_name"])){
    $image = new SimpleImage();
    $image->load($_FILES["iconImage"]["tmp_name"]);
    $image->save("../../images/icons/".$_FILES["iconImage"]["name"]);
    $iconFilename = $_FILES["iconImage"]["name"];

    $r=mysql_query("UPDATE `fsh_groupofgroups` SET icon = '$iconFilename' WHERE id = '$cat_id'");

}else{
    print "ошибка сессии, попробуйте очистить сесии в браузере или заново загрузить файл через кнопку обзор -> выбор файла на диске";
}
mysql_close();  //******************* CLOSE CONNECT TO DB
header ("Location: /admin/?view=category");