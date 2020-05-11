<!--
    Author: Miloš Živkovic
    Github: zivkovicmilos
-->
<?php
    $hour = intval($movie->runtime / 60);
    $minutes = intval($movie->runtime % 60);
    $year = substr($movie->released, 0, 4);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<title>Amenic - <?php echo $movie->title ?></title>
	</head>
	<body>
		<div class="container column">

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

        <div class="movieBackground centerRow" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(<?php echo $movie->backgroundImg ?>); 
        background-size: cover;
	    background-repeat: no-repeat;
	    background-position: center bottom;">
            <div class="row w100 centerX mt-3">
                <img src="<?php echo $movie->poster ?>" class="moviePoster mr-5" />
                <div class="movieInfoWrapper column w40">
                    <span class="movieTitle"><?php echo $movie->title ?></span>
                    <div class="row centerY mt-2">
                        <img src="/assets/Movie/imdb.svg" class="movieInfoIconImdb mr-1" alt="IMDB" class="movieInfoIcon" />
                        <span class="movieInfo mr-2"><?php echo $movie->imdbRating?></span>
                        <img src="/assets/Movie/movieClock.svg" class="movieInfoIconClock mr-1" alt="Runtime" class="movieInfoIcon" />
                        <span class="movieInfo mr-2"><?php echo $hour."h"." ".$minutes."m" ?></span>
                        <span class="movieInfo mr-2"><?php echo $year?></span>
                        <span class="movieInfo"><?php echo $movie->genre ?></span>
                    </div>
                    <div class="row mt-2">
                        <span class="movieDesc w100"><?php echo $movie->plot ?></span>
                    </div>
                    <div class="row mt-2">
                        <a href="<?php echo $movie->trailer ?>" target="_blank">
                        <button type="button" class="trailerButton">Watch trailer</button></a>
                    </div>

                    <div class="row mt-2">
                        <div class="column w70">
                            <span class="movieInfoCrewTitle">Stars:</span>
                            <span class="movieInfoCrew">Joaquin Phoenix, Robert De Niro, Zazle Beetz</span>
                        </div>
                        <div class="column w30">
                            <span class="movieInfoCrewTitle">Director:</span>
                            <span class="movieInfoCrew">Joaquin Phoenix</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row movieSelectorRow centerRow">
            <div class="movieDateSelector centerY">
                <span class="movieSelectorTitle mr-2">Date:</span>
                <img src="/assets/Movie/arrowLeft.svg" class="movieArrow" />
                <div class="column centerRow dateColumn o30 ml-2">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">09</span>
                    <span class="movieDay">Sat</span>
                </div>
                <div class="column centerRow dateColumn o50">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">10</span>
                    <span class="movieDay">Sun</span>
                </div>
                <div class="column centerRow dateColumn">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">11</span>
                    <span class="movieDay">Mon</span>
                </div>
                <div class="column centerRow dateColumn o50">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">12</span>
                    <span class="movieDay">Tue</span>
                </div>
                <div class="column centerRow dateColumn o30 mr-2">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">13</span>
                    <span class="movieDay">Thu</span>
                </div>
                <img src="/assets/Movie/arrowRight.svg" class="movieArrow mr-5" />
            </div>
            <div class="column w10 dropdownColumn ml-5">
                <span class="dropdownTitle">Time</span>
                <span class="dropdownSubtitle">Select</span>
                <div class="dropdownContent">
                    <a href="#">18:30</a>
                    <a href="#">19:30</a>
                    <a href="#">20:00</a>
                </div>
            </div> 
            <div class="column w10 dropdownColumn">
                <span class="dropdownTitle">Cinema</span>
                <span class="dropdownSubtitle">Select</span>
                <div class="dropdownContent">
                    <a href="#">Cineplexx BIG</a>
                    <a href="#">Cineplexx Ušće</a>
                    <a href="#">Jagodinski kulturni centar</a>
                </div>
            </div> 
            <div class="column w10 dropdownColumn">
                <span class="dropdownTitle">Country</span>
                <span class="dropdownSubtitle">Select</span>
                <div class="dropdownContent">
                    <a href="#">Serbia</a>
                </div>
            </div> 
            <div class="column w10 dropdownColumn">
                <span class="dropdownTitle">City</span>
                <span class="dropdownSubtitle">Select</span>
                <div class="dropdownContent">
                    <a href="#">Beograd</a>
                    <a href="#">Novi Sad</a>
                    <a href="#">Niš</a>
                    <a href="#">Jagodina</a>
                </div>
            </div> 
        </div>
        
        <div class="row centerX mb-5 movieShowingContent">
            <div class="column w55 centerY tableCol ml-5">
                <span class="showingTitle">Showing in:</span>
                <table class="showingTable">
                    <thead>
                        <th></th>
                        <th class="tableCinemaCol">Cinema</th>
                        <th class="tableTimeCol">Time</th>
                        <th class="tableRoomCol">Room</th>
                        <th class="tableTypeCol">Type</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableImg"><img src="https://via.placeholder.com/150" class="userIcon" /></td>
                            <td class="tableCinemaCol">Cineplexx Ušće</td>
                            <td class="tableTimeCol">18:30</td>
                            <td class="tableRoomCol">Sala 3</td>
                            <td class="tableTypeCol">2D</td>
                        </tr>                        
                        <tr>
                            <td class="tableImg"><img src="https://via.placeholder.com/150" class="userIcon" /></td>
                            <td class="tableCinemaCol">Cineplexx BIG</td>
                            <td class="tableTimeCol">19:00</td>
                            <td class="tableRoomCol">Sala 1</td>
                            <td class="tableTypeCol">3D</td>
                        </tr>                        
                        <tr>
                            <td class="tableImg"><img src="https://via.placeholder.com/150" class="userIcon" /></td>
                            <td class="tableCinemaCol">Tuckwood</td>
                            <td class="tableTimeCol">19:00</td>
                            <td class="tableRoomCol">Merlyn Monroe</td>
                            <td class="tableTypeCol">2D</td>
                        </tr>                        
                        <tr>
                            <td class="tableImg"><img src="https://via.placeholder.com/150" class="userIcon" /></td>
                            <td class="tableCinemaCol">Terzije Teatar</td>
                            <td class="tableTimeCol">21:00</td>
                            <td class="tableRoomCol">Velika sala</td>
                            <td class="tableTypeCol">2D</td>
                        </tr>                        
                        <tr>
                            <td class="tableImg"><img src="https://via.placeholder.com/150" class="userIcon" /></td>
                            <td class="tableCinemaCol">Test Teatar</td>
                            <td class="tableTimeCol">21:00</td>
                            <td class="tableRoomCol">Test sala</td>
                            <td class="tableTypeCol">3D</td>
                        </tr>                        
                    </tbody>
                </table>
            </div>

            <div class="column w45 criticsPart mr-5">
                <span class="criticsTitle mb-3">Critic reviews:</span>
                <div class="column review mb-5">
                    <span class="reviewBody">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ornare quis est tristique vestibulum. Proin eget eros sit amet velit feugiat porta vel malesuada arcu. Cras at nisi et felis cursus fringilla nec nec enim. Aenean porta rhoncus lectus, eu pharetra ex porta in. Fusce nulla neque, bibendum et elit at, commodo congue orci. Nullam vitae orci ante. Pellentesque pulvinar et lacus at pretium. Suspendisse eget sapien orci. Curabitur non commodo velit. Quisque pretium aliquam quam ut mattis. Vivamus dignissim lacus laoreet mattis semper. 
                    </span>
                    <span class="reviewAuthor">
                        Miloš Živković
                    </span>
                </div>
                <div class="column review">
                    <span class="reviewBody">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ornare quis est tristique vestibulum. Proin eget eros sit amet velit feugiat porta vel malesuada arcu. Cras at nisi et felis cursus fringilla nec nec enim. Aenean porta rhoncus lectus, eu pharetra ex porta in. Fusce nulla neque, bibendum et elit at, commodo congue orci. Nullam vitae orci ante. Pellentesque pulvinar et lacus at pretium. Suspendisse eget sapien orci. Curabitur non commodo velit. Quisque pretium aliquam quam ut mattis. Vivamus dignissim lacus laoreet mattis semper. 
                    </span>
                    <span class="reviewAuthor">
                        This is the review 2 author
                    </span>
                </div>
            </div>
        </div>
	</body>
</html>
