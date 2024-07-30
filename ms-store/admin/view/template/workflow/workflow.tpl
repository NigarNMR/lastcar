<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php //echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-order-product-status">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <?php  
                                for ($i = 0; $i < count($status)+1; $i++) {
                                echo '<tr>';
                                for($j = 0; $j < count($status)+1; $j++) {
                                echo '<td class="text-center">';
                                if ($i == 0 && $j > 0){
                                echo $status[$j-1]['name'];
                                }  
                                if ($j == 0 && $i > 0){
                                echo $status[$i-1]['name'];
                                }

                             


                                if ($i >= 1 &&  $j > 0 ) {
                                
                                $id_A = $status[$i-1]['order_product_status_id'];
                                $id_B = $status[$j-1]['order_product_status_id'];
                                $checked = '';
                                if(isset($order_product_statuses[$id_A][$id_B])) $checked = 'checked';
                                echo '
                                <input type="checkbox" id="status_' .$id_A. '_' .$id_B.' _ " 
                                name="status[' .$id_A. '][' .$id_B.'][]" value="1" '.$checked.'/>';
                              

                                } 
                                echo '</td>';    
                                }
                                echo '</tr>';
                                }?>
                            </thead>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
