<?php
require_once('_header.php');
if (!$user->hasPermission('admin')){
    Redirect::to('index.php');
}
if(Session::exists('success')) {
    echo "<div class='alert alert-success' role='alert'>";
    echo Session::flash('success');
    echo "</div>";
}
if(Session::exists('fail')) {
    echo "<div class='alert alert-danger' role='alert'>";
    echo Session::flash('fail');
    echo "</div>";
}

if (Input::exists('get')||(Input::exists())){
            try{
                if (Input::get('id')&&Input::get('deactivate')==='1'&&Input::get('activate')==='0'){
                    $user = new User(Input::get('id'));
                    $user->deactivate(Input::get('id'));
                    Session::flash('success',"your user has been deactivated");
                    Redirect::to("users.php");
                }
                if (Input::get('id')&&Input::get('deactivate')==='0'&&Input::get('activate')==='1'){
                    $user = new User(Input::get('id'));
                    $user->activate(Input::get('id'));
                    Session::flash('success',"your user has been activated");
                    Redirect::to("users.php");
                }
                $newuser = new User(Input::get('id_user'));
                if ($newuser->setGroup(Input::get('permission'),Input::get('id_user'))){
                    Session::flash('success',"your user has been updated");
                    Redirect::to("users.php");
                }else {
                    Session::flash('fail',"there was a problem updating");
                    Redirect::to("users.php");
                }
            }catch (Exception $e){
                die($e->getMessage());
            }
    }
$db=DB::getInstance();
$db->get('users',array('id',">",'0'));
$users=$db->results();
if (!DB::getInstance()->error()) {
    ?>
    <input type="hidden" name="token" value="<?php  echo $token=Token::generate();?>">
    <div class="container">
    <table class="table">
    <thead>
    <tr>
        <th scope="col">id</th>
        <th scope="col">Username</th>
        <th scope="col">Email</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user) {
        $user2 = new User($user->id) ; ?>
    <tr <?php if (!$user2->isActif()){
        echo "class=\"table-active\"";
    } ?>>
            <th scope="row"><?php echo $user->id ?></th>
            <td><?php echo $user->username ?></td>
            <td><?php echo $user->email ?></td>
            <td>
                <a href="users.php?id=<?php echo $user->id?>&deactivate=1&activate=0" class="btn btn-danger btn-sm deactivate_user <?php if (!$user2->isActif()){ echo "disabled" ;} ?>" role="button"
                   aria-pressed="true">Deactivate</a>
                <a href="users.php?id=<?php echo $user->id?>&activate=1&deactivate=0" class="btn btn-success btn-sm activate_user <?php if ($user2->isActif()){ echo "disabled" ;} ?>" role="button"
                   aria-pressed="true">activate</a>
                <a href="update.php?id=<?php echo $user->id?>" class="btn btn-info btn-sm" role="button"
                aria-pressed="true">edit</a>
            </td>
            <?php } ?>

        </tr>
        </tbody>
        </table>
        </div>
<?php } else { ?>
    <div class="alert alert-info" role="alert">
        No Records Found
    </div>
    <?php
}
?>
<script>
    var button_deactivate = document.getElementsByClassName('deactivate_user');
    var button_activate = document.getElementsByClassName('activate_user');
    for (var button of button_deactivate){
        button.addEventListener('click',function (e) {
            var result = confirm("Are you sure to deactivate the user ?");
            if(result){
                console.log("deactivated!");
            }else {
                console.log("canceled");
                e.preventDefault();
            }
        })
    }
    for (var button2 of button_activate){
        button2.addEventListener('click',function (e) {
            var result = confirm("Are you sure to activate the user ?");
            if(result){
                console.log("activated!");
            }else {
                console.log("canceled");
                e.preventDefault();
            }
        })
    }
</script>
<?php
require_once('_footer.php');
?>