<!DOCTYPE html>
<html lang="en" class="error_page">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo $heading; ?></title>
        
				<link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL. 'backend/bootstrap/css/bootstrap.min.css'; ?>" media="all">
        <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL. 'backend/bootstrap/css/bootstrap-responsive.min.css'; ?>" media="all">
        <link rel="stylesheet" type="text/css" href="<?php echo ASSETS_URL. 'backend/css/style.css'; ?>" media="all">
			
            <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Jockey+One" />
            
	</head>
	<body>

		<div class="error_box">
			<h1>404 Page/File not found</h1>
			<p>The page/file you've requested has been moved or taken off the site.</p>
			<a href="javascript:history.back()" class="back_link btn btn-small">Go back</a>
		</div>

	</body>
</html>

