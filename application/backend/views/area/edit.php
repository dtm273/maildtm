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
                            <label class="control-label"><?php echo $lang->line('location'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="name" value="<?php echo set_value('name', $area['name']); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('parent_id'); ?></label>
                            <div class="controls">
                                <?php
                                $list_city = $all_area[0];
                                $area_options[0] = '';
                                foreach ($list_city as $city_key => $city_value){
                                    $area_options[$city_key] = '<b>'.$city_value['name'].'</b>';
                                    if(isset($all_area[$city_key])){
                                        $list_district = $all_area[$city_key];
                                        foreach ($list_district as $district_key => $district_value){
                                            $area_options[$district_key] = '&nbsp &nbsp' .$district_value['name'];
                                            if(isset($all_area[$district_key])){
                                                $list_road = $all_area[$district_key];
                                                foreach ($list_road as $road_key => $road_value){
                                                    $area_options[$road_key] = '&nbsp &nbsp &nbsp &nbsp' .$road_value['name'];
                                                }
                                            }
                                        }
                                    }
                                }
                                echo form_dropdown('parent_id', $area_options, $area['parent_id']);
                                ?>
                            </div>							
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('type'); ?></label>
                            <div class="controls">
                                <?php 
                                    $type_option = array(
                                        '1' => 'Thành phố/ Tỉnh',
                                        '2' => 'Quận/ Huyện',
                                        '3' => 'Đường'
                                    );
                                    echo form_dropdown('type', $type_option, $area['type']);
                                ?>
                                <!--<input type="text" class="span10" name="type" value="<?php //echo set_value('type', $area['type']); ?>" />-->
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('key_search'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="key_search" value="<?php echo set_value('key_search', $area['key_search']); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('latitude_position'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="latitude" value="<?php echo set_value('latitude', $area['latitude']); ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('longtitude_position'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="longtitude" value="<?php echo set_value('longtitude', $area['longtitude']); ?>" />
                            </div>
                        </div>			
                        <div class="control-group">
                            <label class="control-label"><?php echo $lang->line('radius'); ?></label>
                            <div class="controls">
                                <input type="text" class="span10" name="radius" value="<?php echo set_value('radius', $area['radius']); ?>" />
                            </div>
                        </div>							
                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" name="submit" class="btn btn-inverse" value="<?php echo $submit_button_label; ?>" />
                            </div>
                        </div>						
                    </fieldset>
                    <input type="hidden" name="id" id="id" value= "<?php echo set_value('id', $area['id']); ?>" />					
                </form>
            </div>
        </div>			
    </div>
</div>

