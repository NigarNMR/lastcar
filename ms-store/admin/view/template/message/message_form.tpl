<?php echo $header; ?>
<link type="text/css" href="view/stylesheet/chat.css" rel="stylesheet" media="screen" />
<?php echo $column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-envelope"></i> <?php echo $text_add_message; ?></h3>
            </div>
            <div class="panel-body">
                <fieldset>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_message; ?></label>
                            <div class="col-sm-10">
                                <textarea type="text" name="message" placeholder="<?php echo $entry_message; ?>" id="input-message" class="form-control" ></textarea>
                            </div>
                        </div>
                        <div class="buttons">
                            <div class="pull-right">
                                <button type="button" id="button-send" class="btn btn-primary" ><?php echo $button_send; ?></button>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div id="view-message"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
    $('#button-send').on('click', function () {
            var message = $('.note-editable').html().trim();
            $.ajax({
                url: 'index.php?route=message/message/sendMessage&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
                dataType: 'json',
                method: 'post',
                data: 'message=' + message,
                beforeSend: function () {
                    $('#button-send').button('loading');
                },
                complete: function () {
                    $('#button-send').button('reset');
                },
                success: function (json) {
                    $('.alert').remove();

                    if (json['error_warning']) {
                        $('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error_warning'] + '</div>');
                    }

                    if (json['success']) {
                        $('.breadcrumb').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                        $('.note-editable').html('');

                        viewMessage();
                    }
                }
            });
        });
    //--></script> 
    <script type="text/javascript"><!--
    $(document).ready(function () {
            viewMessage();
            setInterval(function () {
                viewMessage();
            }, 5000);
        });
        function viewMessage() {
            $('#view-message').load('index.php?route=message/message/viewMessage&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');
        }
    //--></script> 
    <script type="text/javascript"><!--
    $('#input-message').summernote({
            height: 300
        });
    //--></script> 
    <style>
        .note-editable{
            height: 250px !important;
        }
    </style></div>
<?php echo $footer; ?>