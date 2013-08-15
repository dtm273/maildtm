<?php if(isset($message)) : ?>
<?php
	$class_alert = 'alert-success';
	if (isset($message_code) ){
		if ($message_code == 2) $class_alert = 'alert-error';
	} 
?>	
		<div class="alert <?php echo $class_alert;?>">
			<a data-dismiss="alert" class="close">×</a>		
			<?php echo $message;?>
		</div>
<?php endif;?>