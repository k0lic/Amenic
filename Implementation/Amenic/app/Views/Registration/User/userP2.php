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
				<a href="/register">
					<img src="/assets/Common/svg/logo.svg" class="logo" alt="Amenic" />
				</a>
				<div class="registerSteps">
					<div class="row centerY">
						<div class="stepCircle">
							1
						</div>
						<span>
							Your info
						</span>
					</div>
					<div class="row centerY">
						<div class="stepCircle stepCircleFaded">
							2
						</div>
						<span>
							Password
						</span>
					</div>
				</div>
			</div>
			<div class="registerWrapper formWrapper">
				<form method="POST" action="/register/user/2">
					<h1 class="formTitle mb-3">Who are you?</h1>

					<div class="row mb-2">
						<div class="column w25 mr-5">
							<label for="firstName">First name</label>
							<input type="text" name="firstName" />
							<div class="formError ml-1"><?php if(isset($errors['firstName'])) echo $errors['firstName'] ?></div>
							
						</div>
						<div class="column w25">
							<label for="lastName">Last name</label>
							<input type="text" name="lastName" />
							<div class="formError ml-1"><?php if(isset($errors['lastName'])) echo $errors['lastName'] ?></div>
						</div>
					</div>
					<div class="row mb-2">
						<div class="column w35 mr-5">
							<label for="email">Email</label>
							<input type="text" name="email" />
							<div class="formError ml-1"><?php if(isset($errors['email'])) echo $errors['email'] ?></div>
						</div>
						<div class="column w35">
							<label for="phone">Phone number</label>
							<input type="text" name="phone" />
							<div class="formError ml-1"><?php if(isset($errors['phone'])) echo $errors['phone'] ?></div>
						</div>
					</div>

					<div class="row mb-2">
						<div class="column w20 mr-5">
							<label for="country">Country</label>
							<select class="formSelect" name="country" required>
								<option value="1">Serbia</option>
							</select>
							<div class="formError ml-1"><?php if(isset($errors['country'])) echo $errors['country'] ?></div>
						</div>
						<div class="column w20">
							<label for="country">City</label>
							<select class="formSelect" name="city" required>
								<option value="1">Beograd</option>
								<option value="2">Novi Sad</option>
								<option value="3">Niš</option>
							</select>
							<div class="formError ml-1"><?php if(isset($errors['city'])) echo $errors['city'] ?></div>
						</div>
					</div>

					<div class="row">
						<div class="column w75">
							<button type="submit">Next</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
