<ul class="nav">
    <li>
        <a href="/AdminController/users" class="<?php if(strcmp($actMenu,"0")==0) echo "activeMenu";?>" >Users</a>
    </li>
    <li>
        <a href="/AdminController/cinemas" class="<?php if(strcmp($actMenu,"0")==1) echo "activeMenu";?>">Cinemas</a>
    </li>
    <li>
        <a href="/AdminController/requests" class="<?php if(strcmp($actMenu,"0")==2) echo "activeMenu";?>">Requests</a>
    </li>
    <li>
        <a href="/AdminController/admins" class="<?php if(strcmp($actMenu,"0")==3) echo "activeMenu";?>">Admins</a>
    </li>
</ul>