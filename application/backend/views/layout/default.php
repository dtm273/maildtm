<?php echo doctype('html5');?>
<html lang="en" <?php if (isset($html_class) && ($html_class)) echo 'class="'.$html_class.'"';?>>
<head>
	<meta content="width=device-width;initial-scale=1.0;maximum-scale=1.0;user-scalable=1;" name="viewport">
	<meta name="viewport" content="user-scalable=no" />
	<title><?php echo $title;?></title>	
	<?php
	   $meta = array(
        array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv')
       ); 
	?>
	<?php echo meta($meta);?>

	<!-- link stylesheet -->
	<?php
		 //Bootstrap framework
	   echo css('bootstrap/css/bootstrap.min.css');
	   echo css('bootstrap/css/bootstrap-responsive.min.css');
	   
	   //theme color
	   echo css('css/blue.css');
	   //tooltip
	   echo css('lib/qtip2/jquery.qtip.min.css');
	   //main styles
	   echo css('css/style.css');
	?>
	
	<link rel="shortcut icon" href="<?php echo ASSETS_URL.'favicon.png';?>" />
	
	<!--[if lte IE 8]>
		<?php 
			echo css('css/ie.css', 'all');
			echo js('ie/html5.js');
			echo js('ie/respond.min.js');
		?>
	<![endif]-->
  
  <?php 
  	//css for page
	  foreach ($stylesheets as $css){
	  	echo css($css);
	  }
	   
	  echo css('css/custom.css');
	?>
	
	<?php 
		//main js
		echo js('js/jquery.min.js');
		//hidden elements width/height
		echo js('js/jquery.actual.min.js');
		//jquery validation
		echo js('lib/validation/jquery.validate.min.js');
		//main bootstrap js
		echo js('bootstrap/js/bootstrap.min.js');
	?>
     
</head>
<body>
	<?php if (isset($user_id)) : ?>
		<script type="text/javascript">
			var base_url = '<?php echo BASE_URL;?>';
		</script>
		
		<div id="loading_layer" style="display:none"><?php echo img('ajax_loader.gif');?></div>
		<?php //require_once(dirname(__FILE__).'/../layout/switcher.php'); ?>
		
		<div id="maincontainer" class="clearfix">
		
			<?php require_once(dirname(__FILE__).'/../layout/header.php'); ?>      
			 
			<!-- main content -->
			<div id="contentwrapper">
				<div class="main_content">    
				 <?php echo $contents; ?>                          
		    </div>
		 	</div>      
		 	      
			<?php require_once(dirname(__FILE__).'/../layout/sidebar.php'); ?>
		</div>
		
		<?php 
		//js cookie plugin
		echo js('js/jquery.cookie.min.js');
		//bootstrap plugins
		echo js('js/bootstrap.plugins.min.js');
		//tooltips
		echo js('lib/qtip2/jquery.qtip.min.js');
		//scrollbar
		echo js('lib/antiscroll/antiscroll.js');
		echo js('lib/antiscroll/jquery-mousewheel.js');
		//common functions
		echo js('js/common.js');
		/* end load css & js */
		
		?>
		
		<?php 
			//js for page
			foreach ($javascripts as $js){
				echo js($js);
			}
		?>
	<?php else : ?>		
		<?php echo $contents; ?>
	<?php endif; ?>

</body>
</html>