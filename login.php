<?php
require_once('_header.php');
if (Input::exists()){
    $validate = new Validate();
    $validation = $validate->check($_POST,array(
            'username'=>array("required" => true),
            'password'=>array("required" => true)
    ));
    if($validation->passed()){
        $user = new User();
        $remember = (Input::get('remember')==='on')?true:false;
        $login=$user->login(Input::get('username'),Input::get('password'),$remember);
        if ($login){
            echo "<div class='alert alert-success' role='alert'>";
            echo Session::flash('login','connected !');
            Redirect::to('index.php');
            echo "</div>";
        }else {
            echo Session::flash('failed','Wrong username or password , Please try again');
        }

    }else{
        $str="";
        foreach ($validation->errors() as $error){
            $str.=$error."<br>" ;
        }
        echo Session::flash('failed',$str);
    }
}
?>
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
                <?php if (Session::exists('failed')){
                              echo "<div class='alert alert-danger' role='alert'>";
                              echo Session::flash('failed');
                              echo "</div>";
                }?>
                    <form action="" method="post" role="form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Password">
                    </div>
                        <div class="form-group">
                            <input type="checkbox" id="remember" name="remember">Remember me
                        </div>
                        <input type="hidden" class="form-control" id="token" name="token" value="<?php echo Token::generate() ; ?>">
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
<?php
require_once('_footer.php');
?>