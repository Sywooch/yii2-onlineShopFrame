<?php
/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php
//$this->registerJs("$('#catalog_menu').tree({expanded: 'li:first'});");
$this->registerJsFile('@web/js/myAjax.js', ['depends'=>'yii\web\YiiAsset','position'=>$this::POS_HEAD]);

$this->registerJs("
    $('#catalog_menu').tree({});
    ", $this::POS_READY);

$this->registerJs("
        $('.goAjax').on('click', 'a', function(e){
            var data = $(this).attr('href');
            var scrollTop= (document.documentElement.scrollTop || document.body && document.body.scrollTop || 0);
            history.replaceState({scroll:scrollTop}, null, location);
            history.pushState({scroll:0}, null, data);
            updateCatalog(data, 0);

            var catname = $(this).html();
            document.title = catname+' \"Suwanna Fashion Shop\"';

            return false;
        });
        window.addEventListener('popstate', function(e){
                                                updateCatalog(location, e.state.scroll);
                                            },false);
        ", $this::POS_READY);

$this->registerJs("
        $('#back-top').hide();
        $(window).scroll(function() {
            if ($(this).scrollTop() > 400) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }
        });
        $('#back-top a').click(function() {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
        return false;
        });
    ", $this::POS_READY);
?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
	<?= Yii::$app->JQTreeMenu->run();?>
    <p id="back-top">
        <a href="#"><span></span></a>
    </p>
</div>

<div class ="col-xs-12 col-sm-9">
	<div id="ajaxContent">
		<?= $content ?>
	</div>
</div>
<?php $this->endContent(); ?>




