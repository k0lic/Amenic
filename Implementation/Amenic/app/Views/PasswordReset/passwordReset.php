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
		<link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>/favicon.ico">
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
				<form class="testClass" method="POST" action="/login/handleReset">
					<h1 class="formTitle mb-3">Password reset</h1>

					<div class="row mb-5">
						<div class="column w25 mr-5">
							<label for="firstPassword">New password</label>
							<input type="password" id="password" name="firstPassword" />
							<div class="formError ml-1"><?php if(isset($errors['firstPassword'])) echo $errors['firstPassword'] ?></div>
						</div>
						<div class="column w25">
							<label for="secondPassword">Confirm password</label>
							<input type="password" name="secondPassword" />
							<div class="formError ml-1"><?php if(isset($errors['secondPassword'])) echo $errors['secondPassword'] ?></div>
						</div>
					</div>

					<div class="row centerY mb-2">
						<span id="strengthBarTitle">Strength: </span>
						<span id="strengthBar1" class="strengthBar mr-1 ml-2"></span>
						<span id="strengthBar2" class="strengthBar mr-1"></span>
						<span id="strengthBar3" class="strengthBar mr-1"></span>
						<span id="strengthBar4" class="strengthBar"></span>
					</div>

					<div class="row">
						<div class="column w55">
							<button type="submit">Submit</button>
						</div>
                    </div>
                    <input type="hidden" value="<?php echo $token ?>" name="token">
				</form>
			</div>
		</div>
	</body>
	<script src="/js/passwordStrength/zxcvbn.js"></script>
    <script src="/js/passwordStrength/passwordStrength.js"></script>
</html>
