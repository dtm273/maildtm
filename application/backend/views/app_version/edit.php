<div class="row-fluid">
    <div class="span12">
        <?php include_once(dirname(__FILE__) . '/../layout/message.php'); ?>				

        <h3 class="heading"><?php echo $title; ?>
            <span class="btn-group pull-right open">
                <?php echo $link_back; ?>
            </span>
        </h3>	
        <div class="dataTables_wrapper form-inline" id="dt_a_wrapper">      		
            <div class="row">				
                <form class="form-horizontal" method="post" action="<?php echo $action; ?>" name="adminForm" id="adminForm">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('os_type'); ?></label>
                            <div class="controls">
                                <?php 
                                $osOption = array(
                                    'ios' => 'ios',
                                    'android' => 'Android'
                                );
                                echo form_dropdown('update_option', $osOption, $app_version['os_type']); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('version'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="version" value="<?php echo set_value('version', $app_version['version']); ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('status'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="status" value="<?php echo set_value('status', $app_version['status']); ?>" />
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('update_option'); ?></label>
                            <div class="controls">
                                <?php 
                                $updateOption = array(
                                    '0' => 'Khong bat buoc',
                                    '1' => 'Bat buoc'
                                );
                                echo form_dropdown('update_option', $updateOption, $app_version['update_option']); 
                                ?>
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('link_appstore'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="link_appstore" value="<?php echo set_value('link_appstore', $app_version['link_appstore']); ?>" />
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('link_googleplay'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="link_googleplay" value="<?php echo set_value('link_googleplay', $app_version['link_googleplay']); ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" name="submit" class="btn btn-inverse" value="<?php echo $submit_button_label; ?>" />
                            </div>
                        </div>						
                    </fieldset>
                    <input type="hidden" name="id" id="id" value= "<?php echo set_value('id', $app_version['id']); ?>" />					
                </form>
            </div>
        </div>			
    </div>
</div>

