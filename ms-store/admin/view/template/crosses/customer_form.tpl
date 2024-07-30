<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">

          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="row">






                <div class="col-sm-10">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-customer">
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_brand_name1; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="brand_name1" value="<?php echo $brand_name1O; ?>" placeholder="<?php echo $entry_brand_name1; ?>" id="input-firstname" class="form-control" />
                          <?php if ($error_brand_name1) { ?>
                          <div class="text-danger"><?php echo $error_brand_name1; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_akey1; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="akey1" value="<?php echo $akey1O; ?>" placeholder="<?php echo $entry_akey1; ?>" id="input-lastname" class="form-control" />
                          <?php if ($error_akey1) { ?>
                          <div class="text-danger"><?php echo $error_akey1; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_brand_name2; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="brand_name2" value="<?php echo $brand_name2O; ?>" placeholder="<?php echo $entry_brand_name2; ?>" id="input-email" class="form-control" />
                          <?php if ($error_brand_name2) { ?>
                          <div class="text-danger"><?php echo $error_brand_name2; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_akey2; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="akey2" value="<?php echo $akey2O; ?>"  id="input-telephone" class="form-control" />

                          <?php if ($error_akey2) { ?>
                          <div class="text-danger"><?php echo $error_akey2; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_side; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="side" value="<?php echo $sideO; ?>" placeholder="<?php echo  $entry_side; ?>" id="input-password" class="form-control" autocomplete="off" />
                          <?php if ($error_side) { ?>
                          <div class="text-danger"><?php echo $error_side; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_code; ?></label>
                        <div class="col-sm-10">
                          <input type="text" name="code" value="<?php echo $codeO; ?>" placeholder="<?php echo $entry_code; ?>" autocomplete="off" id="input-confirm" class="form-control" />
                          <?php if ($error_code) { ?>
                          <div class="text-danger"><?php echo $error_code; ?></div>
                          <?php  } ?>
                        </div>
                      </div>

















