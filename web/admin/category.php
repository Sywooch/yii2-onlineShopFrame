<div style="margin: 0 20px;"><!--margin-->
<div>1 - Категория попадают в группу если в таблице категорий в столбце <b>ID группы</b> стоит номер нужной группы (или через запятую номера нескольких групп), если пусто - то категория вне группы.</div>
<div>2 - Сортировка работает по принципцу от меньшего числа к большому. Категории с меньшим значением в сортировке будут выше.</div>
<div>3 - В целях избежания случайного редактирования, в каждой строке стоит чекбокс. Если его не нажать изменения приняты не будут.</div>
<br>
<table border="1" style="float:left; margin-right:15px;">
<tr>
<th>ID</th><th>Название категории</th><th style="background-color:#cffabe;">ID Подгруппы</th><th>сортировка</th><th>обновить</th><th>удалить</th><th>chekbox</th>
</tr>
<?php
$r=get_category(); 
while ($row=mysql_fetch_array($r)) {
    print "<form method = \"post\" action=\"action/update_category.php\">
            <tr>
                <td><input type=\"hidden\" name=\"id\" value=\"$row[id]\">$row[id]</td>
                <td><input type=\"text\" name=\"catalog_row_name\" value=\"$row[name]\"></td>
                <td><input style=\"background-color:#cffabe;\" type=\"text\" size=\"5\" name=\"group_id\" value=\"$row[group_id]\"></td>
                <td><input type=\"text\" size=\"3\" name=\"sort\" value=\"$row[sort]\"></td>
                <td><button name=\"action\" value=\"update_cat\"><img src=\"img/upd.png\"></button></td>
                <td><button name=\"action\" value=\"delete\"><img src=\"img/del.png\"></button></td>
                <td><input type=\"checkbox\" name=\"chek\" value=\"on\">&ensp;&#10033;</td>
            </tr>
        </form>";
}
?>
<form method = "post" action="action/update_category.php"><tr><td></td><td><button name="action" value="add_new_row"><img src="img/plus.png"></button></td><td></td><td></td><td><input type="checkbox" name="chek" value="on">&lt;&mdash;&#10033;</td></tr></form>
</table>

<!-- table of groups-->
<table border="1">
<tr>
<th style="background-color:#cffabe;">ID</th><th style="background-color:#cffabe;">Название подгруппы</th><th style="background-color:#fac6be;">ID Группы</th><th>сортировка</th><th>обновить</th><th>удалить</th><th>chekbox</th>
</tr>
<?php
$r=get_group_category();
while ($row=mysql_fetch_array($r)) {
    print "<form method = \"post\" action=\"action/update_group_category.php\">
            <tr>
                <td style=\"background-color:#cffabe;\"><input type=\"hidden\" name=\"id\" value=\"$row[id]\">$row[id]</td>
                <td><input type=\"text\" name=\"name\" value=\"$row[name]\"></td>
                <td><input style=\"background-color:#fac6be;\" type=\"text\" size=\"5\" name=\"topgroup_id\" value=\"$row[topgroup_id]\"></td>
		<td><input type=\"text\" size=\"3\" name=\"sort\" value=\"$row[sort]\"></td>
                <td><button name=\"action\" value=\"update_gr_cat\"><img src=\"img/upd.png\"></button></td>
                <td><button name=\"action\" value=\"delete\"><img src=\"img/del.png\"></button></td>
                <td><input type=\"checkbox\" name=\"chek\" value=\"on\">&ensp;&#10033;</td>";
    if ($row[topgroup_id]==null){
        print " <td><img style='max-width: 30px; max-height: 30px;' src='../images/icons/$row[icon]'></td>
                <td><button name=\"action\" value=\"update_icon\"><img src=\"img/ed.png\"></button></td>";
    }
    print "
            </tr>
        </form>";
}
?>
<form method = "post" action="action/update_group_category.php"><tr><td></td><td><button name="action" value="add_new_row"><img src="img/plus.png"></button></td><td></td><td></td><td><input type="checkbox" name="chek" value="on">&lt;&mdash;&#10033;</td></tr></form>
</table>
<br>

<!-- table of TOPgroups-->
<table border="1">
<tr>
<th style="background-color:#fac6be;">ID</th><th style="background-color:#fac6be;">Название группы</th><th>сортировка</th><th>обновить</th><th>удалить</th><th>chekbox</th>
</tr>
<?php
$r=get_group_groups();
while ($row=mysql_fetch_array($r)) {
    print "<form method = \"post\" action=\"action/update_group_groups.php\">
            <tr>
                <td style=\"background-color:#fac6be;\"><input type=\"hidden\" name=\"id\" value=\"$row[id]\">$row[id]</td>
                <td><input type=\"text\" name=\"name\" value=\"$row[name]\"></td>
                <td><input type=\"text\" size=\"3\" name=\"sort\" value=\"$row[sort]\"></td>
                <td><button name=\"action\" value=\"update_gr_cat\"><img src=\"img/upd.png\"></button></td>
                <td><button name=\"action\" value=\"delete\"><img src=\"img/del.png\"></button></td>
                <td><input type=\"checkbox\" name=\"chek\" value=\"on\">&ensp;&#10033;</td>
                <td><img style='max-width: 30px; max-height: 30px;' src='../images/icons/$row[icon]'></td>
                <td><button name=\"action\" value=\"update_icon\"><img src=\"img/ed.png\"></button></td>
            </tr>
        </form>";
}
?>
<form method = "post" action="action/update_group_groups.php"><tr><td></td><td><button name="action" value="add_new_row"><img src="img/plus.png"></button></td><td></td><td></td><td><input type="checkbox" name="chek" value="on">&lt;&mdash;&#10033;</td></tr></form>
</table>
</div><!--margin-->
