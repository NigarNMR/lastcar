<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
    <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <base href="<?php echo $base; ?>" />
        <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>" />
        <?php } ?>
        <?php if ($keywords) { ?>
        <meta name="keywords" content= "<?php echo $keywords; ?>" />
        <?php } ?>
        <link href="catalog/view/theme/basecart/css/bootstrap.min.css" rel="stylesheet">
        <link href="catalog/view/theme/basecart/css/font-awesome.min.css" rel="stylesheet">

        <!-- START basecart module -->

        <?php
        if ($theme == "basecart_module_themedefault") {
        include "catalog/view/theme/basecart/css/bootswatch/default.tpl";
        } elseif ($theme == "basecart_module_themecerulean") {
        include "catalog/view/theme/basecart/css/bootswatch/cerulean.tpl";
        } elseif ($theme == "basecart_module_themecosmo") {
        include "catalog/view/theme/basecart/css/bootswatch/cosmo.tpl";
        } elseif ($theme == "basecart_module_themecyborg") {
        include "catalog/view/theme/basecart/css/bootswatch/cyborg.tpl";
        } elseif ($theme == "basecart_module_themedarkly") {
        include "catalog/view/theme/basecart/css/bootswatch/darkly.tpl";
        } elseif ($theme == "basecart_module_themeflatly") {
        include "catalog/view/theme/basecart/css/bootswatch/flatly.tpl";
        } elseif ($theme == "basecart_module_themejournal") {
        include "catalog/view/theme/basecart/css/bootswatch/journal.tpl";
        } elseif ($theme == "basecart_module_themelumen") {
        include "catalog/view/theme/basecart/css/bootswatch/lumen.tpl";
        } elseif ($theme == "basecart_module_themepaper") {
        include "catalog/view/theme/basecart/css/bootswatch/paper.tpl";
        } elseif ($theme == "basecart_module_themereadable") {
        include "catalog/view/theme/basecart/css/bootswatch/readable.tpl";
        } elseif ($theme == "basecart_module_themesandstone") {
        include "catalog/view/theme/basecart/css/bootswatch/sandstone.tpl";
        } elseif ($theme == "basecart_module_themesimplex") {
        include "catalog/view/theme/basecart/css/bootswatch/simplex.tpl";
        } elseif ($theme == "basecart_module_themeslate") {
        include "catalog/view/theme/basecart/css/bootswatch/slate.tpl";
        } elseif ($theme == "basecart_module_themespacelab") {
        include "catalog/view/theme/basecart/css/bootswatch/spacelab.tpl";
        } elseif ($theme == "basecart_module_themesuperhero") {
        include "catalog/view/theme/basecart/css/bootswatch/superhero.tpl";
        } elseif ($theme == "basecart_module_themeunited") {
        //include "catalog/view/theme/basecart/css/bootswatch/united.tpl";
        echo '<link href="catalog/view/theme/basecart/css/bootswatch/united.css" rel="stylesheet">';
        } elseif ($theme == "basecart_module_themeyeti") {
        include "catalog/view/theme/basecart/css/bootswatch/yeti.tpl";
        }
        ?>

        <!-- END basecart module -->

        <link href="catalog/view/theme/basecart/css/main.css" rel="stylesheet">
        <link href="catalog/view/theme/basecart/css/tablesorter/theme.ice.css" rel="stylesheet">
        <link href="catalog/view/theme/basecart/css/bootstrap-spinner.css" rel="stylesheet">
        <link href="catalog/view/theme/basecart/css/daterangepicker.css" rel="stylesheet">

        <link href="catalog/view/theme/basecart/css/custom.css" rel="stylesheet">

        <script src="catalog/view/theme/basecart/js/jquery.min.js"></script>
        <script src="catalog/view/theme/basecart/js/bootstrap.min.js"></script>
        <script src="catalog/view/theme/basecart/js/jquery.searcher.js"></script>
        <script src="catalog/view/theme/basecart/js/moment.js"></script>
        <script src="catalog/view/theme/basecart/js/daterangepicker.js"></script>
        <script type="text/javascript" src="catalog/view/theme/basecart/js/jquery.tablesorter.js"></script> 
        <script type="text/javascript" src="catalog/view/theme/basecart/js/jquery.spinner.min.js"></script> 
        <script src="catalog/view/theme/basecart/js/common.js"></script>
        <!--<script src="catalog/view/theme/basecart/js/custom.js"></script>-->
        <?php foreach ($links as $link) { ?>
        <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>">
        <?php } ?>
        <?php foreach ($styles as $style) { ?>
        <link href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>">
        <?php } ?>
        <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>"></script>
        <?php } ?>
        <?php foreach ($analytics as $analytic) { ?>
        <?php echo $analytic; ?>
        <?php } ?>
    </head>
    <body class="<?php echo $class; ?>">
        <header>
            <?php if ($nav == "basecart_module_navinverse") { ?>
            <?php $class = 'navbar-inverse'; ?>
            <?php } else { ?>
            <?php $class = 'navbar-default'; ?>
            <?php } ?>
            <nav class="navbar <?php echo $class; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="navbar-header">
                                <button id="sr-but" type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>

                                <?php if ($logo) { ?>
                                <a class="navbar-brand" href="<?php echo $home; ?>">
                                    <img class="img-responsive" src="image/<?php echo $store_info['config_image']; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>"/>
                                </a> 
                                <?php } else { ?>
                                <a class="navbar-brand" href="<?php echo $home; ?>"><?php echo $name; ?></a>
                                <?php } ?>
                            </div>
                        </div>

                        <!--<div class="col-md-1">
                           
            </div>-->
                        <div class="col-md-4">
                            <span class="hidden-xs hidden-sm hidden-md headerlogo">  <h2 class="avtobear"><?php echo  $store_info['config_name']; ?></h2>
                                <small class="small-avtobear text-c headerlogo"><?php echo  $store_info['config_slogan']; ?></small></span>
                        </div>
                        <div class="col-md-7">
                            <ul class="nav navbar-nav navbar-right list-inline hover">                            
                                <li  id="hiddenT"><a href="tel:<?php echo $store_info['config_telephone'];?> "><span class="glyphicon glyphicon-phone"></span><?php echo $store_info['config_telephone'];?></a></li>
                                <li id="hiddenM"><a class="btn btn-link" role="button" data-toggle="collapse" href="#hiddenMenu" aria-expanded="false" aria-controls="hiddenMenu">
                                        <i class="fa fa-ellipsis-h n-icon hover2"></i></a></li>
                                <!--значок корзины в навбаре-->
                                <?php echo $cart; ?>
                                <?php if ($logged) { ?>
                                <li><a href="<?php echo $order; ?>"> <i class="glyphicon glyphicon-th-list x-icon hover2"></i></a></li>                                
                                <!-- /****** Message System Starts *****/ -->
                                <li>
                                    <a href="<?php echo $message_system; ?>">                                        
                                            <?php if($alert_messages) { ?> 
                                            <span class="label label-default pull-right alert_messages" id="alert_messages">
                                            <b><?php echo $alert_messages ?></b>
                                            </span>
                                            <?php } ?>
                                        <i class="fa fa-envelope n-icon hover5"></i>
                                    </a>
                                </li>
                                <li><?php echo $text_logged; ?></li><!--имя клиента -->
                                <?php } else { ?>
                                <?php } ?>
                                <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user n-icon hover4"></i><span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <?php if ($logged) { ?>
                                        <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>                                       
                                        <!-- /****** Message System Starts *****/ -->
                                        <li><a href="<?php echo $message_system; ?>"><?php echo $text_message_system; ?>
                                                <span class="alert_messages">
                                                    <?php if($alert_messages) { ?> 
                                                    <b><?php echo $alert_messages ?></b>
                                                    <?php } ?>
                                                </span>
                                            </a>
                                        </li>
                                        <!-- /****** Message System Ends *****/ -->                                       
                                        <!--выпадающий список корзины -->
                                        <!-- <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                                         <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
                                         <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>-->
                                        <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
                                        <?php } else { ?>
                                        <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
                                        <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            </ul>   
                            <?php //} ?>                            
                            <div id="search-top" class="form-wrapper cf search-box input-group"></div>
                        </div>
                    </div>
                    <div class="row" id="rowMenu">
                        <div class="col-md-12">
                            <div class="collapse navbar-collapse navbar-ex1-collapse" id="no-collapse">
                                <ul class="nav navbar-nav navbar-right">                                 
                                    <li><a href="autoparts">Каталог запчастей</a></li>
                                    <li><a href="index.php?route=information/information&information_id=9">Автосервис</a></a></li>
                                    <li><a href="index.php?route=information/information&information_id=4">О Нас</a></li>
                                    <li><a href="index.php?route=information/information&information_id=6">Доставка</a></li>
                                    <li><a href="index.php?route=information/information&information_id=7">Как заказать</a></li>
                                    <li><a href="index.php?route=information/information&information_id=8">Контакты</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="collapse" id="hiddenMenu">
                            <div class="col-md-4">
                                <?php echo $currency; ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $language; ?>
                            </div>
                            <!--<div class="col-sm-4">
                                <?php //echo $search; ?>
                            </div>-->
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-wrapper cf search-box input-group" id="search-bottom">
                                <input class="form-control" type="text" id="artnum" name="search" value="<?php if(isset($_REQUEST['article'])) print $_REQUEST['article'];?>" placeholder="НОМЕР ДЕТАЛИ (АРТИКУЛ)">
                                <span class="input-group-btn">
                                    <button class="btn btn-success" type="submit" onclick="tdm_search_submit()">НАЙТИ</button>
                                </span>
                                <!--<a href="<?php echo $shopping_cart; ?>">Корзина <span id='cart-total-search'>(<?php //echo $count_products;?>)</span></a>-->
                                <!-- <a href="/autoparts">Чат</a>  -->
                                <!--a href="<?php echo $shopping_cart; ?>"><?php echo $text_shopping_cart; ?></a>-->

                            </div>
                            <script type="text/javascript">
                                function tdm_search_submit() {
                                    var str = '';
                                    str = $('#artnum').val();
                                    str = str.replace(/[^a-zA-Zа-яА-ЯёЁ0-9.-]+/g, '');
                                    url = 'index.php?route=search/parts&article=' + str;
                                    location = url;
                                }
                                $('#artnum').keypress(function (e) {
                                    var keyId = e.keyCode || e.which || 0;
                                    if (keyId == 13) {
                                        tdm_search_submit();
                                        return false;
                                    }
                                });
                            </script>
                        </div>
                    </div>
            </nav>
            <div id="sticky-anchor"></div>
        </header>
