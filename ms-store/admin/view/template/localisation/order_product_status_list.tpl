<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <!--
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-order-product-status').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      -->
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div id="error-message-placeholder"></div>
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
        <!--<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-order-product-status">-->
        <form action="?" method="post" enctype="multipart/form-data" id="form-order-product-status">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><!--<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />--></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($order_product_statuses) { ?>
                <?php foreach ($order_product_statuses as $order_product_status) { ?>
                    <tr>
                      <!--
                      <td class="text-center"><?php if (in_array($order_product_status['order_product_status_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $order_product_status['order_product_status_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $order_product_status['order_product_status_id']; ?>" />
                        <?php } ?>
                      </td>
                      -->
                      <td class='text-center'>
                        <a href="javascript:void(0)" onclick="descendantAreaToggle(<?php echo $order_product_status['order_product_status_id']; ?>)" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary" id="descendant-toggle-<?php echo $order_product_status['order_product_status_id']; ?>" <?php if (empty($order_product_status['descendants_data'])) { echo "disabled"; } ?>><i class="fa fa-chevron-down"></i></a>
                      </td>
                      <td class="text-left"><?php echo $order_product_status['name']; ?></td>
                      <td class="text-right">
                        <a href="javascript:void(0)" onclick="descendantModalShow('<?php echo $order_product_status['order_product_status_id']; ?>')" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
                        <a href="<?php echo $order_product_status['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                    </tr>
                    <?php if (!empty($order_product_status['descendants_data'])) { ?>
                    <tr id="descendant-area-<?php echo $order_product_status['order_product_status_id']; ?>" style="display:none;">
                      <td colspan="3" class="text-center">
                        <table class="table table-bordered">
                          <tbody>
                            <?php foreach ($order_product_status['descendants_data'] as $descendant_data) { ?>
                            <tr>
                                <td class="text-left"><?php echo $descendant_data['name']; ?>
                                <td class="text-right">
                                  <a href="<?php echo $descendant_data['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                  <!--<a href="javascript:void(0)" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-toggle-on"></i></a>-->
                                  <a href="javascript:void(0)" onclick="descendantStatusDelete(<?php echo $descendant_data['order_product_status_id']; ?>)" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-primary"><i class="fa fa-trash-o"></i></a>
                            <?php } ?>
                        </table>
                      </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
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
</div>

<div class="modal fade" id="form-add-order-product-status" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove" aria-hidden="true"></span></button>
          <h4 class="modal-title custom_align" id="heading-add-order-product-status"><?php echo $text_add_order_product_status; ?></h4>
        </div>
        <div class="modal-body">
          <label class="control-label"><?php echo $entry_status_name; ?></label>
          <div class="form-group required">
            <?php foreach ($languages as $language) { ?>
                <div class="input-group">
                  <span class="input-group-addon">
                    <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                  </span>
                  <input type="text" id="form-status-language-id-<?php echo $language['language_id']; ?>" name="order_status[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($order_status[$language['language_id']]) ? $order_status[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_status_name; ?>" class="form-control" />
                </div>
                <br/>
            <?php } ?>
            <input type="text" id="form-status-parent-id" name="status_parent_id" style="display: none;" placeholder="<?php //echo $entry_name; ?>" class="form-control" />
          </div>
          <hr/>
          <div id="message-add-order-product-status"></div>
        </div>
        <div class="modal-footer">
          <button id="button-add-order-product-status" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_add_order_product_status; ?></button>
        </div>
      </div>
    </div>
</div>

<script>
    $('#button-add-order-product-status').on('click', function() {
        // Тут выдрать данные из формы
        partitioning_data = {};
        partitioning_data.status_names = {};
        $("input[name*='order_status']").each(function(index) {
            lang_id = $(this).attr('id').substr(24, $(this).attr('id').length);
            partitioning_data.status_names[lang_id] = $(this).val();
        });
        partitioning_data.status_parent = $('#form-status-parent-id').val();
        
        // Тело Ajax запроса
        $.ajax({
            type: 'post',
            url: 'index.php?route=localisation/order_product_status/adddescendant&token=<?php echo $token; ?>',
            data: {partition_data:JSON.stringify(partitioning_data)},
            dataType: 'json',
            beforeSend: function () {
              $('#button-add-order-product-status').button('loading');
            },
            complete: function () {
              $('#button-add-order-product-status').button('reset');
            },
            success: function (json) {
                switch (json['error_code']) {
                    case '0':
                        $('#message-add-order-product-status').html('<div class="alert alert-success" role="alert"><?php echo $error_partition_message_success; ?></div>');
                        document.location.reload();
                        break;
                    case '1':
                        $('#message-add-order-product-status').html('<div class="alert alert-danger" role="alert"><?php echo $error_partition_message_1; ?></div>');
                        break;
                    case '2':
                        $('#message-add-order-product-status').html('<div class="alert alert-warning" role="alert"><?php echo $error_partition_message_2; ?></div>');
                        break;
                    case '3':
                        $('#message-add-order-product-status').html('<div class="alert alert-warning" role="alert"><?php echo $error_partition_message_3; ?></div>');
                        break;
                    default:
                        $('#message-add-order-product-status').html('<div class="alert alert-warning" role="alert"><?php echo $error_partition_message_default; ?></div>');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    
    function descendantStatusDelete(status_id) {
        $.ajax({
            type: 'post',
            url: 'index.php?route=localisation/order_product_status/deleteDescendant&token=<?php echo $token; ?>',
            data: 'descendant_id=' + status_id,
            dataType: 'json',
            success: function (json) {
                switch (json['error_code']) {
                    case '0':
                        document.location.reload();
                        break;
                    case '1':
                        $('#error-message-placeholder').html('<div class="alert alert-danger">' +
                            '<i class="fa fa-exclamation-circle"></i> <?php echo $error_delete_descendant_1; ?>' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '</div>');
                        break;
                    default:
                        $('#error-message-placeholder').html('<div class="alert alert-danger">' +
                            '<i class="fa fa-exclamation-circle"></i> <?php echo $error_partition_message_default; ?>' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '</div>');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
              alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
    
    function descendantModalShow(parent_id) {
        switch (parent_id) {
            <?php foreach ($order_product_statuses as $order_product_status) { ?>
                case '<?php echo $order_product_status["order_product_status_id"]; ?>':
                    descendant_modal_header = '<?php echo $text_add_order_product_status; ?>' + ' ' + '"<?php echo $order_product_status["name"]; ?>"';
                    $('#heading-add-order-product-status').text(descendant_modal_header);
                    break;
            <?php } ?>
            default:
                break;
        }
        
        $('#form-status-parent-id').val(parent_id);
        
        $('#form-add-order-product-status').modal({show: true});
    }
    
    /**
     * Раскрытие области дочерних статусов
     * @param {type} area_id Идентификатор области
     * @returns {undefined}
     */
    function descendantAreaToggle(area_id) {
        // Проверка элемента на существование
        if ($('#descendant-area-' + area_id).length) {
            // Изменение параметра видимости
            $('#descendant-area-' + area_id).toggle();
            // Изменение иконки
            if($('#descendant-area-' + area_id).css('display') === 'none') {
                $('#descendant-toggle-' + area_id).html('<i class="fa fa-chevron-down"></i>');
            } else {
                $('#descendant-toggle-' + area_id).html('<i class="fa fa-chevron-up"></i>');
            }
        }
    }
</script>
<?php echo $footer; ?> 