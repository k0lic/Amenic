<!--
    Author: Miloš Živkovic
    Github: zivkovicmilos
-->
<?php
	$initCountry = 1;
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>/favicon.ico">
		<title>Amenic - Register</title>
	</head>
	<body>
		<div class="container">
			<div class="registerBar">
				<a href="/">
					<img src="/assets/Common/svg/logo.svg" class="logo" alt="Amenic" />
				</a>
				<div class="registerSteps">
					<div class="row centerY">
						<div class="stepCircle">
							1
						</div>
						<span>
							Cinema info
						</span>
					</div>
					<div class="row centerY">
						<div class="stepCircle stepCircleFaded">
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
				<form method="POST" action="/register/cinema/2">
					<h1 class="formTitle mb-1">Tell us about your cinema</h1>

					<div class="row mb-2">
						<div class="column w45">
							<label for="cinemaName">Cinema name</label>
							<input type="text" name="cinemaName"/>
							<div class="formError ml-1"><?php if(isset($errors['cinemaName'])) echo $errors['cinemaName'] ?></div>
						</div>
					</div>
					<div class="row mb-2">
						<div class="column w35 mr-5">
							<label for="address">Address</label>
							<input type="text" name="address"/>
							<div class="formError ml-1"><?php if(isset($errors['address'])) echo $errors['address'] ?></div>
						</div>
						<div class="column w35">
							<label for="phoneNumber">Phone number</label>
							<input type="text" name="phoneNumber"/>
							<div class="formError ml-1"><?php if(isset($errors['phoneNumber'])) echo $errors['phoneNumber'] ?></div>
						</div>
					</div>
					<div class="row mb-2">
						<div class="column w20 mr-5">
							<label for="country">Country</label>
							<select class="formSelect" name="country" required id="countryDropdown">
							</select>
							<div class="formError ml-1"><?php if(isset($errors['country'])) echo $errors['country'] ?></div>
						</div>
						<div class="column w20">
							<label for="country">City</label>
							<select class="formSelect" name="city" required id="cityDropdown">
							</select>
							<div class="formError ml-1"><?php if(isset($errors['city'])) echo $errors['city'] ?></div>
						</div>
					</div>

					<div class="row textAreaRow mb-2">
						<div class="column w75">
							<label for="description">Description <span class="optionalText">(optional)</span></label>
							<textarea name="description" value="<?php $description ?>"></textarea>
							<div class="formError ml-1"><?php if(isset($errors['description'])) echo $errors['description'] ?></div>
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
		<input type="hidden" id="countryId" value="<?php echo $initCountry ?>">
	</body>
	<script src="/js/register/fieldUpdater.js"></script>
</html>
