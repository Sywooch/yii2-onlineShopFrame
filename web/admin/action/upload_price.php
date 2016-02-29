<?php
session_start();
$_SESSION['alertPrice'] = null;

if ($_FILES['price']['type']=='application/vnd.ms-excel' OR $_FILES['price']['type']=='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
    if(move_uploaded_file($_FILES["price"]["tmp_name"], "../../price/elektro-centr-price_el.xls")){
        $_SESSION['alertPrice'] = 'Прайс лист успешно загружен';
        header ("Location: /admin/?view=price");

    }else{
        $_SESSION['alertPrice'] = "ошибка загрузки файла, попробуйте заново загрузить файл через кнопку обзор -> выбор файла на диске";
        header ("Location: /admin/?view=price");
    }
}elseif($_FILES['price']['type']==''){
    $_SESSION['alertPrice'] = 'Надо выбрать файл с диска, потом загружать';
    header ("Location: /admin/?view=price");
}else{
    $_SESSION['alertPrice'] = 'На сервер возможно загрузить только xls формат.';
    header ("Location: /admin/?view=price");
}


