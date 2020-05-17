<!--
    Author: Andrija Kolić
    Github: k0lic
-->
<!DOCTYPE html>
<html lang="en">
    <!-- STARTUP PHP CODE -->
    <?php
        date_default_timezone_set("Europe/Belgrade");

        $loginError = '';
        if(isset($_COOKIE['loginError'])) {
            $loginError = $_COOKIE['loginError'];
        }
    
        $resetError = '';
        if(isset($_COOKIE['resetError'])) {
            $resetError = $_COOKIE['resetError'];
        }
    ?>
    <!-- HEAD -->
	<head>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<title>Amenic - <?php echo $cinema->name; ?></title>
    </head>
    <!-- BODY -->
    <body onLoad="<?php
        echo "setupOnLoad('".$cinema->email."','".date('Ymd', strtotime('today'))."','".$userIsLoggedIn."');";
        echo " stopCarouselPropagation();";
    ?>">
		<div class="container column">
            <!-- HORIZONTAL NAVIGATION -->
            <div class="horizontalNav">
                <a href="/">
                    <img src="/assets/Common/svg/logo.svg" class="logo" alt="Amenic" />
                </a>
                <ul>
                    <li><a href="/HomeController">Movies</a></li>
                    <li><a href="/HomeController/Cinemas">Cinemas</a></li>
                    <li>
                        <div class="user"><?php
                            if ($userIsLoggedIn)
                            {
                                $image = $userImage == null ? "/assets/defaultUserImage.jpg" : "data:image/jpg;base64, ".$userImage;
                                echo "
                                    <img src=\"".$image."\" class=\"profPic\" alt=\"Profile picture\" />
                                    <span>".$userFullName."</span>
                                ";
                            }
                            else
                            {
                                include "../app/Views/loginModal.php";
                                //echo "<div>Gost</div>";
                            }
                        ?></div>
                    </li>
                </ul>
            </div>
            <!-- BACKGROUND CINEMA BANNER -->
            <!--div class="cinemaBackground centerRow" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(<?php
                /*if ($cinema->banner == null)
                    echo "/assets/Cinema/room.jpg";
                else
                    echo "data:image/jpg;base64, ".$cinema->banner;*/
            ?>); background-position: center bottom; background-size: cover; background-repeat: no-repeat;"></div-->
            <img class="cinemaBackgroundV2 centerRow" src="<?php
                echo "/assets/Cinema/cinemaBG.jpg";
            ?>" />
            <!-- OVER AND UNDER THE BANNER -->
            <div class="row">
                <!-- LEFT COLUMN -->
                <div class="column w70 cinemaInfoWrapper">
                    <!-- MAIN CINEMA INFO DIV -->
                    <div class="row cinemaInfo">
                        <img src="<?php
                            if ($cinema->banner == null)
                                echo "/assets/defaultUserImage.jpg";
                            else
                                echo "data:image/jpg;base64, ".$cinema->banner;
                        ?>" class="cinemaProfileIcon" />
                        <div class="column w70">
                            <div class="cinemaName"><?php
                                echo $cinema->name;
                            ?></div>
                            <div class="cinemaDesc"><?php
                                if ($cinema->description != null)
                                    echo $cinema->description;
                                else
                                    echo "<div class=\"movieSearchItemEmpty\">No description</div>";
                            ?></div>
                        </div>
                    </div>
                    <!-- CINEMA REPERTOIRE -->
                    <div class="row centerX mb-5 movieShowingContent" >
                        <div class="column w90 centerY tableCol ml-5">
                            <div class="row mb-3">
                                <span class="repertoireTableTitle repertoireTableTitleSelected" id="repertoireShowing" onClick="changeMode(0)">Showing</span>
                                <span class="repertoireTableTitle ml-4" id="repertoireSoon" onClick="changeMode(1)">Soon</span>
                            </div>
                            <!-- REPERTOIRE HEADER -->
                            <div class="showingTableHeader row centerY" id="repertoireHeader">
                                <div class="w30 textCenter">Name</div>
                                <div class="w15 textCenter">Time</div>
                                <div class="w20 textCenter">Room</div>
                                <div class="w15 textCenter">Type</div>
                                <div class="w20 textCenter">Free seats</div>
                            </div>
                            <div class="repertoireReserveHeight">
                                <div class="showingTablePatch column centerRow" id="showingTable">
                                    <!-- REPERTOIRE CONTENT -->
                                </div>
                            </div>
                            <!-- REPERTOIRE PAGINATION -->
                            <div class="row spaceBetween mt-2">
                                <div class="movieSearchArrow movieSearchActiveControl column centerRow" id="leftArrow" onClick="pageBack()">
                                    <img src="/assets/Movie/arrowLeft.svg" class="movieArrow" />
                                </div>
                                <div class="row centerRow ml-1 mr-1" id="movieSearchPageNumbers">
                                    <div class="movieSearchPageNumber column centerRow movieSearchActiveControl hidden" id="firstPage" onClick="pageFirst()">1</div>
                                    <div class="movieSearchDots hidden" id="firstDots">...</div>
                                    <div class="movieSearchPageNumber column centerRow movieSearchActiveControl hidden" id="previousPage" onClick="pageBack()">1</div>
                                    <div class="movieSearchPageNumber column centerRow movieSearchCurrentPageNumber" id="currentPage">1</div>
                                    <div class="movieSearchPageNumber column centerRow movieSearchActiveControl hidden" id="nextPage" onClick="pageForward()">1</div>
                                    <div class="movieSearchDots hidden" id="lastDots">...</div>
                                    <div class="movieSearchPageNumber column centerRow movieSearchActiveControl hidden" id="lastPage" onClick="pageLast()">1</div>
                                </div>
                                <div class="movieSearchArrow movieSearchActiveControl column centerRow" id="rightArrow" onClick="pageForward()">
                                    <img src="/assets/Movie/arrowRight.svg" class="movieArrow" />
                                </div>
                            </div>
                            <?php
                                if ($userIsLoggedIn == false)
                                    echo "<div class=\"movieImgText movieSearchItemEmpty mt-2\">Please log in in order to proceed to reservation.</div>";
                            ?>
                        </div>
                    </div>
                </div>
                <!-- RIGHT COLUMN -->
                <div class="column w25 mr-5">
                    <!-- CINEMA CONTACT INFO -->
                    <div class="cinemaContactInfo">
                        <div class="row">
                            <img src="/assets/Cinema/location.png" class="smallIcon" />
                            <div class="ml-1"><?php
                                echo $cinema->address;
                                if (isset($cinemaCity) && $cinemaCity!=null)
                                    echo " &middot; ".$cinemaCity;
                                if (isset($cinemaCountry) && $cinemaCountry!=null)
                                    echo " &middot; ".$cinemaCountry;
                            ?></div>
                        </div>
                        <div class="row mt-2">
                            <img src="/assets/Cinema/phone.png" class="smallIcon" />
                            <div class="ml-1"><?php
                                echo $cinema->phoneNumber;
                            ?></div>
                        </div>
                    </div>
                    <!-- CALENDAR -->
                    <div class="smallCalendarHeader">Pick a day</div>
                    <div class="smallCalendarWrapper"> 
                        <div class="calendar">
                            <?php

                                $targetDate = strtotime("today");

                                $days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
                                $now = strtotime("today");
                                $start = $now;
                                $day = date("w",$start);
                                while ($day != 1)
                                {
                                    $start -= (24*60*60);
                                    $day = date("w",$start);
                                }
                                $curr = $start;

                                echo "<div class=\"row centerRow mt-1\">";
                                for ($i=0;$i<7;$i++)
                                {
                                    echo "<div class=\"dayHeader\">".$days[$i]."</div>";
                                }
                                echo "</div><hr class=\"mb-1\"/>";

                                for ($i=0;$i<2;$i++)
                                {
                                    echo "<div class=\"row centerRow\">";
                                    for ($j=1;$j<8;$j++)
                                    {
                                        if ($curr<$now)
                                        {
                                            echo "<div class=\"yesterdayButton\">".date("j",$curr)."</div>";
                                        }
                                        else if ($curr == $now)
                                        {
                                            $isSelected = $curr == $targetDate;
                                            echo "<button type=\"button\" id=\"buttonDate_".date("Ymd",$curr)."\" class=\"todayButton".($isSelected?" selectedDay":"")."\" onClick=\"selectDate(".date("Ymd",$curr).")\">".date("j",$curr)."</button>";
                                        }
                                        else
                                        {
                                            $isSelected = $curr == $targetDate;
                                            echo "<button type=\"button\" id=\"buttonDate_".date("Ymd",$curr)."\" class=\"dayButton".($isSelected?" selectedDay":"")."\"  onClick=\"selectDate(".date("Ymd",$curr).")\">".date("j",$curr)."</button>";
                                        }
                                        $curr += (24*60*60);
                                    }
                                    echo "</div>";
                                }
                            ?>
                            <div class="row mt-1 ml-2 mb-2">
                                <span>Selected:&nbsp;</span><span id="selectedDate">
                                    <?php
                                        if (isset($values["startDate"]) || isset($target))
                                            echo date("d/m/Y", $targetDate);
                                        else
                                            echo date("d/m/Y", time());
                                    ?>
                                </span>
                            </div>
                            <div class="row"></div>
                        </div>
                    </div>
                    <!-- END OF CALENDAR -->
                    <!-- GALLERY -->
                    <div class="gallery mb-2 w100">
                        <div class="column">
                            <div class="mb-1">Gallery</div>
                            <!--form method="POST" action="/Theatre/ActionAddImage" enctype="multipart/form-data" class="column">
                                <label class="goodButton">
                                    <input type="file" name="newImage" />
                                    Browse
                                </label>
                                <input type="hidden" name="email" value="<?php
                                    echo $cinema->email;
                                ?>" />
                                <button type="submit" class="goodButton mt-1">Save</button>
                            </form-->
                        </div>
                        <div class="galleryItems">
                            <?php
                                if (isset($gallery) && count($gallery)>0)
                                {
                                    $cnt = 0;
                                    foreach ($gallery as $picture)
                                    {
                                        if ($cnt<6)
                                        {
                                            echo "
                                                    <img src=\"data:image/jpg;base64, ".$picture->image."\" class=\"galleryItem coolLink\" onclick=\"openCarousel(".$cnt.")\" />
                                            ";
                                        }
                                        else
                                        {
                                            break;
                                        }
                                        $cnt++;
                                    }
                                }
                            ?>
                        </div>
                    </button>
                </div>
            </div>
            <!-- CAROUSEL MODAL -->
            <?php
                include "CarouselModal.php";
            ?>
        </div>
    </body>
    <script src="/js/carousel.js"></script>
    <script src="/js/theatreRepertoire.js"></script>
</html>
