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
						<div class="stepCircle">
							1
						</div>
						<span>
							Your info
						</span>
					</div>
					<div class="row centerX">
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
						</div>
						<div class="column w25">
							<label for="lastName">Last name</label>
							<input type="text" name="lastName" />
						</div>
					</div>
					<div class="row mb-2">
						<div class="column w35 mr-5">
							<label for="email">Email</label>
							<input type="text" name="email" />
						</div>
						<div class="column w35">
							<label for="phone">Phone number</label>
							<input type="text" name="phone" />
						</div>
					</div>

					<div class="row mb-2">
						<div class="column w20 mr-5">
							<label for="country">Country</label>
							<select class="formSelect">
								<option>Select</option>
								<option>Andrija</option>
								<option>Martin</option>
								<option>Miloš</option>
								<option>Dražen</option>
								<option>Tamara</option>
							</select>
						</div>
						<div class="column w20">
							<label for="country">City</label>
							<select class="formSelect">
								<option>Select</option>
								<option>Andrija</option>
								<option>Martin</option>
								<option>Miloš</option>
								<option>Dražen</option>
								<option>Tamara</option>
							</select>
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
