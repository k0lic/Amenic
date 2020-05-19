<!--
    Author: Miloš Živkovic
    Github: zivkovicmilos
-->

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<link rel="icon" type="image/x-icon" href="<?php echo $baseURL ?>/favicon.ico">
		<title>Amenic - Password reset</title>
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
						Looks like you've forgotten your password.
					</div>
				</div>
			</div>
			<div class="registerWrapper formWrapper">
                <h1 class="resetTitle mb-3">Success :)</h1>
                <span class="resetSubtitle">
                Your password is successfully changed! <br />
                You should be redirected to the home page soon. <br />
            </span>
            </div>
            <?php header( "refresh:8;url=/" ); ?>
		</div>
	</body>
</html>
