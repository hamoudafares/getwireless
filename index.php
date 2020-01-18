<?php
require_once('_header.php');
if(Session::exists('success')) {
    echo "<div class='alert alert-success' role='alert'>";
    echo Session::flash('success');
    echo "</div>";
}
if (Session::exists('login')){
    echo "<div class='alert alert-success' role='alert'>";
    echo Session::flash('login');
    echo "</div>";
}
?>

    <div class="container">
        <div class="alert alert-primary" role="alert">
            Welcome To GET WIRELESS
        </div>
        <br/>
    </div>
<?php
require_once('_footer.php');
?>