<?php
session_start();
include ("../admin_functions.php");
include ("../classes.php");

$id = $_POST['id'];
$group_tag = $_POST['group_tag'];
$action = $_POST['action'];
$id_in_group = $_POST['id_in_group'];
$x1 = $_POST['x1'];
$y1 = $_POST['y1'];
$w = $_POST['w'];
$h = $_POST['h'];
$crop_width = 80;
$crop_height = 90;

if ($action == 'upload_img'){
   if(is_uploaded_file($_FILES["filename"]["tmp_name"])){
        /*move_uploaded_file($_FILES["filename"]["tmp_name"], "../../images/prod/".$_FILES["filename"]["name"]);
        $_SESSION['file'] = $_FILES["filename"]["name"];*/
        $image = new SimpleImage();
        $image->load($_FILES["filename"]["tmp_name"]);
        if ($image->getWidth()>500){
		        $image->resizeToWidth(500);
		}
        $image->save("../../images/prod/".$_FILES["filename"]["name"]);
        $_SESSION['file'] = $_FILES["filename"]["name"];
        //$image_thmb = new SimpleImage();
        //$image_thmb->load($_FILES["filename"]["tmp_name"]);
        //$img=str_replace(".", "-thumb.", $_FILES["filename"]["name"]);
       // code below it is old version saving picture, now here use jqure plugin which give to user to select saving area
        /*$img_w = $image_thmb->getWidth();                         
        $img_h = $image_thmb->getHeight();
       if ($img_w > $img_h){
        $image_thmb->scale(8000/$img_w);
       }
       else{
        $image_thmb->scale(9000/$img_h);
       }*/
        //$image_thmb->save("../../images/prod/thumb/".$img);
    }
    else {print "error with form, MAY BE VERY BIG FILE";}
}
if ($action == 'image_update') {
        $img=str_replace(".", "-thumb.", $_SESSION['file']);
        $new = imagecreatetruecolor($crop_width, $crop_height);
        $current = imagecreatefromjpeg("../../images/prod/".$_SESSION['file']);
        imagecopyresampled($new , $current, 0, 0, $x1, $y1, $crop_width, $crop_height, $w, $h);  
        imagejpeg($new, "../../images/prod/thumb/".$img, 95);
    mysql_connect(HOST, MYSQL_USER, MYSQL_PASS) or die("error with database connect");  //****  OPEN NEW CONNECT TO DB in ACTION file *************
    mysql_select_db(DATABASE) or die("cannot select db");
    if ($group_tag == 0){
        $r=mysql_query("UPDATE `prod` SET img=\"prod/".$_SESSION['file']."\" WHERE id = \"$id\"");
    }
    if ($group_tag == 1){
        $r=mysql_query("UPDATE `group_prod` SET img=\"prod/".$_SESSION['file']."\" WHERE parent_id = \"$id\" AND id_in_group = \"$id_in_group\"");
    }
    $_SESSION['file'] = '';
    mysql_close();  //******************* CLOSE CONNECT TO DB
}

$group_tag == 1 ? header ("Location: /admin/?view=goods&edit_goods=$id&group_tag=$group_tag&id_in_group=$id_in_group") : header ("Location: /admin/?view=goods&edit_goods=$id&group_tag=$group_tag");
?>