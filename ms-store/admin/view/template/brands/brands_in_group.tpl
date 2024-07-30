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

        <form action="<?php echo $results; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>

                  <td class="text-left"><?php if ($sort == 'brand_name1') { ?>
                    <a href="<?php echo $sort_brand_name1; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_brand; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_brand_name1; ?>"><?php echo $column_brand; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'brand_name1') { ?>
                    <a href="<?php echo $sort_brand_name1; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_brand_group; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_brand_name1; ?>"><?php echo $column_brand_group; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'brand_name1') { ?>
                    <a href="<?php echo $sort_brand_name1; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_parent_brand; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_brand_name1; ?>"><?php echo $column_parent_brand; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'brand_name1') { ?>
                    <a href="<?php echo $sort_brand_name1; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_brand_name1; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'brand_name1') { ?>
                    <a href="<?php echo $sort_brand_name1; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_brand_name1; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>


                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>

              </thead>
              <tbody>
                <?php if ($brands) { ?>
                <?php foreach ($brands as $brand) { ?>
                <tr>

                  <td class="text-left"><?php  echo  $brand[0]; ?></td>
                  <td class="text-left"><?php  echo  $brand[2]; ?></td>
                  <td class="text-left"><?php  echo  $brand[4]; ?></td>
                  <td class="text-left"><?php  echo  $brand[5]; ?></td>
                  <td class="text-left"><?php  echo  $brand[6]; ?></td>
                  <td class="text-right"><a href="<?php echo $brand['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a><a href="<?php echo $brand['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-primary"><i class="fa fa-trash-o"></i></a>
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

        <!--  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div> -->
         <!-- <div class="col-sm-6 text-right"><?php echo $results; ?></div> -->
        </div>
      </div>
    </div>
  </div>


<script>
    $(document).ready(function(){
        //при нажатию на любую кнопку, имеющую класс .btn
        $(".btn").click(function() {
            //открыть модальное окно с id="myModal"
            $("form-view-product-status-history").modal('show');
        });
    });
</script>

  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=brands/brands&token=<?php echo $token; ?>';

    var filter_gob_name = $('input[name=\'filter_gob_name\']').val();

    if (filter_gob_name) {
        url += '&filter_gob_name=' + encodeURIComponent(filter_gob_name);
    }

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
$
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>

<script>
    function viewProductStatusHistory() {

       var a = 5;
    }
</script>

<?php echo $footer; ?>
