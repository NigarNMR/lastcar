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
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-code"><?php echo $entry_brand_group; ?></label>
                <input type="text" name="filter_gob_name" value="<?php echo $filter_gob_name; ?>" placeholder="<?php echo $entry_brand_group; ?>" id="input-code" class="form-control" />
                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              </div>
            </div>
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
          <table class="table table-bordered table-hover">
            <thead>
            <tr>

              <td class="text-left"><?php echo $column_brand_group; ?></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr>

            </thead>
            <tbody>
            <?php if ($group_brands) { ?>
            <?php foreach ($group_brands as $group) { ?>
            <tr>

              <td class="text-left">
                <a data-toggle="collapse" href="#collapse_m_<?php  echo str_replace("\"", "", $group['gob_id']);?>"><?php  echo $group['gob_name']; ?></a>
                <div id="collapse_m_<?php  echo str_replace("\"", "", $group['gob_id']); ?>" class="collapse">

                <table border="1" width="100%" class="table table-bordered table-hover">

                  <tr>
                    <th><small><?php  echo $column_brand ?></small></th>
                    <th><?php  echo $column_parent_brand ?></th>
                    <th><?php  echo $column_percent ?></th>
                    <th><?php  echo $column_action ?></th>
                  </tr>
                  <?php if ($group['getBrands']) { ?>
                  <?php  foreach($group['getBrands'] as $brandd) { ?>

                  <tr>
                      <?php if ( $brandd['dict']=="TRUE") { ?>
                    <td class="text-left"><?php  echo $brandd['b__name']; ?> <i class="fa fa-archive" aria-hidden="true" title="Отображается,если бренд находиться в словаре"></i></td>
                    <?php } else { ?>
                    <td class="text-left"><?php  echo $brandd['b__name']; ?></td>
                    <?php }  ?>

                    <td>
                      <?php if ($brandd['b__parent_id']==0) { ?>
                      <i class="fa fa-star" aria-hidden="true" title="Установить основным" onclick="Al('<?php echo $brandd['b__id'];?>')"></i><?php echo " " . $brandd['b__parent_id']; ?>
                      <?php }  else { ?>
                      <i class="fa fa-star-o" aria-hidden="true" onclick="Al('<?php echo $brandd['b__id'];?>')"></i><?php echo " " . $brandd['b__parent_id']; ?>
                      <?php }?>
                    </td>
                   <td> <?php echo $brandd['smlr_percent']; ?></td>
                    <td class="text-center">
                      <div class="btn-group">


                        <input type="button" value="Удалить" id="btn4" onclick="deleteBrand('<?php echo $brandd['b__id'];?>','<?php echo $brandd['b__name'];?>');" class="btn btn-primary pull-right" />
                        <!--  <p data-placement="top" data-toggle="tooltip" title="View status history1"><button class="btn btn-primary btn-xs" data-title="View status history" onclick="editBrand( '11');" ><span class="fa fa-eye"></span></button></p></div>-->
                    </td>
                    <!-- <td><div class="btn-group"><a href="#form-view-product-status-historys" class="btn btn-primary btn-md" data-toggle="modal"><span class="fa fa-eye"></span></a></div></td></tr>
                   <!-- <td class="text-right"><a href="<?php echo $group['brands']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a href="<?php echo $group['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a><a href="<?php echo $group['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-primary"><i class="fa fa-trash-o"></i></a></td></tr>-->



                    <?php  }?>
                    <?php  }?>
                </table>

        </div>
        </td>

      </form>

      <td class="text-right">  <input type="button" value="добавить бренд" title="<?php echo $button_add; ?>" class="btn btn-primary" margin-right="2%" onclick="addBrand('<?php  echo str_replace("\"", "", $group['gob_id']);?>');">  <a href="<?php echo $group['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><?php echo $button_edit; ?></a> <a href="<?php echo $group['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-primary"><?php echo $button_delete; ?></a>
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


<script type="text/javascript"><!--

    $('.fa fa-star').css('cursor', 'pointer');

    // $('#order_product_historys').load('index.php?route=brands/brands/edit&token=<?php echo $token; ?>' +'&b_id=10852&b_name=3ton' );

    $('#button-filter').on('click', function() {
        url = 'index.php?route=brands/GroupBrands&token=<?php echo $token; ?>';

        var filter_gob_name = $('input[name=\'filter_gob_name\']').val();

        if (filter_gob_name) {
            url += '&filter_gob_name=' + encodeURIComponent(filter_gob_name);
        }

        location = url;
    });

    $('#button-search').on('click', function() {


        url = 'index.php?route=brands/GroupBrands&token=<?php echo $token; ?>';

        var filter_brand_name = $('input[name=\'filter_brand_name\']').val();
        // alert(filter_brand_name);
        if (filter_brand_name) {

            var url= 'index.php?route=brands/GroupBrands/SearchBrand&token=<?php echo $token; ?>&key='+ encodeURIComponent(filter_brand_name)+"&st=1";

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


    function Al(a) {

          var url= 'index.php?route=brands/GroupBrands/PostMainStatus&token=<?php echo $token; ?>&b__id='+a;
         location = url;
    }
    function editBrand(id,name) {


        var url= 'index.php?route=brands/brands/edit&token=<?php echo $token; ?>&b_id='+id+'&b_name='+name+'&modal=true'+'&edit=true';

        $('#form-view-product-status-historys').modal({show: true});

        $('#order_product_historys').delegate('.pagination a', 'click', function (e) {
            e.preventDefault();

            $('#order_product_historys').load(this.href);
        });

        $('#order_product_historys').load(url);



    }

    function addBrand(id) {


        var url= 'index.php?route=brands/brands/add&token=<?php echo $token; ?>&gob_id='+id+'&modal=true'+'&add=true';

        $('#form-view-product-status-historys').modal({show: true});

        $('#order_product_historys').delegate('.pagination a', 'click', function (e) {
            e.preventDefault();

            $('#order_product_historys').load(this.href);
        });

        $('#order_product_historys').load(url);

        //alert(id);



    }


    function deleteBrand(id) {


        var url= 'index.php?route=brands/brands/delete&token=<?php echo $token; ?>&b_id='+id+"&modal_group=true";
        location = url;
    }
    //--></script>

<script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: false
    });
    //--></script></div>



<?php echo $footer; ?>
