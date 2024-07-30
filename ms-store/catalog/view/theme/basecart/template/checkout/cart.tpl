<?php echo $header; ?>
<div class="container">
    <?php if ($attention) { ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php }
        elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php }
        else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
            <h1><?php echo $heading_title; ?>
                <?php if ($weight) { ?>
                &nbsp;(<?php echo $weight; ?>)
                <?php } ?>
            </h1>
            <div class="row">
                <div class="col-md-12">
                <form class="form-inline" role="form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                    <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group pull-left" role="group">
                            <button type="button" role="group"  title="<?php echo $button_remove; ?>" class="btn btn-default" onclick="cart.remove_all();"><?php echo $button_clean; ?></button>
                            <button type="button" role="group"  title="<?php echo $button_remove; ?>" class="btn btn-default btn-remove-checked disabled" onclick="cart.remove_checked();" disabled><?php echo $button_delete_selected; ?></button>
                        </div>
                        <div class="form-group">
                            <input type="text" id="tablesearchinput" class="form-control" name="search" placeholder="Поиск по корзине" style="width: 300px;"> 
                        </div>
                        <div class="btn-group pull-right" role="group">
                            <button type="button"  title="<?php echo $button_save; ?>" class="btn btn-default btn_save disabled" onclick="cart.update_all();" disabled><?php echo $button_save; ?></button>
                            <!--<button type="button" class="btn btn-primary btn-checkout cartCheckoutButton" disabled><a href="<?php echo $checkout; ?>"><?php echo $button_checkout; ?></a></button>-->
                            <button type="button" class="btn btn-primary btn-checkout cartCheckoutButton disabled" onclick="cart.checkout()" disabled><?php echo $button_checkout; ?></button>
                        </div>
                    </div>
                    <br/>
                </form>
                </div>
            </div>
        <table id="tabledata" class="table table-bordered table-striped tablesorter">
            <thead>
            <tr>
                <td class="check"><input type="checkbox" name="check_all" value="1" class="cb_all" onclick="cart.check_all();" /></td>
                <td class="text-left" width="100px"><?php echo $column_model; ?></td>
                <td class="text-left"><?php echo $column_article; ?></td>
                <td class="text-left"><?php echo $column_name; ?></td>
                <td class="text-left"><?php echo $column_date; ?></td>
                <td class="text-left" width="100px"><?php echo $column_day; ?></td>
                <td class="text-left"><?php echo $column_quantity; ?></td>
                <td class="text-left price"><?php echo $column_price; ?></td>
                <td class="text-left total"><?php echo $column_total; ?></td>
                <td class="text-left"><?php echo $column_comment; ?></td>
                <td class="text-left"><?php echo $column_remove; ?></td>
            </tr>
            <td class="text-right" colspan='12' class="sum_check_items_total">
                <b><?php echo $sum_check_items_text ?></b>
                <div id="total_check_items_top" class="total_check_items"><?php echo $sum_check_items_value ?></div>
            </td>
            </thead>
            <tbody>
                <?php foreach ($products as $product) { ?>
                <?php if ($product['option']) { ?>
                <?php foreach ($product['option'] as $option) { ?>
                <?php $options[$option['name']] = $option; ?>
                <?php } ?>
                <?php } ?>

                <tr id="item_<?php echo $product['cart_id'] ?>" class="cart_<?php echo $product['cart_id']; ?>">
                    <td class="text-center">  
                        <input type="checkbox" name="check[<?php echo $product['cart_id'] ?>]" value="<?php echo $product['cart_id'] ?>" class="cb_cart" onclick="cart.active_button( <?php echo $product['cart_id'] ?> )" <?php if ($product['checkbox']) echo 'checked'; ?>>
                    </td>
                    <td class="text-center"><?php echo $product['model']; ?></td> <!-- фирма -->
                    <td class="text-center"><?php if (isset($product['article'])) {
                        echo $product['article'];
                        } ?></td> <!-- Код детали -->

                    <!-- характеристики -->
                    <td class="text-left">
                        <a href="<?php if (isset($product['tecdoc'])) {
                           echo $product['product_url'];
                           }
                           else {
                           echo $product['href'];
                           } ?>"><?php echo $product['name']; ?></a><br>
                       
                    </td>
                    <td class="text-center"><small><?php echo $product['date_added'] ?></small></td>
                    <td class="text-center"><?php if (isset($product['day'])) {
                        echo $product['day'];
                        } ?></td>
                    <td class="text-center">
                        <div class="input-group btn-block" style="max-width: 200px;">
                            <div class="input-group spinner" data-trigger="spinner">
                                <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" class="form-control text-center f-quantity" data-rule="quantity">
                                <div class="input-group-addon">
                                    <a href="javascript:;" name="spin-up[<?php echo $product['cart_id']; ?>]" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>
                                    <a href="javascript:;" name="spin-down[<?php echo $product['cart_id']; ?>]" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>
                                </div>
                            </div>
                            <span class="input-group-btn">
                                <button id="btn_update_<?php echo $product['cart_id']; ?>" type="button" ata-toggle="tooltip" class="btn btn-default disabled btn-update" onclick="cart.update_my('<?php echo $product['cart_id']; ?>');"><i class="glyphicon glyphicon-repeat"></i></button>
                            </span>
                        </div>
                    </td>
                    <td id="price_<?php echo $product['cart_id'] ?>" class="text-center price"><?php echo $product['price']; ?></td>
                    <td id="total_<?php echo $product['cart_id'] ?>" class="text-center "><?php echo $product['total']; ?></td>
                    <td class="text-center"><input type="text" class="form-control text-center f-comment" name="comment[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['comment']; ?>" size="10"  maxlength="200"/></td>
                    <td class="text-center">
                        <span class="group-btn">
                            <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><i class="fa fa-times-circle"></i></button>               
                        </span>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach ($vouchers as $voucher) { ?>
                <tr>
                    <!--<td class="text-center"><?php echo $voucher['description']; ?></td>-->
                    <td class="text-center">
                        <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="voucher.remove('<?php echo $voucher['key']; ?>');"><i class="fa fa-times-circle"></i></button>
                    </td>
                    <!--<td class="text-right"><?php echo $voucher['amount']; ?></td>
                     <td class="text-right"><?php echo $voucher['amount']; ?></td>-->
                </tr>
                <?php } ?>
            </tbody>
            <tr>
                <td class="text-right" colspan='12' class="sum_check_items_total">
                    <b><?php echo $sum_check_items_text ?></b>
                    <div id="total_check_items_bottom" class="total_check_items"><?php echo $sum_check_items_value ?></div>
                </td>
            </tr>
        </table>
    
            </form>
                
       
<?php if ($modules) { ?>
<h2><?php echo $text_next; ?></h2>
<p><?php echo $text_next_choice; ?></p>
<div class="panel-group" id="accordion">
    <?php foreach ($modules as $module) { ?>
    <?php echo $module; ?>
    <?php } ?>
</div>
<?php } ?>
<!--<div class="container">
  <div class="btn-group " role="group">
    <div class="btn-group pull-left">
      <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-default" onclick="cart.remove_all();"><?php echo $button_clean; ?></button>
    </div>
    <div class="btn-group pull-left">
      <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-default btn-remove-checked" onclick="cart.remove_checked();"><?php echo $button_delete_selected; ?></button> 
    </div>
    <div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
  </div>
  <div class="btn-group pull-right" role="group">
    <button type="button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-default" onclick="cart.update_all();"><?php echo $button_save; ?></button>
    <button type="button" class="btn btn-primary btn-checkout cartCheckoutButton" disabled><a href="<?php echo $checkout; ?>"><?php echo $button_checkout; ?></a></button>
  </div>
</div>-->

<!--<div class="container">-->
<div class="row">
    <div class="col-md-12">
    <div class="pull-right">
        <table class="table table-bordered">
            <?php foreach ($totals as $total) { ?>
            <tr>
                <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
                <td class="text-right" id="<?php echo $total['code']; ?>"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    <form class="form-inline" role="form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group pull-left" role="group">
                <button type="button" role="group"  title="<?php echo $button_remove; ?>" class="btn btn-default" onclick="cart.remove_all();"><?php echo $button_clean; ?></button>
                <button type="button" role="group"  title="<?php echo $button_remove; ?>" class="btn btn-default btn-remove-checked disabled" onclick="cart.remove_checked();" disabled><?php echo $button_delete_selected; ?></button>
            </div>

            <div class="btn-group pull-right" role="group">
                <button type="button"  title="<?php echo $button_save; ?>" class="btn btn-default btn_save disabled" onclick="cart.update_all();" disabled><?php echo $button_save; ?></button>
                <!--<button type="button" class="btn btn-primary btn-checkout cartCheckoutButton" disabled><a href="<?php echo $checkout; ?>"><?php echo $button_checkout; ?></a></button>-->
                <button type="button" class="btn btn-primary btn-checkout cartCheckoutButton disabled" onclick="cart.checkout()" disabled><?php echo $button_checkout; ?></button>
            </div>
        </div>
        <br/>
    </form>
</div>
</div>


<?php echo $content_bottom; ?>
<?php echo $column_right; ?>
</div>
</div>
</div>
<?php echo $footer; ?> 
