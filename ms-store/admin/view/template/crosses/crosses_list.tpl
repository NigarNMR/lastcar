<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
      </div>
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
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-brand1"><?php echo $entry_brand_name1; ?></label>
                <input type="text" name="filter_brand1" value="<?php echo $filter_brand1; ?>" placeholder="<?php echo $entry_brand_name1; ?>" id="input-brand1" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-akey1"><?php echo $entry_akey1; ?></label>
                <input type="text" name="filter_akey1" value="<?php echo $filter_akey1; ?>" placeholder="<?php echo $entry_akey1; ?>" id="input-akey1" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-brand2"><?php echo $entry_brand_name2; ?></label>
                <input type="text" name="filter_brand2" value="<?php echo $filter_brand2; ?>" placeholder="<?php echo $entry_brand_name2; ?>" id="input-brand2" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-akey2"><?php echo $entry_akey2; ?></label>
                <input type="text" name="filter_akey2" value="<?php echo $filter_akey2; ?>" placeholder="<?php echo $entry_akey2; ?>" id="input-akey2" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_side; ?></label>
                <input type="text" name="filter_side" value="<?php echo $filter_side; ?>" placeholder="<?php echo $entry_side; ?>" id="input-side" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-code"><?php echo $entry_code; ?></label>
                <input type="text" name="filter_code" value="<?php echo $filter_code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>

                  <td class="text-left"><?php if ($sort == 'brand_name1') { ?>
                    <a href="<?php echo $sort_brand_name1; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_brand_name1; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_brand_name1; ?>"><?php echo $column_brand_name1; ?></a>
                    <?php } ?></td>

                  <td class="text-left"><?php if ($sort == 'akey1') { ?>
                    <a href="<?php echo $sort_akey1; ?>" class="<?php echo strtolower($order); ?>"><?php echo  $column_akey1; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_akey1; ?>"><?php echo $column_akey1; ?></a>
                    <?php } ?></td>


                  <td class="text-left"><?php if ($sort == 'brand_name2') { ?>
                    <a href="<?php echo $sort_brand_name2; ?>" class="<?php echo strtolower($order); ?>"><?php echo  $column_brand_name2; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_brand_name2; ?>"><?php echo $column_brand_name2; ?></a>
                    <?php } ?></td>

                  <td class="text-left"><?php if ($sort == 'akey2') { ?>
                    <a href="<?php echo $sort_akey2; ?>" class="<?php echo strtolower($order); ?>"><?php echo  $column_akey2; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_akey2; ?>"><?php echo $column_akey2; ?></a>
                    <?php } ?></td>

                  <td class="text-left"><?php if ($sort == 'side') { ?>
                    <a href="<?php echo $sort_side; ?>" class="<?php echo strtolower($order); ?>"><?php echo  $column_side; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_side; ?>"><?php echo $column_side; ?></a>
                    <?php } ?></td>

                  <!-- добавить  сортировку по балансу -->
                  <td class="text-left"><?php if ($sort == 'code') { ?>
                    <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo  $column_code; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                    <?php } ?></td>

                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($crosses) { ?>
                <?php foreach ($crosses as $cross) { ?>
                <tr>

                  <td class="text-left"><?php  echo $cross['brand_name1']; ?></td>
                  <td class="text-left"><?php  echo $cross['akey1']; ?></td>
                  <td class="text-left"><?php  echo $cross['brand_name2']; ?></td>
                  <td class="text-left"><?php  echo $cross['akey2']; ?></td>
                  <td class="text-left"><?php  echo $cross['side']; ?></td>
                  <td class="text-left"><?php  echo $cross['code']; ?></td>

                  <td class="text-right"><a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> <a href="<?php echo $cross['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a><a href="<?php echo $cross['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-primary"><i class="fa fa-trash-o"></i></a>
                   <!-- <button type="button" value="<?php echo $order['order_id']; ?>" id="button-delete<?php echo $cross['delete']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>--></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">

          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=crosses/crosses&token=<?php echo $token; ?>';

    var filter_akey1 = $('input[name=\'filter_akey1\']').val();

    if (filter_akey1) {
        url += '&filter_akey1=' + encodeURIComponent(filter_akey1);
    }

    var filter_brand1 = $('input[name=\'filter_brand1\']').val();

    if (filter_brand1) {
        url += '&filter_brand1=' + filter_brand1;
    }

    var filter_brand2 = $('input[name=\'filter_brand2\']').val();

    if (filter_brand2) {
        url += '&filter_brand2=' + encodeURIComponent(filter_brand2);
    }

    var filter_akey2 = $('input[name=\'filter_akey2\']').val();

    if (filter_akey2) {
        url += '&filter_akey2=' + encodeURIComponent(filter_akey2);
    }

    var filter_side = $('input[name=\'filter_side\']').val();

    if (filter_side) {
        url += '&filter_side=' + encodeURIComponent(filter_side);
    }

    var filter_code = $('input[name=\'filter_code\']').val();

    if (filter_code) {
        url += '&filter_code=' + encodeURIComponent(filter_code);
    }

/*
	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	var filter_approved = $('select[name=\'filter_approved\']').val();

	if (filter_approved != '*') {
		url += '&filter_approved=' + encodeURIComponent(filter_approved);
	}

	var filter_ip = $('input[name=\'filter_ip\']').val();

	if (filter_ip) {
		url += '&filter_ip=' + encodeURIComponent(filter_ip);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}*/

	location = url;
});
//--></script>
  <script type="text/javascript">
    $('#btn-refresh').on('click', function(e) {
        e.preventDefault();
        url = '<?php echo $refresh; ?>';
        url = url.replace(/&amp;/g, '&');
        $('input[name=\'selected[]\']').each(function(index, obj) {
            if ($(obj).prop('checked')) {
                url += '&selected[]=' + $(obj).val();
            }
        });
        location = url;
    });
  </script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_email\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['email'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_email\']').val(item['label']);
	}
});
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
