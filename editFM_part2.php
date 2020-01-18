<?php
require_once "_header.php";
if (Input::exists()){
    if (Session::exists(Config::get("session/token_name")) && (Session::get(Config::get("session/token_name")) === Input::get('token'))) {
        if (Input::get('disabled')==='disabled'){
            Redirect::to("declaration_degat.php",array('visit=true&id'=>Input::get('id')));
        }
        $validate = new Validate();
        foreach ($_POST as $item => $valeur) {
            if (!substr_compare($item, "cout", 0, 4, true)) {
                $check[$item] = array('float' => true);
            }
        }
        $validation = $validate->check($_POST, $check);
        if ($validation->passed()) {
            try {
                $fields = array(
                    'mission_file_part2' => json_encode($_POST)
                );
                    $fm = new FM(Input::get('id'));
                    $fm->update($fields,Input::get('id'));
                if ($fm->is_visiting()) {
                    Redirect::to("declaration_degat.php",array('visit=true&id'=>Input::get('id')));
                }
                Redirect::to("declaration_degat.php",array('id'=>Input::get('id')));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
        else {
            $str="";
            foreach ($validation->errors() as $error){
                $str.=$error."<br>" ;
            }
            echo Session::flash('failed',$str);
        }
    }
}else if (Input::exists('get')) {
    $fm = new FM(Input::get('id'));
    secure(Input::get('id'));
    if ($fm->is_visiting()==='on'){
        $fm->visited(Input::get('id')) ;
        if (Input::get('visit')==='true') {
            $fm->visit(Input::get('id'));
        }
    }
    if (!$fm->data()->mission_file_part2) {
        $datas = json_decode($fm->data()->mission_file);
    }
    else {
        $datas = json_decode($fm->data()->mission_file_part2);
    }
        foreach ($datas as $name => $data) {
            $_POST[$name] = $data;
        }
}
?>
<?php if (Session::exists('failed')){
    echo "<div class='alert alert-danger' role='alert'>";
    echo Session::flash('failed');
    echo "</div>";
}?>
    <form action="" method="post" role="form">
        <fieldset <?php disable(Input::get('id')) ;?>>
            <p class="float-right" id="FM_infos" name="FM_infos"><?php echo Input::get('FM_id') ?></p>
            <input type="hidden" name="FM_id" value="<?php echo Input::get('FM_id'); ?>">
            <div class="form-row ">
                <div class="col-2">
                    <label for="projet">Projet</label>
                    <select class="custom-select form-control" id="projet" name="projet">
                        <option>...</option>
                        <?php options('projet', 'projet'); ?>
                    </select>
                </div>
                <div class="col-2">
                    <label for="operateur">Opérateur</label>
                    <select class="custom-select form-control" id="operateur" name="operateur">
                        <option selected>...</option>
                        <?php options('operateur', 'operateur'); ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <table class="table col-12 table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Mission</th>
                        <th scope="col">Véhicules à utiliser</th>
                        <th scope="col">Site description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <label>durée</label>
                            <div class="row">
                                <div class="form-group col-10">
                                    <label for="datedebut">Du</label>
                                    <input type="date" class="form-control" id="datedebut1"
                                           name=<?php echo $name = "datedebut1"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="heuredebut">à</label>
                                    <input type="time" class="form-control" id="heuredebut1"
                                           name=<?php echo $name = "heuredebut1"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                                <div class="form-group col-10">
                                    <label for="datefin">Au</label>
                                    <input type="date" class="form-control" id="datefin1"
                                           name=<?php echo $name = "datefin1"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="heurefin">à</label>
                                    <input type="time" class="form-control" id="heurefin1"
                                           name=<?php echo $name = "heurefin1"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-10">
                                    <label for="datedebut2">Du</label>
                                    <input type="date" class="form-control" id="datedebut2"
                                           name=<?php echo $name = "datedebut2"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="heuredebut2">à</label>
                                    <input type="time" class="form-control" id="heuredebut2"
                                           name=<?php echo $name = "heuredebut2"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                                <div class="form-group col-10">
                                    <label for="datefin2">Au</label>
                                    <input type="date" class="form-control" id="datefin2"
                                           name=<?php echo $name = "datefin2"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="heurefin2">à</label>
                                    <input type="time" class="form-control" id="heurefin2"
                                           name=<?php echo $name = "heurefin2"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-10">
                                    <label for="datedebut3">Du</label>
                                    <input type="date" class="form-control" id="datedebut3"
                                           name=<?php echo $name = "datedebut3"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="heuredebut3">à</label>
                                    <input type="time" class="form-control" id="heuredebut3"
                                           name=<?php echo $name = "heuredebut3"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                                <div class="form-group col-10">
                                    <label for="datefin3">Au</label>
                                    <input type="date" class="form-control" id="datefin3"
                                           name=<?php echo $name = "datefin3"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="heurefin3">à</label>
                                    <input type="time" class="form-control" id="heurefin3"
                                           name=<?php echo $name = "heurefin3"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-10">
                                    <label for="datedebut4">Du</label>
                                    <input type="date" class="form-control" id="datedebut4"
                                           name=<?php echo $name = "datedebut4"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="heuredebut4">à</label>
                                    <input type="time" class="form-control" id="heuredebut4"
                                           name=<?php echo $name = "heuredebut4"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                                <div class="form-group col-10">
                                    <label for="datefin4">Au</label>
                                    <input type="date" class="form-control" id="datefin4"
                                           name=<?php echo $name = "datefin4"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="heurefin4">à</label>
                                    <input type="time" class="form-control" id="heurefin4"
                                           name=<?php echo $name = "heurefin4"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                            </div>

                        </td>
                        <td>
                            <div class="row">
                                <div class="form-group col-md-4 offset-md-2">
                                    <b>Véhicule 1</b>
                                    <br>
                                    <label for="nature_vehicule1">Nature</label>
                                    <select class="custom-select form-control" id="nature_vehicule1"
                                            name="nature_vehicule1">
                                        <option selected>...</option>
                                        <?php options('nature_vehicule', 'nature_vehicule1'); ?>
                                    </select>
                                    <label for="type">Type</label>
                                    <select class="custom-select form-control" id="type_vehicule1" name="type_vehicule1">
                                        <option selected>...</option>
                                        <?php options('type_vehicule', 'type_vehicule1'); ?>
                                    </select>
                                    <label for="matricule">Matricule</label>
                                    <input type="text" class="form-control" id="matricule1"
                                           name=<?php echo $name = "matricule1"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="kmavant1">Kmavant</label>
                                    <input type="text" class="form-control" id="kmavant1"
                                           name=<?php echo $name = "kmavant1"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="kmapres1">Kmaprès</label>
                                    <input type="text" class="form-control" id="kmapres1"
                                           name=<?php echo $name = "kmapres1"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                                <div class="form-group col-md-4 offset-md-2">
                                    <b>Véhicule 2</b>
                                    <br>
                                    <label for="nature_vehicule2">Nature</label>
                                    <select class="custom-select form-control" id="nature_vehicule2"
                                            name="nature_vehicule2">
                                        <option selected>...</option>
                                        <?php options('type_vehicule', 'nature_vehicule2'); ?>
                                    </select>
                                    <label for="type">Type</label>
                                    <select class="custom-select form-control" id="type_vehicule2" name="type_vehicule2">
                                        <option selected>...</option>
                                        <?php options('type_vehicule', 'type_vehicule2'); ?>
                                    </select>
                                    <label for="matricule">Matricule</label>
                                    <input type="text" class="form-control" id="matricule2"
                                           name=<?php echo $name = "matricule2"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                    <label for="kmavant2">Kmavant</label>
                                    <input type="text" class="form-control" id="kmavant2"
                                           name=<?php echo $name = "kmavant2"; ?>
                                           value="<?php echo Input::get($name); ?>">
                                    <label for="kmapres2">Kmaprès</label>
                                    <input type="text" class="form-control" id="kmapres2"
                                           name=<?php echo $name = "kmapres2"; ?>
                                           value="<?php echo Input::get($name);
                                           $name = ""; ?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <table class="table table-bordered" id="description">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Région</th>
                                    <th scope="col">Cluster Name</th>
                                    <th scope="col">Site Name</th>
                                    <th scope="col">Nbre Visites</th>
                                    <th scope="col">Réalisation</th>
                                    <th scope="col">Remarques</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" id="region1"
                                               name=<?php echo $name = "region1"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="clustername1"
                                               name=<?php echo $name = "clustername1"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="sitename1"
                                               name=<?php echo $name = "sitename1"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="number" class="form-control" id="nbrevisites1"
                                               name=<?php echo $name = "nbrevisites1"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="realisation1"
                                               name=<?php echo $name = "realisation1"; ?>
                                               value="<?php echo Input::get($name);
                                               ?>"></td>
                                    <td><input type="text" class="form-control" id="remarques1"
                                               name=<?php echo $name = "remarques1"; ?>
                                               value="<?php echo Input::get($name);
                                               ?>"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="region2"
                                               name=<?php echo $name = "region2"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="clustername2"
                                               name=<?php echo $name = "clustername2"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="sitename2"
                                               name=<?php echo $name = "sitename2"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="number" class="form-control" id="nbrevisites2"
                                               name=<?php echo $name = "nbrevisites2"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="realisation2"
                                               name=<?php echo $name = "realisation2"; ?>
                                               value="<?php echo Input::get($name);
                                               ?>"></td>
                                    <td><input type="text" class="form-control" id="remarques2"
                                               name=<?php echo $name = "remarques2"; ?>
                                               value="<?php echo Input::get($name);
                                               ?>"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="region3"
                                               name=<?php echo $name1 = "region3"; ?>
                                               value="<?php echo Input::get($name1);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="clustername3"
                                               name=<?php echo $name2 = "clustername3"; ?>
                                               value="<?php echo Input::get($name2);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="sitename3"
                                               name=<?php echo $name3 = "sitename3"; ?>
                                               value="<?php echo Input::get($name3);
                                               $name = ""; ?>"></td>
                                    <td><input type="number" class="form-control" id="nbrevisites3"
                                               name=<?php echo $name4 = "nbrevisites3"; ?>
                                               value="<?php echo Input::get($name4);
                                               ?>"></td>
                                    <td><input type="text" class="form-control" id="realisation3"
                                               name=<?php echo $name5 = "realisation3"; ?>
                                               value="<?php echo Input::get($name5);
                                               ?>"></td>
                                    <td><input type="text" class="form-control" id="remarques3"
                                               name=<?php echo $name6 = "remarques3"; ?>
                                               value="<?php echo Input::get($name6);
                                               ?>"></td>

                                </tr>
                                <?php
                                $num = substr($name1, -1);
                                $num++;
                                $test = 'paiement_carburant4';
                                while (Input::get($test)) {
                                    echo " <tr>";

                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";

                                    $newname = substr($name2, 0, strlen($name2) - 1) . $num;
                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";

                                    $newname = substr($name3, 0, strlen($name3) - 1) . $num;
                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";


                                    $newname = substr($name4, 0, strlen($name4) - 1) . $num;
                                    echo "<td><input type='number' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";

                                    $newname = substr($name5, 0, strlen($name5) - 1) . $num;
                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";

                                    $newname = substr($name6, 0, strlen($name6) - 1) . $num;
                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";

                                    echo "</tr>";

                                    $num++;
                                    $newname = substr($name1, 0, strlen($name) - 1) . $num;
                                    $test = substr($test, 0, strlen($test) - 1) . $num;

                                }
                                $name1 = "";
                                $name2 = "";
                                $name3 = "";
                                $name4 = "";
                                $name5 = "";
                                $name6 = "";
                                ?>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-secondary btn-lg btn-block add">add a Line</button>
                        </td>
                    </tbody>
                </table>
            </div>
            <div class="form-row">
                <table class="table col-12 table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Extra Site description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <table class="table table-bordered" id="extra">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Région</th>
                                    <th scope="col">Cluster Name</th>
                                    <th scope="col">Site Name</th>
                                    <th scope="col">Nbre Visites</th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" id="region1"
                                               name=<?php echo $name = "extra_region1"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="clustername1"
                                               name=<?php echo $name = "extra_clustername1"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="sitename1"
                                               name=<?php echo $name = "extra_sitename1"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="number" class="form-control" id="nbrevisites1"
                                               name=<?php echo $name = "extra_nbrevisites1"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="region2"
                                               name=<?php echo $name = "extra_region2"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="clustername2"
                                               name=<?php echo $name = "extra_clustername2"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="sitename2"
                                               name=<?php echo $name = "extra_sitename2"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                    <td><input type="number" class="form-control" id="nbrevisites2"
                                               name=<?php echo $name = "extra_nbrevisites2"; ?>
                                               value="<?php echo Input::get($name);
                                               $name = ""; ?>"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control" id="region3"
                                               name=<?php echo $name1 = "extra_region3"; ?>
                                               value="<?php echo Input::get($name1);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="clustername3"
                                               name=<?php echo $name2 = "extra_clustername3"; ?>
                                               value="<?php echo Input::get($name2);
                                               $name = ""; ?>"></td>
                                    <td><input type="text" class="form-control" id="sitename3"
                                               name=<?php echo $name3 = "extra_sitename3"; ?>
                                               value="<?php echo Input::get($name3);
                                               $name = ""; ?>"></td>
                                    <td><input type="number" class="form-control" id="nbrevisites3"
                                               name=<?php echo $name4 = "extra_nbrevisites3"; ?>
                                               value="<?php echo Input::get($name4);
                                               ?>"></td>
                                </tr>
                                <?php
                                $num = substr($name1, -1);
                                $num++;
                                $test = 'paiement_carburant4';
                                while (Input::get($test)) {
                                    echo " <tr>";

                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";

                                    $newname = substr($name2, 0, strlen($name2) - 1) . $num;
                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";

                                    $newname = substr($name3, 0, strlen($name3) - 1) . $num;
                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";


                                    $newname = substr($name4, 0, strlen($name4) - 1) . $num;
                                    echo "<td><input type='number' class='form-control' id='", $newname, "'";
                                    echo "name='", $newname, "'";
                                    echo "value='", Input::get($newname), "'";
                                    echo "></td>";


                                    echo "</tr>";

                                    $num++;
                                    $newname = substr($name1, 0, strlen($name) - 1) . $num;
                                    $test = substr($test, 0, strlen($test) - 1) . $num;

                                }
                                $name1 = "";
                                $name2 = "";
                                $name3 = "";
                                $name4 = "";
                                ?>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-secondary btn-lg btn-block add">add a Line</button>
                    </tbody>
                </table>
                <div>
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center">Dépenses</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Carburant</th>
                                        <th scope="col">Péage Autoroute</th>
                                        <th scope="col">Repas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <table class="table" id="carburant">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Coût</th>
                                                    <th scope="col">Paiement</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control cout" id="cout_carburant1"
                                                               name=<?php echo $name = "cout_carburant1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>" onkeyup="myFunction()">
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment"
                                                                id="paiement_carburant1"
                                                                name="paiement_carburant1">
                                                            <option selected>...</option>
                                                            <?php options('paiement_carburant','paiement_carburant1');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text" class="form-control cout" id="cout_carburant2"
                                                               name=<?php echo $name = "cout_carburant2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>" onkeyup="myFunction()">
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment"
                                                                id="paiement_carburant2"
                                                                name="paiement_carburant2">
                                                            <option selected>...</option>
                                                            <?php options('paiement_carburant','paiement_carburant2');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text" class="form-control cout" id="cout_carburant3"
                                                               name=<?php echo $name1 = "cout_carburant3"; ?> value="<?php echo Input::get($name1);
                                                               ?>" onkeyup="myFunction()">
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment"
                                                                id="paiement_carburant3"
                                                                name=<?php echo $name2 = "paiement_carburant3"; ?>>
                                                            <option selected>...</option>
                                                            <?php options('paiement_carburant','paiement_carburant3');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php
                                                $num = substr($name1, -1);
                                                $num++;
                                                $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                $test = 'paiement_carburant4';
                                                while (Input::get($test)) {
                                                    echo " <tr>";

                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control cout' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo "onkeyup='myFunction()'></td>";

                                                    $newname = substr($name2, 0, strlen($name2) - 1) . $num;
                                                    echo "<td><select class='custom-select form-control payment' id='";
                                                    echo $newname, "'";
                                                    echo "name='", $newname, "' >";

                                                    echo " <option selected >...</option>";
                                                    echo " <option value='One'", select($newname, 'One'), ">One</option>";
                                                    echo " <option value='Two'", select($newname, 'Two'), ">Two</option>";
                                                    echo " <option value='Three'", select($newname, 'Three'), ">Three</option>";

                                                    echo "</tr>";

                                                    $num++;
                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    $test = substr($test, 0, strlen($test) - 1) . $num;
                                                }
                                                $name1 = "";
                                                $name2 = "";
                                                ?>

                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <table class="table" id="peage">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Coût</th>
                                                    <th scope="col">Paiement</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control cout" id="cout_peage1"
                                                               name=<?php echo $name = "cout_peage1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>" onkeyup="myFunction()">
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="paiement_peage1"
                                                                name="paiement_peage1">
                                                            <option selected>...</option>
                                                            <?php options('paiement_peage','paiement_peage1');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text" class="form-control cout" id="cout_peage2"
                                                               name=<?php echo $name = "cout_peage2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>" onkeyup="myFunction()">
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="paiement_peage2"
                                                                name="paiement_peage2">
                                                            <option selected>...</option>
                                                            <?php options('paiement_peage','paiement_peage2');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text" class="form-control cout" id="cout_peage3"
                                                               name=<?php echo $name1 = "cout_peage3"; ?> value="<?php echo Input::get($name1); ?>"
                                                               onkeyup="myFunction()">
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="paiement_peage3"
                                                                name=<?php echo $name2 = "paiement_peage3"; ?>>
                                                            <option selected>...</option>
                                                            <?php options('paiement_peage','paiement_peage3');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php
                                                $num = substr($name1, -1);
                                                $num++;
                                                $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                $test = 'paiement_carburant4';
                                                while (Input::get($test)) {
                                                    echo " <tr>";

                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control cout' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo "onkeyup='myFunction()'></td>";

                                                    $newname = substr($name2, 0, strlen($name2) - 1) . $num;
                                                    echo "<td><select class='custom-select form-control payment' id='";
                                                    echo $newname, "'";
                                                    echo "name='", $newname, "' >";

                                                    echo " <option selected >...</option>";
                                                    echo " <option value='One'", select($newname, 'One'), ">One</option>";
                                                    echo " <option value='Two'", select($newname, 'Two'), ">Two</option>";
                                                    echo " <option value='Three'", select($newname, 'Three'), ">Three</option>";

                                                    echo "</tr>";

                                                    $num++;
                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    $test = substr($test, 0, strlen($test) - 1) . $num;
                                                }
                                                $name1 = "";
                                                $name2 = "";
                                                ?>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <table class="table" id="repas">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Nature</th>
                                                    <th scope="col">Nbre P</th>
                                                    <th scope="col">Nbre J</th>
                                                    <th scope="col">Coût</th>
                                                    <th scope="col">Paiement</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <select class="custom-select form-control" id="nature1" name="nature1">
                                                            <option selected>...</option>
                                                            <?php options('nature_repas','nature1');?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" id="nb_p_repas1"
                                                               name=<?php echo $name = "nb_p_repas1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>">
                                                    </td>
                                                    <td><input type="text" class="form-control" id="nb_j_repas1"
                                                               name=<?php echo $name = "nb_j_repas1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>">
                                                    </td>
                                                    <td><input type="text" class="form-control cout" id="cout_repas1"
                                                               name=<?php echo $name = "cout_repas1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>" onkeyup="myFunction()">
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="paiement_repas1"
                                                                name="paiement_repas1">
                                                            <option selected>...</option>
                                                            <?php options('paiement_repas','paiement_repas1');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select class="custom-select form-control " id="nature2" name="nature2">
                                                            <option selected>...</option>
                                                            <?php options('nature_repas','nature2');?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" id="nb_p_repas2"
                                                               name=<?php echo $name = "nb_p_repas2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>">
                                                    </td>
                                                    <td><input type="text" class="form-control" id="nb_j_repas2"
                                                               name=<?php echo $name = "nb_j_repas2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>">
                                                    </td>
                                                    <td><input type="text" class="form-control cout" id="cout_repas2"
                                                               name=<?php echo $name = "cout_repas2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               onkeyup="myFunction()"
                                                        >
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="paiement_repas2"
                                                                name="paiement_repas2">
                                                            <option selected>...</option>
                                                            <?php options('paiement_repas','paiement_repas2');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select class="custom-select form-control" id="nature3"
                                                                name=<?php echo $name1 = "nature3"; ?>>
                                                            <option selected>...</option>
                                                            <?php options('nature_repas','nature3');?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" id="nb_p_repas3"
                                                               name=<?php echo $name2 = "nb_p_repas3"; ?>
                                                               value="<?php echo Input::get($name2);
                                                               ?>">
                                                    </td>
                                                    <td><input type="text" class="form-control" id="nb_j_repas3"
                                                               name=<?php echo $name3 = "nb_j_repas3"; ?>
                                                               value="<?php echo Input::get($name3);
                                                               ?>">
                                                    </td>
                                                    <td><input type="text" class="form-control cout" id="cout_repas3"
                                                               name=<?php echo $name4 = "cout_repas3"; ?> value="<?php echo Input::get($name4);
                                                               ?>"
                                                               onkeyup="myFunction()"
                                                        >
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="paiement_repas3"
                                                                name=<?php echo $name5 = "paiement_repas3"; ?>>
                                                            <option selected>...</option>
                                                            <?php options('paiement_repas','paiement_repas3');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php
                                                $num = substr($name1, -1);
                                                $num++;
                                                $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                $test = 'paiement_carburant4';
                                                while (Input::get($test)) {
                                                    echo " <tr>";

                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    echo "<td><select class='custom-select form-control ' id='";
                                                    echo $newname, "'";
                                                    echo "name='", $newname, "' >";
                                                    echo " <option selected >...</option>";
                                                    echo " <option value='One'", select($newname, 'One'), ">One</option>";
                                                    echo " <option value='Two'", select($newname, 'Two'), ">Two</option>";
                                                    echo " <option value='Three'", select($newname, 'Three'), ">Three</option>";

                                                    $newname = substr($name2, 0, strlen($name2) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo "></td>";

                                                    $newname = substr($name3, 0, strlen($name3) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo "></td>";

                                                    $newname = substr($name4, 0, strlen($name4) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control cout' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo " onkeyup='myFunction()' ></td>";

                                                    $newname = substr($name5, 0, strlen($name5) - 1) . $num;
                                                    echo "<td><select class='custom-select form-control payment' id='";
                                                    echo $newname, "'";
                                                    echo "name='", $newname, "' >";
                                                    echo " <option selected >...</option>";
                                                    echo " <option value='One'", select($newname, 'One'), ">One</option>";
                                                    echo " <option value='Two'", select($newname, 'Two'), ">Two</option>";
                                                    echo " <option value='Three'", select($newname, 'Three'), ">Three</option>";

                                                    echo "</tr>";

                                                    $num++;
                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    $test = substr($test, 0, strlen($test) - 1) . $num;

                                                }
                                                $name1 = "";
                                                $name2 = "";
                                                $name3 = "";
                                                $name4 = "";
                                                $name5 = "";
                                                ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-secondary btn-lg btn-block add">add a Line</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center">Dépenses</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <table class="table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Hôtel</th>
                                        <th scope="col">Autres Frais</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <table class="table" id="hotel">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Nbre P</th>
                                                    <th scope="col">Nbre J</th>
                                                    <th scope="col">PU</th>
                                                    <th scope="col">Coût</th>
                                                    <th scope="col">Paiement</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td><input type="text"
                                                               name=<?php echo $name = "nb_p_hotel1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control" id="nb_p_hotel1"
                                                        >
                                                    </td>
                                                    <td><input type="text"
                                                               name=<?php echo $name = "nb_j_hotel1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control" id="nb_j_hotel1"
                                                        >
                                                    </td>
                                                    <td><input type="text"
                                                               name=<?php echo $name = "pu1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control" id="pu1">
                                                    </td>
                                                    <td><input type="text"
                                                               name= <?php echo $name = "cout_hotel1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control cout" id="cout_hotel1"
                                                               onkeyup="myFunction()"
                                                        >
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="Paiement_hotel1"
                                                                name="Paiement_hotel1">
                                                            <option selected>...</option>
                                                            <?php options('paiement_hotel','paiement_hotel1');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text"
                                                               name= <?php echo $name = "nb_p_hotel2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control" id="nb_p_hotel2"
                                                        >
                                                    </td>
                                                    <td><input type="text"
                                                               name=<?php echo $name = "nb_j_hotel2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control" id="nb_j_hotel2"
                                                        >
                                                    </td>
                                                    <td><input type="text"
                                                               name=<?php echo $name = "pu2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control" id="pu2">
                                                    </td>
                                                    <td><input type="text"
                                                               name=<?php echo $name = "cout_hotel2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control cout" id="cout_hotel2"
                                                               onkeyup="myFunction()"
                                                        >
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="Paiement_hotel2"
                                                                name="Paiement_hotel2">
                                                            <option selected>...</option>
                                                            <?php options('paiement_hotel','paiement_hotel2');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text"
                                                               name=<?php echo $name1 = "nb_p_hotel3"; ?> value="<?php echo Input::get($name1);
                                                               ?>"
                                                               class="form-control" id="nb_p_hotel3"
                                                        >
                                                    </td>
                                                    <td><input type="text"
                                                               name=<?php echo $name2 = "nb_j_hotel3"; ?> value="<?php echo Input::get($name2);
                                                               ?>"
                                                               class="form-control" id="nb_j_hotel3"
                                                        >
                                                    </td>
                                                    <td><input type="text"
                                                               name=<?php echo $name3 = "pu3"; ?> value="<?php echo Input::get($name3);
                                                               ?>"
                                                               class="form-control" id="pu3">
                                                    </td>
                                                    <td><input type="text"
                                                               name=<?php echo $name4 = "cout_hotel3"; ?> value="<?php echo Input::get($name4);
                                                               ?>"
                                                               class="form-control cout" id="cout_hotel3"
                                                               onkeyup="myFunction()"
                                                        >
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="Paiement_hotel3"
                                                                name=<?php echo $name5 = "Paiement_hotel3"; ?>>
                                                            <option selected>...</option>
                                                            <?php options('paiement_hotel','paiement_hotel3');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php
                                                $num = substr($name1, -1);
                                                $num++;
                                                $test = 'paiement_carburant4';
                                                while (Input::get($test)) {
                                                    echo " <tr>";

                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo "></td>";

                                                    $newname = substr($name2, 0, strlen($name2) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo "></td>";

                                                    $newname = substr($name3, 0, strlen($name3) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo "></td>";


                                                    $newname = substr($name4, 0, strlen($name4) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control cout' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo " onkeyup='myFunction()' ></td>";

                                                    $newname = substr($name5, 0, strlen($name5) - 1) . $num;
                                                    echo "<td><select class='custom-select form-control payment' id='";
                                                    echo $newname, "'";
                                                    echo "name='", $newname, "' >";
                                                    echo " <option selected >...</option>";
                                                    echo " <option value='One'", select($newname, 'One'), ">One</option>";
                                                    echo " <option value='Two'", select($newname, 'Two'), ">Two</option>";
                                                    echo " <option value='Three'", select($newname, 'Three'), ">Three</option>";

                                                    echo "</tr>";

                                                    $num++;
                                                    $newname = substr($name1, 0, strlen($name) - 1) . $num;
                                                    $test = substr($test, 0, strlen($test) - 1) . $num;
                                                }
                                                $name1 = "";
                                                $name2 = "";
                                                $name3 = "";
                                                $name4 = "";
                                                ?>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <table class="table" id="autres">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Coût</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Paiement</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text"
                                                               name=<?php echo $name = "cout_autres1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control cout" id="cout_autres1"
                                                               onkeyup="myFunction()"
                                                        >
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control" id="description_autres1"
                                                                name="description_autres1">
                                                            <option selected>...</option>
                                                            <?php options('description_autres_frais','decription_autres1');?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="Paiement_autres1"
                                                                name="Paiement_autres1">
                                                            <option selected>...</option>
                                                            <?php options('paiement_autres_frais','paiement_autres1');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text"
                                                               name=<?php echo $name = "cout_autres2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control cout" id="cout_autres2"
                                                               onkeyup="myFunction()"
                                                        >
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control" id="desciption_autres2"
                                                                name="desciption_autres2">
                                                            <option selected>...</option>
                                                            <?php options('description_autres_frais','decription_autres2');?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="Paiement_autres2"
                                                                name="Paiement_autres2">
                                                            <option selected>...</option>
                                                            <?php options('paiement_autres_frais','paiement_autres2');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="text"
                                                               name=<?php echo $name1 = "cout_autres3"; ?> value="<?php echo Input::get($name1);
                                                               ?>"
                                                               class="form-control cout" id="cout_autres3"
                                                               onkeyup="myFunction()"
                                                        >
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control" id="description_autres3"
                                                                name=<?php echo $name2 = "description_autres3"; ?>>
                                                            <option selected>...</option>
                                                            <?php options('description_autres_frais','decription_autres2');?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="custom-select form-control payment" id="Paiement_autres3"
                                                                name=<?php echo $name3 = "Paiement_autres3"; ?>>
                                                            <option selected>...</option>
                                                            <?php options('paiement_autres_frais','paiement_autres3');?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php
                                                $num = substr($name1, -1);
                                                $num++;
                                                $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                $test = 'paiement_carburant4';
                                                while (Input::get($test)) {
                                                    echo " <tr>";

                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control cout' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo " onkeyup='myFunction()' ></td>";

                                                    $newname = substr($name2, 0, strlen($name2) - 1) . $num;
                                                    echo "<td><select class='custom-select form-control   ' id='";
                                                    echo $newname, "'";
                                                    echo "name='", $newname, "' >";
                                                    echo " <option selected >...</option>";
                                                    echo " <option value='One'", select($newname, 'One'), ">One</option>";
                                                    echo " <option value='Two'", select($newname, 'Two'), ">Two</option>";
                                                    echo " <option value='Three'", select($newname, 'Three'), ">Three</option>";

                                                    $newname = substr($name3, 0, strlen($name3) - 1) . $num;
                                                    echo "<td><select class='custom-select form-control payment' id='";
                                                    echo $newname, "'";
                                                    echo "name='", $newname, "' >";
                                                    echo " <option selected >...</option>";
                                                    echo " <option value='One'", select($newname, 'One'), ">One</option>";
                                                    echo " <option value='Two'", select($newname, 'Two'), ">Two</option>";
                                                    echo " <option value='Three'", select($newname, 'Three'), ">Three</option>";

                                                    echo "</tr>";

                                                    $num++;
                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    $test = substr($test, 0, strlen($test) - 1) . $num;

                                                }
                                                $name1 = "";
                                                $name2 = "";
                                                $name3 = "";
                                                ?>
                                                </tbody>
                                            </table>
                                        </td>

                                        <td>
                                            <table class="table" id="total">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>=</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text"
                                                               name=<?php echo $name = "total1"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control total" id="total1">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text"
                                                               name=<?php echo $name = "total2"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control total" id="total2">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text"
                                                               name=<?php echo $name1 = "total3"; ?> value="<?php echo Input::get($name1);
                                                               ?>"
                                                               class="form-control total" id="total3">
                                                    </td>
                                                </tr>
                                                <?php
                                                $num = substr($name1, -1);
                                                $num++;
                                                $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                $test = 'paiement_carburant4';
                                                while (Input::get($test)) {
                                                    echo " <tr>";

                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    echo "<td><input type='text' class='form-control total' id='", $newname, "'";
                                                    echo "name='", $newname, "'";
                                                    echo "value='", Input::get($newname), "'";
                                                    echo "></td>";

                                                    echo "</tr>";

                                                    $num++;

                                                    $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                                                    $test = substr($test, 0, strlen($test) - 1) . $num;
                                                }
                                                $name = "";
                                                ?>
                                                </tbody>
                                            </table>
                                    <tr>
                                        <td colspan="2">
                                            <button type="button" class="btn btn-secondary btn-lg btn-block add">add a Line
                                            </button>
                                        </td>
                                        <td>
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <th scope="row">Total en espèces</th>
                                                    <td><input type="text"
                                                               name=<?php echo $name = "total"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control" id="total_espece">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Dépassement</th>
                                                    <td><input type="text"
                                                               name=<?php echo $name = "depassement"; ?> value="<?php echo Input::get($name);
                                                               $name = ""; ?>"
                                                               class="form-control" id="depassement"
                                                        >
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>
            <input type="hidden" name="id" value="<?php echo Input::get('id'); ?>">
            <input type="hidden" name="disabled" value="<?php  disable(Input::get('id')); ?>">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <input type="hidden" id="add_lines" name="add_lines" value=0>
            <input type="hidden" id="lines" name="lines" value="<?php echo Input::get('add_lines'); ?>">
            <button type="submit" class="btn btn-primary btn-lg btn-block">Next</button>
    </form>

    <script type="text/javascript" src="editFMM_2.js"></script>
<?php
require_once('_footer.php');
?>