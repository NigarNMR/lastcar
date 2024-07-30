<fieldset>
		<legend><?php echo $customer_name ?></legend>
		<?php if($messages) { ?>
		<?php foreach($messages as $message) {	 ?>
		<?php if($message['sender']=='user'){ ?>
			<div class="chat-box-left">
				<?php echo $message['message'] ?>
			</div>
			<div class="chat-box-name-left"><?php echo $text_administrator; ?>
				<br>
				(<small><?php echo $message['date_added'] ?></small>) 
			</div>
			<hr class="hr-clas" />
			
			<!-------------------------------------------->
			
			
			<?php }else if($message['sender']=='customer') { ?>
			<div class="chat-box-right"><?php echo $message['message'] ?></div>
			<div class="chat-box-name-right"><?php echo $message['name'];; ?>
				<br>
				(<small><?php echo $message['date_added'] ?></small>) 
			</div>
			<hr class="hr-clas" />
		<?php } ?>
		<?php } ?>
		<?php }else{ ?>
			<div class="text-center"><?php echo $text_no_message ?></div>
		<?php } ?>
</fieldset>
<script>
$('.alert_messages').html(' <?php echo $total_unread ?>');
$('.alert_messages').addClass('hidden');
</script>