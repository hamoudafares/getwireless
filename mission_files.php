<?php
require_once('_header.php');
$user = new User();

if(Session::exists(Config::get("session/token_name"))&&(Session::get(Config::get("session/token_name"))===Input::get('token'))) {
    $fm = new FM(Input::get('id'));
   if (Input::get('delete')==='true')
   {
       $fm->delete();
   }else if (Input::get('close')==='true') {
        $finish='4';
       $fields = array(
           'is_finished'=>$finish,
           'close_date'=>date('Y-m-d H:i:s')
       );
       $fm->update($fields,Input::get('id'));

   }else if (Input::get('valider')==='true'){
       $finish='3';
       $fields = array(
           'is_finished'=>$finish,
           'reject'=>0
       );
       $fm->update($fields,Input::get('id'));
   }else if (Input::get('valider')==='false'){
       $finish='1';
       $fields = array(
           'is_finished'=>$finish,
           'reject'=>true
       );
       $user1= new User($fm->data()->author);
       $user2=new User();
       $msg='Votre Fiche de mission numéro '.$fm->data()->id.' a été rejeté \r\n merci de revoir votre fiche de Mission ';
       mail($user1->data()->email,'Refus de Fiche de mission',$msg);
       if ($fm->data()->declaration_degat) {
           $datas = json_decode($fm->data()->declaration_degat);
           if (isset($datas->empty)){
               if ($datas->empty === 'on') {
                   $fields['declaration_degat'] = '';
               }
           }

       }
       $fm->update($fields,Input::get('id'));
   }else if (Input::get('comment')==='true'){
       $mission_id = Input::get('id_mission');
       $fm = new FM($mission_id);
       $comments=$fm->data()->comments;
       $comments=$user->data()->username.":".Input::get('msg')."\n".$comments;
       $fm->update(array('comments'=>$comments),$mission_id);
   }
}
if(Session::exists('success')) {
    echo "<div class='alert alert-success' role='alert'>";
    echo Session::flash('success');
    echo "</div>";
}
if (Session::exists('failed')) {
    echo "<div class='alert alert-danger' role='alert'>";
    echo Session::flash('failed');
    echo "</div>";
}
$db=DB::getInstance();
$db->get('mission_files',array('id',">",'0'),"ORDER BY date DESC");
$missions=$db->results();
if (!DB::getInstance()->error()) {
    ?>
    <input type="hidden" name="token" value="<?php  echo $token=Token::generate();?>">
    <div class="container">
	<div class="row">
		<div class="col-md-9">
        <div class="input-group">
  <input type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="Search..." id='search' onkeyup='filter()'>
  <div class="input-group-append">
      <select class="custom-select" id="filtre">
          <option disabled selected>Filter By</option>
          <option value="2">Projet</option>
          <option value="1">Auhtor</option>
          <option value="5">Date</option>
      </select>
  </div>
</div>
  </div>
	</div>
</div>
<br><br><br>
    <div class="container">
    <table class="table" id="table_filter">
    <thead>
    <tr>
        <th scope="col">identifiant</th>
        <th scope="col">Projet_id</th>
        <th scope="col">Author</th>
        <th scope="col">Projet</th>
        <th scope="col">Total</th>
        <th scope="col">Actions</th>
        <th scope="col">created</th>
        <th scope="col">Comments</th>
        <th scope="col">Conforme SLA</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($missions as $mission) { ?>
        <tr <?php  $user = new User();
        check_permissions($user,$mission->author,$mission->id,$mission->chef_projet,$mission->logistique);
        $fm = new FM($mission->id);
        if ($fm->is_finished()==='4'){
            echo "class='table-success'";
        }else if ($fm->data()->reject==='1'){
            echo "class='table-danger'";
        }
        ?> >
            <th scope="row"><?php echo $mission->id ;?></th>
            <td><?php echo $mission->projet_id ;?></td>
            <td><?php echo $mission->author ;?></td>
            <td><?php echo $mission->projet ;?></td>
            <td><?php echo $mission->total_espece ;?></td>
            <td>
                <?php if (($mission->author===$user->data()->username)&&($fm->is_finished()==='1')){ ?>
                <a href="editFM_part2.php?id=<?php echo $mission->id ;?>" class="btn btn-primary btn-sm" role="button"
                   aria-pressed="true">Complete</a>
                <?php } ?>
                <?php if(!(($user->data()->username===$mission->author)&&($fm->is_finished()==='1'))) {?>
                <a href="editFM_part1.php?id=<?php echo $mission->id ;?>&visit=true" class="btn btn-warning btn-sm " role="button"
                   aria-pressed="true">Visit</a>
                <?php }?>
                <a href="mission_files.php?id=<?php echo $mission->id ;?>&token=<?php echo $token?>&delete=true" class="btn btn-danger btn-sm delete_mission" role="button"
                   aria-pressed="true">Delete</a>
                <?php if (($mission->chef_projet===$user->data()->username)&&($fm->is_finished()==='3')&&($fm->is_finished()!='4')){ ?>
                <a href="mission_files.php?id=<?php echo $mission->id ;?>&token=<?php echo $token?>&close=true" class="btn btn-success btn-sm " role="button"
                   aria-pressed="true">Close</a>
                <?php }?>
                <?php if (($mission->logistique===$user->data()->username)&&($fm->is_finished()==='2')){ ?>
                    <a href="mission_files.php?id=<?php echo $mission->id ;?>&token=<?php echo $token?>&valider=true" class="btn btn-success btn-sm " role="button"
                       aria-pressed="true">Validate</a>
                    <a href="mission_files.php?id=<?php echo $mission->id ;?>&token=<?php echo $token?>&valider=false" class="btn btn-dark btn-sm " role="button"
                       aria-pressed="true">Reject</a>

                <?php } ?>
            </td>
            <td><?php echo $mission->date ?></td>
            <td class="col-4">
                <form action="" method="post" role="form">
                    <textarea class="form-control" name="<?php echo $name="discussion",$mission->id?>" rows="3" readonly><?php echo $mission->comments ;?></textarea>
                    <input type="text" name="msg" class="form-control">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <input type="hidden" name="comment" value="true">
                    <input type="hidden" name="id_mission" value=<?php echo $mission->id ;?>>
                    <button type="submit" class="btn btn-primary  btn-lg " >Comment
                    </button>
                </form>
            </td>
            <td>
            <?php  $data=(array)json_decode($mission->mission_file_part2);
            if (!isset($data['datefin1'])){$data['datefin1']="";}
            if (!isset($data['datefin2'])){$data['datefin2']="";}
            if (!isset($data['datefin3'])){$data['datefin3']="";}
            if (!isset($data['datefin4'])){$data['datefin4']="";}
            $date_fin=$data['datefin1'];
            if ($data['datefin2']) {
                $date_fin = $data['datefin2'];
                if ($data['datefin3']) {
                    $date_fin = $data['datefin3'];
                    if ($data['datefin4']) {
                        $date_fin = $data['datefin4'];
                    }
                }
            }
            $this_date = $mission->finish_date;
            $hours=(strtotime($this_date)-strtotime($date_fin))/3600;
            if (!$date_fin){
                echo 'date fin not mentioned ! ';
            }elseif (( $hours < 48 )&&($hours>0)){
            echo 'Yes';
            }else {
            echo "No";
            }
            ?>
            </td>
        </tr>
    <?php } ?>
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
function filter() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById('search');
  filter = input.value.toUpperCase();
  table = document.getElementById("table_filter");
  tr = table.getElementsByTagName("tr");
  j=document.getElementById('filtre').value;
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[j];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>
    <script type="text/javascript" src="getmissi.js"></script>
<?php
require_once('_footer.php');

?>