<?php echo $header; ?>
<link type="text/css" href="catalog/view/theme/default/stylesheet/chat.css" rel="stylesheet" media="screen" />
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset id="msg">
          <div class="form-group required">
            <div class="col-sm-12">
              <textarea height="350px" name="message" placeholder="<?php echo $entry_message; ?>" id="input-message" class="form-control jqte-test"></textarea>
            </div>
          </div>
					<div class="buttons">
						<div class="pull-right">
							<button type="button" id="button-send" class="btn btn-primary" ><?php echo $button_send; ?></button>
						</div>
					</div>
				</fieldset>
				<div id="view-message"></div>
			</form>
      <?php echo $content_bottom; ?>
		</div>
    <?php echo $column_right; ?>
	</div>
</div>
<script type="text/javascript"><!--
$('#button-send').on('click', function() {
	var editor_messgae = $('.jqte .jqte_editor').html().trim();
	$.ajax({
		url: 'index.php?route=account/message/sendMessage',
		dataType: 'json',
		method: 'post',
		data: 'message='+ editor_messgae,
		beforeSend: function() {
			$('#button-send').button('loading');
		},
		complete: function() {
			$('#button-send').button('reset');
		},
		success: function(json) {
			$('.alert').remove();
			
			
			if(json['error_warning']) {
					$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+ json['error_warning'] +'</div>');
			}
			
			if(json['success']) {
					$('.breadcrumb').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+ json['success'] +'</div>');
					$('.jqte .jqte_editor').html('');
					viewMessage();
			}
			
			if(json['redirect']) {
					location = json['redirect'];
			}
		}
	});
});
//--></script> 

<script type="text/javascript"><!--
$(document).ready(function() {
	viewMessage();
	setInterval(function(){ 
		viewMessage();
	}, 5000);	
});
function viewMessage(){
$('#view-message').load('index.php?route=account/message/viewMessage');
}
//--></script>
<link type="text/css" rel="stylesheet" href="catalog/view/javascript/jquery/editor/jquery-te-1.4.0.css">
<script type="text/javascript" src="catalog/view/javascript/jquery/editor/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<script>
	$('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$(".status").click(function()
	{
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});
</script>
<style>
.jqte{
	height:200px;
}
</style>
<?php echo $footer; ?>