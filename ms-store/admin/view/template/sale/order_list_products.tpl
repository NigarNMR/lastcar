<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
                    <div class="pull-right">
                        <button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
                        <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
                        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
                    <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>



            <div class="well">
          <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-firm"><?php echo $entry_firm; ?></label>
                <input type="text" name="filter_firm" value="<?php echo $filter_firm; ?>" placeholder="<?php echo $entry_firm; ?>" id="input-firm" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-code"><?php echo $entry_code; ?></label>
                <input type="text" name="filter_code" value="<?php echo $filter_code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-description"><?php echo $entry_description; ?></label>
                <input type="text" name="filter_description" value="<?php echo $filter_description; ?>" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
              </div>
               <div class="form-group">
                <label class="control-label" for="input-days"><?php echo $entry_days; ?></label>
                <input type="text" name="filter_days" value="<?php echo $filter_days; ?>" placeholder="<?php echo $entry_days; ?>" id="input-days" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-count"><?php echo $entry_count; ?></label>
                <input type="text" name="filter_count" value="<?php echo $filter_count; ?>" placeholder="<?php echo $entry_count; ?>" id="input-count" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-price"><?php echo $entry_price; ?></label>
                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
              </div>

            </div>
            <div class="col-sm-4">
                <div class="form-group">
                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                </div>
                <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                        <select name="filter_order_status" id="input-order-status" class="form-control">
                            <option value="*"></option>
                            <?php if ($filter_order_status == '0') { ?>
                            <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                            <?php } else { ?>
                            <option value="0"><?php echo $text_missing; ?></option>
                            <?php } ?>
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                </div>


              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>


            <input type="text" id="tablesearchinput" class="form-control" name="search" placeholder="Поиск по заказам" style="width: 300px;">

            <?php if ($products) { ?>
            <ul class="nav nav-tabs">
                <li ><a href="<?php echo $sale_orders;?>">Список заказов</a></li>
                <li class="active"><a href="#">Список заказов по позициям</a></li>
            </ul>
            <div class="table-responsive">
                <table id="table_order_product" class="table table-bordered table-striped tablesorter table-hover">
                    <thead>
                        <tr>
                            <td class="text-center"><?php echo $column_date_added; ?></td>
                            <td class="text-center"><?php echo $column_order_id; ?></td>
                            <td class="text-center" width="100px"><?php echo $column_firm; ?></td>
                            <td class="text-center"><?php echo $column_article; ?></td>
                            <td class="text-center"><?php echo $column_description; ?></td>
                            <td class="text-center" width="70px"><?php echo $column_day; ?></td>
                            <td class="text-center" width="70px"><?php echo $column_acquiring_price; ?></td>
                            <td class="text-center" width="120px"><?php echo $column_quantity; ?></td>
                            <td class="text-center"><?php echo $column_price; ?></td>
                            <td class="text-center"><?php echo $column_total; ?></td>
                            <td class="text-center"><?php echo $column_order_product_status; ?></td>
                            <td class="text-center"><?php echo $column_customer; ?></td>
                            <td class="text-center"></td>
                            <!--<td></td>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) { ?>
                        <?php if ($product['option']) { ?>
                        <?php foreach ($product['option'] as $option) { ?>
                        <?php $options[$option['name']] = $option; ?>
                        <?php } ?>
                        <?php } ?>
                        <tr id="order_product_<?php echo $order_product_id; ?>">
                            <td class="text-left"><?php echo $product['date_added']; ?></td>
                            <td class="text-right"><?php echo $product['order_id']; ?></td>
                            <td><?php echo $product['model']; ?></td>
                            <td><?php echo $options['Артикул']['value']; //Пиздец какой костыль!!! Исправить эту хрень при первой возможности ?></td>
                            <td><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br>
                                <!--<a data-toggle="collapse" href="#collapse_<?php echo $product['order_product_id'] ?>"><?php echo $text_more; ?></a>
                                <div id="collapse_<?php echo $product['order_product_id'] ?>" class="collapse">
                                    <?php if ($options) { ?>
                                    <?php foreach ($options as $key=>$value) { ?>
                                    <br />
                                    <!--сторока со всеми опциями <small><?php echo $key; ?>: <?php echo $value['value']; ?></small>
                                    <?php } ?>
                                    <?php } ?>
                                </div>-->
                            </td>
                            <td><?php echo $options['Срок поставки (дней)']['value']; //Пиздец какой костыль!!! Исправить эту хрень при первой возможности  ?></td>
                            <td class="text-right"><?php echo $product['acquiring_price']; ?></td>
                            <td class="text-right"><?php echo $product['quantity']; ?></td>
                            <td class="text-right"><?php echo $product['price']; ?></td>
                            <td class="text-right"><?php echo $product['total']; ?></td>
                            <td class="text-left" style="background-color: <?php echo '#' . $product['bg_color']; ?>; color: <?php echo '#' . $product['text_color']; ?> ;"><?php echo $product['order_status']; ?></td>
                            <td class="text-right"><?php echo $product['customer']; ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <p data-placement="top" data-toggle="tooltip" title="View status history"><button class="btn btn-primary btn-xs" data-title="View status history" onclick="viewProductStatusHistory( '<?php echo $product['order_product_id']; ?>');" ><span class="fa fa-eye"></span></button></p></div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="text-right"><?php echo $pagination; ?></div>
            <?php }
            else { ?>
            <p><?php echo $text_empty; ?></p>
            <?php } ?>
            <div class="buttons clearfix">
                <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
            </div>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>

<div class="modal fade" id="form-view-product-status-history" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
          <h4 class="modal-title custom_align" id="heading-status-history"><?php echo $text_view_status_history; ?></h4>
        </div>
        <div class="modal-body">
            <div id="order_product_history">

            </div>
        </div>

      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

<script type="text/javascript">
    $('#button-filter').on('click', function() {
	url = 'index.php?route=sale/order/products&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}

	var filter_order_status = $('select[name=\'filter_order_status\']').val();

	if (filter_order_status != '*') {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}

	location = url;
});

    function viewProductStatusHistory(key) {
  $('#form-view-product-status-history').modal({show: true});

  $('#order_product_history').delegate('.pagination a', 'click', function (e) {
    e.preventDefault();

    $('#order_product_history').load(this.href);
  });

  $('#order_product_history').load('index.php?route=sale/order/historyorderproduct&token=<?php echo $token; ?>&order_product_id=' + key);
  console.log('<?php echo $token; ?>');
}

function viewProductBranchStatusHistory(key) {
    $('#form-view-product-status-history').modal({show: true});

    $('#order_product_history').delegate('.pagination a', 'click', function (e) {
        e.preventDefault();

        $('#order_product_history').load(this.href);
    });

    $('#order_product_history').load('index.php?route=sale/order/historyorderproductbranch&token=<?php echo $token; ?>&order_product_id=' + key);
}

</script>





<?php echo $footer; ?>
