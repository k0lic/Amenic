<ul class="nav">
    <li>
        <!-- MOVIES -->
        <?php 
            if (!isset($optionPrimary) || $optionPrimary==0)
                echo "<div class=\"activeMenuText\">Movies</div>";
            else
                echo "<a href=\"/Cinema\">Movies</a>";
        ?>
    </li>
    <li>
        <!-- ROOMS -->
        <?php 
            if (isset($optionPrimary) && $optionPrimary==1)
                echo "<div class=\"activeMenuText\">Rooms</div>";
            else
                echo "<a href=\"/Cinema/Rooms\">Rooms</a>";
        ?>
    </li>
    <li>
        <!-- EMPLOYEES -->
        <?php 
            if (isset($optionPrimary) && $optionPrimary==2)
                echo "<div class=\"activeMenuText\">Employees</div>";
            else
                echo "<a href=\"/Cinema/Employees\">Employees</a>";
        ?>
    </li>
</ul>