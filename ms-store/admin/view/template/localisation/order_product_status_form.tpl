<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-order-product-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-order-product-status" class="form-horizontal">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                            <?php foreach ($languages as $language) { ?>
                            <div class="input-group input-status"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                                <input type="text" name="order_product_status[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($order_product_status[$language['language_id']]) ? $order_product_status[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                            </div>
                            <?php if (isset($error_name[$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                            <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- изменение цвета статуса -->
                 
                        <div class="form-group row">
                            <label for="text-color" class="col-sm-2 col-form-label text-right">Цвет текста</label>
                            <div class="col-sm-10">
                                <input type="text" id="text-color" value="<?php echo $text_color; ?>" name="text_color" class="pick-a-color form-control" placeholder="Определите цвет текста">
                            </div>
                            <label for="bg-color" class="col-sm-2 col-form-label text-right">Цвет фона</label>
                            <div class="col-sm-10">
                                <input type="text" id="bg-color" value="<?php echo $bg_color; ?>" name="bg_color" class="pick-a-color form-control" placeholder="Определите цвет фона">			
                            </div>
                        </div>
                </form>
                <!--<script src="..../jquery-1.9.1.min.js"></script> на всякий случай   -->
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

            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>