<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

  <div class="page-header">
    <div class="container-fluid">
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list_dictionaries; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-code"><?php echo $entry_brand_group; ?></label>
                <form action="<?php echo $get; ?>" method="post">
                  <p><select class="form-control" id="dictionary" name="WS" >
                      <option value='ARMTEK'>ARMTEK</option>
                      <option value='EMEX'>EMEX</option>
                      <option value='TECDOC'>TECDOC</option>
                    </select></p>

                  <p><input type="checkbox" name="gr" id="gr" checked >Показывать бренды в группах<Br>
                </form>
              </div>
            </div>
            </div>

          </div>
          </div>
        </div>
        <form action="<?php echo $results; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">

        </form>

          <div id="product_summary" width="50%"></div>




      </div>
    </div>
  </div>

<div class="modal fade" id="form-view-product-status-historys" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align" id="heading-status-historys"><?php echo "Добавить в группу" ?></h4>
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

<?php echo $footer; ?>
  <script type="text/javascript"><!--


      $('#dictionary,#gr').on('change', function() {
          var gr = $('#gr').is(':checked');
          var WS = $('#dictionary').val();
          $.ajax({
              url: 'index.php?route=brands/AliasDict/DictContent&token=<?php echo $token; ?>&ws='+ WS+'&gr='+gr ,
              dataType: 'html',

          success: function(htmlText) {

              $('#product_summary').html(htmlText);
              return false;

          },

          error: function(xhr, ajaxOptions, thrownError) {

              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

          }

      });



      });

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

     // $('#order_product_historys').load('index.php?route=brands/brands/edit&token=<?php echo $token; ?>' +'&b_id=10852&b_name=3ton' );

//--></script>

  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>






