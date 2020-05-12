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
                <img src="/assets/Movie/arrowLeft.svg" class="movieArrow" id="movieArrowLeft"/>
                <div class="column centerRow dateColumn o30 ml-2" id="movieDateO1">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">09</span>
                    <span class="movieDay">Sat</span>
                </div>
                <div class="column centerRow dateColumn o50" id="movieDateI1">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">10</span>
                    <span class="movieDay">Sun</span>
                </div>
                <div class="column centerRow dateColumn" id="movieDate">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">11</span>
                    <span class="movieDay">Mon</span>
                </div>
                <div class="column centerRow dateColumn o50" id="movieDateI2">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">12</span>
                    <span class="movieDay">Tue</span>
                </div>
                <div class="column centerRow dateColumn o30 mr-2" id="movieDateO2">
                    <span class="movieMonth">May</span>
                    <span class="movieDate">13</span>
                    <span class="movieDay">Thu</span>
                </div>
                <img src="/assets/Movie/arrowRight.svg" class="movieArrow mr-5" id="movieArrowRight"/>
            </div>
            
            <div class="column w10 dropdownColumn ml-5" id="timeColumn">
                <ul>
                    <li><div class="column">
                    <span class="dropdownTitle">Time</span>
                    <span class="dropdownSubtitle" id="timeSelect">Select</span>
                    </div>
                        <ul class="dropdown">
                            <li><a href="#">18:30</a></li>
                            <li><a href="#">19:30</a></li>
                            <li><a href="#">20:00</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            
            <div class="column w10 dropdownColumn" id="cinemaColumn">
                <ul>
                    <li><div class="column">
                    <span class="dropdownTitle">Cinema</span>
                    <span class="dropdownSubtitle" id="cinemaSelect">Select</span>
                    </div>
                        <ul class="dropdown">
                            <li><a href="#">Cineplexx BIG</a></li>
                            <li><a href="#">Cineplexx Ušće</a></li>
                            <li><a href="#">Jagodinski kulturni centar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="column w10 dropdownColumn" id="countryColumn">
                <ul>
                    <li><div class="column">
                    <span class="dropdownTitle">Country</span>
                    <span class="dropdownSubtitle" id="countrySelect">Select</span>
                    </div>
                        <ul class="dropdown">
                            <li><a href="#">Serbia</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            
            <div class="column w10 dropdownColumn" id="cityColumn">
                <ul>
                    <li><div class="column">
                    <span class="dropdownTitle">City</span>
                    <span class="dropdownSubtitle" id="citySelect">Select</span>
                    </div>
                        <ul class="dropdown" id="cityDropdown">
                            <li><a href="#" class="cityDropdownItem" id="cityBeograd">Beograd</a></li>
                            <li><a href="#" class="cityDropdownItem" id="cityNovi Sad">Novi Sad</a></li>
                            <li><a href="#" class="cityDropdownItem" id="cityNiš">Niš</a></li>
                            <li><a href="#" class="cityDropdownItem" id="cityJagodina">Jagodina</a></li>
                        </ul>
                    </li>
                </ul>
            </div> 
        </div>
        
        <div class="row centerX mb-5 movieShowingContent">
            <div class="column w55 centerY tableCol ml-5">
                <span class="showingTitle">Showing in:</span>

                <div class="showingTable column">
                    <div class="showingTableHeader row centerY">
                        <div class="w10"></div>
                        <div class="w30 textCenter">
                            Cinema
                        </div>
                        <div class="w20 textCenter">
                            Time
                        </div>
                        <div class="w20 textCenter">
                            Room
                        </div>
                        <div class="w20 textCenter">
                            Type
                        </div>
                    </div>
                    <div class="showingTableRow row centerY mb-1">
                        <div class="w10 column centerRow">
                            <img src="https://via.placeholder.com/150" class="userIcon" />
                        </div>
                        <div class="w30 textCenter">
                            Cineplexx Ušće
                        </div>
                        <div class="w20 textCenter">
                            18:30
                        </div>
                        <div class="w20 textCenter">
                            Sala 3
                        </div>
                        <div class="w20 textCenter">
                            2D
                        </div>
                    </div>
                    <div class="showingTableRow row centerY mb-1">
                        <div class="w10 column centerRow">
                            <img src="https://via.placeholder.com/150" class="userIcon" />
                        </div>
                        <div class="w30 textCenter">
                            Cineplexx BIG
                        </div>
                        <div class="w20 textCenter">
                            19:00
                        </div>
                        <div class="w20 textCenter">
                            Sala 1
                        </div>
                        <div class="w20 textCenter">
                            3D
                        </div>
                    </div>
                    <div class="showingTableRow row centerY mb-1">
                        <div class="w10 column centerRow">
                            <img src="https://via.placeholder.com/150" class="userIcon" />
                        </div>
                        <div class="w30 textCenter">
                            Tuckwood
                        </div>
                        <div class="w20 textCenter">
                            19:00
                        </div>
                        <div class="w20 textCenter">
                            Merlyn Monroe
                        </div>
                        <div class="w20 textCenter">
                            2D
                        </div>
                    </div>
                    <div class="showingTableRow row centerY mb-1">
                        <div class="w10 column centerRow">
                            <img src="https://via.placeholder.com/150" class="userIcon" />
                        </div>
                        <div class="w30 textCenter">
                            Terazije Teatar
                        </div>
                        <div class="w20 textCenter">
                            21:00
                        </div>
                        <div class="w20 textCenter">
                            Velika sala
                        </div>
                        <div class="w20 textCenter">
                            2D
                        </div>
                    </div>
                    <div class="showingTableRow row centerY">
                        <div class="w10 column centerRow">
                            <img src="https://via.placeholder.com/150" class="userIcon" />
                        </div>
                        <div class="w30 textCenter">
                            Test Teatar
                        </div>
                        <div class="w20 textCenter">
                            21:00
                        </div>
                        <div class="w20 textCenter">
                            Tst Sala
                        </div>
                        <div class="w20 textCenter">
                            3D
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="showingTablePagination">
                            <div class="column centerRow showingTableArrow mr-3">
                                <img src="/assets/Movie/arrowLeft.svg" class="movieArrow" id="movieArrowLeft" />
                            </div>
                            <div class="column centerRow showingTableArrow">
                                <img src="/assets/Movie/arrowRight.svg" class="movieArrow" id="movieArrowLeft" />
                            </div>
                        </div>
                    </div>
                </div>
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
    <script src="/js/movieDateSelector.js"></script>
    <script src="/js/movieSearchFilter.js"></script>
</html>
