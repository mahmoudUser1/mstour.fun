<?php

$pageTitle = 'home';
$pageDesc = 'هذه الصفحة التي به الرحلات';
$_SESSION['mail_mstour'] = "";

include "initials.php";

?>
<div class="cover-page"></div>
<div class="section-hello w-100">
    <p>Quietly explore the world with -<strong> mstour.fun</strong></p>

    <p class="desc">these trips are designed to give you moments of peace, contemplate beauty and explore destinations
        that inspire
        your soyl.</p>

    <button class="btn btn-success">juon us</button>
</div>
<?php include $temp . "footer.php"; ?>