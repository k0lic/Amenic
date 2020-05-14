<!--
    Author: Miloš Živkovic
    Github: zivkovicmilos
-->
<?php
    $hour = intval($movie->runtime / 60); 
    $minutes = intval($movie->runtime % 60); 
    $year = substr($movie->released, 0, 4); 
    $actors = explode(',',
    $movie->actors, 4); 
    $cnt = count($actors); 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<title>Amenic - Reservation</title>
	</head>
	<body>
		<div class="container column mb-5">
			<div class="horizontalNav">
				<a href="/">
					<img src="/assets/Common/svg/logo.svg" class="logo" alt="Amenic" />
				</a>

				<ul>
					<li>Movies</li>
					<li>Cinemas</li>
					<li>
						<div class="user">
							<img src="https://via.placeholder.com/150" class="userIcon" />
							<span class="userName" id="userName">Miloš</span>
						</div>
					</li>
				</ul>
			</div>

			<div
				class="movieBackground centerRow"
				style="
					background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
						url(<?php echo $movie->backgroundImg?>);
					background-size: cover;
					background-repeat: no-repeat;
					background-position: center bottom;
				"
			>
				<div class="row w100 centerRow mt-3">
					<img src="<?php echo $movie->poster ?>" class="moviePoster mr-5" />
					<div class="movieInfoWrapper column w40">
						<span class="movieTitle"><?php echo $movie->title ?></span>
						<div class="row centerY mt-2">
							<img
								src="/assets/Movie/imdb.svg"
								class="movieInfoIconImdb mr-1"
								alt="IMDB"
								class="movieInfoIcon"
							/>
							<span class="movieInfo mr-2"
								><?php echo $movie->imdbRating?></span
							>
							<img
								src="/assets/Movie/movieClock.svg"
								class="movieInfoIconClock mr-1"
								alt="Runtime"
								class="movieInfoIcon"
							/>
							<span class="movieInfo mr-2"
								><?php echo $hour."h"." ".$minutes."m" ?></span
							>
							<span class="movieInfo mr-2"><?php echo $year?></span>
							<span class="movieInfo"><?php echo $movie->genre ?></span>
						</div>
						<div class="row mt-2">
							<span class="movieDesc w100"><?php echo $movie->plot ?></span>
						</div>
						<div class="row mt-2">
							<a href="<?php echo $movie->trailer ?>" target="_blank">
								<button type="button" class="trailerButton">
									Watch trailer
								</button></a
							>
						</div>

						<div class="row mt-2">
							<div class="column w60">
								<span class="movieInfoCrewTitle">Stars:</span>
								<span class="movieInfoCrew">
									<?php for($i = 0; $i < count($actors) && $i < 3; $i++) {
                                    echo $actors[$i];
                                    if($i != 2 && $i != $cnt-1) echo ", ";
                                }
                            ?></span
								>
							</div>
							<div class="column centerY w40">
								<span class="movieInfoCrewTitle">Director:</span>
								<span class="movieInfoCrew"><?php echo $movie->director?></span>
							</div>
						</div>
					</div>
				</div>
            </div>
            
            <div class="row w100 mt-2 reservationWrapper">
                <div class="column w15 ml-5 priceColumn">
                    <span class="reservationTitle">Your selected seats</span>
                    <div class='column mt-2'>
                        <span class="reservationSubtitle">2 seats</span>
                        <div class="reservedSeats mt-3 textCenter">
                            <span class="reservedSeat">D9</span>
                            <span class="reservedSeat">D10</span>
                            <span class="reservedSeat">D9</span>
                            <span class="reservedSeat">D10</span>
                            <span class="reservedSeat">D9</span>

                        </div>
                    </div>
                    <div class="column mt-3">
                        <div class="row spaceBetween">
                            <span class="reservationBottom">Total:</span>
                            <span class="reservationBottom id="totalPrice">€14.00</span>
                        </div>
                        <button type="submit" class="trailerButton reservationButton mt-2">Make reservation</button>
                    </div>
                    
                    <!--<hr class="reservationHR">-->
                </div>
                <div class="column w70 screenColumn">
                                 
                </div>
                <div class="column w15 legendColumn mr-5">
					<div class="row">
                    	<span class="reservationTitle mb-2">Legend</span>
					</div>
                    <div class="legendStep row centerY mb-2">
                        <div class="legend legendAvailable mr-2"></div> <span class="legendName">Available</span>
                    </div>
                    <div class="legendStep row centerY mb-2">
                        <div class="legend legendReserved mr-2"></div> <span class="legendName">Reserved</span>
                    </div>
                    <div class="legendStep row centerY">
                        <div class="legend legendSelected mr-2"></div> <span class="legendName">Selected</span>
                    </div>
                </div>
            </div>
		</div>
	</body>
</html>
