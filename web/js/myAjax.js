/**
 * Created by KVUSH-NBOOK on 27.11.2015.
 */

function openItem(data){
    $.ajax({
        type: 'GET',
        url: data,
        cache: false,
        beforeSend: function () {
            $('#ajaxContent').html('downloading...');
        },
        success: function (answer) {
            $('#ajaxContent').html(answer);            
        },
        complete: function () {
            $(window).scrollTop('0');
        }
    });
    updateBreadcrumbs(data);
}

function updateBreadcrumbs(data){
    $.ajax({
        type: 'GET',
        url: data+'?breadcrumbs=1',
        cache: false,
        success: function (answer) {
            $('#breadcrumbs_ajax').html(answer);
        }
    });
}

function addToCart(data, button){
    $.ajax({
        type: 'GET',
        url: data,
        cache: false,
        beforeSend: function () {
            button.replaceWith('<div id="loadAnimatDiv">\n\
<div id="floatingCirclesG">\n\
<div class="f_circleG" id="frotateG_01"></div>\n\
<div class="f_circleG" id="frotateG_02"></div>\n\
<div class="f_circleG" id="frotateG_03"></div>\n\
<div class="f_circleG" id="frotateG_04"></div>\n\
<div class="f_circleG" id="frotateG_05"></div>\n\
<div class="f_circleG" id="frotateG_06"></div>\n\
<div class="f_circleG" id="frotateG_07"></div>\n\
<div class="f_circleG" id="frotateG_08"></div>\n\
</div>\n\
</div>');
        },
        success: function (answer) {
            $('#cartOnUpMenu').css('display', 'block');
            $('#cartOnUpMenu a').html(answer);
        }
    });
}

function updateCatalog(data, scrollValue){
    $.ajax({
        type: 'GET',
        url: data,
        cache: false,
        beforeSend: function () {
           $('#ajaxContent').html('ดาวน์โหลด...');
        },
        success: function (answer) {
            $('#ajaxContent').html(answer);
            $('.openGoodsAjax').click( function(e){
                var url = $(this).attr('href');
                var scrollTop= (document.documentElement.scrollTop || document.body && document.body.scrollTop || 0);
                history.replaceState({scroll:scrollTop}, null, location);
                history.pushState({scroll:0}, null, url);
                openItem(url);
                return false;
            }); 
            ajaxVkorzinu();
            updateBreadcrumbs(data);
        },
        complete: function () {
            $(window).scrollTop(scrollValue);
        }
    });
}

function ajaxVkorzinu() {
    /*var orderAmount = 1;
    $('.inputRow').children().find('[name*=orderAmount]').change(function(){orderAmount = $(this).val();});*/
    
    $('.vkorzinuButton').click(function() {
        var orderAmount = $(this).parents('form').children().find('[name*=orderAmount]').val();
        var button = $(this);
        var id = $(this).parents('form').children().find('[name=id]').attr('value');
        var update = $(this).parents('form').children().find('[name=update]').attr('value');
        var url = '/products/add-to-cart?id=' + id + '&update=' + update + '&orderAmount[' + id + ']=' + orderAmount;
        addToCart(url + '&menuUpdate=1', button);
        setTimeout(function() {
            $('#ajaxLoad_' + id).parent('.ajaxLoadOut').load(url + '&menuUpdate=0', null, function(){ajaxVkorzinu();})
        }, 500);
        //orderAmount = 1;
        return false;
    });
}



