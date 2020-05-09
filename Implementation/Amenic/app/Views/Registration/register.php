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
		<title>Amenic - Register</title>
	</head>
	<body>
		<div class="container">
			<div class="registerBar">
				<img src="/assets/Common/svg/logo.svg" class="logo" alt="Amenic" />
				<div class="registerSteps">
					<div class="registerBarTitle">Thank you for signing up.</div>
					<div class="registerBarSubtitle mt-3">
						Let us know what kind of account you would like to create.
					</div>
				</div>
			</div>
			<div class="registerWrapper centerRow">
				<form method="POST" action="/register/user/1" id="typeUser">
				<div class="registerRow centerRow" onclick="document.forms['typeUser'].submit();">
					<img src="/assets/Registration/user.svg" class="icon" />

					<div class="registerType">
						<span class="registerTypeTitle">Individual</span>
						<span class="registerTypeDesc"
							>I want to create an account to reserve tickets.</span
						>
					</div>

					<img src="/assets/Registration/next.svg" class="icon" />
				</div>
				<input type="hidden" name="type" value="user">
				</form>

				<form method="POST" action="/register/cinema/1" id="typeCinema">
				<div class="registerRow centerRow mt-5" onclick="document.forms['typeCinema'].submit();">
					<img src="/assets/Registration/clapper.svg" class="icon" />

					<div class="registerType">
						<span class="registerTypeTitle">Cinema</span>
						<span class="registerTypeDesc">
							I want to create an account for my cinema.
						</span>
					</div>

					<img src="/assets/Registration/next.svg" class="icon" />
				</div>
				<input type="hidden" name="type" value="cinema">
				</form> 
			</div>
		</div>
	</body>
</html>
