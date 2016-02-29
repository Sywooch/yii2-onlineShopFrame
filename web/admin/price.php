<?php ?>
<div style="margin: 20px;">
    <?php if ($_SESSION['alertPrice'] != null):?>
    <div style="background-color: #eed3d7; padding: 10px;">
        <?=$_SESSION['alertPrice']?>
    </div>
    <?php endif;?>
    <br>
	<p>Чтоб прайс открывался у клиентов без ошибок то лучше загружать его в формате XLS, новый формат XLSX будет открываться с предупрежеднием.</p>
	<br>
    <form enctype="multipart/form-data" action="action/upload_price.php" method="POST" >
        Выбор файла: <input name="price" type="file" />
        <input type="submit" value="Загрузить прайс" />
    </form>
</div>
<?php $_SESSION['alertPrice']=null; ?>