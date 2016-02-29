<?php 
session_start();
include ("../admin_functions.php");
include ("../classes.php");

$action_id = $_POST['action'];                         // action  update/delete/add
$chek = $_POST['chek'];
$prod_id =$_POST['prod_id'];
$x1 = $_POST['x1'];
$y1 = $_POST['y1'];
$w = $_POST['w'];
$h = $_POST['h'];
$crop_width = 190;
$crop_height = 190;

mysql_connect(HOST, MYSQL_USER, MYSQL_PASS) or die("error with database connect");  //****  OPEN NEW CONNECT TO DB in ACTION file *************
mysql_select_db(DATABASE) or die("cannot select db");

$pos=strpos($action_id, ".");
$action=substr($action_id, 0, $pos);
$img_id=substr($action_id, $pos+1);
//if ($chek == 'on'){

switch ($action) {
    case 'main_img':
        $r=mysql_query("UPDATE `prod_img` SET main='0' WHERE prod_id = '$prod_id'")  or die (mysql_error());
        $r2=mysql_query("UPDATE `prod_img` SET main='1' WHERE id = '$img_id'") or die (mysql_error());
		mysql_close();  //******************* CLOSE CONNECT TO DB
        header ("Location: /admin/?view=goods&edit_goods=$prod_id");
        break;
    
    case 'del_img':
		if($chek=="on"){
			$r=mysql_query("UPDATE `prod_img` SET prod_id = NULL WHERE id = '$img_id'") or die (mysql_error());
			/*$r2=mysql_query("SELECT * FROM `prod_img` WHERE id = '$img_id'") or die (mysql_error());
			$row = mysql_fetch_array($r2);
			if($row[img]!=""){
				rename("../../images/".$row[img], "../../images/bin/".$row[img]);
			}*/		
			mysql_close();  //******************* CLOSE CONNECT TO DB
		}        
		header ("Location: /admin/?view=goods&edit_goods=$prod_id");
        break;
		
	case 'add_new_img':
		$chek_img_quantety = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `prod_img` WHERE prod_id = '$prod_id'"));
		$articul = mysql_fetch_array(mysql_query("SELECT articul FROM products WHERE id='$prod_id'"));
		$main_img = 0;	
		if($chek_img_quantety[0] == 0){
			$picture = 'prod/a_'.$articul[0].'_1.jpg';
			$main_img = 1;			
		}		
		if($chek_img_quantety[0] == 1)
			$picture = 'prod/b_'.$articul[0].'_1.jpg';
		if($chek_img_quantety[0] == 2)
			$picture = 'prod/c_'.$articul[0].'_1.jpg';
		if($chek_img_quantety[0] == 3)
			$picture = 'prod/e_'.$articul[0].'_1.jpg';
		if($chek_img_quantety[0] == 4)
			$picture = 'prod/f_'.$articul[0].'_1.jpg';
		if($chek_img_quantety[0] == 5)
			$picture = 'prod/g_'.$articul[0].'_1.jpg';
		
		$r=mysql_query("INSERT INTO `prod_img` (prod_id, img, main) VALUES ('$prod_id', '$picture', '$main_img')")  or die (mysql_error());
		mysql_close();  //******************* CLOSE CONNECT TO DB
        header ("Location: /admin/?view=goods&edit_goods=$prod_id");
        break;	
		
	case 'upload_img':
		if(is_uploaded_file($_FILES["filename"]["tmp_name"])){
			$image = new SimpleImage();
			$image->load($_FILES["filename"]["tmp_name"]);
			$image->save("../../images/prod/temp/".$_FILES["filename"]["name"]);
			$_SESSION['file'] = $_FILES["filename"]["name"];
		}else{
			print "ошибка сессии, попробуйте очистить сесии в браузере или заново загрузить файл через кнопку обзор -> выбор файла на диске";}
		header ("Location: /admin/?view=goods&edit_goods=$prod_id");
        break;	
		
	case 'image_update':
	$upl_file = "../../images/prod/temp/".$_SESSION['file']; // temporary file downloaded through form input file 
	$fileName = $_SESSION['file'];
	/*
	* make original file with max height 1000px and one more copy with max width 450px
	*/
	if(is_readable($upl_file)){
		$image = new SimpleImage();
		$image->load($upl_file);
		if($image->getHeight()>1000){
			$image->resizeToHeight(1000);}
		$image->save("../../images/prod/big/big-".$fileName);
		if($image->getWidth()>450){
			$image->resizeToWidth(450);}
		$image->save("../../images/prod/".$fileName);
	}else{
		echo "не найден файл во временной папке.  Попробуйте заново загрузить файл через кнопку обзор -> выбор файла на диске";
		break;}
	/*
	* make crop copy with 190x190px
	*/
        $new = imagecreatetruecolor($crop_width, $crop_height);
        $current = imagecreatefromjpeg($upl_file);
        imagecopyresampled($new , $current, 0, 0, $x1, $y1, $crop_width, $crop_height, $w, $h);  
        imagejpeg($new, "../../images/prod/thumb/thumb-".$fileName, 95);
	/*
	* save file path in DB
	*/	
		$r=mysql_query("UPDATE `prod_img` SET img = 'prod/$fileName', big_img = 'prod/big/big-$fileName', crop_img='prod/thumb/thumb-$fileName' WHERE id = '$img_id'")  or die (mysql_error());
		mysql_close();  //******************* CLOSE CONNECT TO DB
	/*
	* delete temporary file and clean session
	*/	
		unlink($upl_file);
		$_SESSION['file'] = '';
		
		header ("Location: /admin/?view=goods&edit_goods=$prod_id");
        break;	
}
//}
?>