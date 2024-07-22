<?php $relPath = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])); ?>
<link rel="stylesheet" href="<?= $relPath.'/../palettes/colors.css' ?>">
<link rel="stylesheet" href="<?= $relPath.'/authModal.css' ?>">
<script src="<?= $relPath.'/authModal.js' ?>" defer></script>
<script>
    modal=true
</script>
<script src="<?= $relPath.'/../../login/login.js' ?>"></script>
<div id="reauth">
    <div id="reauth-text">
        <p id="expired">Session expired</p>
        <p id="again">Please log in to continue</p>
    </div>
    <div id="loginform">
        <div id="fields">
            <div class="text-field">
                <input type="text" name="user" value="" placeholder=" ">
                <span class="placeholder">Username</span>
                <p class="error-text"></p>
            </div>
            <div class="text-field">
                <input type="password" name="pwd" value="" placeholder=" ">
                <span class="placeholder">Password</span>
                <p class="error-text"></p>
            </div>
        </div>
        <button type="finish" onclick="login()">Log in</button>
    </div>
</div>