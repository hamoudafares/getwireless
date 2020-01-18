<?php
require_once('_header.php');
$user = new User();
if (!$user->isLoggedIn()){
    Redirect::to('index.php');
}else{
    if (!$user->hasPermission('admin')){
        Redirect::to('index.php');
    }
    if (Input::get('id')){
        $user = new User(Input::get('id'));
    }
    if (Input::exists()){
        if(Session::exists(Config::get("session/token_name"))&&(Session::get(Config::get("session/token_name"))===Input::get('token'))){
            $validate = new Validate(Input::get('id'));
            $validation = $validate->check($_POST,array(
                'name'=>array(
                    'required'=>true,
                    'min'=>2,
                    'max'=>20,
                ),
                'username'=>array(
                    'required'=>true,
                    'min'=>2,
                    'max'=>20,
                    'unique_update'=>"users"
                ),
                'email'=>array(
                    'required'=>true,
                    'min'=>5
                )
            ));
            if ($validation->passed()){
                try{
                    if (Input::get('id')){
                        $user->update(array(
                            'name'=>Input::get('name'),
                            'username'=>Input::get('username'),
                            'email'=>Input::get('email'),
                        ),Input::get('id'));
                        if ($user->setGroup(Input::get('permission'),Input::get('id'))){
                            Session::flash('success',"your user has been updated");
                            Redirect::to("users.php");
                        }else {
                            Session::flash('fail',"there was a problem updating");
                            Redirect::to("users.php");
                        }
                    }else{
                        $user->update(array(
                            'name'=>Input::get('name'),
                            'username'=>Input::get('username'),
                            'email'=>Input::get('email'),
                        ));
                        if ($user->setGroup(Input::get('permission'),$user->data()->id)){
                            Session::flash('success',"your details have been updated");
                            Redirect::to("index.php");
                        }
                        }

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
                <form action="" method="post" role="form">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="<?php echo escape($user->data()->name); ?>">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo escape($user->data()->username);  ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo escape($user->data()->email);  ?>">
                    </div>
                    <div class="form-group">
                        <label for="permission">Permission</label>
                        <?php
                        $user1 = new User(Input::get('id'));
                        $newname="permission";
                        echo "<select class='custom-select form-control' id='";
                        echo $newname, "'";
                        echo "name='", $newname, "' >";
                        echo " <option value='2'";
                        if ($user1->hasPermission('admin')){
                            echo "selected";
                        }
                        echo ">Administrateur</option>";
                        echo " <option value='3'";
                        if ($user1->hasPermission('responsable')){
                            echo "selected";
                        }
                        echo ">Responsable</option>";
                        echo " <option value='1'";
                        if ($user1->hasPermission('technicien')){
                            echo "selected";
                        }
                        echo ">Technicien</option>";
                        ?>
                    </div>
                    <input type="hidden" name="token" value="<?php echo Token::generate();?>">
                    <input type="hidden" name="id" value="<?php echo Input::get('id');?>">
                    <br>
                    <br>
                    <a href="changepassword.php?id=<?php echo Input::get('id') ;?>" class="btn btn-warning btn-sm <?php if (! Input::get('id')){echo 'disabled' ;} ?>" role="button"
                       aria-pressed="true">Change Password </a>
                    <br>
                    <br>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
<?php require_once('_footer.php'); ?>