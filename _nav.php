<?php require_once 'core/init.php';?>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top ">
    <a class="navbar-brand" >GET Wireless </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                <a class="nav-link" href="index.php">Home </a>
            </li>
            <?php
            $user = new User();
            if (!$user->isLoggedIn())
            { ?>

                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            <?php } else {
                if ($user->hasPermission('admin')) { ?>
                    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : '' ?>  " >
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>">
                    <a class="nav-link" href="users.php">Users</a>
                </li>
                    <li class="nav-item dropdown  <?php echo basename($_SERVER['PHP_SELF']) == ('update.php'||'changepassword.php') ? 'active' : '' ?>">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Edit
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item <?php echo basename($_SERVER['PHP_SELF']) == 'update.php' ? 'active' : '' ?>" href="update.php">Profile</a>
                            <a class="dropdown-item  <?php echo basename($_SERVER['PHP_SELF']) == 'changepassword.php' ? 'active' : '' ?>" href="changepassword.php">Password</a>
                            <a class="dropdown-item  <?php echo basename($_SERVER['PHP_SELF']) == 'options.php' ? 'active' : '' ?>" href="options.php">Options</a>
                        </div>
                    </li>
                <?php }?>
                <?php
                if ($user->hasPermission('responsable')||$user->hasPermission('admin')) {
                    ?>
                    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'rapport.php' ? 'active' : '' ?>">
                        <a class="nav-link" href="rapport.php">Rapport</a>
                    </li>
                    <?php
                }
                ?>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'mission_files.php' ? 'active' : '' ?>">
                    <a class="nav-link" href="mission_files.php">Mission Files</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="choisir_projet.php">Create a mission File</a>
                </li>
            <?php }?>
        </ul>
    </div>
    <?php
    if ($user->isLoggedIn()){
        ?>
        <span class="alert alert-success my-2 my-lg-0 navbar-brand" role="alert">
        Hello<?php echo " ",escape($user->data()->name);?> !
    </span>
        <span class=" my-2 my-lg-0 navbar-brand" role="alert">
                    <a class="nav-link" href="logout.php">Logout</a>
        </span>
        <br>
        <br>
        <?php
    } 
    ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
<div class="mb-2"></div>