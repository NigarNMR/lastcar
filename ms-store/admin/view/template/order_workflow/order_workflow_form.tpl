<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-order-status" class="form-horizontal">
                    <div class="form-group">
                        <h3 class="col-md-4" value="<?php echo $order_status['order_status_id']; ?>"><strong><?php echo $entry_name; ?>:</strong>
                            <?php echo $order_status['name']; ?></h3>  
                    </div>
                    <div  class="container" id = "or" >
                        <div  class="col-md-3">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr> 
                                        <td class="text-center"></td>
                                        <td class="text-left">Хоть один</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($product_statuses) { ?>
                                    <?php foreach ($product_statuses as $status_workflow) { ?>
                                    <tr >
                                        <td class="text-center">                                            
                                            <?php if (isset($product_status_check[1][$status_workflow['order_product_status_id']])) { ?>
                                            <input type="checkbox"  id="status_1_<?php echo $status_workflow['order_product_status_id']; ?>" name="selected_if_one[1][<?php echo $status_workflow['order_product_status_id']; ?>][]"  type="1" value="1" checked="checked" onchange = "check_one( <?php echo $status_workflow['order_product_status_id']; ?> );"/>
                                            <?php } else { ?>
                                            <input type="checkbox" id="status_1_<?php echo $status_workflow['order_product_status_id']; ?>" name="selected_if_one[1][<?php echo $status_workflow['order_product_status_id']; ?>][]" type="1" onchange = "check_one( <?php echo $status_workflow['order_product_status_id']; ?> );"/>
                                            <?php } ?></td>
                                        <td type="1"  class="text-left"><?php echo $status_workflow['name']; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <?php } else { ?>                                
                                    <tr>
                                        <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div  class="col-md-3" >
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr> 
                                        <td class="text-center"></td>

                                        <td class="text-left">Любой из</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($product_statuses) { ?>
                                    <?php foreach ($product_statuses as $status_workflow) { ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php if (isset($product_status_check[2][$status_workflow['order_product_status_id']])) { ?>
                                            <input type="checkbox" id="status_2_<?php echo $status_workflow['order_product_status_id']; ?>" name="selected_any[2][<?php echo $status_workflow['order_product_status_id']; ?>][]" value="1" type="2" checked="checked" onchange = "check_any( <?php echo $status_workflow['order_product_status_id']; ?> );"/>
                                            <?php } else { ?>
                                            <input type="checkbox" id="status_2_<?php echo $status_workflow['order_product_status_id']; ?>" name="selected_any[2][<?php echo $status_workflow['order_product_status_id']; ?>][]" type="2" onchange = "check_any( <?php echo $status_workflow['order_product_status_id']; ?> );"/>
                                            <?php } ?></td>
                                        <td type="2" class="text-left"><?php echo $status_workflow['name']; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <?php } else { ?>                                
                                    <tr>
                                        <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>         
            </div>
        </div>

        <?php echo $footer; ?>


        <script type="text/javascript">
            function check_one(id) {
                if ($("#status_1_" + id).is(':checked')) {
                    $("#status_2_" + id).attr("disabled", true);
                    $("#status_2_" + id).addClass('any-color');
                } else {
                    $("#status_2_" + id).attr("disabled", false);
                }
            }
            
            function check_any(id) {
                if ($("#status_2_" + id).is(':checked')) {
                    $("#status_1_" + id).attr("disabled", true);
                } else {
                    $("#status_1_" + id).attr("disabled", false);
                }
            }
        </script>