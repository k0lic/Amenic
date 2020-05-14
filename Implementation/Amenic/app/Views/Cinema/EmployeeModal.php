<div class="modalWrapper" id="employeeModalWrapper" onclick="document.getElementById('employeeModalWrapper').classList.remove('showModal');">
    <div class="modal centerX" id="employeeModal">
        <div class="modalHead centerX spaceBetween">
            <span>Add employee</span>
            <img src="/assets/close.svg" 
                onClick="document.getElementById('employeeModalWrapper').classList.remove('showModal');"
                class="modalClose"
                alt="Close form" />
        </div>
        
        <div class="modalRow">
            <div class="modalColumn">
                <label for="firstName">First name</label>
                <input type="text" name="firstName" value="<?php
                    if (isset($values["firstName"]))
                        echo $values["firstName"];
                ?>" />
                <div class="formError ml-1">
                    <?php 
                        if(isset($errors["firstName"]))
                            echo $errors["firstName"];
                    ?>
                </div>
            </div>
            <div class="modalColumn ml-1">
                <label for="lastName">Last name</label>
                <input type="text" name="lastName" value="<?php
                    if (isset($values["lastName"]))
                        echo $values["lastName"];
                ?>" />
                <div class="formError ml-1">
                    <?php 
                        if(isset($errors["lastName"]))
                            echo $errors["lastName"];
                    ?>
                </div>
            </div>
        </div>

        <div class="modalColumn mt-2">
            <label for="email">E-mail</label>
            <input type="text" name="email" value="<?php
                if (isset($values["email"]))
                    echo $values["email"];
            ?>" />
            <div class="formError ml-1">
                <?php 
                    if(isset($errors["email"]))
                        echo $errors["email"];
                ?>
            </div>
        </div>

        <div class="modalColumn mt-2">
            <label for="password">Password</label>
            <input type="password" name="password" />
            <div class="formError ml-1">
                <?php 
                    if(isset($errors["password"]))
                        echo $errors["password"];
                ?>
            </div>
        </div>

        <div class="modalColumn mt-2">
            <label for="confirm">Confirm password</label>
            <input type="password" name="confirm" />
            <div class="formError ml-1">
                <?php 
                    if(isset($errors["confirm"]))
                        echo $errors["confirm"];
                ?>
            </div>
        </div>

        <div class="row centerY mt-2">
            <span id="strengthBarTitle">Strength: </span>
            <span id="strengthBar1" class="strengthBarModal mr-1 ml-2"></span>
            <span id="strengthBar2" class="strengthBarModal mr-1"></span>
            <span id="strengthBar3" class="strengthBarModal mr-1"></span>
            <span id="strengthBar4" class="strengthBarModal"></span>
        </div>

        <div class="modalRow centerY modalBottom confirmModuleButtons mt-2">
            <button type="submit" formaction="/Cinema/ActionAddEmployee">Add</button>
        </div>
    </div>
</div>
