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
		<link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>/favicon.ico">
		<title>Amenic - Reservation</title>
	</head>
	<body>
		<div class="container column mb-5">
			<div class="horizontalNav">
				<a href="/">
					<img src="/assets/Common/svg/logo.svg" class="logo" alt="Amenic" />
				</a>

				<ul>
					<li><a href="/HomeController">Movies</a></li>
					<li><a href="/HomeController/cinemas">Cinemas</a></li>
					<?php
                    if (isset($token))
                    {
                        echo "
                        <li>
                            <div class=\"user\">
                                <img
                                src="; 
                        if(!$token->image) echo"\"/assets/profPic.png\""; else echo "\"data:image/jpg;base64, ".$token->image."\"";
                        echo    "class=\"profPic\"
                                alt=\"Profile picture\"
                                />
                                <span>
                                    ".$token->firstName." ".$token->lastName."
                                </span>
                            </div>
                        </li>";
                    }
                ?>
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
            
            <div class="row w100 mt-5 reservationWrapper">
                <div class="column w15 ml-5 priceColumn">
                    <span class="reservationTitle">Your selected seats</span>
                    <div class='column mt-2 mb-3'>
                        <span class="reservationSubtitle" id="numSeats"></span>
                        <div class="reservedSeats mt-3 textCenter" id ="reservedSeats">
                        </div>
					</div>
					<div class="row">
						<img src="/assets/Reservation/dots.svg" class="hrDots" />
					</div>
                    <div class="column mt-3">
                        <div class="row spaceBetween">
                            <span class="reservationBottom">Total:</span>
                            <span class="reservationBottom" id="totalPrice"></span>
                        </div>
						<button type="submit" class="trailerButton reservationButton mt-2" id="reservationButton"
						onclick="
						updateReservationModal();
						document.getElementById('reservationModal').classList.add('showModal');
						">Make reservation</button>
                    </div>
					
                </div>
                <div class="column w70 screenColumn">
					<div class="row mb-5">
						<img src="/assets/Reservation/screen.svg" class="reservationScreen" />
					</div>
					<div class="row centerRow">
						<div id="projectionSeating">

						</div>
					</div>
					<div class="row centerRow mt-2">
						<span class="projectionError" id="projectionError">You can only select a maximum of 6 seats!</span>
					</div>
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
					
					<div class="column mt-5">
						<span class="reservationCinema"><?php echo $cinemaName ?></span>
						<span class="reservationRoom"><?php echo $roomName ?></span>

					</div>
                </div>
			</div>
			<?php include 'reservationModal.php'; ?>
		</div>
		<input type="hidden" value="<?php echo $numRows ?>" id="projectionRows">
		<input type="hidden" value="<?php echo $numCols ?>" id="projectionColumns">
		<input type="hidden" value="<?php echo $idPro ?>" id="idPro">
		<input type="hidden" value="<?php echo $ticketPrice ?>" id="ticketPrice">
	</body>
	<script src="/js/reservations/reservation.js"></script>
</html>
