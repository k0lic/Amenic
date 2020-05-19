<!--
    Author: Andrija Kolić
    Github: k0lic
-->
<!DOCTYPE html>
<html lang="en">
    <?php
        date_default_timezone_set("Europe/Belgrade");
        $errors = [];
        if (isset($_COOKIE["addMovieErrors"]))
        {
            parse_str($_COOKIE["addMovieErrors"], $errors);
            setcookie("addMovieErrors","",time() - 3600, "/");
        }
        $values = [];
        if (isset($_COOKIE["addMovieValues"]))
        {
            parse_str($_COOKIE["addMovieValues"], $values);
            setcookie("addMovieValues", "", time() - 3600, "/");
        }
    ?>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="/css/style.css"/>
        <!-- TITLE -->
		<title>
            <?php
                if (isset($target) || isset($halfTarget)) {
                    echo "Amenic - Edit projection";
                } else {
                    echo "Amenic - New projection";
                }
            ?>
        </title>
	</head>
	<body onLoad="stopModalPropagation(); setupListeners();">
        <div class="container">
            <!-- SIDE NAVBAR -->
            <div class="menuBar">
                <a href="/"><img src="/assets/logo.svg" class="logo" alt="Amenic" /></a>
                <!-- NAV ITEMS -->
                <ul class="nav">
                    <?php
                        if (!isset($isWorker) || $isWorker==false)
                        {

                            // MOVIES //

                            if (!isset($optionPrimary) || $optionPrimary==0)
                                echo "<li><a href=\"/Cinema\" class=\"activeMenu\">Movies</a></li>";
                            else
                                echo "<li><a href=\"/Cinema\">Movies</a></li>";

                            // ROOMS //

                            if (isset($optionPrimary) && $optionPrimary==1)
                                echo "<li><a href=\"/Cinema/Rooms\" class=\"activeMenu\">Rooms</a></li>";
                            else
                                echo "<li><a href=\"/Cinema/Rooms\">Rooms</a></li>";

                            // EMPLOYEES //

                            if (isset($optionPrimary) && $optionPrimary==2)
                                echo "<li><a href=\"/Cinema/Employees\" class=\"activeMenu\">Employees</a></li>";
                            else
                                echo "<li><a href=\"/Cinema/Employees\">Employees</a></li>";

                        }
                        else
                        {
                            // MOVIES //

                            if (!isset($optionPrimary) || $optionPrimary==0)
                                echo "<li><a href=\"/Cinema\" class=\"activeMenu\">Movies</a></li>";
                            else
                                echo "<li><a href=\"/Cinema\">Movies</a></li>";

                            // RESERVATIONS //

                            if (isset($optionPrimary) && $optionPrimary==1)
                                echo "<li><a href=\"/Worker\" class=\"activeMenu\">Reservations</a></li>";
                            else
                                echo "<li><a href=\"/Worker\">Reservations</a></li>";
                        }
                    ?>
                </ul>
                <!-- SETTINGS -->
                <a href="<?php
                    if (!isset($isWorker) || $isWorker==0)
                        echo "/Cinema/Settings";
                    else
                        echo "/Worker/Settings";
                    ?>">
                    <div class="icon baseline">
                        <svg width="48" height="48" viewBox="0 0 512 512">
							<path
								d="m256 133.61c-67.484 0-122.39 54.906-122.39 122.39s54.906 122.39 122.39 122.39 122.39-54.906 122.39-122.39-54.906-122.39-122.39-122.39zm0 214.18c-50.613 0-91.793-41.18-91.793-91.793s41.18-91.793 91.793-91.793 91.793 41.18 91.793 91.793-41.18 91.793-91.793 91.793z"
							/>
							<path
								d="m499.95 197.7-39.352-8.5547c-3.4219-10.477-7.6602-20.695-12.664-30.539l21.785-33.887c3.8906-6.0547 3.0352-14.004-2.0508-19.09l-61.305-61.305c-5.0859-5.0859-13.035-5.9414-19.09-2.0508l-33.887 21.785c-9.8438-5.0039-20.062-9.2422-30.539-12.664l-8.5547-39.352c-1.5273-7.0312-7.7539-12.047-14.949-12.047h-86.695c-7.1953 0-13.422 5.0156-14.949 12.047l-8.5547 39.352c-10.477 3.4219-20.695 7.6602-30.539 12.664l-33.887-21.785c-6.0547-3.8906-14.004-3.0352-19.09 2.0508l-61.305 61.305c-5.0859 5.0859-5.9414 13.035-2.0508 19.09l21.785 33.887c-5.0039 9.8438-9.2422 20.062-12.664 30.539l-39.352 8.5547c-7.0312 1.5312-12.047 7.7539-12.047 14.949v86.695c0 7.1953 5.0156 13.418 12.047 14.949l39.352 8.5547c3.4219 10.477 7.6602 20.695 12.664 30.539l-21.785 33.887c-3.8906 6.0547-3.0352 14.004 2.0508 19.09l61.305 61.305c5.0859 5.0859 13.035 5.9414 19.09 2.0508l33.887-21.785c9.8438 5.0039 20.062 9.2422 30.539 12.664l8.5547 39.352c1.5273 7.0312 7.7539 12.047 14.949 12.047h86.695c7.1953 0 13.422-5.0156 14.949-12.047l8.5547-39.352c10.477-3.4219 20.695-7.6602 30.539-12.664l33.887 21.785c6.0547 3.8906 14.004 3.0391 19.09-2.0508l61.305-61.305c5.0859-5.0859 5.9414-13.035 2.0508-19.09l-21.785-33.887c5.0039-9.8438 9.2422-20.062 12.664-30.539l39.352-8.5547c7.0312-1.5312 12.047-7.7539 12.047-14.949v-86.695c0-7.1953-5.0156-13.418-12.047-14.949zm-18.551 89.312-36.082 7.8438c-5.543 1.207-9.9648 5.3789-11.488 10.84-3.9648 14.223-9.668 27.977-16.949 40.879-2.7891 4.9414-2.6172 11.02 0.45313 15.793l19.98 31.078-43.863 43.867-31.082-19.98c-4.7734-3.0703-10.852-3.2422-15.789-0.45313-12.906 7.2812-26.66 12.984-40.883 16.949-5.4609 1.5234-9.6328 5.9453-10.84 11.488l-7.8438 36.082h-62.031l-7.8438-36.082c-1.207-5.543-5.3789-9.9648-10.84-11.488-14.223-3.9648-27.977-9.668-40.879-16.949-4.9414-2.7891-11.02-2.6133-15.793 0.45313l-31.078 19.98-43.863-43.867 19.977-31.078c3.0703-4.7734 3.2461-10.852 0.45703-15.793-7.2812-12.902-12.984-26.656-16.953-40.879-1.5234-5.4609-5.9414-9.6328-11.484-10.84l-36.086-7.8438v-62.031l36.082-7.8438c5.543-1.207 9.9648-5.3789 11.488-10.84 3.9648-14.223 9.668-27.977 16.949-40.879 2.7891-4.9414 2.6172-11.02-0.45313-15.793l-19.98-31.078 43.863-43.867 31.082 19.98c4.7734 3.0703 10.852 3.2422 15.789 0.45313 12.906-7.2812 26.66-12.984 40.883-16.949 5.4609-1.5234 9.6328-5.9453 10.84-11.488l7.8438-36.082h62.031l7.8438 36.082c1.207 5.543 5.3789 9.9648 10.84 11.488 14.223 3.9648 27.977 9.668 40.879 16.949 4.9414 2.7891 11.02 2.6133 15.793-0.45313l31.078-19.98 43.863 43.867-19.977 31.078c-3.0703 4.7734-3.2461 10.852-0.45703 15.793 7.2852 12.902 12.984 26.656 16.953 40.879 1.5234 5.4609 5.9414 9.6328 11.484 10.84l36.086 7.8438z"
							/>
						</svg>
                    </div>
                    Settings
                </a>
            </div>
            <!-- CONTENT -->
            <div class="emptyWrapper">
                <!-- PROFILE PICTURE AND NAME -->
                <div class="accountWrapper">
                    <div class="topBar centerY">
                        <div></div>
                        <div class="user">
                            <img src="<?php 
                                    if (!isset($userImage) || $userImage==null)
                                        echo "/assets/profPic.png";
                                    else
                                        echo "data:image/jpg;base64, ".$userImage;
                            ?>" class="profPic" alt="Profile picture" />
                            <span><?php
                                echo $userFullName;
                            ?></span>
                        </div>
                    </div>
                </div>
                <!-- FORM -->
                <div class="formWrapper removeTopPadding">
                    <form method="POST">
                        <!-- TITLE -->
                        <h1 class="formTitle mb-3">
                            <?php
                                if (isset($target) || isset($halfTarget)) {
                                    echo "Edit movie";
                                } else {
                                    echo "Add movie";
                                }
                            ?>
                        </h1>
                        <!-- MOVIE SEARCH BY NAME -->
                        <div class="row mb-2">
                            <div class="column w70">
                                <div class="movieDropdownFrame">
                                    <label for="movieName">Movie name</label>
                                    <input type="text" name="movieName" id="movieNameInput" value="<?php
                                        if (isset($targetName))
                                            echo $targetName;
                                        else if (isset($halfTargetName))
                                            echo $halfTargetName;
                                        else if (isset($values["movieName"]))
                                            echo $values["movieName"];
                                    ?>" <?php if (isset($target) || isset($halfTarget)) echo "disabled"; ?> />
                                    <div class="formError ml-1">
                                        <?php
                                            if(isset($errors["movieName"]))
                                                echo $errors["movieName"];
                                        ?>
                                    </div>
                                    <div id="movieSearchResultsContainer" class="movieDropdownResultsContainer row">
                                        <div class="column movieSearchResultListWrapper">
                                            <ul class="movieSearchResultList" id="movieSearchResults"></ul>
                                            <div class="row spaceBetween mt-2">
                                                <div class="movieSearchArrow movieSearchActiveControl column centerRow" id="leftArrow">
                                                    <img src="/assets/Movie/arrowLeft.svg" class="movieArrow" />
                                                </div>
                                                <div class="row centerRow ml-1 mr-1" id="movieSearchPageNumbers">
                                                    <div class="movieSearchPageNumber column centerRow movieSearchActiveControl hidden" id="firstPage">1</div>
                                                    <div class="movieSearchDots hidden" id="firstDots">...</div>
                                                    <div class="movieSearchPageNumber column centerRow movieSearchActiveControl hidden" id="previousPage">1</div>
                                                    <div class="movieSearchPageNumber column centerRow movieSearchCurrentPageNumber" id="currentPage">1</div>
                                                    <div class="movieSearchPageNumber column centerRow movieSearchActiveControl hidden" id="nextPage">1</div>
                                                    <div class="movieSearchDots hidden" id="lastDots">...</div>
                                                    <div class="movieSearchPageNumber column centerRow movieSearchActiveControl hidden" id="lastPage">1</div>
                                                </div>
                                                <div class="movieSearchArrow movieSearchActiveControl column centerRow" id="rightArrow">
                                                    <img src="/assets/Movie/arrowRight.svg" class="movieArrow" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="tmdbID" id="tmdbIDInput" value="<?php
                                    if (isset($target))
                                        echo $target->tmdbID;
                                    else if (isset($halfTarget))
                                        echo $halfTarget->tmdbID;
                                    else if (isset($values["tmdbID"]))
                                        echo $values["tmdbID"];
                                ?>" <?php if (isset($target) || isset($halfTarget)) echo "readonly"; ?> maxlength="64" />
                                <div class="formError ml-1">
                                    <?php 
                                        if(isset($errors["tmdbID"]))
                                            echo $errors["tmdbID"];
                                    ?>
                                </div>
                                <?php
                                    if (isset($target))
                                    {
                                        echo "<input type=\"hidden\" name=\"oldIdPro\" value=\"$target->idPro\" />";
                                        echo "<div class=\"formError ml-1\">".(isset($errors["oldIdPro"])?$errors["oldIdPro"]:"")."</div>";
                                    }
                                ?>
                            </div>
                        </div>
                        <!-- TWO DYNAMIC SELECTS - ROOM AND TECH -->
                        <div class="row mb-2">
                            <div class="column w30 mr-5">
                                <label for="room">Room</label>
                                <select class="formSelect" name="room" <?php if (isset($target)) echo "disabled"; ?>>
                                    <?php
                                        foreach ($rooms as $room)
                                        {
                                            $isSelected = false;
                                            if (isset($target))
                                                $isSelected = $target->roomName == $room->name;
                                            else if (isset($values["room"]))
                                                $isSelected = $values["room"] == $room->name;
                                            echo "<option value=\"$room->name\"".($isSelected?" selected":"").">$room->name</option>";
                                        }
                                    ?>
                                </select>
                                <div class="formError ml-1">
                                    <?php 
                                        if(isset($errors["room"]))
                                            echo $errors["room"];
                                    ?>
                                </div>
                            </div>
                            <div class="column w25">
                                <label for="tech">Technology</label>
                                <select class="formSelect" name="tech" <?php if (isset($target)) echo "disabled"; ?>>
                                    <?php
                                        if (isset($target))
                                            $selectedTechId = $target->idTech;
                                        else if (isset($values["tech"]))
                                            $selectedTechId = $values["tech"];
                                        foreach ($technologies as $tech)
                                        {
                                            $isSelected = isset($selectedTechId) && $selectedTechId == $tech->idTech;
                                            echo "<option value=\"$tech->idTech\"".($isSelected?" selected":"").">$tech->name</option>";
                                        }
                                    ?>
                                </select>
                                <div class="formError ml-1">
                                    <?php 
                                        if(isset($errors["tech"]))
                                            echo $errors["tech"];
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- CALENDAR -->
                        <div class="row mb-2">
                            <div class="column w40 mr-5">
                                <label for="placeholder">Date of projection</label>
                                <div class="calendar">
                                    <?php

                                        $targetDateInput = isset($values["startDate"]) ? $values["startDate"] : (isset($target) ? $target->dateTime : "1972-01-01");
                                        $targetDate = strtotime(date("Y-m-d", strtotime($targetDateInput)));

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
                                        <input type="hidden" id="selectedDateHidden" name="startDate" value="<?php
                                            if (isset($values["startDate"]) || isset($target))
                                                echo date("Y-m-d", $targetDate);
                                            else
                                                echo date("Y-m-d", time());
                                        ?>" />
                                    </div>
                                    <div class="formError ml-1">
                                        <?php 
                                            if(isset($errors["startDate"]))
                                                echo $errors["startDate"];
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!-- TWO NUMBER INPUTS - START TIME AND PRICE -->
                            <div class="column w30">
                                <label for="startTime">Start time</label>
                                <input type="time" name="startTime" value="<?php
                                    if (isset($values["startTime"]))
                                        echo date("H:i", strtotime($values["startTime"]));
                                    else if (isset($target))
                                        echo date("H:i", strtotime($target->dateTime));
                                    else
                                        echo date("H:i", time());
                                ?>" />
                                <div class="formError ml-1 mb-2">
                                    <?php 
                                        if(isset($errors["startTime"]))
                                            echo $errors["startTime"];
                                    ?>
                                </div>
                                <label for="price">Ticket price (&euro;)</label>
                                <input type="number" name="price" min="0" step="0.01" value="<?php
                                    if (isset($target))
                                        echo $target->price;
                                    else if (isset($values["price"]))
                                        echo $values["price"];
                                ?>" <?php if (isset($target)) echo "disabled"; ?> />
                                <div class="formError ml-1">
                                    <?php 
                                        if(isset($errors["price"]))
                                            echo $errors["price"];
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- CHECKBOX AND SUBMIT - ADD TO SOON -->
                        <div class="row mb-2 centerY">
                            <div class="column w25 mr-5">
                                <div class="row">
                                    <input type="checkbox" name="soon" class="formCheckbox" value="soonVal" <?php
                                        if (isset($target))
                                            echo "";
                                        else if (isset($values["soon"]))
                                            echo "checked";
                                        else if (!isset($values["tmdbID"]) && isset($halfTarget))
                                            echo "checked";
                                    ?> <?php if (isset($target)) echo "disabled"; ?> />
                                    <label for="soon">Add to Soon</label>
                                </div>
                                <div class="formError ml-1">
                                    <?php 
                                        if(isset($errors["soon"]))
                                            echo $errors["soon"];
                                    ?>
                                </div>
                            </div>
                            <?php
                                if (isset($target) || isset($halfTarget))
                                    echo "<div class=\"column w30\">
                                            <button type=\"button\"
                                            formaction=\"/Cinema/".(isset($target)?"ActionCancelMovie":"actionCancelComingSoon")."\"
                                            onClick=\"areYouSure('You are about to cancel a movie','/Cinema/".(isset($target)?"ActionCancelMovie":"ActionCancelComingSoon")."')\"
                                            class=\"standardButton badButton\">Cancel movie</button>
                                        </div>
                                    ";
                            ?>
                            <div class="column w30">
                                <button type="submit" formaction="/Cinema/<?php
                                    if (isset($target))
                                        echo "ActionEditMovie";
                                    else if (isset($halfTarget))
                                        echo "ActionReleaseComingSoon";
                                    else
                                        echo "ActionAddMovie";
                                ?>" class="standardButton goodButton"><?php
                                    if (isset($target) || isset($halfTarget))
                                        echo "Save changes";
                                    else
                                        echo "Add movie";
                                ?></button>
                            </div>
                        </div>
                        <!-- ARE YOU SURE? MODAL -->
                        <?php
                            include 'AreYouSure.php';
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script src="/js/calendar.js"></script>
    <script src="/js/areYouSure.js"></script>
    <script src="/js/addMovieSearch.js"></script>
</html>
