<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-2">
                <ul class="text-left">
                    <li class="list"><p class="text-uppercase text-muted powered"> <?php echo $powered; ?></p></li>
                </ul>
            </div>
            <div class="col-sm-2">
                <ul class="text-left">
                    <li class="list"><a href="autoparts">Каталог</a></a></li>
                    <li class="list"><a href="index.php?route=information/information&information_id=9">Услуги</a></a></li>
                    <li class="list"><a href="index.php?route=information/information&information_id=7">Как заказать</a></a></li>
                </ul>
            </div>
            <div class="col-sm-6">
                <ul class="text-left">
                    <li class="list"><a href="index.php?route=information/information&information_id=6">Доставка и оплата</a></a></li>
                    <li class="list"><a href="index.php?route=information/information&information_id=8">Контакты</a></a></li>
                </ul>
            </div>
            <div class="col-sm-2">
                <ul class="text-right">
                    <li class="list"><a href="#"><img src="image/pay/MasterCard.png"></a></li>
                    <!--<li class="list"><a href="tel: +7 (3822)977-430 "><p class="text-uppercase text-muted "><span class="glyphicon glyphicon-phone"></span>+7 (3822)977-430 </p></a></li>
                    <li class="list"><a href="tel: +7 (952)180-4661 "><p class="text-uppercase text-muted "><span class="glyphicon glyphicon-phone"></span>+7 (952)180-4661 </p></a></li>-->
					<li class="list"><a href="tel: +7 (3822)977-430 "><p class="text-uppercase text-muted "><span class="glyphicon glyphicon-phone"></span><?php echo $store_info['config_telephone'];?> </p></a></li>
                    <li class="list"><p class="text-uppercase text-muted "><span></span><?php echo $store_info['config_address'];?> </p></li>
			   </ul>
            </div>
          
            <!--<div class="row">
                <?php if ($informations) { ?>
                <div class="col-sm-3">
                  <h4><?php echo $text_information; ?></h4>
                  <ul class="list-group">
                    <?php foreach ($informations as $information) { ?>
                    <li class="list-group-item"><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                    <?php } ?>
                  </ul>
                </div>
                <?php } ?>
                <div class="col-sm-3">
                  <h4><?php echo $text_service; ?></h4>
                  <ul class="list-group">
                    <li class="list-group-item"><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
                  </ul>
                </div>
                <div class="col-sm-3">
                  <h4><?php echo $text_extra; ?></h4>
                  <ul class="list-group">
                    <li class="list-group-item"><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
                  </ul>
                </div>
                <div class="col-sm-3">
                  <h4><?php echo $text_account; ?></h4>
                  <ul class="list-group">
                    <li class="list-group-item"><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
                    <li class="list-group-item"><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
                  </ul>
                </div>
              </div>-->
        </div>
    </div>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->

</body></html>
