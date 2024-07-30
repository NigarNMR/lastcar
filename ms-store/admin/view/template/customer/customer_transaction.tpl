<div class="container-responsive">
    <div class="row">
        <div class="dropdown col-md-2">
            <button class="btn btn-primary dropdown-toggle" id="page-tab" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width:100%">
                <?php echo $page_tab_value; ?>
                <span class="pull-right"><i class="caret"></i></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-left scrollable-menu page-tab-opt" aria-labelledby="dropdownMenuTab">
              <li><a href="<?php echo $page_tab_url[1]; ?>"><?php echo $page_tab_text[1]; ?></a></li>
              <li><a href="<?php echo $page_tab_url[2]; ?>"><?php echo $page_tab_text[2]; ?></a></li>
            </ul>
        </div>
        <div class="dropdown col-md-2 col-md-offset-1">
            <button class="btn btn-primary dropdown-toggle" id="page-limit" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width:100%">
                <?php echo $page_limit_value; ?>
                <span class="pull-right"><i class="caret"></i></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-left scrollable-menu page-limit-opt" aria-labelledby="dropdownMenuLimit">
              <li><a href="<?php echo $page_limit_url[1]; ?>"><?php echo $page_limit_text[1]; ?></a></li>
              <li><a href="<?php echo $page_limit_url[2]; ?>"><?php echo $page_limit_text[2]; ?></a></li>
              <li><a href="<?php echo $page_limit_url[3]; ?>"><?php echo $page_limit_text[3]; ?></a></li>
              <li><a href="<?php echo $page_limit_url[4]; ?>"><?php echo $page_limit_text[4]; ?></a></li>
              <li><a href="<?php echo $page_limit_url[5]; ?>"><?php echo $page_limit_text[5]; ?></a></li>
            </ul>
        </div>
        <div class="col-md-4 col-md-offset-1">
            <div class="input-group" style="width:100%">
                <input type="text" class="form-control" id="date-range" value="<?php echo $datepicker_value; ?>" placeholder="" />
            </div>
        </div>
        <div class="col-md-2">
            <span class="input-group-btn">
              <a href="javascript:void(0)" id="page-filter-btn" class="btn btn-primary" role="button" style="width:100%">
                  <?php echo $text_filter; ?>
              </a>
            </span>
        </div>
    </div>
</div>
<br/>
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
      <?php foreach ($transactions as $transaction) { ?>
      <tr>
        <td class="text-left"><?php echo $transaction['date_added']; ?></td>
        <td class="text-left"><?php echo $transaction['operation']; ?></td>
        <td class="text-left"><?php echo $transaction['product']; ?></td>
        <td class="text-left"><?php echo $transaction['description']; ?></td>
        <td class="text-right"><?php echo $transaction['amount']; ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td class="text-right" colspan="4"><b><?php echo $text_balance_total; ?></b></td>
        <td class="text-right"><?php echo $balance; ?></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
<div class="buttons clearfix" id="transaction-export">
  <div class="pull-right"><a href="<?php echo $export_url; ?>" class="btn btn-primary"><?php echo $button_export; ?></a></div>
</div>
<script type="text/javascript">
$(function() {
    $('#date-range').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        },
        autoApply: true
    });
});
$('#page-filter-btn').on('click', function() {
    dateArray = $('#date-range').val().split(' - ');
    
    url = 'index.php?route=customer/customer/transaction';
    url += '&token=<?php echo $token; ?>';
    url += '&customer_id=<?php echo $customer_id; ?>';
    url += '&tab=<?php echo $page_tab_code; ?>';
    url += '&page-limit=<?php echo $page_limit_value; ?>';
    url += '&date-start=' + dateArray[0];
    url += '&date-end=' + dateArray[1];
    
    $('#transaction').load(url);
});
</script>
