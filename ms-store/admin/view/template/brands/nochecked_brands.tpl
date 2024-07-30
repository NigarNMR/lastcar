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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list_no_checked; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-code"><?php echo $entry_brand_name; ?></label>
                <input type="text" name="filter_brand_name" value="<?php echo $entry_brand_name; ?>" placeholder="<?php echo $entry_brand_name; ?>" id="input-code" class="form-control" />
                <button type="button" id="button-search" class="btn btn-primary pull-right" ><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              </div>
            </div>
          </div>
          </div>
        </div>

        <form action="<?php echo $results; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>

                  <td class="text-left"><?php echo $column_brand; ?>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>

              </thead>
              <tbody>
                <?php if ($brands) { ?>
                <?php foreach ($brands as $brand) { ?>
                <tr>

                  <td class="text-left"><?php  echo  $brand[0]; ?></td>
                  <td class="text-right"><input type="button" value="добавить бренд в группу" title="<?php echo $button_add; ?>" class="btn btn-primary" margin-right="2%" onclick="addBrand('<?php  echo str_replace("\"", "",  $brand[0]);?>');"><a href="<?php echo $brand['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-primary"><i class="fa fa-trash-o"></i></a>
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


<div class="modal fade" id="form-view-product-status-historys" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align" id="heading-status-historys"><?php echo "Редактирование" ?></h4>
      </div>
      <div class="modal-body">
        <div id="order_product_historys">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div>

  </div>

</div>

<div class="modal fade" id="form-view-product-status-historys" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align" id="heading-status-historys"><?php echo "Редактирование" ?></h4>
      </div>
      <div class="modal-body">
        <div id="order_product_historys">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
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

      $('#button-search').on('click', function() {


          url = 'index.php?route=brands/GroupBrands&token=<?php echo $token; ?>';

          var filter_brand_name = $('input[name=\'filter_brand_name\']').val();
          // alert(filter_brand_name);
          if (filter_brand_name) {

              var url= 'index.php?route=brands/GroupBrands/SearchBrand&token=<?php echo $token; ?>&key='+ encodeURIComponent(filter_brand_name)+'&st=0';

              $('#form-view-product-status-historys').modal({show: true});

              $('#order_product_historys').delegate('.pagination a', 'click', function (e) {
                  e.preventDefault();

                  $('#order_product_historys').load(this.href);
              });

              $('#order_product_historys').load(url);

              //url += '&filter_gob_name=' + encodeURIComponent(filter_gob_name);
              //alert(url);
          }

          // location = url;*/
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


    function addBrand(id) {


        var url= 'index.php?route=brands/brands/noCheckedAdd&token=<?php echo $token; ?>&b_id='+ encodeURIComponent(id)+'&modal=true'+'&nochecked=true';

        $('#form-view-product-status-historys').modal({show: true});

        $('#order_product_historys').delegate('.pagination a', 'click', function (e) {
            e.preventDefault();

            $('#order_product_historys').load(this.href);
        });

        $('#order_product_historys').load(url);

       // alert(id);



    }
  </script>
  <script type="text/javascript"><!--
$
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>


<?php echo $footer; ?>
