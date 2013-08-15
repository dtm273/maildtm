<div class="row-fluid">
    <div class="span12">
        <?php include_once(dirname(__FILE__) . '/../layout/message.php'); ?>				

        <h3 class="heading"><?php echo $title;?></h3>	
        <div class="dataTables_wrapper form-inline" id="dt_a_wrapper">      		
            <div class="row">				
                <form class="form-horizontal" method="post" action="<?php echo $action; ?>" name="adminForm" id="adminForm">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('site_name'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="site_name" value="<?php echo set_value('site_name', $siteconfig['site_name']); ?>" />
                            </div>
                        </div>						
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('site_email'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="site_email" value="<?php echo set_value('site_email', $siteconfig['site_email']); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('site_support_email'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="site_support_email" value="<?php echo set_value('site_support_email', $siteconfig['site_support_email']); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('log_enabled'); ?></label>
                            <div class="controls">
                                <?php
                                $log_enabled_options = array(
                                    '1' => $lang->line('yes'),
                                    '0' => $lang->line('no')
                                );
                                echo form_dropdown('log_enabled', $log_enabled_options, $siteconfig['log_enabled'], 'style="z-index:9999"');
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('log_clean_day'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="log_clean_day" value="<?php echo set_value('log_clean_day', $siteconfig['log_clean_day']); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('log_print_screen'); ?></label>
                            <div class="controls">
                                <?php
                                $log_print_screen_options = array(
                                    '1' => $lang->line('yes'),
                                    '0' => $lang->line('no')
                                );
                                echo form_dropdown('log_print_screen', $log_print_screen_options, $siteconfig['log_print_screen'], 'style="z-index:9999"');
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <!--<input type="submit" name="submit" class="btn btn-inverse" value="<?php //echo $submit_button_label; ?>" />-->
                            </div>
                        </div>						
                    </fieldset>
                </form>
            </div>
        </div>			
    </div>
</div>

