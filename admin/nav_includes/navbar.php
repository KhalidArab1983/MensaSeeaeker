<div class="collapse" id="navbarToggleExternalContent">
    <div class="bg-light p-4 w-100" style="display:inline-block;">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
            <li class="nav-item item_besonder">
                <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>" href="./index.php"><h6>Haupt Seite |</h6></a>
            </li>

            <li class="nav-item item_besonder">
                <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'create_user.php') echo 'active'; ?>" href="./create_user.php"><h6>Neu Benutzer |</h6></a>
            </li>

            <li class="nav-item item_besonder">
                <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'a_user_page.php') echo 'active'; ?>" href="./a_user_page.php"><h6>Benutzer Seite |</h6></a>
            </li>

            <li class="nav-item item_besonder">
                <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'meals_edit.php') echo 'active'; ?>" href="./meals_edit.php"><h6>Gerichte bearbeiten |</h6></a>
            </li>

            <li class="nav-item item_besonder">
                <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'a_setting.php') echo 'active'; ?>" href="./a_setting.php"><h6>Einstellungen |</h6></a>
            </li>

            <li class="nav-item item_besonder">
                <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'a_logout.php') echo 'active'; ?>" href="./a_logout.php"><h6>Abmelden</h6></a>
            </li>
        </ul>
    </div>
</div>

<img class="logo" src="../images/logo.png" alt="Seeäkerschule Logo" width=10% style="float:right;">
<nav class="navbar navbar-dark bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h5 style="margin: 0;">Herzlich Willkommen <span style="font-weight:bold; color:<?php echo $adminColor;?>"><?php echo $_SESSION['adminName']; ?></span></h5>
        <a class="font25" href="./a_setting.php"  style="color:<?php echo $adminColor;?>"><i class="fa-solid fa-user-gear fa-beat-fade fa-lg"></i></a>
    </div>
</nav>