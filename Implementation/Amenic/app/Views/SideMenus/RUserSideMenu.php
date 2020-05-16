<ul class="nav">
    <li>
        <a href="/HomeController" class="<?php if(strcmp($actMenu,"0")==0) echo "activeMenu";?>" >Movies</a>
    </li>
    <li>
        <a href="/HomeController/cinemas" class="<?php if(strcmp($actMenu,"1")==0) echo "activeMenu";?>">Cinemas</a>
    </li>
</ul>