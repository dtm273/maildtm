<div class="row-fluid">
    <div class="span12">
        <?php include_once(dirname(__FILE__) . '/../layout/message.php'); ?>

        <h3 class="heading"><?php echo $lang->line('manager'); ?></h3>		
        <div role="grid" class="dataTables_wrapper form-inline" id="dt_a_wrapper">
            <form class="form-horizontal" method="post" action="<?php echo $action ?>" name="adminForm" id="adminForm">
                <fieldset>
                    <div class="control-group">
                        <?php
                        $data = array(
                            'name'          => 'title',
                            'id'            => 'title',
                            'maxlength'     => '100',
                            'placeholder'   => 'title',
                            'style'         => 'width:500px'
                        );
                        echo '<label class="control-label">';
                        echo form_label('Title', 'title');
                        echo '</label>';
                        echo '<div class="controls">';
                        echo form_input($data);
                        echo '</div>';
                        ?>     
                    </div>
                    <div class="control-group">
                        <?php
                        $data = array(
                            'name'  => 'message',
                            'id'    => 'message',
                            'style' => 'width:500px'
                        );
                        echo '<label class="control-label">';
                        echo form_label('Message', 'message_label');
                        echo '</label>';
                        echo '<div class="controls">';
                        echo form_textarea($data);
                        echo '</div>';
                        ?>     
                    </div>
                    <div class="control-group">
                        <?php
                        $data = array(
                            'name'      => 'link',
                            'id'        => 'link',
                            'maxlength' => '100',
                            'placeholder' => 'Link',
                            'style'     => 'width:500px'
                        );
                        echo '<label class="control-label">';
                        echo form_label('Link', 'link_label');
                        echo '</label>';
                        echo '<div class="controls">';
                        echo form_input($data);
                        echo '</div>';
                        ?>     
                    </div>
                    <div class="control-group">
                        <?php
                        $data = array(
                            'name'      => 'token_test',
                            'id'        => 'token_test',
                            'maxlength' => '100',
                            'placeholder' => 'Device',
                            'style'     => 'width:500px'
                        );
                        echo '<label class="control-label">';
                        echo form_label('Device test (token)', 'token_label');
                        echo '</label>';
                        echo '<div class="controls">';
                        echo form_input($data);
                        echo '</div>';
                        ?>     
                    </div>
                    <div class="control-group">
                        <?php
                        $data = array(
                            '0' => 'Không push đến tất cả',
                            '1' => 'Push đến toàn bộ drivers',
                            '2' => 'Push đến toàn bộ customers',
                        );
                        echo '<label class="control-label">';
                        echo form_label('Push đến tất cả', 'push_all_label');
                        echo '</label>';
                        echo '<div class="controls">';
                        echo form_dropdown('push_user_type', $data, 'push_user_type');
                        echo '</div>';
                        ?>     
                    </div>
                    <div class="control-group">
                        <?php
                        $os_option = array(
                            'android'   => 'Android',
                            'ios'       => 'IOS',
                        );
                        echo '<label class="control-label">';
                        echo form_label('OS Type', 'os_label');
                        echo '</label>';
                        echo '<div class="controls">';
                        echo form_dropdown('os_type', $os_option);
                        echo '</div>';
                        ?>     
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <input type="submit" name="submit" class="btn btn-inverse" value="Send" />
                        </div>
                    </div>
            </form>
        </div>			
    </div>
</div>           