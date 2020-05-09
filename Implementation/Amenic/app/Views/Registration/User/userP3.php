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
				<img src="./imgs/logo.svg" class="logo" alt="Amenic" />
				<div class="registerSteps">
					<div class="row centerX">
						<div class="stepCircle stepCircleFaded">
							1
						</div>
						<span>
							Your info
						</span>
					</div>
					<div class="row centerX">
						<div class="stepCircle">
							2
						</div>
						<span>
							Password
						</span>
					</div>
				</div>
			</div>
			<div class="registerWrapper formWrapper">
				<form class="testClass" method="POST" action="/register/user/3">
					<h1 class="formTitle mb-3">Create a password</h1>

					<div class="row mb-5">
						<div class="column w25 mr-5">
							<label for="firstPassword">Your password</label>
							<input type="password" name="firstPassword" />
						</div>
						<div class="column w25">
							<label for="secondPassword">Confirm password</label>
							<input type="password" name="secondPassword" />
						</div>
					</div>

					<div class="row centerX mb-2">
						<span id="strengthBarTitle">Strength: </span>
						<span id="strengthBar1" class="strengthBar mr-1 ml-2"></span>
						<span id="strengthBar2" class="strengthBar mr-1"></span>
						<span id="strengthBar3" class="strengthBar mr-1"></span>
						<span id="strengthBar4" class="strengthBar"></span>
					</div>

					<div class="row">
						<div class="column w55">
							<button type="submit">Finish</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>