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
					<div class="row centerX">
						<div class="stepCircle">
							1
						</div>
						<span>
							Cinema info
						</span>
					</div>
					<div class="row centerX">
						<div class="stepCircle stepCircleFaded">
							2
						</div>
						<span>
							Your info
						</span>
					</div>
					<div class="row centerX">
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
				<form method="POST" action="/register/cinema/2">
					<h1 class="formTitle mb-1">Tell us about your cinema</h1>

					<div class="row mb-2">
						<div class="column w45">
							<label for="name">Cinema name</label>
							<input type="text" name="name"/>
						</div>
					</div>
					<div class="row mb-2">
						<div class="column w35 mr-5">
							<label for="address">Address</label>
							<input type="text" name="address"/>
						</div>
						<div class="column w35">
							<label for="phoneNumber">Phone number</label>
							<input type="text" name="phoneNumber"/>
						</div>
					</div>
					<div class="row mb-2">
						<div class="column w20 mr-5">
							<label for="country">Country</label>
							<select class="formSelect">
								<option value="val1">Select</option>
								<option value="val2">Andrija</option>
								<option value="val3">Martin</option>
								<option value="val4">Miloš</option>
								<option value="val5">Dražen</option>
								<option value="val6">Tamara</option>
							</select>
						</div>
						<div class="column w20">
							<label for="country">City</label>
							<select class="formSelect">
								<option value="val1">Select</option>
								<option value="val2">Andrija</option>
								<option value="val3">Martin</option>
								<option value="val4">Miloš</option>
								<option value="val5">Dražen</option>
								<option value="val6">Tamara</option>
							</select>
						</div>
					</div>

					<div class="row textAreaRow mb-2">
						<div class="column w75">
							<label for="description">Description (optional)</label>
							<textarea name="description" value="<?php $description ?>"></textarea>
						</div>
					</div>

					<div class="row">
						<div class="column w75">
							<button type="submit">
								Next
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
