<ul class="nav">
    <li>
        <!-- MOVIES -->
        <?php 
            if (isset($optionPrimary) && $optionPrimary==0)
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