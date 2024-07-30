<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit">Отправить форму</button>
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
                        <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_b_name; ?></label>
                        <div class="col-sm-10">
                          <?php if (isset($b_nameO)) { ?>
                          <input type="text" name="b_name" value="<?php echo $b_nameO; ?>" placeholder="<?php echo $entry_b_name; ?>" id="input-firstname" class="form-control" />
                          <?php } else { ?>
                          <input type="text" name="b_name"  placeholder="<?php echo $entry_b_name; ?>" id="input-firstname" class="form-control" />
                          <?php } ?>
                          <?php if ($error_gob_name) { ?>
                          <div class="text-danger"><?php echo $error_b_name; ?></div>
                          <?php } ?>
                        </div>
                      </div>






















