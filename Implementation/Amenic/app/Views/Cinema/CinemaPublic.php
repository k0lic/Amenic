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

        $errors = [];
        if (isset($_COOKIE["addGalleryImageErrors"]))
        {
            parse_str($_COOKIE["addGalleryImageErrors"], $errors);
            setcookie("addGalleryImageErrors","",time() - 3600, "/");
        }

        if ($cinema->banner == null)
            $cinemaBannerSource = "/assets/profPic.png";
        else
            $cinemaBannerSource = "data:image/jpg;base64, ".$cinema->banner;
    ?>
    <!-- HEAD -->
	<head>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="/css/style.css"/>
        <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>/favicon.ico">
		<title>Amenic - <?php echo $cinema->name; ?></title>
    </head>
    <!-- BODY -->
    <body onLoad="<?php
        echo "setupOnLoad('".$cinema->email."','".date('Ymd', strtotime('today'))."','".$userIsLoggedIn."','".$cinemaBannerSource."');";
        echo " stopCarouselPropagation();";
    ?>">
		<div class="container column">
            <!-- HORIZONTAL NAVIGATION -->
            <div class="horizontalNav">
                <a href="/">
                    <img src="/assets/Common/svg/logo.svg" class="logo" alt="Amenic" />
                </a>
                <ul>
                    <?php
                        if ($cinemaIsLoggedIn)
                        {
                            echo "<li><a href=\"/Cinema\">Movies</a></li>";
                            echo "<li><a href=\"/Cinema/Rooms\">Rooms</a></li>";
                            echo "<li><a href=\"/Cinema/Employees\">Employees</a></li>";
                        }
                        else
                        {
                            echo "<li><a href=\"/HomeController\">Movies</a></li>";
                            echo "<li><a href=\"/HomeController/Cinemas\">Cinemas</a></li>";
                        }
                    ?>
                    <li>
                        <div class="user"><?php
                            if ($userIsLoggedIn || $cinemaIsLoggedIn)
                            {
                                $image = $userImage == null ? "/assets/profPic.png" : "data:image/jpg;base64, ".$userImage;
                                echo "
                                    <img src=\"".$image."\" class=\"profPic\" alt=\"Profile picture\" />
                                    <span>".$userFullName."</span>
                                ";
                            }
                            else
                            {
                                include "../app/Views/loginModal.php";
                            }
                        ?></div>
                    </li>
                </ul>
            </div>
            <!-- BACKGROUND CINEMA BANNER -->
            <img class="cinemaBackgroundV2 centerRow" src="<?php
                echo "/assets/Cinema/cinemaBG.jpg";
            ?>" />
            <!-- OVER AND UNDER THE BANNER -->
            <div class="row">
                <!-- LEFT COLUMN -->
                <div class="column w70 cinemaInfoWrapper">
                    <!-- MAIN CINEMA INFO DIV -->
                    <div class="row cinemaInfo">
                        <!-- CINEMA BANNER -->
                        <?php
                            if ($cinemaIsLoggedIn)
                            {
                                echo "
                                    <div class=\"column centerY\">
                                ";
                            }
                        ?>
                        <img src="<?php
                            echo $cinemaBannerSource;
                        ?>" class="cinemaProfileIcon" id="cinemaBannerImage" />
                        <!-- CINEMA BANNER EDITOR -->
                        <?php
                            if ($cinemaIsLoggedIn)
                            {
                                echo "
                                        <form method=\"POST\" action=\"/Theatre/ActionChangeBanner\" enctype=\"multipart/form-data\" class=\"column centerRow mt-1\">
                                            <div class=\"row\">
                                                <label class=\"galleryAdminButton\">
                                                    <input type=\"file\" onchange=\"previewBanner()\" name=\"newBanner\" id=\"newBannerSource\" required accept=\".jpg, .jpeg, .png\" />
                                                    Browse
                                                </label>
                                                <button type=\"submit\" class=\"galleryAdminButton galleryHidden ml-1\" id=\"newBannerSubmit\">Save change</button>
                                            </div>
                                        </form>
                                        <div class=\"formError\">".(isset($errors["newBanner"]) ? $errors["newBanner"] : "")."</div>
                                    </div>
                                ";
                            }
                        ?>
                        <!-- CINEMA DESCRIPTION -->
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
                                <div class="w10 textCenter">Time</div>
                                <div class="w20 textCenter">Room</div>
                                <div class="w10 textCenter">Type</div>
                                <div class="w15 textCenter">Price</div>
                                <div class="w15 textCenter">Free seats</div>
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
                                if ($userIsLoggedIn == false && $cinemaIsLoggedIn == false)
                                    echo "<div class=\"movieImgText movieSearchItemEmpty mt-2\">Please log in in order to proceed to reservation.</div>";
                            ?>
                        </div>
                    </div>
                </div>
                <!-- RIGHT COLUMN -->
                <div class="column w25 mr-5">
                    <!-- CINEMA CONTACT INFO -->
                    <div class="cinemaContactInfo">
                        <div class="row centerY">
                            <img src="/assets/Cinema/pin.svg" class="smallIcon" />
                            <div class="ml-1"><?php
                                echo $cinema->address;
                                if (isset($cinemaCity) && $cinemaCity!=null)
                                    echo " &middot; ".$cinemaCity;
                                if (isset($cinemaCountry) && $cinemaCountry!=null)
                                    echo " &middot; ".$cinemaCountry;
                            ?></div>
                        </div>
                        <div class="row centerY mt-2">
                            <img src="/assets/Cinema/phone.svg" class="smallIcon" />
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
                        <!-- ADD NEW IMAGE TO GALLERY -->
                        <?php
                            if ($cinemaIsLoggedIn)
                            {
                                $localFormErrorPart1 = isset($errors["newImage"]) ? $errors["newImage"] : null;
                                $localFormErrorPart2 = isset($errors["imageName"]) ? $errors["imageName"] : null;
                                $localFormError = $localFormErrorPart1 == null ? "" : $localFormErrorPart1;
                                $localFormError = $localFormErrorPart2 == null ? $localFormError : ($localFormErrorPart1 == null ? $localFormErrorPart2 : $localFormErrorPart1."<br/>".$localFormErrorPart2);
                                echo "
                                    <div class=\"column mt-3\">
                                        <form method=\"POST\" action=\"/Theatre/ActionAddImage\" enctype=\"multipart/form-data\" class=\"column centerRow mb-2\">
                                            <div class=\"row mb-1\">
                                                <label class=\"galleryAdminButton\">
                                                    <input type=\"file\" onchange=\"showPicture()\" name=\"newImage\" id=\"galleryNewImageSource\" required accept=\".jpg, .jpeg, .png\" />
                                                    Browse
                                                </label>
                                                <button type=\"submit\" class=\"galleryAdminButton galleryHidden ml-1\" id=\"galleryNewImageSubmit\">Add image</button>
                                            </div>
                                            <img src=\"https://via.placeholder.com/100x76\" class=\"galleryAdminPreview galleryHidden\" id=\"galleryNewImagePreview\" />
                                            <div class=\"formError\">".$localFormError."</div>
                                        </form>
                                    </div>
                                ";
                            }
                        ?>
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
