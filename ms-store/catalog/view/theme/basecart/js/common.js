$.ajaxSetup({
    beforeSend: function () {
//создаем div
        var bg_loading = $("<div>", {
            "class": "messages-ajax"
        });
        //$("body").append(loading);
        //$(window).height()
        $(bg_loading)
                //.css("left", ($(window).width() - $('div.messages-ajax').outerWidth()) / 2 + $(window).scrollLeft() + "px")
                .css("height", $(window).height() + "px")
                .css("width", $(window).width() + "px");
        //.css("width", $(window).width() + "px")
        //.delay(2000)
        //.fadeOut(1000);

        $("body").append(bg_loading);
        var container = $("<div>", {
            "class": "container"
        });
        $("body div.messages-ajax").append(container);
        var loading = $("<span>", {
            "class": "glyphicon glyphicon-refresh ds-loading"
        });
        //выравним div по центру страницы
        //console.log(' wh =' + $(window).height() + ' lh = ' + $(loading).height());
        //console.log(' dw =' + $(document).width() + ' lw = ' + $(loading).width());
        //console.log(' top =' + (($(window).height() / 2) - ($(loading).height() / 2)));
        //console.log(' left =' + (($(window).width() / 2) - ($(loading).width() / 2)));
        $(loading).css("top", ($(window).height() / 2) - ($(loading).height() / 2)).css("left", ($(document).width() / 2) - ($(loading).width() / 2));
        //$(container).css("top", ($(window).height() / 2) - ($(container).height() / 2)).css("left", ($(document).width() / 2) - ($(container).width() / 2));
        //добавляем созданный div в конец документа
        //$("body").append(loading);
        $("body div.messages-ajax div.container").append(loading);
    },
    complete: function () {
        //уничтожаем div    
        $(".messages-ajax").detach();
        //$(".ds-loading").detach();
    }
});
function getURLVar(key) {
    var value = [];
    var query = String(document.location).split('?');
    if (query[1]) {
        var part = query[1].split('&');
        for (i = 0; i < part.length; i++) {
            var data = part[i].split('=');
            if (data[0] && data[1]) {
                value[data[0]] = data[1];
            }
        }

        if (value[key]) {
            return value[key];
        } else {
            return '';
        }
    }
}

// shopping cart product counter
function getQuantity() {
    var nop = document.getElementById("cart-total").innerHTML;
    nop = nop.replace(/(^\d+)(.+$)/i, '$1');
    document.getElementById("cart-total").innerHTML = nop;
    if (document.getElementById("cart-total").innerHTML !== "0") {
        document.getElementById("cart-total").style.display = "block";
    }
}

/* оркугление float чисел*/
function modRound(value, precision)
{
    // спецчисло для округления
    var precision_number = Math.pow(10, precision);
    // округляем
    return Math.round(value * precision_number) / precision_number;
}

$(document).ready(function () {
// adding the clear Fix
    cols1 = $('#column-right, #column-left').length;
    if (cols1 == 2) {
        $('#content .product-layout:nth-child(2n+2)').after('<div class="clearfix visible-md visible-sm"></div>');
    } else if (cols1 == 1) {
        $('#content .product-layout:nth-child(3n+3)').after('<div class="clearfix visible-lg"></div>');
    } else {
        $('#content .product-layout:nth-child(4n+4)').after('<div class="clearfix"></div>');
    }

// highlight any found errors
    $('.text-danger').each(function () {
        var element = $(this).parent().parent();
        if (element.hasClass('form-group')) {
            element.addClass('has-error');
        }
    });
    // currency
    $('#form-currency .currency-select').on('click', function (e) {
        e.preventDefault();
        $('#form-currency input[name=\'code\']').attr('value', $(this).attr('name'));
        $('#form-currency').submit();
    });
    // language
    $('#form-language .language-select').on('click', function (e) {
        e.preventDefault();
        $('#form-language input[name=\'code\']').attr('value', $(this).attr('name'));
        $('#form-language').submit();
    });
    // search
    $('#search input[name=\'search\']').parent().find('button').on('click', function () {
        url = $('base').attr('href') + 'index.php?route=product/search';
        var value = $('nav input[name=\'search\']').val();
        if (value) {
            url += '&search=' + encodeURIComponent(value);
        }

        location = url;
    });
    $('#search input[name=\'search\']').on('keydown', function (e) {
        if (e.keyCode == 13) {
            $('nav input[name=\'search\']').parent().find('button').trigger('click');
        }
    });
    // product list
    $('#list-view').click(function () {
        $('#content .product-layout > .clearfix').remove();
        $('#content .row > .product-layout').attr('class', 'product-layout product-list col-xs-12');
        localStorage.setItem('display', 'list');
    });
    // product grid
    $('#grid-view').click(function () {
        $('#content .product-layout > .clearfix').remove();
        // what a shame bootstrap does not take into account dynamically loaded columns
        cols = $('#column-right, #column-left').length;
        if (cols == 2) {
            $('#content .product-layout').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
        } else if (cols == 1) {
            $('#content .product-layout').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
        } else {
            $('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
        }

        localStorage.setItem('display', 'grid');
    });
    if (localStorage.getItem('display') == 'list') {
        $('#list-view').trigger('click');
    } else {
        $('#grid-view').trigger('click');
    }

// checkout
    $(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function (e) {
        if (e.keyCode == 13) {
            $('#collapse-checkout-option #button-login').trigger('click');
        }
    });

    // tooltips on hover
    $('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
    // makes tooltips work on ajax generated content
    $(document).ajaxStop(function () {
        $('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
    });
    getQuantity();
/////////////////////////////////////////////////////////////////////////////////////////////////
    /* CheckBox Cart*/

    // подсчет суммы выбранных позиций при изменении количества
    $('.quantity').click(function () {
        cart.calc_checked();
    });
    ////////////////////////////////////////////////////////////////////////////////
    //console.log($("#tabledata"));
    if ($("#tabledata")[0]) {
        $("#tabledata").searcher({
            inputSelector: "#tablesearchinput"

        });
    }
    // Сортировка по input полям
    //console.log($.tablesorter);
    if (typeof $.tablesorter != 'undefined') {
        $.tablesorter.addParser({
            id: 'inputs',
            is: function (s) {
                return false;
            },
            format: function (s, table, cell, cellIndex) {
                var $c = $(cell);
                // return 1 for true, 2 for false, so true sorts before false
                if (!$c.hasClass('updateInput')) {
                    $c
                            .addClass('updateInput')
                            .bind('keyup', function () {
                                $(table).trigger('updateCell', [cell, false]); // false to prevent resort
                            });
                }
                return $c.find('input').val();
            },
            type: 'text'
        });

        $("#tabledata").tablesorter({
            theme: 'ice',
            // return the modified template string
            onRenderTemplate: null,
            stripingRowClass: ['even', 'odd'], // class names for striping supplyed as a array.
            sortList: [[0, 0], [1, 0]],
            headers: {
                0: {sorter: false},
                4: {sorter: "shortDate", dateFormat: "ddmmyyyy"},
                6: {sorter: 'inputs'},
                9: {sorter: 'inputs'},
                10: {sorter: false},
                11: {sorter: false},
                tfoot: {sorter: false},
            }
        });

///////////////////////////////////////////////////////////////////////////////
        $("#tableorder").searcher({
            inputSelector: "#tablesearchinput"
                    //itemSelector: 'tbody > intput.value'
                    // itemSelector (tbody > tr) and textSelector (td) already have proper default values
        });

        $("#tableorder").tablesorter({
            theme: 'ice',
            // return the modified template string
            onRenderTemplate: null,
            stripingRowClass: ['even', 'odd'], // class names for striping supplyed as a array.
            sortList: [[0, 1]],
            headers: {
                0: {sorter: true},
                1: {sorter: true},
                5: {sorter: 'inputs'},
                8: {sorter: 'inputs'},
                10: {sorter: true}
            }
        });

        $("#table_order_product").searcher({
            inputSelector: "#tablesearchinput"
                    //itemSelector: 'tbody > intput.value'
                    // itemSelector (tbody > tr) and textSelector (td) already have proper default values
        });

        $("#table_order_product").tablesorter({
            theme: 'ice',
            // return the modified template string
            onRenderTemplate: null,
            stripingRowClass: ['even', 'odd'], // class names for striping supplyed as a array.
            sortList: [[0, 1]],
            headers: {
                10: {sorter: false}
            }
        });
    }
    $("input.f-comment").change(function () {
        cart.changes(this);
    });
    $("input.f-comment").keyup(function () {
        cart.changes(this);
    });
    $("input.f-quantity").change(function () {
        cart.changes(this);
    });
    $("input.f-quantity").keyup(function () {
        cart.changes(this);
    });
    $(".spin-up").click(function () {
        cart.changes(this);
    });
    $(".spin-down").click(function () {
        cart.changes(this);
    });
    cart.active_button();

    /* функция для header при скроллинге */
    function offsetPosition(e) { // отступ от верхнего края экрана до элемента
        //console.log(e);
        var offsetTop = 50; // 50px прокрутки прежде чем сработает функция
        do {
            offsetTop += e.offsetTop;
        } while (e = e.offsetParent);
        return offsetTop;
    }

    var left_logo = document.querySelector('header'),
            OP = offsetPosition(left_logo);

    function sticky_relocate() {
        var window_top = $(window).scrollTop();
        var div_top = $('#sticky-anchor').offset().top;
        if (window_top > div_top) {
            $('.navbar').addClass('navbar-fixed-top');

            $('#sticky-anchor').height($('#sticky').outerHeight());
            $('#rowMenu').addClass('hidden'); // скрыли меню
            $('#sr-but').addClass('hidden'); // скрыли имя
            $('#hiddenT').addClass('hidden'); // скрыли номер телефона
            $('#hiddenM').addClass('hidden'); // скрыли icon ellipsis
            $('#hiddenMenu').addClass('hidden');
            $('.img-responsive').addClass('logoheader'); // класс для изменения размера лого
            $('.navbar-header').addClass('logoheader');
            $('.headerlogo').addClass('hidden');
            $('div.col-md-7').removeClass('col-md-7').addClass('col-md-11');

            $('.navbar-fixed-top').fadeIn(500);

            var i = $('#search-bottom').html();
            if (i) {
                //console.log(i);
                $('#search-top').html(i);
                $('#search-bottom').html('');
                $('#artnum').keypress(function (e) {
                    var keyId = e.keyCode || e.which || 0;
                    if (keyId == 13) {
                        tdm_search_submit();
                        return false;
                    }
                });

                //onsole.log(left_logo);
            }
        } else {
            $('#sticky').removeClass('stick');
            $('#sticky-anchor').height(0);
            $('#rowMenu').removeClass('hidden');
            $('.navbar').removeClass('navbar-fixed-top');
            $('.navbar').css("display", "");
            $('#hiddenT').removeClass('hidden');
            $('#hiddenM').removeClass('hidden');
            $('#hiddenMenu').removeClass('hidden');
            $('#sr-but').removeClass('hidden');
            $('.img-responsive').removeClass('logoheader');
            $('.navbar-header').removeClass('logoheader');
            $('.headerlogo').removeClass('hidden');
            $('div.col-md-11').removeClass('col-md-11').addClass('col-md-7');

            $('.navbar-fixed-top').fadeOut();

            var ii = $('#search-top').html();
            if (ii) {
                $('#search-bottom').html(ii);
                $('#search-top').html('');
                $('#artnum').keypress(function (e) {
                    var keyId = e.keyCode || e.which || 0;
                    if (keyId == 13) {
                        tdm_search_submit();
                        return false;
                    }
                });
            }
        }
    }

    $(function () {
        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });

    var dir = 1;
    var MIN_TOP = 200;
    var MAX_TOP = 300;

    function autoscroll() {
        var window_top = $(window).scrollTop() + dir;
        if (window_top >= MAX_TOP) {
            window_top = MAX_TOP;
            dir = -1;
        } else if (window_top <= MIN_TOP) {
            window_top = MIN_TOP;
            dir = 1;
        }
        $(window).scrollTop(window_top);
        window.setTimeout(autoscroll, 100);
    }


    /*  window.onscroll = function () {
     if (left_logo.className = (OP < window.pageYOffset)) {
     $('.navbar').addClass('navbar-fixed-top'); // зафиксировали навбар
     $('#rowMenu').addClass('hidden'); // скрыли меню
     $('#hiddenT').addClass('hidden'); // скрыли номер телефона
     $('#hiddenM').addClass('hidden'); // скрыли icon ellipsis
     $('#hiddenMenu').addClass('hidden');
     $('.img-responsive').addClass('logoheader'); // класс для изменения размера лого
     $('.navbar-header').addClass('logoheader');
     $('.headerlogo').addClass('hidden');    
     $('div.col-md-7').removeClass('col-md-7').addClass('col-md-11');
     
     var i = $('#search-bottom').html();
     if (i) {
     //console.log(i);
     $('#search-top').html(i);
     $('#search-bottom').html('');
     $('#artnum').keypress(function (e) {
     var keyId = e.keyCode || e.which || 0;
     if (keyId == 13) {
     tdm_search_submit();
     return false;
     }
     });
     
     //onsole.log(left_logo);
     }
     } else {
     $('#rowMenu').removeClass('hidden');
     $('.navbar').removeClass('navbar-fixed-top');
     $('#hiddenT').removeClass('hidden');
     $('#hiddenM').removeClass('hidden');
     $('#hiddenMenu').removeClass('hidden');
     $('.img-responsive').removeClass('logoheader');
     $('.navbar-header').removeClass('logoheader');
     $('.headerlogo').removeClass('hidden');
     $('div.col-md-11').removeClass('col-md-11').addClass('col-md-7');
     
     var ii = $('#search-top').html();
     if (ii) {
     $('#search-bottom').html(ii);
     $('#search-top').html('');
     $('#artnum').keypress(function (e) {
     var keyId = e.keyCode || e.which || 0;
     if (keyId == 13) {
     tdm_search_submit();
     return false;
     }
     });
     }
     }
     }*/
});

///////////////////////////////////////////////////////////////////////////////////////////////////////////

// cart add remove functions
var cart = {
    'add': function (product_id, quantity) {
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: 'product_id=' + product_id + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
            dataType: 'json',
            complete: function () {
                $('#cart > a').button('reset');
                $(".messages-ajax").detach();
            },
            success: function (json) {
                $('.alert, .text-danger').remove();
                if (json.redirect) {
                    location = json.redirect;
                }

                if (json.success) {
                    $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    // need to set timeout otherwise it wont update the total
                    setTimeout(function () {
                        $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');
                        getQuantity();
                    }, 100);
                    $('html, body').animate({scrollTop: 0}, 'slow');
                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'add_from_tdm': function (productId, uGroupId) {
        console.log(productId);
        //var quantity = document.getElementById(PHID).value;
        var quantity = $('#product-' + productId + ' .count_inp').val();
        //console.log(quantity);
        $.ajax({
            url: 'index.php?route=checkout/cart/addfromtdm',
            type: 'post',
            data: 'product-id=' + productId + '&ug-id=' + uGroupId + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
            dataType: 'json',
            complete: function () {
                //$('#cart > a').button('reset');
                $(".messages-ajax").detach();
            },
            success: function (json) {
                console.log(json);
                // $('.alert, .text-danger').remove();
                /*if (json.redirect) {
                 location = json.redirect;
                 }*/

                if (json.success) {
                    //$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    // need to set timeout otherwise it wont update the total
                    setTimeout(function () {
                        $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');
                        getQuantity();
                    }, 100);
                    //$('html, body').animate({scrollTop: 0}, 'slow');
                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                    //console.log($('#' + json.phid + ' a.tdcartadd'));
                    $('#product-' + json.productId + ' a.tdcartadd').addClass('added');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'checkout': function () {
        location = 'index.php?route=checkout/simplecheckout'; // исправленно согласно url модуля simple
    },
    'update': function (key, quantity) {
        $.ajax({
            url: 'index.php?route=checkout/cart/edit',
            type: 'post',
            data: 'key=' + key + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
            dataType: 'json',
            complete: function () {
                $('#cart > a').button('reset');
                $(".messages-ajax").detach();
            },
            success: function (json) {
                // need to set timeout otherwise it wont update the total
                setTimeout(function () {
                    $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');
                    getQuantity();
                }, 100);
                if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
                    location = 'index.php?route=checkout/cart';
                } else {
                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    // кнопка сохранения
    'update_all': function () {
        // цикл
        $('.cb_cart').each(function (i, elem) {
            // бежим по каждому элем с cb_cart
            //console.log(i);
            //console.log(elem);
            if ($(elem)) {
                k = $(elem).val(); // определение id товара в корзине  
                //console.log(k);
                cart.update_my(k);
            }
        })
        $('.btn_save').addClass('disabled');
        $('.btn_save').attr('disabled', 'disabled');
        $('.btn_save').addClass('btn-default');
        $('.btn_save').removeClass('btn-success');
    },
    // сохранение количества и комментария
    'update_my': function (key) {
        quantity = $('[name="quantity[' + key + ']"]').val();
        comment = $('[name="comment[' + key + ']"]').val();
        $.ajax({
            url: 'index.php?route=checkout/cart/edit_ajax',
            type: 'post', // скрытый запрос
            data: 'key=' + key + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1) + '&comment=' + (typeof (comment) != 'undefined' ? comment : ''),
            // typeof (quantity) != 'undefined' ? quantity : 1 - если значение в переменной определенно то подставить то что попределил, если нет то 1
            dataType: 'json',
            complete: function () {
                $('#cart > a').button('reset');
                $(".messages-ajax").detach();
            },
            success: function (json) {
                // need to set timeout otherwise it wont update the total
                //alert(json.total);
                //console.log(json);
                setTimeout(function () {
                    $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');
                    getQuantity();
                }, 100);
                //alert(getURLVar('route'));
                if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
                    //location = 'index.php?route=checkout/cart';
                } else {
                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
                price_text = $("#price_" + key).html();
                //console.log("#price_" + key);
                console.log(price_text);
                regexp_price = /(\d+)\.(\d+)/; // шаблон регулярного выражения для определения цены товара
                price = regexp_price.exec(price_text);
                //console.log('key = ' + key);
                //console.log('total = ' + quantity * price[0]);
                $('#total_' + key).html(quantity * price[0] + ' p.'); // вывод total в столбце
                //var arr = json.totals;
                //console.log(json.totals);
                // ОБЬЕКТЫ ИЗ ЦИКЛА json.total
                // достали из массива нужные значение
                jQuery.each(json.totals, function (i, val) {
                    $("#" + i).text(val);
                });
                $('#btn_update_' + key).addClass('disabled');
                $('#btn_update_' + key).attr('disabled', 'disabled');
                $('#btn_update_' + key).addClass('btn-default');
                $('#btn_update_' + key).removeClass('btn-success');
                cart.check_changes();
                // проверить есть ли еще измененные строки

                var item, index = 0, length = json.totals.length;
                console.log(json.totals);
                for (; index < length; index++) {
                    item = array[index];
                }
                cart.calc_checked();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'remove': function (key) {
        $.ajax({
            url: 'index.php?route=checkout/cart/remove',
            type: 'post',
            data: 'key=' + key,
            dataType: 'json',
            complete: function () {
                $('#cart > a').button('reset');
                $("tr.cart_" + key).remove();
                $(".messages-ajax").detach();
            },
            success: function (json) {
                // need to set timeout otherwise it wont update the totalsss
                setTimeout(function () {
                    $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');
                    getQuantity();
                }, 100);
                if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
                    //location = 'index.php?route=checkout/cart';
                } else {
                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    // удаление отмеченных
    'remove_checked': function () {
        $('.cb_cart').each(function (i, elem) {
            //console.log(i);
            //console.log(elem);
            if ($(elem).prop('checked')) {
                k = $(elem).val();
                //console.log(k);
                cart.remove(k);
            }
        });
    },
    // кнопка удалить все
    'remove_all': function () {
        $('.cb_cart').each(function (i, elem) {
            //console.log(i);
            //console.log(elem);
            if ($(elem)) {
                k = $(elem).val();
                //console.log(k);
                cart.remove(k);
            }
        });
    },
    // отметить все позиции в товаре
    'check_all': function () {
        if ($('.cb_all').prop('checked')) { // проверяем его значение
            $('.cb_cart:enabled').prop('checked', true); // если чекбокс отмечен, отмечаем все чекбоксы
        } else {
            $('.cb_cart:enabled').prop('checked', false); // если чекбокс не отмечен, снимаем отметку со всех чекбоксов
        }
        cart.active_button();
    },
    'active_button': function (product_id) {
        count_checked = 0;
        all_check = 0;
        check_id = '';
        regexp_id = /(\d+)/;
        // таким образом задаем значение по умолчанию  
        if (product_id === undefined) {
            product_id = 0;
            $('.cb_cart').each(function (i, elem) {
                check_id = $(this).attr('name');
                id = regexp_id.exec(check_id);
                key = id[0];
                if ($(elem).prop('checked')) { // проверяем его значение checkbox
                    count_checked++;
                    // изменение класса для отмеченных tr
                    $('#item_' + id[0]).addClass('success');
// здесь определяем check в значении 1 если он отмечен флажком
                    $.ajax({
                        url: 'index.php?route=checkout/cart/edit_check',
                        type: 'post', // скрытый запрос
                        data: 'key=' + key + '&check=' + 1,
                        dataType: 'json',
                        success: function (json) {
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                } else {
                    $('#item_' + id[0]).removeClass('success');
                    // здесь определяем значение check 0 если не отмечен флажок                
                    $.ajax({
                        url: 'index.php?route=checkout/cart/edit_check',
                        type: 'post', // скрытый запрос
                        data: 'key=' + key + '&check=' + 0,
                        dataType: 'json',
                        success: function (json) {
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }
                all_check++;
            })
        } else {
            // 1. определяем состояние
            // 2. ajax запрос
            // 3. бежим по всем элементам для определения cb_all

            if ($('input[name="check[' + product_id + ']"]').prop('checked')) {
                // проверяем его значение checkbox
                $.ajax({
                    url: 'index.php?route=checkout/cart/edit_check',
                    type: 'post', // скрытый запрос, получение данных
                    data: 'key=' + product_id + '&check=' + 1,
                    dataType: 'json',
                    success: function (json) {
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            } else {
                $.ajax({
                    url: 'index.php?route=checkout/cart/edit_check',
                    type: 'post', // скрытый запрос
                    data: 'key=' + product_id + '&check=' + 0,
                    dataType: 'json',
                    success: function (json) {
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }

            $('.cb_cart').each(function (i, elem) {
                check_id = $(this).attr('name');
                id = regexp_id.exec(check_id);
                key = id[0];
                if ($(elem).prop('checked')) { // проверяем его значение checkbox
                    count_checked++;
                    // изменение класса для отмеченных tr
                    $('#item_' + id[0]).addClass('success');
                } else {
                    $('#item_' + id[0]).removeClass('success');
                }
                all_check++;
            })

        }


        //активация кнопок
        if (count_checked > 0) {
            //$('.btn-remove-checked').prop('disabled', false);
            $('.btn-remove-checked').removeClass('disabled');
            $('.btn-remove-checked').removeAttr("disabled");
            //$('.btn-checkout').prop('disabled', false);
            $('.btn-checkout').removeClass('disabled');
            $('.btn-checkout').removeAttr("disabled");
        } else {
            //$('.btn-remove-checked').prop('disabled', true);
            $('.btn-remove-checked').addClass('disabled');
            $('.btn-remove-checked').attr('disabled', 'disabled');
            //$('.btn-checkout').prop('disabled', true);
            $('.btn-checkout').addClass('disabled');
            $('.btn-checkout').attr('disabled', 'disabled');
            $('.cb_all').prop('checked', false); // снять флажок с cb_all
        }

        if (count_checked == all_check) {
            $('.cb_all').prop('checked', true); // надеваем флажок на cb_all
        }
        cart.calc_checked();
    },
    'changes': function (elm) {
        var $this = $(elm);
        $('.btn_save').removeClass('disabled');
        $('.btn_save').removeAttr("disabled");
        $('.btn_save').removeClass('btn-default');
        $('.btn_save').addClass('btn-success');
        regexp_id = /(\d+)/;
        check_id = $this.attr('name');
        id = regexp_id.exec(check_id);
        key = id[0];
        //console.log(key);
        $('#btn_update_' + key).removeClass('disabled');
        $('#btn_update_' + key).removeAttr("disabled");
        $('#btn_update_' + key).removeClass('btn-default');
        $('#btn_update_' + key).addClass('btn-success');
    },
    'check_changes': function () {
        count_change = 0;
        all_btn_update = 0;
        change_id = '';
        regexp_id = /(\d+)/;
        $('.btn-update').each(function (i, elem) {
            change_id = $(this).attr('id');
            id = regexp_id.exec(change_id);
            key = id[0];
            if ($(elem).hasClass('disabled')) { // проверяем его значение checkbox
                count_change++;
            }
            all_btn_update++;
        })

        if (count_change == all_btn_update) {
            $('.btn_save').addClass('disabled');
            $('.btn_save').attr('disabled', 'disabled');
            $('.btn_save').addClass('btn-default');
            $('.btn_save').removeClass('btn-success');
        }
    },
    // функция подсчетв суммы выбранных позиций
    'calc_checked': function () {
        var total = 0;
        var check_id = '';
        var regexp_id = /(\d+)/;
        var regexp_price = /(\d+\.*\d*)/;
        var t = '';
        var t2 = '';
        var id = '';
        var counter = 1;
        var price = 0;
        var btn_check = 0;

        $('.cb_cart').each(function () {
            check_id = $(this).prop('name');
            //console.log(check_id);
            id = regexp_id.exec(check_id);
            if ($(this).is(":checked")) {
                // получить количество позиций по данному товару
                counter = $('input[name="quantity[' + id[0] + ']"]').val(); //получили количество отмеченного товара
                t = $("#price_" + id[0]).text();
                t2 = regexp_price.exec(t);//получили его стоимость за 1 шт массив
                price = parseFloat(t2[0]); //цена из массива               
                total = parseFloat(total) + counter * parseFloat(price);
                $('#item_' + id[0]).addClass('item_check');
                btn_check = 1;
            } else {
                $('#item_' + id[0]).removeClass('item_check');
            }

            //запретить оформлять заказ, если не выбраны позиции
            if (btn_check == 1) {
                $('.cartCheckoutButton').removeClass('btn_disabled');
                $('.cartCheckoutButton').removeAttr("disabled");
            } else {
                $('.cartCheckoutButton').addClass('btn_disabled');
                $('.cartCheckoutButton').attr('disabled', 'disabled');
            }
        });
        //$("#pay_price").text(found1[0]);
        $("#total_check_items_top").text(modRound(total, 3) + ' р.');
        $("#total_check_items_bottom").text(modRound(total, 3) + 'р.');
    }
};
var voucher = {
    'add': function () {

    },
    'remove': function (key) {
        $.ajax({
            url: 'index.php?route=checkout/cart/remove',
            type: 'post',
            data: 'key=' + key,
            dataType: 'json',
            complete: function () {
                $('#cart > a').button('reset');
            },
            success: function (json) {
                // need to set timeout otherwise it wont update the total
                setTimeout(function () {
                    $('#cart > a').html('<i class="fa fa-shopping-cart n-icon"></i>' + '<span id="cart-total">' + json.total + '</span>');
                    getQuantity();
                }, 100);
                if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
                    location = 'index.php?route=checkout/cart';
                } else {
                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
};
var wishlist = {
    'add': function (product_id) {
        $.ajax({
            url: 'index.php?route=account/wishlist/add',
            type: 'post',
            data: 'product_id=' + product_id,
            dataType: 'json',
            success: function (json) {
                $('.alert').remove();
                if (json.success) {
                    $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                if (json.info) {
                    $('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json.info + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                $('#wishlist-total span').html(json.total);
                //$('#wishlist-total').attr('title', json.total);

                $('html, body').animate({scrollTop: 0}, 'slow');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'remove': function () {

    }
};
var compare = {
    'add': function (product_id) {
        $.ajax({
            url: 'index.php?route=product/compare/add',
            type: 'post',
            data: 'product_id=' + product_id,
            dataType: 'json',
            success: function (json) {
                $('.alert').remove();
                if (json.success) {
                    $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json.success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    $('#compare-total').html(json.total);
                    $('html, body').animate({scrollTop: 0}, 'slow');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'remove': function () {

    }
};
var transaction = {
    'dateFilter': function (page_limit, active_tab) {
        dateArray = $('#daterange').val().split(' - ');
        url = 'index.php?route=account/transaction&page-limit=' + page_limit;
        url += '&tab=' + active_tab;
        url += '&date-start=' + dateArray[0];
        url += '&date-end=' + dateArray[1];
        window.location.replace(url);
    },
    'export': function (dateStart, dateEnd) {
        document.location.assign('index.php?route=account/transaction/export&date-start=' + dateStart + '&date-end=' + dateEnd);
    }
}
// agree to terms
$(document).delegate('.agree', 'click', function (e) {
    e.preventDefault();
    $('#modal-agree').remove();
    var element = this;
    $.ajax({
        url: $(element).attr('href'),
        type: 'get',
        dataType: 'html',
        success: function (data) {
            html = '<div id="modal-agree" class="modal">';
            html += '  <div class="modal-dialog">';
            html += '    <div class="modal-content">';
            html += '      <div class="modal-header">';
            html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
            html += '      </div>';
            html += '      <div class="modal-body">' + data + '</div>';
            html += '    </div';
            html += '  </div>';
            html += '</div>';
            $('body').append(html);
            $('#modal-agree').modal('show');
        }
    });
});
// autocomplete
(function ($) {
    $.fn.autocomplete = function (option) {
        return this.each(function () {
            this.timer = null;
            this.items = [];
            $.extend(this, option);
            $(this).attr('autocomplete', 'off');
            // focus
            $(this).on('focus', function () {
                this.request();
            });
            // blur
            $(this).on('blur', function () {
                setTimeout(function (object) {
                    object.hide();
                }, 200, this);
            });
            // keydown
            $(this).on('keydown', function (event) {
                switch (event.keyCode) {
                    case 27: // escape
                        this.hide();
                        break;
                    default:
                        this.request();
                        break;
                }
            });
            // click
            this.click = function (event) {
                event.preventDefault();
                value = $(event.target).parent().attr('data-value');
                if (value && this.items[value]) {
                    this.select(this.items[value]);
                }
            };
            // show
            this.show = function () {
                var pos = $(this).position();
                $(this).siblings('ul.dropdown-menu').css({
                    top: pos.top + $(this).outerHeight(),
                    left: pos.left
                });
                $(this).siblings('ul.dropdown-menu').show();
            };
            // hide
            this.hide = function () {
                $(this).siblings('ul.dropdown-menu').hide();
            };
            // request
            this.request = function () {
                clearTimeout(this.timer);
                this.timer = setTimeout(function (object) {
                    object.source($(object).val(), $.proxy(object.response, object));
                }, 200, this);
            };
            // response
            this.response = function (json) {
                html = '';
                if (json.length) {
                    for (i = 0; i < json.length; i++) {
                        this.items[json[i].value] = json[i];
                    }

                    for (i = 0; i < json.length; i++) {
                        if (!json[i].category) {
                            html += '<li data-value="' + json[i].value + '"><a href="#">' + json[i].label + '</a></li>';
                        }
                    }

                    // get all the ones with a categories
                    var category = [];
                    for (i = 0; i < json.length; i++) {
                        if (json[i].category) {
                            if (!category[json[i].category]) {
                                category[json[i].category] = [];
                                category[json[i].category].name = json[i].category;
                                category[json[i].category].item = [];
                            }

                            category[json[i].category].item.push(json[i]);
                        }
                    }

                    for (var i in category) {
                        html += '<li class="dropdown-header">' + category[i].name + '</li>';
                        for (j = 0; j < category[i].item.length; j++) {
                            html += '<li data-value="' + category[i].item[j].value + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i].item[j].label + '</a></li>';
                        }
                    }
                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $(this).siblings('ul.dropdown-menu').html(html);
            };
            $(this).after('<ul class="dropdown-menu"></ul>');
            $(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));
        });
    };
})(window.jQuery);

