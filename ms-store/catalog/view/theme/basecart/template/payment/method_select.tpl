<?php echo $header; ?>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <h1><?php echo $heading_title; ?></h1>
      <br/>
      <h4><?php echo $method_select; ?></h4>
      <div class="pull-left">
        <a href="<?php echo $url_cash; ?>" title="<?php echo $button_payment_cash; ?>" class="btn btn-lg btn-primary">
          <div class="pull-left"><i class="fa fa-money"></i></div>
          &nbsp;<?php echo $method_cash; ?>
          <br/>
          <small><?php echo $method_cash_info; ?></small>
        </a>
        <a href="<?php echo $url_online; ?>" title="<?php echo $button_payment_online; ?>" class="btn btn-lg btn-primary">
          <div class="pull-left"><i class="fa fa-credit-card"></i></div>
          &nbsp;<?php echo $method_online; ?>
          <br/>
          <small><?php echo $method_online_info; ?></small>
        </a>
      </div>
      <div class="clearfix"></div>
      <hr/>
      <input type="checkbox" id="agreement-check"/>&nbsp;<?php echo $agreement; ?>
      <a href="javascript:void(0)">"<?php echo $agreement_doc; ?>"</a>
    </div>
    <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>