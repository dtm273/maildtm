<!-- sidebar -->
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
<div class="sidebar">			
    <div class="antiScroll">
        <div class="antiscroll-inner">
            <div class="antiscroll-content">
                <div class="sidebar_inner">
                    <div id="side_accordion" class="accordion">

                        <div class="accordion-group">
                            <div class="accordion-heading sdb_h_active">
                                <a href="#collapseOne" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                    <i class="icon-folder-close"></i> <?php echo $lang->line('menu_manager'); ?>
                                </a>
                            </div>
                            <div class="accordion-body in collapse" id="collapseOne" style="height: auto;">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li><a href="<?php echo site_url('index'); ?>"><?php echo $lang->line('menu_company'); ?></a></li>
                                        <li><a href="<?php echo site_url('account'); ?>"><?php echo $lang->line('menu_account'); ?></a></li>									
                                        <li><a href="<?php echo site_url('history'); ?>"><?php echo $lang->line('menu_history'); ?></a></li>																	

                                        <li><a href="<?php echo site_url('service'); ?>"><?php echo $lang->line('menu_service'); ?></a></li>
                                        <li><a href="<?php echo site_url('push_notification'); ?>"><?php echo $lang->line('menu_push_notification'); ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseTwo" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                    <i class="icon-cog"></i> <?php echo $lang->line('menu_configuration'); ?>
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapseTwo">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li><a href="<?php echo site_url('configsite'); ?>"><?php echo $lang->line('menu_config_site'); ?></a></li>
                                        <li><a href="<?php echo site_url('configsearch'); ?>"><?php echo $lang->line('menu_config_search'); ?></a></li>									
                                        <li><a href="<?php echo site_url('configpush'); ?>"><?php echo $lang->line('menu_config_push'); ?></a></li>																	
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a href="#collapseThree" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                    <i class="icon-user"></i> <?php echo $lang->line('menu_statistics'); ?>
                                </a>
                            </div>
                            <div class="accordion-body collapse" id="collapseThree">
                                <div class="accordion-inner">
                                    <ul class="nav nav-list">
                                        <li><a href="<?php echo site_url('statisuser'); ?>"><?php echo $lang->line('menu_statis_user'); ?></a></li>
                                        <li><a href="<?php echo site_url('statissearch'); ?>"><?php echo $lang->line('menu_statis_search'); ?></a></li>									
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="push"></div>
        </div>
    </div>
</div>