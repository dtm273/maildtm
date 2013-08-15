<div class="row-fluid">
    <div class="span12">
        <?php include_once(dirname(__FILE__) . '/../layout/message.php'); ?>

        <h3 class="heading"><?php echo $lang->line('manager'); ?></h3>		
        <div role="grid" class="dataTables_wrapper form-inline" id="dt_a_wrapper">
            <form class="form-horizontal" method="post" action="" name="adminForm" id="adminForm">
                <div class="row">				
                    <div class="span6">
                        <label><?php echo $lang->line('search') .' theo tiêu đề'; ?>: 
                            <input type="text" aria-controls="search" id="search" value="<?php //echo $data['keyword']; ?>"></label>
                        <?php echo img('gCons/search.png', array('id' => 'search_button')); ?>
                        <?php echo img('ajax_loader.gif', array('class' => 'ajax_waiting')); ?>
                    </div>
                    <div class="span6">
                        <div class="clearfix sepH_b">
                            <div class="btn-group pull-right open">
                                <span>
                                    <?php echo anchor($item_name . '/add/', $lang->line('add'), array('class' => 'btn btn-inverse dropdown-toggle')); ?>
                                </span>
                                <span style="padding-left:5px;">
                                    <?php echo anchor($item_name . '/delete/', $lang->line('delete'), array('class' => 'delete_rows_simple btn btn-inverse dropdown-toggle', 'data-tableid' => 'list')); ?>								
                                </span>
                            </div>
                            <div class="hide">
                                <!-- confirmation box -->
                                <div id="confirm_dialog" class="cbox_content">
                                    <div class="sepH_c tac"><strong><?php echo $lang->line('confirm_delete'); ?></strong></div>
                                    <div class="tac">
                                        <a href="#" class="btn btn-gebo confirm_yes"><?php echo $lang->line('yes'); ?></a>
                                        <a href="#" class="btn confirm_no"><?php echo $lang->line('no'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php echo $table; ?>
            </form>
            <div class="row" id="pagination">
                <div class="span6">
                    <?php echo $pagination_info; ?>					
                </div>
                <div class="span6">
                    <?php echo $pagination_links; ?>
                </div>
            </div>
        </div>			
    </div>
</div>           