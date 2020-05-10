<!--
    Copied by: Andrija Kolić
    From: Views\Registration\registerError.php
-->

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<title>Amenic - Error</title>
	</head>
	<body>
		<div class="container">
			<div class="registerBar">
				<a href="/">
					<img src="/assets/Common/svg/logo.svg" class="logo" alt="Amenic" />
				</a>
				<div class="registerSteps">
                    <div class="registerBarTitle">Oh no!</div>
                    <div class="registerBarSubtitle mt-3">
                        Looks like something went wrong.
                    </div>
				</div>
			</div>
			<div class="registerWrapper formWrapper">
				<span class="registerSuccess">
                    <strong><?php echo $msg ?></strong> <br />
                    You should be redirected to the "<?php echo $destination ?>" page in less than 10 seconds, or <a href="<?php echo $destination ?>">click here</a> to do that now.
				</span>
                <?php header( "refresh:8;url=$destination" ); ?>
			</div>
		</div>
	</body>
</html>
