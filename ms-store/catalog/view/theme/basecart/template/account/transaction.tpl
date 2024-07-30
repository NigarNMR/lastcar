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
      <p><?php echo $text_balance_current; ?> <b><?php echo $data_balance_current; ?></b>.</p>
      <p><?php echo $text_balance_reserve; ?> <b><?php echo $data_balance_reserve; ?></b>.</p>
      <p><?php echo $text_balance_limit; ?> <b><?php echo $data_balance_limit; ?></b>.</p>
      <p><?php echo $text_balance_available; ?> <b><?php echo $data_balance_available; ?></b>.</p>
      <p><?php echo $text_balance_limit_available; ?> <b><?php echo $data_balance_limit_available; ?></b>.</p>
      <a class="btn btn-primary" type="button" href="<?php echo $payment_methods_link; ?>"><?php echo 'Пополнить счет'; ?></a>
      <hr/>
      <div class="container-responsive">
        <div class="row">
            <div class="dropdown col-md-3">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?php echo $page_limit_description . ' ' . $page_limit_value; ?> 
                <span class="caret"></span>
                </button>
                <ul class="dropdown-menu scrollable-menu" aria-labelledby="dropdownMenuLimit">
                  <li><a href="<?php echo $page_limit_url[1]; ?>"><?php echo $page_limit_text[1]; ?></a></li>
                  <li><a href="<?php echo $page_limit_url[2]; ?>"><?php echo $page_limit_text[2]; ?></a></li>
                  <li><a href="<?php echo $page_limit_url[3]; ?>"><?php echo $page_limit_text[3]; ?></a></li>
                  <li><a href="<?php echo $page_limit_url[4]; ?>"><?php echo $page_limit_text[4]; ?></a></li>
                  <li><a href="<?php echo $page_limit_url[5]; ?>"><?php echo $page_limit_text[5]; ?></a></li>
                </ul>
            </div>
            <div class="col-md-8 col-md-offset-1">
                <div class="input-group">
                  <input type="text" class="form-control" id="daterange" value="<?php echo $date_filter_value; ?>" placeholder="<?php echo $date_filter_placeholder; ?>" />
                  <span class="input-group-btn">
                    <a href="javascript:void(0)" onclick="transaction.dateFilter('<?php echo $page_limit_value;?>','<?php echo $tab_active; ?>')" class="btn btn-primary" role="button"><?php echo $date_filter_description; ?></a>
                  </span>
                </div>
            </div>
        </div>
      </div>
      <br/>
      <div class="container-responsive">
          <ul class="nav nav-tabs">
            <li <?php echo $tabs['balance_current_class']; ?>>
                <a href="<?php echo $tabs['balance_current_url']; ?>">
                  <?php echo $text_balance_current; ?>
                </a>
            </li>
            <li <?php echo $tabs['balance_reserve_class']; ?>>
                <a href="<?php echo $tabs['balance_reserve_url']; ?>">
                  <?php echo $text_balance_reserve; ?>
                </a>
            </li>
          </ul>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <td class="text-left"><?php echo $column_operation; ?></td>
              <td class="text-left"><?php echo $column_product; ?></td>
              <td class="text-left"><?php echo $column_description; ?></td>
              <td class="text-right"><?php echo $column_amount; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($transactions) { ?>
            <?php foreach ($transactions  as $transaction) { ?>
            <tr>
              <td class="text-left"><?php echo $transaction['date_added']; ?></td>
              <td class="text-left"><?php echo $transaction['operation']; ?></td>
              <td class="text-left"><?php echo $transaction['product']; ?></td>
              <td class="text-left"><?php echo $transaction['description']; ?></td>
              <td class="text-right"><?php echo $transaction['amount']; ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="5"><?php echo $text_empty; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="javascript:void(0)" onclick="transaction.export('<?php echo $date_filter_start; ?>','<?php echo $date_filter_end; ?>')" class="btn btn-primary"><?php echo $button_export; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
$(function() {
    $('#daterange').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        },
        autoApply: true
    });
});
</script>
<?php echo $footer; ?>
