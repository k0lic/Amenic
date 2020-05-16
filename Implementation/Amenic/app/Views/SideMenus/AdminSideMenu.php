<ul class="nav">
    <li>
        <a href="/AdminController/users" class="<?php if(strcmp($actMenu,"0")==0) echo "activeMenu";?>" >Users</a>
    </li>
    <li>
        <a href="/AdminController/cinemas" class="<?php if(strcmp($actMenu,"1")==0) echo "activeMenu";?>">Cinemas</a>
    </li>
    <li>
        <a href="/AdminController/requests" class="<?php if(strcmp($actMenu,"2")==0) echo "activeMenu";?>">Requests</a>
    </li>
    <li>
        <a href="/AdminController/admins" class="<?php if(strcmp($actMenu,"3")==0) echo "activeMenu";?>">Admins</a>
    </li>
</ul>