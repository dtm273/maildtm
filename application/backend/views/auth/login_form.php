<?php
if ($login_by_username AND $login_by_email) {
    $login_label = 'Email or username';
} else if ($login_by_username) {
    $login_label = 'Username';
} else {
    $login_label = 'Email';
}
$login = array(
    'name' => 'login',
    'id' => 'login',
    'value' => set_value('login'),
    'placeholder' => $login_label,
    'maxlength' => 80,
    'size' => 30,
);
$password = array(
    'name' => 'password',
    'id' => 'password',
    'placeholder' => 'Password',
    'size' => 30,
);
$remember = array(
    'name' => 'remember',
    'id' => 'remember',
    'value' => 1,
    'checked' => set_value('remember'),
);
$captcha = array(
    'name' => 'captcha',
    'id' => 'captcha',
    'maxlength' => 8,
);
?>
<div class="login_box">
    <?php echo form_open($this->uri->uri_string(), 'id="login_form"'); ?>
    <div class="top_b">Sign in to <?php echo SITE_NAME; ?></div>    
    <div class="alert alert-info alert-login">
        <?php
        if (isset($errors[$login['name']])) {

            echo $errors[$login['name']];
        } else if (isset($errors[$password['name']])) {

            echo $errors[$password['name']];
        } else {
            echo 'Please login!';
        }
        ?>
    </div>
    <div class="cnt_b">
        <div class="formRow">
            <div class="input-prepend">
                <span class="add-on"><i class="icon-user"></i></span><?php echo form_input($login); ?>
            </div>
        </div>
        <div class="formRow">
            <div class="input-prepend">
                <span class="add-on"><i class="icon-lock"></i></span><?php echo form_password($password); ?>
            </div>
        </div>
        <div class="formRow clearfix">
            <label class="checkbox"><?php echo form_checkbox($remember); ?> <?php echo form_label('Remember me', $remember['id']); ?></label>
        </div>
    </div>
    <div class="btm_b clearfix">
<?php echo form_submit('submit', 'Sign In', 'class="btn btn-inverse pull-right"'); ?>
        <span class="link_reg"><?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Not registered? Sign up here'); ?></span>
    </div>
<?php echo form_close(); ?>

    <div class="links_b links_btm clearfix">
        <span class="linkform"><?php echo anchor('/auth/forgot_password/', 'Forgot password'); ?></span>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        //* boxes animation
        form_wrapper = $('.login_box');
        function boxHeight() {
            form_wrapper.animate({marginTop: (-(form_wrapper.height() / 2) - 24)}, 400);
        }
        ;
        form_wrapper.css({marginTop: (-(form_wrapper.height() / 2) - 24)});

        //* validation
        $('#login_form').validate({
            onkeyup: false,
            errorClass: 'error',
            validClass: 'valid',
            rules: {
                login: {required: true, minlength: 3},
                password: {required: true, minlength: 3}
            },
            highlight: function(element) {
                $(element).closest('div').addClass("f_error");
                setTimeout(function() {
                    boxHeight()
                }, 200)
            },
            unhighlight: function(element) {
                $(element).closest('div').removeClass("f_error");
                setTimeout(function() {
                    boxHeight()
                }, 200)
            },
            errorPlacement: function(error, element) {
                $(element).closest('div').append(error);
            }
        });
    });
</script>