
<div id="content" >

    <form action="<?php echo $results; ?>" method="post" enctype="multipart/form-data" id="form-customer">
      <div class="table-responsive" >
        <table class="table table-bordered" >
          <thead>
          <tr>
            <td class="text-right"><?php echo $column_brand; ?></td>
            <td class="text-right"><?php echo $column_brand_group; ?></td>
          </tr>

          </thead>
          <tbody>

          <?php if ($dictionaries) { ?>
          <?php foreach ($dictionaries as $dict) { ?>
          <tr>
            <td class="text-left"><?php  echo ($dict['desc_brand'] . " " . '(' .$dict['repl_brand']) .')';?><!-- <button type="button" value="<?php echo $order['order_id']; ?>" id="button-delete<?php echo $cross['delete']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>--></td>
            <td class="text-left">
              <input type="button" value="добавить бренд в группу" title="<?php echo $button_add; ?>" class="btn btn-primary" margin-right="2%" onclick="addBrand('<?php  echo str_replace("\"", "",   $dict['desc_brand']);?>');">
              <a data-toggle="collapse" href="#collapse_m_<?php  echo str_replace("\"", "", $dict['gob_id']);?>"><?php  echo $dict['gob_name']; ?></a>
              <div id="collapse_m_<?php  echo str_replace("\"", "", $dict['gob_id']); ?>" class="collapse">
              <table border="1" width="100%" class="table table-bordered table-hover">
                <tr>
                  <th><small><?php  echo $column_brand ?></small></th>
                  <th><?php  echo "Роль" ?></th>
                </tr>
                <?php if ($dict['brands']) { ?>
                <?php  foreach($dict['brands'] as $brandd) { ?>
                <tr>
                  <td>
                    <?php  ?>
                    <?php  echo $brandd['b__name'] ; ?>

                  </td>

                  <td>
                    <i class="fa fa-star" aria-hidden="true" onclick="Al('<?php echo $brandd['b__id'];?>')"></i> <?php echo $brandd['b__parent_id']; ?>
                  </td>

                </tr>

                  <!-- <td><div class="btn-group"><a href="#form-view-product-status-historys" class="btn btn-primary btn-md" data-toggle="modal"><span class="fa fa-eye"></span></a></div></td></tr>
                 <!-- <td class="text-right"><a href="<?php echo $group['brands']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a href="<?php echo $group['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a><a href="<?php echo $group['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-primary"><i class="fa fa-trash-o"></i></a></td></tr>-->

                  <?php  }?>
                  <?php  }?>
              </table>
      </div>


    </form>

    </tr>
    <?php } ?>

    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="8"><?php echo $text_no_result; ?></td>
    </tr>
    <?php } ?>

    </tbody>
    </table>
  </div>
  </form>
  <div class="row">
    <div id="product_summary"></div>
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

    function Al(a) {

        var url= 'index.php?route=brands/GroupBrands/PostMainStatus&token=<?php echo $token; ?>&b__id='+a+"&al=true";
        location = url;
    }

    function addBrand(id) {


        var url= 'index.php?route=brands/brands/noCheckedAdd&token=<?php echo $token; ?>&b_id='+id+'&modal=true'+'&nochecked=true'+'&dict=true';

        $('#form-view-product-status-historys').modal({show: true});

        $('#order_product_historys').delegate('.pagination a', 'click', function (e) {
            e.preventDefault();

            $('#order_product_historys').load(this.href);
        });

        $('#order_product_historys').load(url);

        // alert(id);



    }

    // $('#order_product_historys').load('index.php?route=brands/brands/edit&token=<?php echo $token; ?>' +'&b_id=10852&b_name=3ton' );

    //--></script>

<script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: false
    });
    //--></script></div>



<?php echo $footer; ?>





















