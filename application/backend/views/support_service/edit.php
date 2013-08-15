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
                            <label class="control-label"><?php echo $lang->line('title'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="price" value="<?php echo set_value('title', $support_service['title']); ?>" />
                            </div>
                        </div>
                        	
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('message'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="price" value="<?php echo set_value('message', $support_service['message']); ?>" />
                            </div>
                        </div>
                        								
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" name="submit" class="btn btn-inverse" value="<?php echo $submit_button_label; ?>" />
                            </div>
                        </div>						
                    </fieldset>
                    <input type="hidden" name="id" id="id" value= "<?php echo set_value('id', $support_service['id']); ?>" />					
                </form>
            </div>
        </div>			
    </div>
</div>

