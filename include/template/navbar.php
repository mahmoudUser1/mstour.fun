<nav>
    <div class="div-logo">

        <img src="<?= $img ?>rGtX5ULlO4tZ.jpeg" alt="">
        <img src="<?= $img ?>rdJ39yaVCfgl.jpg" class="opacity-75" alt="">

    </div>



    <div class="links">
        <a href="index.php" class="<?php if ($pageTitle == 'home') {
            echo 'act-link';
        } ?>"> home </a>
        <a href="destinations.php" class="<?php if ($pageTitle == 'destinations') {
            echo 'act-link';
        } ?>"> destinations
        </a>
        <a href="tours.php" class="<?php if ($pageTitle == 'tours') {
            echo 'act-link';
        } ?>"> tours </a>
        <a href="reservations.php" class="<?php if ($pageTitle == 'reservations') {
            echo 'act-link';
        } ?>"> reservations
        </a>
        <a href="about.php" class="<?php if ($pageTitle == 'about') {
            echo 'act-link';
        } ?>"> about </a>
        <a href="connect.php" class="<?php if ($pageTitle == 'connect') {
            echo 'act-link';
        } ?>"> connect </a>
    </div>

    <div class="control position-relative">

        <select name="" id="">
            <option value="en">EN</option>
            <option value="ar">AR</option>
        </select>

        <button class="btn-sign-in" id="BTNSignIN">
            <i class="fa-regular fa-user"></i>
        </button>

        <div class="<?php if (isset($_SESSION['mail_mstour'])) { ?>control-profile<?php } else { ?>control-sign<?php } ?> position-absolute"
            id="menuControl">
            <?php if (isset($_SESSION['mail_mstour'])) { ?>
                <ul>
                    <li><a href="">edit profile</a></li>
                    <li><a href="">view profile</a></li>
                    <li><a href="">reserved flights</a></li>
                    <li><a href="">book a private flight</a></li>
                    <li><a href="">logout</a></li>
                </ul>
            <?php } else { ?>
                <p>Register now to book your flight</p>
                <div class="div-btn">
                    <button class="btn btn-primary btn-sm text-light">sign in</button>
                    <button class="btn btn-warning btn-sm text-light">sign up</button>
                </div>
            <?php } ?>
        </div>

        <button class="icon-bars">
            <i class="fa-solid fa-bars"></i>
        </button>

    </div>
</nav>