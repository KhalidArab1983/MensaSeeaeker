<div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-light p-4 w-100" style="display:inline-block;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills nav_besonder">
                <li class="nav-item item_besonder">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'a_login.php') echo 'active'; ?>" href="./a_login.php"><h6>Anmelden</h6></a>
                </li>
                
                <li class="nav-item item_besonder">
                    <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'a_register.php') echo 'active'; ?>" href="./a_register.php"><h6>Register</h6></a>
                </li>
            </ul>
        </div>
    </div>
    <img src="../images/logo.png" alt="Seeäkerschule Logo" width=10% style="float:right;">
    <nav class="navbar navbar-dark bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler bg-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>