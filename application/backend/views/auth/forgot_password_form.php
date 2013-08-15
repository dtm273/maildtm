<?php
$login = array(
	'name'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'placeholder' => 'Your email address',
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or username';
} else {
	$login_label = 'Email';
}
?>
<div class="login_box" style="margin-top: -152.5px;">
<?php echo form_open($this->uri->uri_string(),'id="pass_form"'); ?>
<div class="top_b">Can't sign in?</div>    
	<div class="alert alert-info alert-login">
		<?php 
			if (isset($errors[$login['name']])){
				echo $errors[$login['name']];
			}
			else{
				echo 'Please enter your email address. You will receive a link to create a new password via email.';
			}
		?>	
	</div>
	<div class="cnt_b">
		<div class="formRow clearfix">
			<div class="input-prepend">
				<span class="add-on">@</span><?php echo form_input($login); ?>
			</div>
		</div>
	</div>
	<div class="btm_b tac">
		<?php echo form_submit('reset', 'Request New Password','class="btn btn-inverse"'); ?>
	</div> 
<?php echo form_close(); ?>
</div>
