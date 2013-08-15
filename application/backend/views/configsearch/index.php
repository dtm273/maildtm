<div class="row-fluid">
    <div class="span12">
        <?php include_once(dirname(__FILE__) . '/../layout/message.php'); ?>				

        <h3 class="heading"><?php echo $title;?></h3>	
        <div class="dataTables_wrapper form-inline" id="dt_a_wrapper">      		
            <div class="row">				
                <form class="form-horizontal" method="post" action="<?php echo $action; ?>" name="adminForm" id="adminForm">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('page_limit'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="page_limit" value="<?php echo set_value('page_limit', $searchconfig['page_limit']); ?>" />
                            </div>
                        </div>						
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('search_zn_enabled'); ?></label>
                            <div class="controls">
                                <?php
                                $search_zn_enabled_options = array(
                                    'yes' => $lang->line('yes'),
                                    'no' => $lang->line('no')
                                );
                                echo form_dropdown('search_zn_enabled', $search_zn_enabled_options, $searchconfig['search_zn_enabled'], 'style="z-index:9999"');
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('search_nct_enabled'); ?></label>
                            <div class="controls">
                                <?php
                                $search_nct_enabled_options = array(
                                    'yes' => $lang->line('yes'),
                                    'no' => $lang->line('no')
                                );
                                echo form_dropdown('search_nct_enabled', $search_nct_enabled_options, $searchconfig['search_nct_enabled'], 'style="z-index:9999"');
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('search_ns_enabled'); ?></label>
                            <div class="controls">
                                <?php
                                $search_ns_enabled_options = array(
                                    'yes' => $lang->line('yes'),
                                    'no' => $lang->line('no')
                                );
                                echo form_dropdown('search_ns_enabled', $search_ns_enabled_options, $searchconfig['search_ns_enabled'], 'style="z-index:9999"');
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

