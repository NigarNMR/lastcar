<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" class="btn btn-primary" onclick="GetSimilarity();" >Рассчитать соответствия</button>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list_similarity; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-5">
              <div class="form-group">
                <label class="control-label" for="input-brand1"><?php echo $from; ?></label>
                <input type="text" name="filter_brand1"   id="input-brand1" class="form-control" />
              </div>
            </div>
             <div class="col-sm-5">
              <div class="form-group">
                <label class="control-label" for="input-akey1"><?php echo $before; ?></label>
                <input type="text" name="filter_akey1"   id="input-akey1" class="form-control" />
                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              </div>

             </div>

        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php echo  $column_brand; ?></td>
                  <td class="text-left"><?php echo  $column_brand_group; ?></td>
                  <td class="text-left"><?php echo  $column_main; ?></td>
                  <td class="text-right"><?php echo "Скрыть"; ?></td>
                  <td class="text-left"><?php echo  $column_percent; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody id="t_brands">
                <?php if ($brands) { ?>
                <?php foreach ($brands as $brand) { ?>
                <tr>

                   <?php if ( $brand[8]=="TRUE") { ?>
                  <td class="text-left"><?php  echo $brand[0]; ?> <i class="fa fa-archive" aria-hidden="true" title="Отображается,если бренд находиться в словаре"></i></td>
                  <?php } else { ?>
                  <td class="text-left"><?php  echo $brand[0]; ?></td>
                  <?php }  ?>
                  <td  class="text-left"><?php  echo $brand[2]; ?></td>
                  <td class="text-left"><?php  echo $brand[4]; ?></td>
                  <td class="text-left"><input type="checkbox" class="checkbox1"  onclick="hide(<?php  echo $brand[3]; ?>)"/></td>
                  <td id="<?php  echo $brand[3]; ?>" class="text-left"><?php  echo $brand[6]; ?></td>
                  <td>
                    <input type="button"  value="Редактировать" title="<?php echo $button_add; ?>" class="btn btn-primary"  onclick="addBrand('<?php  echo str_replace("\"", "",  $brand[0]);?>');">
                    <input type="button"  value="Подходящие группы" title="<?php echo $button_add; ?>" class="btn btn-primary"  onclick="SimilarityGroup('<?php  echo str_replace("\"", "",  $brand[0]);?>');">
                    <a href="<?php echo $brand['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-primary">Удалить</a>
                  </td>
                </tr>
                   <!-- <button type="button" value="<?php echo $order['order_id']; ?>" id="button-delete<?php echo $cross['delete']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>-->
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

          <div class="modal fade" id="form-view-product-status-historys" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
                <h4 class="modal-title custom_align" id="heading-status-historys"><?php echo "Группы со схожими названиями" ?></h4>
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
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--



      function GetSimilarity()
      {
          var url= 'index.php?route=brands/similarity/GetSimilarity&token=<?php echo $token; ?>';
          location = url;
      }


      function hide(obj) {
          //alert(obj);
          if ($(".checkbox1").is(':checked')) {
              document.getElementById(obj).style.visibility = "hidden";
          }

          else {
              document.getElementById(obj).style.visibility = "visible";
          }

      }
      function SimilarityGroup(id) {


          var url= 'index.php?route=brands/similarity/SimilarityGroup&token=<?php echo $token; ?>&b_name='+encodeURIComponent(id)+'&modal=true';

          $('#form-view-product-status-historys').modal({show: true});

          $('#order_product_historys').delegate('.pagination a', 'click', function (e) {
              e.preventDefault();

              $('#order_product_historys').load(this.href);
          });

          $('#order_product_historys').load(url);

          // alert(id);



      }



      function addBrand(id) {


          var url= 'index.php?route=brands/brands/noCheckedAdd&token=<?php echo $token; ?>&b_id='+id+'&modal=true'+'&nochecked=true';

          $('#form-view-product-status-historys').modal({show: true});

          $('#order_product_historys').delegate('.pagination a', 'click', function (e) {
              e.preventDefault();

              $('#order_product_historys').load(this.href);
          });

          $('#order_product_historys').load(url);

          // alert(id);



      }


$('#button-filter').on('click', function() {
	url = 'index.php?route=brands/similarity&token=<?php echo $token; ?>';

    var filter_akey1 = $('input[name=\'filter_akey1\']').val();
 //   if (filter_akey1=" ") filter_akey1=100;}
    if (filter_akey1) {
        url += '&before=' + encodeURIComponent(filter_akey1);
    }

    var filter_brand1 = $('input[name=\'filter_brand1\']').val();
    //if (filter_brand1=" "){ filter_brand1=0;}

    if (filter_brand1) {
        url += '&from=' + filter_brand1;
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
