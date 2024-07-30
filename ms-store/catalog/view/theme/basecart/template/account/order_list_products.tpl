<?php echo $header; ?>
<?php error_reporting( E_ERROR ); ?>
<div class="container">
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
            <h1><?php echo $heading_title; ?></h1>
            <?php if ($products) { ?>
            <ul class="nav nav-tabs">
                <li ><a href="<?php echo $account_orders;?>">Список заказов</a></li>
                <li class="active"><a href="#">Список заказов по позициям</a></li>
            </ul>

            <input type="text" id="tablesearchinput" class="form-control" name="search" placeholder="Поиск по заказам" style="width: 300px;"> 


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
                            <td class="text-center" width="120px"><?php echo $column_quantity; ?></td>
                            <td class="text-center"><?php echo $column_price; ?></td>
                            <td class="text-center"><?php echo $column_total; ?></td>
                            <td class="text-center"><?php echo $column_order_product_status; ?></td>
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
                        <tr id="order_product_<?php echo $product['order_product_id']; ?>">
                            <td class="text-left"><?php echo $product['date_added']; ?></td>
                            <td class="text-right"><a href="<?php echo $product['view']; ?>" title="<?php echo $button_view; ?>">#<?php echo $product['order_id']; ?></a></td>                
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
                            <td class="text-right"><?php echo $product['quantity']; ?></td>
                            <td class="text-right"><?php echo $product['price']; ?></td>
                            <td class="text-right"><?php echo $product['total']; ?></td>
                            <td class="text-right" style="background-color: <?php echo '#' . $product['bg_color']?>; color: <?php echo '#' . $product['text_color']?>;">
                                <p id="order_product_status_<?php echo $product['order_product_id']; ?>"><?php echo $product['status']; ?></p></td>
                            <td class="text-center">
                                <div class="btn-group">                     
                                    <p data-placement="top" data-toggle="tooltip" title="View status history"><button class="btn btn-primary btn-xs" data-title="View status history" onclick="viewProductStatusHistory('<?php echo $product['order_id']; ?>', '<?php echo $product['order_product_id']; ?>');" ><span class="fa fa-eye"></span></button></p></div>
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

<!-- model content -->	
<div class="modal fade" id="form-view-product-status-history" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
                <h4 class="modal-title custom_align" id="heading-status-history"><?php echo $text_product_status_history;?></h4>
            </div>
            <div class="modal-body">
                <div id="order_product_history"></div>                   
            </div>

        </div>
        <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
</div>

<script type="text/javascript">
    function viewProductStatusHistory(order_id, order_product_id) {
        // получить список статусов
        //$('#order_porduct_id').val(key);
        $('#form-view-product-status-history').modal({show: true});

        $('#order_product_history').delegate('.pagination a', 'click', function (e) {
            e.preventDefault();

            $('#order_product_history').load(this.href);
        });

        $('#order_product_history').load('index.php?route=account/order/historyorderproduct&order_id=' + order_id + '&order_product_id=' + order_product_id);
    }
</script> 
<?php echo $footer; ?>
