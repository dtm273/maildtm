<!-- header -->
<header>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="<?php echo site_url();?>"><i class="icon-home icon-white"></i> <?php echo SITE_NAME;?></a>
				<ul class="nav user_menu pull-right">
					<li class="divider-vertical hidden-phone hidden-tablet"></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo img('user_avatar.png',array('class' => 'user_avatar'));?> <?php echo $username;?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo anchor('/auth/logout/', $lang->line('login_logout')); ?></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>