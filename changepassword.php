<?php
require_once('_header.php');
$user = new User();
if (!$user->isLoggedIn()){
    Redirect::to('index.php');
}else{
    if (!$user->hasPermission('admin')){
        Redirect::to('index.php');
    }
    if (Input::exists()){
        if (Input::exists('get')){$user=new User(Input::get('id'));}
        if(Session::exists(Config::get("session/token_name"))&&(Session::get(Config::get("session/token_name"))===Input::get('token'))){
            $validate = new Validate();
            $validation = $validate->check($_POST,array(
                'currentP   assword'=>array(
                    'required'=>true,
                    'min'=>6
                ),
                'newPassword'=>array(
                    'required'=>true,
                    'min'=>6
                ),
                'passwordAgain'=>array(
                    'required'=>true,
                    'min'=>6,
                    'matches'=>'newPassword'
                )
            ));
            if ($validation->passed()){
                try{
                    $currentPassword = Input::get('currentPassword');
                    if ($user->data()->password!=Hash::make($currentPassword,$user->data()->salt)){
                        Session::flash('fail',"wrong Password !");
                        Redirect::to("changepassword.php");
                    }
                    $salt = Hash::salt(32);
                    $user->update(array(
                        'password'=>Hash::make(Input::get('newPassword'),$salt),
                        'salt'=>$salt
                    ));
                    Session::flash('success',"your password has been updated");
                    Redirect::to("index.php");
                }catch (Exception $e){
                    die($e->getMessage());
                }
            }
            else{
                $str="";
                foreach ($validation->errors() as $error){
                    $str.=$error."<br>" ;
                }
                echo Session::flash('failed',$str);
            }
        }
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

                <?php if (Session::exists('fail')){
                    echo "<div class='alert alert-danger' role='alert'>";
                    echo Session::flash('fail');
                    echo "</div>";
                }?>
                <form action="" method="post" role="form">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword">
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword"  >
                    </div>
                    <div class="form-group">
                        <label for="passwordAgain">Confirm New Password</label>
                        <input type="password" class="form-control" id="passwordAgain" name="passwordAgain" >
                    </div>
                    <input type="hidden" name="token" value="<?php echo Token::generate();?>">
                    <button type="submit" class="btn btn-success">Update Password </button>
                </form>
            </div>
        </div>
    </div>
<?php require_once('_footer.php'); ?>