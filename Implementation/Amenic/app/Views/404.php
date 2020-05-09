/*
    Author: Miloš Živkovic
    Github: zivkovicmilos
*/

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<title>Amenic - 404</title>
	</head>
	<body>
        <div class="container centerRow notFoundWrapper">
            <div class="column centerRow">
            <a href="/"><img src="/assets/MoviesPage/imgs/logo.svg" class="logo" alt="Amenic" /></a>

            <span class="notFoundTitle">404</span>
            <span class="notFoundText">
                Looks like you've gone off script. <br />
                You should be redirected to the home page soon. <br />
            </span>
            </div>
            <?php header( "refresh:8;url=/" ); ?>
        </div>
	</body>
</html>
