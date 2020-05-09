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
		<title>Amenic - Register Cinema</title>
	</head>
	<body>
		<div class="container">
			<div class="registerBar">
				<a href="/">
					<img src="/assets/Common/svg/logo.svg" class="logo" alt="Amenic" />
				</a>
				<div class="registerSteps">
				<div class="registerBarTitle">All done!</div>
				</div>
			</div>
			<div class="registerWrapper formWrapper">
				<span class="registerSuccess">
				<strong>Thank you for registering on the Amenic platform!</strong> <br />
				Our administrators will review your request and let you know the next steps. <br />
				You should be redirected to the home page in less than 10 seconds, or <a href="/">click here</a> to do that now.
				</span>
                <?php header( "refresh:8;url=/" ); ?>
			</div>
		</div>
	</body>
</html>
