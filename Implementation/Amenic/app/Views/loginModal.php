<button class="modalTrigger" onclick="document.getElementById('loginModal').classList.add('showModal');">
    Log in
</button>
<div class="modalWrapper <?php echo $loginError!=''?'showModal':''?>" id="loginModal" >
    <div class="modal centerX">
        <form method="POST" action="/login">
            <div class="modalHead centerX spaceBetween">
                <span>Log in</span>
                <img src="assets/close.svg" class="modalClose" alt="Close form" 
                    onclick="document.getElementById('loginModal').classList.remove('showModal');"/>
            </div>
            <div class="modalColumn">
                <label for="email">E-mail</label>
                <input type="text" id="email" name="email" />
            </div>

            <div class="modalColumn mt-2">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" />
            </div>

            <div class="modalRow mt-2 formError">
                <?php echo $loginError ?>
            </div>

            <div class="modalRow centerY modalBottom mt-2">
                <div class="loginLinks">
                    <a href="/login/forgot" class="loginLink">
                        <span>Forgot the password?</span>
                    </a>
                    <a href="/register" class="loginLink">
                        <span>I don't have an account</span>
                    </a>
                </div>

                <button type="submit" name="signin" class="modalButton">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
