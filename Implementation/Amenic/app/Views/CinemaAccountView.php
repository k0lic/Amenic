<!--
    Author: Andrija Kolić
    Github: k0lic
-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<title>Amenic - Movies</title>
	</head>
	<body>
        <div class="container">
            <div class="menuBar">
                <a href="/"><img src="/assets//logo.svg" class="logo" alt="Amenic" /></a>
                <ul class="nav">
                    <li>
                        <a href="/Cinema" class="<?php if (!isset($optionPrimary) || $optionPrimary==0) echo "activeMenu";?>" >Movies</a>
                    </li>
                    <li>
                        <a href="/Cinema" class="<?php if (isset($optionPrimary) && $optionPrimary==1) echo "activeMenu";?>" >Rooms</a>
                    </li>
                    <li>
                        <a href="/Cinema" class="<?php if (isset($optionPrimary) && $optionPrimary==2) echo "activeMenu";?>" >Employees</a>
                    </li>
                </ul>
                <a href="#">
                    <div class="icon baseline">
                        <img src="/assets/settings.svg">
                    </div>
                    Settings
                </a>
            </div>
            <div class="moviesWrapper">
                <div class="topBar">
                    <form class="searchForm">
                        <label>
                            <input type="text" placeholder="Search something" class="search" name="title" />
                        </label>
                        <!-- pamtim na kojoj sam strani -->
                        <input type="hidden" id="optionPrimary" name="optionPrimary" value="<?php if(isset($optionPrimary)) echo $optionPrimary;?>" />
                        <input type="hidden" id="optionSecondary" name="optionSecondary" value="<?php if(isset($optionSecondary)) echo $optionSecondary;?>" />
						
                    </form>
                    <div class="user">
                        <img src="/assets/profPic.png" class="profPic" alt="Profile picture" />
                        <span>Lošmi</span>
                    </div>
                </div>
                <ul class="movieBtns">
                    <li>
                        <a href="/Cinema" class="<?php if (!isset($optionSecondary) || $optionSecondary==0) echo "activeMenu" ?>">Now playing</a>
                    </li>
                    <li>
                        <a href="/Cinema/comingSoon" class="<?php if (isset($optionSecondary) && $optionSecondary==1) echo "activeMenu" ?>">Coming soon</a>
                    </li>
                </ul>
                <div class="movies">
                <?php

                for($i=0;$i<count($items);$i++)
                {
                    if ($optionSecondary == 0)
                    {
                        echo ("
                            <div class=\"movieImgExtended centerX column\">
                                <img src=\"".$items[$i]["poster"]."\" class=\"movieImg\" />
                                <div class=\"movieImgText row w80 mt-1 spaceBetween\">
                                    <div>".$items[$i]["projection"]->roomName."</div>
                                    <div>".date("D H:i", strtotime($items[$i]["projection"]->dateTime))."</div>
                                </div>
                            </div>
                        ");
                    }
                    else
                    {
                        echo ("
                            <img src=\"".$items[$i]["poster"]."\" class=\"movieImg\" />
                        ");
                    }
                }

                ?>
                </div>
            </div>
        </div>
	</body>
</html>
