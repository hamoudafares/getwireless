<?php
require_once('_header.php');
$user = new User();
if (!$user->hasPermission('admin')){
    Redirect::to('index.php');
}
if (Input::exists()){
 if(Session::exists(Config::get("session/token_name"))&&(Session::get(Config::get("session/token_name"))===Input::get('token'))){
     $validate = new Validate();
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
            'unique'=>"users"
        ),
        'password'=>array(
            'required'=>true,
            'min'=>6
        ),
        'password_again'=>array(
            'required'=>true,
            'matches'=>'password'
        ),
        'email'=>array(
            'required'=>true,
            'min'=>5
        )
    ));
    if ($validation->passed()){
        $user = new User();
        $salt=Hash::salt(32);
        try{
            $fields=array(
                'username' => Input::get('username'),
                'password'=> Hash::make(Input::get('password'),$salt),
                'password_without_hash'=>Input::get('password'),
                'email'=>Input::get('email'),
                'salt'=>$salt,
                'name'=> Input::get('name'),
                'joined'=>date('Y-m-d H:i:s'),
                'groupe'=>1
        );
        $user->create($fields);
        }catch (Exception $e){
            die($e->getMessage());
        }
        Session::flash('success','you registered successfully ! ');
        Redirect::to("index.php");
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
                        <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="<?php echo escape(Input::get('name')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo escape(Input::get('username')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo escape(Input::get('email')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="password_again">Confirm Password</label>
                        <input type="password" class="form-control" id="password_again" name="password_again"
                               placeholder="Confirm Password">
                    </div>
                    <input type="hidden" name="token" value="<?php echo Token::generate();?>">
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
<?php require_once('_footer.php'); ?>