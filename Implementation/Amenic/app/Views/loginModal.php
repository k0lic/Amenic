<button class="modalTrigger" onclick="document.getElementById('loginModal').classList.add('showModal');">
    Log in
</button>
<div class="modalWrapper <?php echo $loginError!='' || $resetError!=''?'showModal':''?>" id="loginModal" 
        onclick="
        document.getElementById('loginModal').classList.remove('showModal');
        document.getElementById('passResetInner').classList.remove('showModal');
        document.getElementById('loginModalInner').classList.remove('hideModal');
        ">
    <div class="modal centerX <?php echo $resetError!=''?'hideModal':''?>" id="loginModalInner">
        <form method="POST" action="/login">
            <div class="modalHead centerX spaceBetween">
                <span>Log in</span>
                <a href="/login/clearErrors" onclick="
                    document.getElementById('loginModal').classList.remove('showModal');
                    document.getElementById('passResetInner').classList.remove('showModal');
                    document.getElementById('loginModalInner').classList.remove('hideModal');
                ">
                <img src="assets/close.svg" class="modalClose" alt="Close form" />
                </a> 
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
                    
                    <span onclick="
                    document.getElementById('loginModalInner').classList.add('hideModal');
                    document.getElementById('passResetInner').classList.add('showModal');
                    ">Forgot the password?</span>
                    
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

    <div class="modal centerX <?php echo $resetError!=''?'showModal':''?>" id="passResetInner">
        <form method="POST" action="/login/forgot" id="passResetForm">
            <div class="modalHead centerX spaceBetween mb-2">
                <span class="mr-2">Account recovery</span>
                <a href="/login/clearErrors" onclick="
                    document.getElementById('loginModal').classList.remove('showModal');
                    document.getElementById('passResetInner').classList.remove('showModal');
                    document.getElementById('loginModalInner').classList.remove('hideModal');
                ">
                <img src="assets/close.svg" class="modalClose" alt="Close form" />
                </a>    
            </div>
            <div class="modalColumn">
                <label for="email">E-mail</label>
                <input type="text" id="email" name="email" />
            </div>

            <div class="modalRow mt-1 formError">
                <?php echo $resetError ?>
            </div>

            <div class="modalRow centerY modalBottom mt-2">
                <button type="submit" name="signin" class="modalButton forgotPasswordButton">
                    Send reset link
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('loginModalInner').addEventListener(('click'), (e) => {
        e.stopPropagation();
        return false;
    });

    document.getElementById('passResetInner').addEventListener(('click'), (e) => {
        e.stopPropagation();
        return false;
    });

</script>
