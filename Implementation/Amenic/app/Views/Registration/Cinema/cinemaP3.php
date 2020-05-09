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
					<div class="row centerY">
						<div class="stepCircle stepCircleFaded">
							1
						</div>
						<span>
							Cinema info
						</span>
					</div>
					<div class="row centerY">
						<div class="stepCircle">
							2
						</div>
						<span>
							Your info
						</span>
					</div>
					<div class="row centerY">
						<div class="stepCircle stepCircleFaded">
							3
						</div>
						<span>
							Password info
						</span>
					</div>
				</div>
			</div>
			<div class="registerWrapper formWrapper">
				<form method="POST" action="/register/cinema/3">
					<h1 class="formTitle mb-3">Who are you?</h1>

					<div class="row mb-2">
						<div class="column w25 mr-5">
							<label for="mngFirstName">First name</label>
							<input type="text" name="mngFirstName" />
							<div class="formError ml-1"><?php if(isset($errors['mngFirstName'])) echo $errors['mngFirstName'] ?></div>
						</div>
						<div class="column w25">
							<label for="mngLastName">Last name</label>
							<input type="text" name="mngLastName" />
							<div class="formError ml-1"><?php if(isset($errors['mngLastName'])) echo $errors['mngLastName'] ?></div>
						</div>
					</div>
					<div class="row mb-2">
						<div class="column w35 mr-5">
							<label for="mngEmail">Email</label>
							<input type="text" name="mngEmail" />
							<div class="formError ml-1"><?php if(isset($errors['mngEmail'])) echo $errors['mngEmail'] ?></div>
						</div>
						<div class="column w35">
							<label for="mngPhoneNumber">Phone number</label>
							<input type="text" name="mngPhoneNumber" />
							<div class="formError ml-1"><?php if(isset($errors['mngPhoneNumber'])) echo $errors['mngPhoneNumber'] ?></div>
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
