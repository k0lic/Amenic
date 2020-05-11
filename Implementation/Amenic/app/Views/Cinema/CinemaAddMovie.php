<!--
    Author: Andrija Kolić
    Github: k0lic
-->
<!DOCTYPE html>
<html lang="en">
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
	<body>
        <div class="container">
            <!-- SIDE NAVBAR -->
            <div class="menuBar">
                <a href="/"><img src="/assets/logo.svg" class="logo" alt="Amenic" /></a>
                <!-- NAV ITEMS -->
                <ul class="nav">
                    <li>
                        <!-- MOVIES -->
                        <?php 
                            if (!isset($optionPrimary) || $optionPrimary==0)
                                echo "<a href=\"/Cinema\" class=\"activeMenu\">Movies</a>";
                            else
                                echo "<a href=\"/Cinema\">Movies</a>";
                        ?>
                    </li>
                    <li>
                        <!-- ROOMS -->
                        <?php 
                            if (isset($optionPrimary) && $optionPrimary==1)
                                echo "<a href=\"/Cinema/Rooms\" class=\"activeMenu\">Rooms</a>";
                            else
                                echo "<a href=\"/Cinema/Rooms\">Rooms</a>";
                        ?>
                    </li>
                    <li>
                        <!-- EMPLOYEES -->
                        <?php 
                            if (isset($optionPrimary) && $optionPrimary==2)
                                echo "<a href=\"/Cinema/Employees\" class=\"activeMenu\">Employees</a>";
                            else
                                echo "<a href=\"/Cinema/Employees\">Employees</a>";
                        ?>
                    </li>
                </ul>
                <!-- SETTINGS -->
                <a href="#">
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
                    <div class="topBar">
                        <div></div>
                        <div class="user">
                            <img src="/assets/profPic.png" class="profPic" alt="Profile picture" />
                            <span>Lošmi</span>
                        </div>
                    </div>
                </div>
                <!-- FORM -->
                <div class="formWrapper">
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
                        <!-- TEXT INPUT -->
                        <div class="row mb-2">
                            <div class="column w35">
                                <label for="movieName">Movie name</label>
                                <input type="text" name="movieName" value="<?php
                                    if (isset($targetName))
                                        echo $targetName;
                                    else if (isset($halfTargetName))
                                        echo $halfTargetName;
                                ?>" />
                                <input type="hidden" name="tmdbID" value="<?php
                                    if (isset($target))
                                        echo $target->tmdbID;
                                    else if (isset($halfTarget))
                                        echo $halfTarget->tmdbID;
                                ?>" />
                                <?php
                                    if (isset($target))
                                        echo "<input type=\"hidden\" name=\"oldIdPro\" value=\"$target->idPro\" />";
                                ?>
                                <?php
                                    if (isset($halfTarget))
                                        echo "<input type=\"hidden\" name=\"oldtmdbID\" value=\"$halfTarget->tmdbID\" />";
                                ?>
                                <input type="hidden" name="oldIdPro" />
                            </div>
                        </div>
                        <!-- TWO DYNAMIC SELECTS -->
                        <div class="row mb-2">
                            <div class="column w30 mr-5">
                                <label for="room">Room</label>
                                <select class="formSelect" name="room">
                                    <?php
                                        foreach ($rooms as $room)
                                        {
                                            $isSelected = isset($target) && $target->roomName == $room->name;
                                            echo "<option value=\"$room->name\"".($isSelected?" selected":"").">$room->name</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="column w25">
                                <label for="tech">Technology</label>
                                <select class="formSelect" name="tech">
                                    <?php
                                        foreach ($technologies as $tech)
                                        {
                                            $isSelected = isset($target) && $target->idTech == $tech->idTech;
                                            echo "<option value=\"$tech->idTech\"".($isSelected?" selected":"").">$tech->name</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- CALENDAR -->
                        <div class="row mb-2">
                            <div class="column w40 mr-5">
                                <label for="placeholder">Date of projection</label>
                                <div class="calendar">
                                    <?php

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
                                                    $isSelected = isset($target) && $curr == (strtotime($target->dateTime)-strtotime($target->dateTime)%(24*60*60));
                                                    echo "<button type=\"button\" id=\"buttonDate_".date("Ymd",$curr)."\" class=\"todayButton".($isSelected?" selectedDay":"")."\" onClick=\"selectDate(".date("Ymd",$curr).")\">".date("j",$curr)."</button>";
                                                }
                                                else
                                                {
                                                    $isSelected = isset($target) && $curr == (strtotime($target->dateTime)-strtotime($target->dateTime)%(24*60*60));
                                                    echo "<button type=\"button\" id=\"buttonDate_".date("Ymd",$curr)."\" class=\"dayButton".($isSelected?" selectedDay":"")."\"  onClick=\"selectDate(".date("Ymd",$curr).")\">".date("j",$curr)."</button>";
                                                }
                                                $curr += (24*60*60);
                                            }
                                            echo "</div>";
                                        }
                                    ?>
                                    <div class="row mt-1 ml-2 mb-2">
                                        <span>Selected:&nbsp;</span><span id="selectedDate" value="<?php
                                            if (isset($target))
                                                echo date("d/m/Y", strtotime($target->dateTime));
                                        ?>"></span>
                                        <input type="hidden" id="selectedDateHidden" name="startDate" value="<?php
                                            if (isset($target))
                                                echo date("d/m/Y", strtotime($target->dateTime));
                                        ?>" />
                                    </div>
                                </div>
                            </div>
                            <!-- TWO NUMBER INPUTS -->
                            <div class="column w30">
                                <label for="startTime">Start time</label>
                                <input type="time" name="startTime" class="mb-2" value="<?php
                                    if (isset($target))
                                        echo date("H:i", strtotime($target->dateTime));
                                ?>" />
                                <label for="price">Ticket price (&euro;)</label>
                                <input type="number" name="price" min="0" step="0.01" value="<?php
                                    if (isset($target))
                                        echo $target->price;
                                ?>" />
                            </div>
                        </div>
                        <!-- CHECKBOX AND SUBMIT -->
                        <div class="row mb-2 centerY">
                            <div class="column w25 mr-5">
                                <div class="row">
                                    <input type="checkbox" name="soon" class="formCheckbox" value="<?php
                                        if (isset($halfTarget))
                                            echo "true";
                                    ?>" />
                                    <label for="soon">Add to Soon</label>
                                </div>
                            </div>
                            <?php
                                if (isset($target) || isset($halfTarget))
                                    echo "
                                        <div class=\"column w30\">
                                            <button type=\"submit\" formaction=\"/Cinema/ActionCancelMovie\" class=\"standardButton badButton\">Cancel movie</button>
                                        </div>
                                    ";
                            ?>
                            <div class="column w30">
                                <button type="submit" formaction="/Cinema/ActionAddMovie" class="standardButton goodButton"><?php
                                    if (isset($target) || isset($halfTarget))
                                        echo "Save changes";
                                    else
                                        echo "Add movie";
                                ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script src="/js/calendar.js"></script>
</html>
