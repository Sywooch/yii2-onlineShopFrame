<?php
mysql_connect(HOST, MYSQL_USER, MYSQL_PASS) or die("error with database connect"); //****  FIRST CONNECT TO DB *************
mysql_select_db(DATABASE) or die("login or password incorrect"); // ******************     FIRST CONNECT TO DB *************
?>

<?php $view = empty($_GET['view']) ? 'goods' : $_GET['view'];?>


<header>
    <nav>
		<a name="top"></a><?php //якорь ссылка на верх из справки ?>
        <ul>
            <li><a href="/admin/?view=goods">GOODS</a></li>
            <li><a href="/admin/?view=category">CATEGORY</a></li>
            <li><a href="/admin/?view=goods&edit_goods=0">ADD NEW GOODS</a></li>
            <li><a href="/admin/?view=price">PRICE</a></li>
			<li><a href="/admin/?view=help"><img style="padding: 10px 20px 10px 0; border-right: solid 2px; " src="img/help.png" alt="HELP"></a></li>
            <?php if ($_GET['edit_goods']){print "<div class=\"edit_goods\">EDIT GOODS</div>";}?>
			<li style="line-height: 50px; padding: 0 20px; float: right;">-= Name app =-&emsp;| ver 1.5 &copy </li>
			
        </ul>
    </nav>
</header>

<div class="cont">
<?php 
include ($view.".php"); ?>
</div>


<?php 
mysql_close();  //******************* CLOSE CONNECT TO DB
?>
    