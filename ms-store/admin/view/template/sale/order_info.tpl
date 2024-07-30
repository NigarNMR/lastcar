<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $invoice; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></a> <a href="<?php echo $shipping; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></a> <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $text_order_detail; ?></h3>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_store; ?>" class="btn btn-info btn-xs"><i class="fa fa-shopping-cart fa-fw"></i></button></td>
                <td><a href="<?php echo $store_url; ?>" target="_blank"><?php echo $store_name; ?></a></td>
              </tr>
              <tr>
                <td><button data-toggle="tooltip" title="<?php echo $text_date_added; ?>" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
                <td><?php echo $date_added; ?></td>
              </tr>
              <tr>
                <td><button data-toggle="tooltip" title="<?php echo $text_payment_method; ?>" class="btn btn-info btn-xs"><i class="fa fa-credit-card fa-fw"></i></button></td>
                <td><?php echo $payment_method; ?></td>
              </tr>
              <?php if ($shipping_method) { ?>
                <tr>
                  <td><button data-toggle="tooltip" title="<?php echo $text_shipping_method; ?>" class="btn btn-info btn-xs"><i class="fa fa-truck fa-fw"></i></button></td>
                  <td><?php echo $shipping_method; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user"></i> <?php echo $text_customer_detail; ?></h3>
          </div>
          <table class="table">
            <tr>
              <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_customer; ?>" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
              <td><?php if ($customer) { ?>
                  <a href="<?php echo $customer; ?>" target="_blank"><?php echo $firstname; ?> <?php echo $lastname; ?></a>
                  <?php
                }
                else {
                  ?>
                  <?php echo $firstname; ?> <?php echo $lastname; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_customer_group; ?>" class="btn btn-info btn-xs"><i class="fa fa-group fa-fw"></i></button></td>
              <td><?php echo $customer_group; ?></td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_email; ?>" class="btn btn-info btn-xs"><i class="fa fa-envelope-o fa-fw"></i></button></td>
              <td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
            </tr>
            <tr>
              <td><button data-toggle="tooltip" title="<?php echo $text_telephone; ?>" class="btn btn-info btn-xs"><i class="fa fa-phone fa-fw"></i></button></td>
              <td><?php echo $telephone; ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-cog"></i> <?php echo $text_option; ?></h3>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td><?php echo $text_invoice; ?></td>
                <td id="invoice" class="text-right"><?php echo $invoice_no; ?></td>
                <td style="width: 1%;" class="text-center"><?php if (!$invoice_no) { ?>
                    <button id="button-invoice" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_generate; ?>" class="btn btn-success btn-xs"><i class="fa fa-cog"></i></button>
                    <?php
                  }
                  else {
                    ?>
                    <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-refresh"></i></button>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $text_reward; ?></td>
                <td class="text-right"><?php echo $reward; ?></td>
                <td class="text-center"><?php if ($customer && $reward) { ?>
                    <?php if (!$reward_total) { ?>
                      <button id="button-reward-add" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_reward_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                      <?php
                    }
                    else {
                      ?>
                      <button id="button-reward-remove" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_reward_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>
                    <?php } ?>
                    <?php
                  }
                  else {
                    ?>
                    <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $text_affiliate; ?>
                  <?php if ($affiliate) { ?>
                    (<a href="<?php echo $affiliate; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a>)
                  <?php } ?></td>
                <td class="text-right"><?php echo $commission; ?></td>
                <td class="text-center"><?php if ($affiliate) { ?>
                    <?php if (!$commission_total) { ?>
                      <button id="button-commission-add" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_commission_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                      <?php
                    }
                    else {
                      ?>
                      <button id="button-commission-remove" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_commission_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>
                    <?php } ?>
                    <?php
                  }
                  else {
                    ?>
                    <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                  <?php } ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_order; ?></h3>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <td style="width: 50%;" class="text-left"><?php echo $text_payment_address; ?></td>
              <?php if ($shipping_method) { ?>
                <td style="width: 50%;" class="text-left"><?php echo $text_shipping_address; ?>
                <?php } ?></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-left"><?php echo $payment_address; ?></td>
              <?php if ($shipping_method) { ?>
                <td class="text-left"><?php echo $shipping_address; ?></td>
              <?php } ?>
            </tr>
          </tbody>
        </table>

        <table class="table table-bordered">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_supplier; ?></td>
              <td class="text-left"><?php echo $column_firm; ?></td>
              <td class="text-left"><?php echo $column_article; ?></td>
              <td class="text-left"><?php echo $column_description; ?></td>              
              <td class="text-left"><?php echo $column_day; ?></td>              
              <td class="text-right"><?php echo $column_quantity; ?></td>
              <th class="text-right"><b><?php echo $column_acquiring_price; ?></b></th>
              <td class="text-right"><?php echo $column_price; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
              <td class="text-right"><?php echo $column_order_product_status;  ?></td>
              <td class="text-center"></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>

              <?php if ($product['option']) { ?>
                <?php foreach ($product['option'] as $option) { ?>
                  <?php $options[$option['name']] = $option; ?>
                <?php } ?>
              <?php } ?>
              <tr id="order_product_<?php echo $product['order_product_id']; ?>">
                <td><?php echo $options['Поставщик']['value']; //Пиздец какой костыль!!! Исправить эту хрень при первой возможности   ?></td>
                <td><?php echo $product['model']; ?></td>
                <td><?php echo $options['Артикул']['value']; //Пиздец какой костыль!!! Исправить эту хрень при первой возможности  ?></td>
                <td><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br>
                  <a data-toggle="collapse" href="#collapse_<?php echo $product['order_product_id'] ?>"><?php echo $text_more; ?></a>
                  <div id="collapse_<?php echo $product['order_product_id'] ?>" class="collapse">

                    <?php if ($product['option']) { ?>
                      <?php foreach ($product['option'] as $option) { ?>
                        <br />
                        <!--сторока со всеми опциями --> <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </td>
                <!-- таблица списказа позиций по заказу -->
                <td>
                  <!--<?php echo $options['Срок поставки (дней)']['value']; //Пиздец какой костыль!!! Исправить эту хрень при первой возможности   ?>-->
                    <?php 
                      if (intval($product['status_id']) === intval($product_received_status_id)) {
                      ?>
                        <div class="input-group btn-block" style='max-width: 100px;'>
                            <div class="input-group">
                                <input type="text" name="delivery_time[<?php echo $product['order_product_id']; ?>]" value="<?php echo $options['Срок поставки (дней)']['value']; ?>" class="form-control text-center f-quantity" data-rule="quantity">
                            </div>
                            <span class="input-group-btn">
                                <button type="button" data-toggle="tooltip" class="btn btn-primary btn-update" onclick="order.update_product_delivery_time('<?php echo $product['order_product_id']; ?>');"><i class="fa fa-refresh"></i></button>
                            </span>
                        </div>
                      <?php
                      } else {
                          echo $options['Срок поставки (дней)']['value'];
                      }
                    ?>
                </td>
                <td id="data-product-quantity-<?php echo $product['order_product_id']; ?>" class="text-right">
                    <?php 
                      if (intval($product['status_id']) === intval($product_received_status_id)) {
                      ?>
                        <div class="input-group btn-block" style='max-width: 100px;'>
                            <div class="input-group">
                                <input type="text" name="quantity[<?php echo $product['order_product_id']; ?>]" value="<?php echo $product['quantity']; ?>" class="form-control text-center f-quantity" data-rule="quantity">
                            </div>
                            <span class="input-group-btn">
                                <button type="button" data-toggle="tooltip" class="btn btn-primary btn-update" onclick="order.update_product_quantity('<?php echo $product['order_product_id']; ?>');"><i class="fa fa-refresh"></i></button>
                            </span>
                        </div>
                      <?php
                      } else {
                          echo $product['quantity']; 
                      }
                    ?>
                </td>
                <td id="data-product-acquiring-price-<?php echo $product['order_product_id']; ?>" class="text-right">
                    <?php 
                      if (intval($product['status_id']) === intval($product_received_status_id)) {
                      ?>
                        <div class="input-group btn-block" style='max-width: 150px;'>
                            <div class="input-group">
                                <input type="text" name="acquiring_price[<?php echo $product['order_product_id']; ?>]" value="<?php echo $product['acquiring_price']; ?>" class="form-control text-center f-quantity" data-rule="quantity">
                            </div>
                            <span class="input-group-btn">
                                <button type="button" data-toggle="tooltip" class="btn btn-primary btn-update" onclick="order.update_product_acquiring_price('<?php echo $product['order_product_id']; ?>');"><i class="fa fa-refresh"></i></button>
                            </span>
                        </div>
                      <?php
                      } else {
                          echo $product['acquiring_price'];
                      }
                    ?>
                </td>
                <td id="data-product-price-<?php echo $product['order_product_id']; ?>" class="text-right">
                    <?php 
                      if (intval($product['status_id']) === intval($product_received_status_id)) {
                      ?>
                        <div class="input-group btn-block" style='max-width: 150px;'>
                            <div class="input-group">
                                <input type="text" name="price[<?php echo $product['order_product_id']; ?>]" value="<?php echo $product['price']; ?>" class="form-control text-center f-quantity" data-rule="quantity">
                            </div>
                            <span class="input-group-btn">
                                <button type="button" data-toggle="tooltip" class="btn btn-primary btn-update" onclick="order.update_product_price('<?php echo $product['order_product_id']; ?>');"><i class="fa fa-refresh"></i></button>
                            </span>
                        </div>
                      <?php
                      } else {
                          echo $product['price'];
                      }
                    ?>
                </td>
                <td id="data-product-total-<?php echo $product['order_product_id']; ?>" class="text-right"><?php echo $product['total']; ?></td>
                <!-- окрашивание статуса   -->
               <td class="text-right status-color" style="background-color: <?php echo '#' . $product['bg_color']; ?>; color: <?php echo '#' . $product['text_color']; ?>;">
                  <p id="order_product_status_<?php echo $product['order_product_id']; ?>"><?php echo $product['status']; ?></p></td>
                <td class="text-center">
                  <div class="btn-group"> 
                    <?php if (!$product['fragmentation_status'] && intval($product['quantity']) > 1 && intval($product['status_id']) !== intval($product_received_status_id)) { ?>
                    <p data-placement="top" data-toggle="tooltip" title="<?php echo $button_product_partition; ?>"><button class="btn btn-primary btn-xs" data-title="Fragmentate order" onclick="showPartitioningWindow('<?php echo $product['order_product_id']; ?>', '<?php echo $product['quantity']; ?>');" ><span class="fa fa-unlink"></span></button></p>
                    <?php } ?>
                    <?php if (!$product['fragmentation_status']) { ?>
                    <p data-placement="top" data-toggle="tooltip" title="<?php echo $button_edit_status; ?>"><button class="btn btn-primary btn-xs" data-title="Edit status" onclick="changeProductStatus('<?php echo $product['order_product_id']; ?>', '<?php echo $product['status_id']; ?>');" ><span class="fa fa-pencil"></span></button></p>
                    <?php } ?>
                    <p data-placement="top" data-toggle="tooltip" title="<?php echo $button_view_status_history; ?>"><button class="btn btn-primary btn-xs" data-title="View status history" onclick="viewProductStatusHistory('<?php echo $product['order_product_id']; ?>');" ><span class="fa fa-eye"></span></button></p></div>
                </td>
              </tr>
              <tr data-toggle="collapse" data-target=".partition-group-<?php echo $product['order_product_id']; ?>">
                <td colspan="11" class="text-center">
                  <span class="fa fa-chevron-down"></span>
                </td>
              </tr>
              
              
              
              <?php if (!empty($product['branch'])) { ?>
                <tr class="collapse partition-group-collapse partition-group-<?php echo $product['order_product_id']; ?>" id="partition-group-<?php echo $product['order_product_id']; ?>">
                  <td colspan="11">
                    <table class="table table-bordered">
                      <thead>
                        <th class="text-left"><b><?php echo $column_day; ?></b></th>
                        <th class="text-right"><b><?php echo $column_quantity; ?></b></th>
                        <th class="text-right"><b><?php echo $column_acquiring_price; ?></b></th>
                        <th class="text-right"><b><?php echo $column_price; ?><b/></th>
                        <th class="text-right"><b><?php echo $column_total; ?></b></th>
                        <th class="text-right"><b><?php echo $column_order_product_status; ?></b></th>
                        <th class="text-center"></th>
                      </thead>
                      <tbody>
                        <?php foreach ($product['branch_leaves'] as $leaf) { ?>
                        <tr>
                            <td class="test-left"><?php echo $options['Срок поставки (дней)']['value']; ?></td>
                            <td class="text-right"><?php echo $leaf['quantity']; ?></td>
                            <td class="text-right"><?php echo $leaf['acquiring_price']; ?></td>
                            <td class="text-right"><?php echo $leaf['price']; ?></td>
                            <td class="text-right"><?php echo $leaf['total']; ?></td>
                            <td class="text-right" style="background-color: <?php echo '#' . $leaf['bg_color']; ?>; color: <?php echo '#' . $leaf['text_color']; ?>;"><?php echo $leaf['status']; ?></td>
                            <td class="text-center">
                                <?php if (!$leaf['fragmentation_status'] && intval($leaf['quantity']) > 1) { ?>
                                <p data-placement="top" data-toggle="tooltip" title="<?php echo $button_product_partition; ?>"><button class="btn btn-primary btn-xs" data-title="Fragmentate order" onclick="showPartitioningWindow('<?php echo $leaf['order_product_id']; ?>', '<?php echo $leaf['quantity']; ?>');" ><span class="fa fa-unlink"></span></button></p>
                                <?php } ?>
                                <?php if (!$leaf['fragmentation_status']) { ?>
                                <p data-placement="top" data-toggle="tooltip" title="<?php echo $button_edit_status; ?>"><button class="btn btn-primary btn-xs" data-title="Edit status" onclick="changeProductStatus('<?php echo $leaf['order_product_id']; ?>', '<?php echo $leaf['order_product_status_id']; ?>');" ><span class="fa fa-pencil"></span></button></p>
                                <?php } ?>
                                <p data-placement="top" data-toggle="tooltip" title="<?php echo $button_view_status_history; ?>"><button class="btn btn-primary btn-xs" data-title="View status history" onclick="viewProductBranchStatusHistory('<?php echo $leaf['order_product_id']; ?>');" ><span class="fa fa-eye"></span></button></p></div>
                            </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </td>
                </tr>
              <?php } ?>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
              <tr>
                <td class="text-left"><a href="<?php echo $voucher['href']; ?>"><?php echo $voucher['description']; ?></a></td>
                <td class="text-left"></td>
                <td class="text-right">1</td>
                <td class="text-right"><?php echo $voucher['amount']; ?></td>
                <td class="text-right"><?php echo $voucher['amount']; ?></td>
              </tr>
            <?php } ?>
            <?php if (isset($profit)) { ?>
              <tr>
                <td colspan="8" class="text-right">Profit</td>
                <td class="text-right"><?php echo $profit; ?></td>
              </tr>
            <?php } ?>
            <?php foreach ($totals as $total) { ?>
              <tr>
                <td colspan="8" class="text-right"><?php echo $total['title']; ?></td>
                <td class="text-right"><?php echo $total['text']; ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php if ($comment) { ?>
          <table class="table table-bordered">
            <thead>
              <tr>
                <td><?php echo $text_comment; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $comment; ?></td>
              </tr>
            </tbody>
          </table>
        <?php } ?>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-comment-o"></i> <?php echo $text_history; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
          <li><a href="#tab-additional" data-toggle="tab"><?php echo $tab_additional; ?></a></li>
          <?php foreach ($tabs as $tab) { ?>
            <li><a href="#tab-<?php echo $tab['code']; ?>" data-toggle="tab"><?php echo $tab['title']; ?></a></li>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-history">
            <div id="history"></div>
            <br />
            <!--
            <fieldset>
              <legend><?php echo $text_history_add; ?></legend>
              <form class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                  <div class="col-sm-10">                    
                    <select name="order_status_id" id="input-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_statuses) { ?>
                        <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
                          <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </form>
            </fieldset>
            
            <div class="text-right">
              <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
            </div>
            -->
          </div>
          <div class="tab-pane" id="tab-additional">
            <?php if ($account_custom_fields) { ?>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <td colspan="2"><?php echo $text_account_custom_field; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($account_custom_fields as $custom_field) { ?>
                    <tr>
                      <td><?php echo $custom_field['name']; ?></td>
                      <td><?php echo $custom_field['value']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } ?>
            <?php if ($payment_custom_fields) { ?>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <td colspan="2"><?php echo $text_payment_custom_field; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($payment_custom_fields as $custom_field) { ?>
                    <tr>
                      <td><?php echo $custom_field['name']; ?></td>
                      <td><?php echo $custom_field['value']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } ?>
            <?php if ($shipping_method && $shipping_custom_fields) { ?>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <td colspan="2"><?php echo $text_shipping_custom_field; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($shipping_custom_fields as $custom_field) { ?>
                    <tr>
                      <td><?php echo $custom_field['name']; ?></td>
                      <td><?php echo $custom_field['value']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } ?>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td colspan="2"><?php echo $text_browser; ?></td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo $text_ip; ?></td>
                  <td><?php echo $ip; ?></td>
                </tr>
                <?php if ($forwarded_ip) { ?>
                  <tr>
                    <td><?php echo $text_forwarded_ip; ?></td>
                    <td><?php echo $forwarded_ip; ?></td>
                  </tr>
                <?php } ?>
                <tr>
                  <td><?php echo $text_user_agent; ?></td>
                  <td><?php echo $user_agent; ?></td>
                </tr>
                <tr>
                  <td><?php echo $text_accept_language; ?></td>
                  <td><?php echo $accept_language; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <?php foreach ($tabs as $tab) { ?>
            <div class="tab-pane" id="tab-<?php echo $tab['code']; ?>"><?php echo $tab['content']; ?></div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <!-- model content -->	
  <div class="modal fade" id="form-edit-product-status" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
          <h4 class="modal-title custom_align" id="Heading"><?php echo $text_edit_product_status; ?></h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="control-label"><?php echo $entry_order_status; ?></label>
            <input class="form-control " type="hidden" name="order_product_id" id="order_porduct_id">
            <input class="form-control " type="hidden" name="order_id" id="order_id" value="<?php echo $order_id;?>">
            <select name="order_product_status_id" id="order_product_status" class="form-control">
              <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
            </select>  
          </div>
          <div class="form-group">
            <label class="control-label"><?php echo $entry_notify; ?></label>
            <input type="checkbox" name="order_product_notify" value="1" />        
          </div>
          <div class="form-group">
            <label class="control-label"><?php echo $entry_comment; ?></label>
            <textarea rows="2" class="form-control" name="order_product_comment" ></textarea>


          </div>
        </div>
        <div class="modal-footer ">
          <button id="button-product-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
        </div>
      </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>

  <!-- model content -->	
  
  <div class="modal fade" id="form-view-product-status-history" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
          <h4 class="modal-title custom_align" id="heading-status-history"><?php echo $text_view_status_history; ?></h4>
        </div>
        <div class="modal-body">
          <div id="order_product_history"></div>                   
        </div>
        
      </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>
  
  <!-- order product partitioning modal -->
  
  <div class="modal fade" id="form-partition-order-product" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
          <h4 class="modal-title custom_align" id="heading-product-partitioning"><?php echo $text_product_partitioning; ?></h4>
          <input type="hidden" id='partition-product-key' value="" />
        </div>
        <div class="modal-body">
          <div id="order_product_partition_input_area">
            <div class="row partition_lable_row">
                <div class="col-md-5">
                    <label><?php echo $entry_partition_length; ?>:</label>
                </div>
                <div class="col-md-5">
                    <label><?php echo $entry_partition_select_status; ?>:</label>
                </div>
                <div class="col-md-2">
                </div>
            </div>
            <div class="row partition_form_groups partition_form_group_1" id="partition_form_group_1">
                <br/>
                <div class="col-md-5">
                  <input autocomplete="off" class="input form-control order-partition-num" id="field1" name="prof1" type="text" placeholder="<?php echo $text_partition_length_placeholder; ?>" data-items="8"/>
                </div>
                <div class="col-md-5">
                    <select class="form-control" id="sel1">
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="partition-forms-button-1" name="button1" class="btn btn-primary btn-block partition-forms-button-add" type="button">
                      <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
          </div>
          <hr/>
          <div id="order_product_partition_description_area">
            <label><?php echo $entry_partition_description; ?>:</label>
            <input type="text" class="input form-control" id="order_product_partition_description" placeholder="<?php echo $text_partition_description_placeholder; ?>"/>
          </div>
          <hr/>
          <div id="order_product_partition_info">
            <div class="row">
              <div class="col-md-6">
                <label><?php echo $entry_partition_current_length; ?>:</label>
                <input type="text" class="input form-control order_product_info_current" readonly/>
              </div>
              <div class="col-md-6">
                <label><?php echo $entry_partition_total_length; ?>:</label>
                <input type="text" class="input form-control order_product_info_total" readonly/>
              </div>
            </div>
          </div>
          <hr/>
          <div id="order_product_partition_message">
          </div>
        </div>
        <div class="modal-footer ">
          <button id="button-partition-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_partition_submit; ?></button>
        </div>
      </div>
    </div>
  </div>
  
<script src="view/javascript/tinycolor-0.9.15.min.js"></script>
<script src="view/javascript/pick-a-color-1.2.3.min.js"></script>	
<script type="text/javascript">
    $(document).ready(function () {
        $(".pick-a-color").pickAColor({
            showSpectrum: true,
            showSavedColors: true,
            saveColorsPerElement: true,
            fadeMenuToggle: true,
            showAdvanced: true,
            showBasicColors: true,
            showHexInput: true,
            allowBlank: true,
            inlineDropdown: true
        });
    });
</script>
<script type="text/javascript">

$(document).ready(function(){
  $(".partition-group-collapse").on("hide.bs.collapse", function() {
    var element_id = $(this).attr('id');
    var element_id_num = element_id.substr(16,element_id.length);
    
    $('*[data-target=".partition-group-' + element_id_num + '"]').html('<td colspan="11" class="text-center"><span class="fa fa-chevron-down"></span></td>');
  });
  $(".partition-group-collapse").on("show.bs.collapse", function() {
    var element_id = $(this).attr('id');
    var element_id_num = element_id.substr(16,element_id.length);
    
    $('*[data-target=".partition-group-' + element_id_num + '"]').html('<td colspan="11" class="text-center"><span class="fa fa-chevron-up"></span></td>');
  });
});

$(document).delegate('#button-partition-confirm', 'click', function () {
    partitioning_groups_size = [];
    $('.partition_form_groups').each(function() {
        var field_num = this.id.slice(21, this.id.length);
        tmp_array = [Number($('#field' + field_num).val()), Number($('#sel' + field_num + ' option:selected').data('statusKey'))];
        partitioning_groups_size.push(tmp_array);
    });
    partitioning_description = $('#order_product_partition_description').val();
    partitioning_data = {};
    partitioning_data.product_key = $('#partition-product-key').val();
    partitioning_data.description = partitioning_description;
    partitioning_data.groups = partitioning_groups_size;
    $.ajax({
        type: 'post',
        url: 'index.php?route=sale/order/productpartition&token=<?php echo $token; ?>',
        data: {partition_data:JSON.stringify(partitioning_data)},
        dataType: 'json',
        beforeSend: function () {
          $('#button-partition-confirm').button('loading');
        },
        complete: function () {
          $('#button-partition-confirm').button('reset');
        },
        success: function (json) {
          if (json['error_code'] == '0') {
              $('#order_product_partition_message').html('<div class="alert alert-success" role="alert"><?php echo $error_partition_message_success; ?></div>');
          }

          $.ajax({
            url: '<?php echo $store_url; ?>index.php?route=api/order/partitionproductstatus&token=' + token,
            type: 'post',
            dataType: 'json',
            data: 'order_product_id=' + partitioning_data.product_key,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (json) {
              /*
              $('.alert').remove();

              if (json['error']) {
              }

              if (json['success']) {
              }
              key = $('input[name=\'order_product_id\']').val();
              $('#order_product_status_' + key).text($('#order_product_status :selected').text());
              $('#form-edit-product-status').modal('hide');
              */
            },
            error: function (xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
          });
          
          switch (json['error_code']) {
            case '0':
                $('#order_product_partition_message').html('<div class="alert alert-success" role="alert"><?php echo $error_partition_message_success; ?></div>');
                document.location.reload();
                break;
            case '1':
                $('#order_product_partition_message').html('<div class="alert alert-danger" role="alert"><?php echo $error_partition_message_1; ?></div>');
                break;
            case '2':
                $('#order_product_partition_message').html('<div class="alert alert-warning" role="alert"><?php echo $error_partition_message_2; ?></div>');
                break;
            case '3':
                $('#order_product_partition_message').html('<div class="alert alert-warning" role="alert"><?php echo $error_partition_message_3; ?></div>');
                break;
            case '4':
                $('#order_product_partition_message').html('<div class="alert alert-warning" role="alert"><?php echo $error_partition_message_4; ?></div>');
                break;
            case '5':
                $('#order_product_partition_message').html('<div class="alert alert-danger" role="alert"><?php echo $error_partition_message_5; ?></div>');
                break;
            default:
                $('#order_product_partition_message').html('<div class="alert alert-warning" role="alert"><?php echo $error_partition_message_default; ?></div>');
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).delegate('#button-ip-add', 'click', function () {
  $.ajax({
    url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
    type: 'post',
    data: 'ip=<?php echo $api_ip; ?>',
    dataType: 'json',
    beforeSend: function () {
      $('#button-ip-add').button('loading');
    },
    complete: function () {
      $('#button-ip-add').button('reset');
    },
    success: function (json) {
      $('.alert').remove();

      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$(document).delegate('#button-invoice', 'click', function () {
  $.ajax({
    url: 'index.php?route=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
    dataType: 'json',
    beforeSend: function () {
      $('#button-invoice').button('loading');
    },
    complete: function () {
      $('#button-invoice').button('reset');
    },
    success: function (json) {
      $('.alert').remove();

      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }

      if (json['invoice_no']) {
        $('#invoice').html(json['invoice_no']);

        $('#button-invoice').replaceWith('<button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-cog"></i></button>');
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$(document).delegate('#button-reward-add', 'click', function () {
  $.ajax({
    url: 'index.php?route=sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
    type: 'post',
    dataType: 'json',
    beforeSend: function () {
      $('#button-reward-add').button('loading');
    },
    complete: function () {
      $('#button-reward-add').button('reset');
    },
    success: function (json) {
      $('.alert').remove();

      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

        $('#button-reward-add').replaceWith('<button id="button-reward-remove" data-toggle="tooltip" title="<?php echo $button_reward_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>');
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$(document).delegate('#button-reward-remove', 'click', function () {
  $.ajax({
    url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
    type: 'post',
    dataType: 'json',
    beforeSend: function () {
      $('#button-reward-remove').button('loading');
    },
    complete: function () {
      $('#button-reward-remove').button('reset');
    },
    success: function (json) {
      $('.alert').remove();

      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

        $('#button-reward-remove').replaceWith('<button id="button-reward-add" data-toggle="tooltip" title="<?php echo $button_reward_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>');
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$(document).delegate('#button-commission-add', 'click', function () {
  $.ajax({
    url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
    type: 'post',
    dataType: 'json',
    beforeSend: function () {
      $('#button-commission-add').button('loading');
    },
    complete: function () {
      $('#button-commission-add').button('reset');
    },
    success: function (json) {
      $('.alert').remove();

      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

        $('#button-commission-add').replaceWith('<button id="button-commission-remove" data-toggle="tooltip" title="<?php echo $button_commission_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>');
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$(document).delegate('#button-commission-remove', 'click', function () {
  $.ajax({
    url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
    type: 'post',
    dataType: 'json',
    beforeSend: function () {
      $('#button-commission-remove').button('loading');
    },
    complete: function () {
      $('#button-commission-remove').button('reset');
    },
    success: function (json) {
      $('.alert').remove();

      if (json['error']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
      }

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

        $('#button-commission-remove').replaceWith('<button id="button-commission-add" data-toggle="tooltip" title="<?php echo $button_commission_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>');
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

var token = '';

// Login to the API
$.ajax({
  url: '<?php echo $store_url; ?>index.php?route=api/login',
  type: 'post',
  dataType: 'json',
  data: 'key=<?php echo $api_key; ?>',
  crossDomain: true,
  success: function (json) {
    $('.alert').remove();

    if (json['error']) {
      if (json['error']['key']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }

      if (json['error']['ip']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');
      }
    }

    if (json['token']) {
      token = json['token'];
      //console.log(json);
    }
  },
  error: function (xhr, ajaxOptions, thrownError) {
    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
  }
});

$('#history').delegate('.pagination a', 'click', function (e) {
  e.preventDefault();

  $('#history').load(this.href);
});

$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

$('#button-history').on('click', function () {
  if (typeof verifyStatusChange == 'function') {
    if (verifyStatusChange() == false) {
      return false;
    } else {
      addOrderInfo();
    }
  } else {
    addOrderInfo();
  }

  $.ajax({
    url: '<?php echo $store_url; ?>index.php?route=api/order/history&token=' + token + '&order_id=<?php echo $order_id; ?>',
    type: 'post',
    dataType: 'json',
    data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&override=' + ($('input[name=\'override\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
    beforeSend: function () {
      $('#button-history').button('loading');
    },
    complete: function () {
      $('#button-history').button('reset');
    },
    success: function (json) {
      $('.alert').remove();

      if (json['error']) {
        $('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }

      if (json['success']) {
        $('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

        $('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        $('textarea[name=\'comment\']').val('');
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

/* Обновить статус товара в заказе */
$('#button-product-history').on('click', function () {
  /*if (typeof verifyStatusChange == 'function'){
   if (verifyStatusChange() == false){
   return false;
   } else{
   addOrderInfo();
   }
   } else{
   addOrderInfo();
   }*/

  /*
   * получить order_product_id, статус, уведомление и комментарий к статусу 
   * айди лучше получить из скрытого параметра, но при нажатии на кнопу нужно заполнить данными форму  
   */


  $.ajax({
    url: '<?php echo $store_url; ?>index.php?route=api/order/historyproductstatus&token=' + token + '&order_product_id=' + $('input[name=\'order_product_id\']').val(),
    type: 'post',
    dataType: 'json',
    data: 'order_id=' + encodeURIComponent($('input[name=\'order_id\']').val()) +'&order_product_status_id=' + encodeURIComponent($('select[name=\'order_product_status_id\']').val()) + '&notify=' + ($('input[name=\'order_product_notify\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'order_product_comment\']').val()),
    beforeSend: function () {
      //$('#button-history').button('loading');
    },
    complete: function () {
      //$('#button-history').button('reset');          
    },
    success: function (json) {
      $('.alert').remove();

      if (json['error']) {
        //$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }

      if (json['success']) {
        //$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

        //$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

        //$('textarea[name=\'comment\']').val('');

      }
      key = $('input[name=\'order_product_id\']').val();
      //console.log(key);
      //console.log($('#order_product_status :selected').text());
      //console.log($('#order_product_status_' + key).text());
      $('#order_product_status_' + key).text($('#order_product_status :selected').text());
      $('#form-edit-product-status').modal('hide');
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

function showPartitioningWindow(product_key, product_quantity) {
    $('#form-partition-order-product').modal({show: true});
    $('#partition-product-key').val(product_key);
    $('.order_product_info_total').val(product_quantity);
    
    $('#order_product_partition_input_area').empty();
    
    var partition_req_forms = '<div class="row partition_lable_row">';
    partition_req_forms += '<div class="col-md-5"><label><?php echo $entry_partition_length; ?>:</label></div>';
    partition_req_forms += '<div class="col-md-5"><label><?php echo $entry_partition_select_status; ?>:</label></div>';
    partition_req_forms += '<div class="col-md-2"></div></div>';
    partition_req_forms += '<div class="row partition_form_groups partition_form_group_1" id="partition_form_group_1">';
    partition_req_forms += '<br/><div class="col-md-5">';
    partition_req_forms += '<input autocomplete="off" class="input form-control order-partition-num" id="field1" name="prof1" type="text" placeholder="<?php echo $text_partition_length_placeholder; ?>"/>';
    partition_req_forms += '</div><div class="col-md-5"><select class="form-control" id="sel1"></select></div>';
    partition_req_forms += '<div class="col-md-2">';
    partition_req_forms += '<button id="partition-forms-button-1" name="button1" class="btn btn-primary btn-block partition-forms-button-add" type="button">';
    partition_req_forms += '<i class="fa fa-plus"></i></button></div></div>';
               
    $('#order_product_partition_input_area').html(partition_req_forms);
    $('#order_product_partition_description').val('');
    $('.order_product_info_current').val('');
    
    var next = 1;
    $('.partition-forms-button-add').click(function(e) {
        e.preventDefault();
        var cur_position = next;
        var next_position = ++next;
        var append_to = '.partition_form_group_' + cur_position;
        
        var new_form = '<div class="row partition_form_groups partition_form_group_' + next_position + '" id="partition_form_group_' + next_position + '">';
        new_form += '<br/><div class="col-md-5">';
        new_form += '<input autocomplete="off" class="input form-control order-partition-num" id="field' + next_position + '" type="text" placeholder="<?php echo $text_partition_length_placeholder; ?>"/></div>';
        new_form += '<div class="col-md-5"><select class="form-control" id="sel' + next_position + '"></select></div>';
        new_form += '<div class="col-md-2"><button id="partition-forms-button-' + next_position + '" class="btn btn-danger btn-block partition-forms-button-remove" type="button">';
        new_form += '<i class="fa fa-minus"></i></button></div></div>';
        
        $('.partition_form_group_1').before(new_form);
        
        $('#sel' + next_position).html($('#sel1').html());
        
        $('#field' + next_position).val($('#field1').val());
        $('#sel' + next_position).val($('#sel1').val());
        $('#field1').val('');
        $('#sel1').val('');
        
        $('.partition-forms-button-remove').on('click', function(e) {
            e.preventDefault();
            var field_num = this.id.slice(23, this.id.length);
            $('.partition_form_group_' + field_num).remove();
            
            sum = 0;
            $('.order-partition-num').each(function() {
                sum += Number($(this).val());
            });
            if (isNaN(sum)) {
                $('.order_product_info_current').val('<?php echo $error_incorrect_number_input; ?>');
            } else {
                $('.order_product_info_current').val(sum);
            }
        });
    });
    
    $.ajax({
        url: 'index.php?route=sale/order/getorderproductstatuses&token=<?php echo $token; ?>',
        data: 'order_product_id=' + product_key,
        type: 'post',
        dataType: 'json',
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function (json) {
          $('#sel1').find('option').remove().end();
          $.each(json.statuses, function (index, status_obj) {
            $('#sel1').append('<option data-status-key="' + status_obj.order_product_status_id + '">' + status_obj.name + '</option>');
          });
          $('#sel1').val(json.current_status);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });

    $(document).on('keyup', '.order-partition-num', function() {
        sum = 0;
        $('.order-partition-num').each(function() {
            sum += Number($(this).val());
        });
        if (isNaN(sum)) {
            $('.order_product_info_current').val('<?php echo $error_incorrect_number_input; ?>');
        } else {
            $('.order_product_info_current').val(sum);
        }
    });
}

function changeProductStatus(key) {
  // получить список статусов
  $('#order_porduct_id').val(key);
  $('#form-edit-product-status').modal({show: true});
 // console.log(key);

  $.ajax({
    // url для получения списка статусов
    // тут лучше отправлять статус
    url: 'index.php?route=sale/order/getorderproductstatuses&token=<?php echo $token; ?>',
    data: 'order_product_id='+key,
    type: 'post',
    dataType: 'json',
    beforeSend: function () {
    },
    complete: function () {
    },
    success: function (json) {
      $('#order_product_status').find('option').remove().end();
      $.each(json.statuses, function (index, status_obj) {
        $('#order_product_status').append('<option value="' + status_obj.order_product_status_id + '">' + status_obj.name + '</option>');
      });
      $('#order_product_status').val(json.current_status);
       id = $('#order_product_status_'+key).parent('td');
       $('#order_product_status_'+key).parent('.status-color').css('background-color','#'+json.bg_color);
       $('#order_product_status_'+key).parent('.status-color').css('color','#'+json.text_color);

    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

function viewProductStatusHistory(key) {
  $('#form-view-product-status-history').modal({show: true});

  $('#order_product_history').delegate('.pagination a', 'click', function (e) {
    e.preventDefault();

    $('#order_product_history').load(this.href);
  });

  $('#order_product_history').load('index.php?route=sale/order/historyorderproduct&token=<?php echo $token; ?>&order_product_id=' + key);
}

function viewProductBranchStatusHistory(key) {
    $('#form-view-product-status-history').modal({show: true});

    $('#order_product_history').delegate('.pagination a', 'click', function (e) {
        e.preventDefault();

        $('#order_product_history').load(this.href);
    });

    $('#order_product_history').load('index.php?route=sale/order/historyorderproductbranch&token=<?php echo $token; ?>&order_product_id=' + key);
}

function changeStatus() {
  var status_id = $('select[name="order_status_id"]').val();

  $('#openbay-info').remove();

  $.ajax({
    url: 'index.php?route=extension/openbay/getorderinfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id=' + status_id,
    dataType: 'html',
    success: function (html) {
      $('#history').after(html);
    }
  });
}

function addOrderInfo() {
  var status_id = $('select[name="order_status_id"]').val();

  $.ajax({
    url: 'index.php?route=extension/openbay/addorderinfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id=' + status_id,
    type: 'post',
    dataType: 'html',
    data: $(".openbay-data").serialize()
  });
}

$(document).ready(function () {
  changeStatus();
});

$('select[name="order_status_id"]').change(function () {
  changeStatus();
});

var order = {
    'update_product_quantity': function (order_product_id) {
        quantity = $("[name='quantity[" + order_product_id + "]']").val();
        $.ajax({
            url: 'index.php?route=sale/order/updateproductquantity&token=<?php echo $token; ?>',
            type: 'post',
            data: 'order_product_id=' + order_product_id + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
            dataType: 'json',
            success: function (json) {
                document.location.reload();
            }
        });
    },
    'update_product_acquiring_price': function (order_product_id) {
        acquiring_price = $("[name='acquiring_price[" + order_product_id + "]']").val();
        $.ajax({
            url: 'index.php?route=sale/order/updateproductacquiringprice&token=<?php echo $token; ?>',
            type: 'post',
            data: 'order_product_id=' + order_product_id + '&acquiring_price=' + (typeof (acquiring_price) != 'undefined' ? acquiring_price : 1),
            dataType: 'json',
            success: function (json) {
                document.location.reload();
            }
        });
    },
    'update_product_price': function (order_product_id) {
        price = $("[name='price[" + order_product_id + "]']").val();
        $.ajax({
            url: 'index.php?route=sale/order/updateproductprice&token=<?php echo $token; ?>',
            type: 'post',
            data: 'order_product_id=' + order_product_id + '&price=' + (typeof (price) != 'undefined' ? price : 1),
            dataType: 'json',
            success: function (json) {
                document.location.reload();
            }
        });
    },
    'update_product_delivery_time': function(order_product_id) {
        delivery_time = $("[name='delivery_time[" + order_product_id + "]']").val();
        $.ajax({
            url: 'index.php?route=sale/order/updateproductdeliverytime&token=<?php echo $token; ?>',
            type: 'post',
            data: 'order_product_id=' + order_product_id + '&delivery_time=' + (typeof (delivery_time) != 'undefined' ? delivery_time : 1),
            dataType: 'json',
            success: function (json) {
                document.location.reload();
            }
        });
    }
}
</script> 
</div>
<?php echo $footer; ?> 