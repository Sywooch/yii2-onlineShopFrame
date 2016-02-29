<?php
//$login = $_SESSION['login'];
//$pass = $_SESSION['pass'];
define ("HOST", "localhost");
define ("DATABASE", "dbname");
define ("MYSQL_USER", "root");
define ("MYSQL_PASS", "");

function chek_login(){
    if (in_array($_SESSION['pass'], array ("admin")) and in_array($_SESSION['login'], array ("admin"))) {
        include ("admin_panel.php");
    }
    else {
        include ("login.html");
    }
}

function get_category($prod_id='', $group_id=''){
	// в случае просмотра как каталог при выборе ВНЕ ГРУППЫ
	if($prod_id=='' and $group_id=='NULL'){
		$r=mysql_query ("SELECT * FROM `fsh_catalog` WHERE group_id is NULL ORDER BY `group_id`, `sort` ASC");
	// в случае просмотра как каталог при выборе ВСЕ КАТЕГОРИИ	
	}elseif($prod_id=='' and $group_id==''){
		$r=mysql_query ("SELECT * FROM `fsh_catalog` ORDER BY `group_id`, `sort` ASC");
	// в случае просмотра как каталог при выборе какой то группы	
	}elseif($prod_id=='' and ($group_id!='' AND $group_id!='NULL')){
		$r=mysql_query ("SELECT * FROM `fsh_catalog` WHERE FIND_IN_SET ($group_id, group_id) ORDER BY `group_id`, `sort` ASC");
	// в случае редактора товара при выборе какой то группы		
	}elseif($prod_id!='' and ($group_id!='' AND $group_id!='NULL')){
		$r=mysql_query ("SELECT t1.*, \"1\" as cat_flag FROM fsh_catalog t1
							JOIN fsh_prod_catalog t2 ON t1.id=t2.cat_id WHERE t2.prod_id=$prod_id AND FIND_IN_SET ($group_id, group_id)
						UNION 
						SELECT *, \"0\" as cat_flag FROM `fsh_catalog` WHERE id NOT IN(SELECT t1.id FROM fsh_catalog t1
							JOIN fsh_prod_catalog t2 ON t1.id=t2.cat_id WHERE t2.prod_id=$prod_id) AND FIND_IN_SET ($group_id, group_id)					
						ORDER BY `sort` ASC");
	// в случае редактора товара при выборе ВНЕ ГРУППЫ	
	}elseif($prod_id!='' and $group_id=='NULL'){
		$r=mysql_query ("SELECT t1.*, \"1\" as cat_flag FROM fsh_catalog t1
							JOIN fsh_prod_catalog t2 ON t1.id=t2.cat_id WHERE t2.prod_id=$prod_id AND group_id is NULL
						UNION 
						SELECT *, \"0\" as cat_flag FROM `fsh_catalog` WHERE id NOT IN(SELECT t1.id FROM fsh_catalog t1
							JOIN fsh_prod_catalog t2 ON t1.id=t2.cat_id WHERE t2.prod_id=$prod_id) AND group_id is NULL					
						ORDER BY `sort` ASC");	
	// в случае редактора товара при выборе ВСЕ КАТЕГОРИИ							
	}else{
		$r=mysql_query ("SELECT t1.*, \"1\" as cat_flag FROM fsh_catalog t1
							JOIN fsh_prod_catalog t2 ON t1.id=t2.cat_id WHERE t2.prod_id=$prod_id
						UNION 
						SELECT *, \"0\" as cat_flag FROM `fsh_catalog` WHERE id NOT IN(SELECT t1.id FROM fsh_catalog t1
							JOIN fsh_prod_catalog t2 ON t1.id=t2.cat_id WHERE t2.prod_id=$prod_id)						
						ORDER BY `sort` ASC");
	}	
    return $r;
}

function get_group_category($topgroup_id='all'){
    if ($topgroup_id=='all'){
        $r=mysql_query ("SELECT * FROM `fsh_groupofcat` ORDER BY `sort` ASC");
    }else{
        $r=mysql_query ("SELECT * FROM `fsh_groupofcat` WHERE topgroup_id=$topgroup_id ORDER BY `sort` ASC");
    }    
    return $r;
}

function get_group_name ($id){
	if($id==''){
		return 'Выберите группу';
	}elseif($id=='NULL'){
		return 'Вне групп';
	}else{
		$r=mysql_query ("SELECT * FROM `fsh_groupofcat` WHERE id=$id");
		$row=mysql_fetch_array($r);
		return $row['name'];
	}
}

function get_group_groups(){
    $r=mysql_query ("SELECT * FROM `fsh_groupofgroups` ORDER BY `sort` ASC");
    return $r;
}

function get_goods($cat_id){
	if($cat_id=='none'){
		$r=mysql_query("SELECT * FROM products prod
						WHERE NOT EXISTS (SELECT * FROM fsh_prod_catalog cat WHERE cat.prod_id=prod.id)");
	}else{
		$r=mysql_query("SELECT t2.* FROM fsh_prod_catalog t1
						JOIN products t2 ON t2.id=t1.prod_id
						JOIN fsh_catalog t3 ON t1.cat_id=t3.id WHERE t3.id=\"$cat_id\""); 
						/*$r=mysql_query("SELECT  t1.id, t1.name, t1.brand_name, t1.articul,  t1.price, t1.size, t1.weight, t1.short_dscr, t1.full_dscr, t1.tags, t1.best_sell, t2.img as item_img FROM products t1
						LEFT JOIN prod_img t2 on t1.id = t2.prod_id");*/
	}
    return $r;
}

function get_main_image($id){
    $r=mysql_query("SELECT * FROM `prod_img` WHERE prod_id = $id AND main=1")
	or die (mysql_error());
    return $r;
}

function get_images($id){
    $r=mysql_query("SELECT * FROM `prod_img` WHERE prod_id = $id")
	or die (mysql_error());
    return $r;
}
/*
function get_prod_cat($id){
    $r=mysql_query ("SELECT * FROM `fsh_prod_catalog` WHERE prod_id = $id") 
	or die (mysql_error());
    return $r;
}
*/
function get_goods_info($id){    
        $r=mysql_query("SELECT * FROM `products` WHERE id = $id") 
		or die (mysql_error());
    return $r;
}

/*function sort_grouped_goods($id_in_group_arr){
    for ($a=0; ;$a++){
        $id_in_group = $id_in_group_arr[$a] [id_in_group];
        if (!$id_in_group_arr[$a] [id_in_group]){ break;}
    }
print "This is the grouped item, and in his group have ".$a++." goods.";
}*/

function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}
function str2url($str) {
    // переводим в транслит
    $str = rus2translit($str);
    // в нижний регистр
    $str = strtolower($str);
    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    // удаляем начальные и конечные '-'
    $str = trim($str, "-");
    return $str;
}
?>