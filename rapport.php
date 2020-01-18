<?php
require_once "_header.php";
if (!(($user->hasPermission('admin'))||($user->hasPermission('responsable')))){
    Redirect::to('index.php');
}
if (!Session::exists('date_debut')){
    Session::put('date_debut','2019-01-01');
}
if (!Session::exists('date_fin')){
    Session::put('date_fin',date('Y-m-d'));
}
if (Session::exists(Config::get("session/token_name")) && (Session::get(Config::get("session/token_name")) === Input::get('token'))) {
    Session::put('date_debut',Input::get('statistique/debut'));
    Session::put('date_fin',Input::get('statistique/fin'));
}
?>
<form action="" method="post" role="form">
<span><strong>from:</strong><input type="date" class="form-control" name="statistique/debut" value="<?php echo Session::get("date_debut") ;?>"></span>
<span><strong>to:</strong><input type="date" class="form-control" name="statistique/fin" value="<?php echo Session::get("date_fin") ;?>"></span>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <button type="submit" class="btn btn-primary  btn-lg " >Change
    </button>
    <div>
    <?php
    echo "<img src='graphe1.php' /> "
    ?>
</div>
<?php
$db=DB::getInstance();
$db->get('mission_files',array('id',">",'0'),"ORDER BY id DESC");
$missions=$db->results();
if (!DB::getInstance()->error()) {
?>
<br><br><br>
    <div class="container">
        <button type="button" class="btn btn-dark float-right" onclick="exportTableToExcel('rapport', 'Rapport')">Export</button>
        <table class="table" id="rapport">
    <thead>
    <tr>
        <th scope="col">Projet_id</th>
        <th scope="col">Projet_id</th>
        <th scope="col">Author</th>
        <th scope="col">Project Manager</th>
        <th scope="col">Projet</th>
        <th scope="col">Total</th>
        <th scope="col">created</th>
        <th scope="col">finished</th>
        <th scope="col">Closed</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($missions as $mission) { ?>
        <tr <?php  $user = new User();
        $fm = new FM($mission->id);
        if ($fm->is_finished()!='4'){
            echo "hidden";
        }
        ?> >
            <td><?php echo $mission->id ;?></td>
            <td><?php echo $mission->projet_id ;?></td>
            <td><?php echo $mission->author ;?></td>
            <td><?php echo $mission->chef_projet ;?></td>
            <td><?php echo $mission->projet ;?></td>
            <td><?php echo $mission->total_espece ;?></td>
            <td><?php echo $mission->date ?></td>
            <td><?php echo $mission->finish_date ?></td>
            <td><?php echo $mission->close_date ?></td>
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
    function exportTableToExcel(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }
</script>
<?php
require_once "_footer.php";
?>
