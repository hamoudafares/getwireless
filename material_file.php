<?php
require_once('_header.php');
if (Input::exists()){
    if (Session::exists(Config::get("session/token_name")) && (Session::get(Config::get("session/token_name")) === Input::get('token'))) {
        if (Input::get('disabled')==='disabled') {
                Redirect::to("editFM_part2.php",array('visit=true&id'=>Input::get('id')));
            }

        try {
            $fields = array(
                'material_file' => json_encode($_POST)
            );
            $fm = new FM(Input::get('id'));
            $fm->update($fields,Input::get('id'));
            if($fm->is_visiting()){
                    Redirect::to("editFM_part2.php",array('visit=true&id'=>Input::get('id')));
            }else{
                Redirect::to("suivi_vehicule.php",array('id'=>Input::get('id')));
            }
        } catch (Exception $e) {
            die($e->getMessage());
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
    if ($fm->data()->material_file){
        $datas = json_decode($fm->data()->material_file);
        foreach ($datas as $name => $data) {
            $_POST[$name] = $data;
        }
    }else{
        $datas = json_decode($fm->data()->mission_file);
        foreach ($datas as $name => $data) {
            $_POST[$name] = $data;
        }
    }
}
?>
<h1 class="display-4 align-middle" align="center">Fiche Matériel</h1>
<form action="" method="post" role="form">
    <fieldset <?php disable(Input::get('id')) ;?>>
    <p class="float-right" id="FM_infos" name="FM_infos"><?php echo Input::get('FM_id') ?></p>
    <div class="row">
        <div class="form-group col-2">
            <label for="dt">DT</label>
            <input type="text" class="form-control" id="dt"
                   name=<?php echo $name = "dt"; ?>
                   value="<?php echo Input::get($name);
                   $name = ""; ?>">
        </div>
        <div>
            <label for="driver1">Driver</label>
            <input type="text" class="form-control" id="driver1"
                   name=<?php echo $name = "driver1"; ?>
                   value="<?php echo Input::get($name);
                   $name = ""; ?>">
        </div>
    </div>
    <p class="h5">Chaine de mesure</p>
    <table class="table col-12 table-bordered" id="carburant">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Matériel</th>
            <th scope="col">Qté</th>
            <th scope="col">Code Equipement</th>
            <th scope="col">Remarques</th>
            <th scope="col">N° de puce</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><input type="text" class="form-control" id="materiel_mesure1"
                       name=<?php echo $name1 = "materiel_mesure1"; ?>
                       value="<?php echo Input::get($name1);
                        ?>"></td>
            <td><input type="text" class="form-control" id="qte_mesure1"
                       name=<?php echo $name2 = "qte_mesure1"; ?>
                       value="<?php echo Input::get($name2);
                       ?>"></td>
            <td><input type="text" class="form-control" id="code_mesure1"
                       name=<?php echo $name3 = "code_mesure1"; ?>
                       value="<?php echo Input::get($name3);
                        ?>"></td>
            <td><input type="text" class="form-control" id="remarque_mesure1"
                       name=<?php echo $name4 = "remarque_mesure1"; ?>
                       value="<?php echo Input::get($name4);
                        ?>"></td>
            <td><input type="text" class="form-control" id="puce_mesure1"
                       name=<?php echo $name5 = "puce_mesure1"; ?>
                       value="<?php echo Input::get($name5);
                        ?>"></td>
        </tr>
        <?php
        $num = substr($name1, -1);
        $num++;
        $test = 'materiel_mesure2';
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
            echo "<td><input type='text' class='form-control' id='", $newname, "'";
            echo "name='", $newname, "'";
            echo "value='", Input::get($newname), "'";
            echo "></td>";


            $newname = substr($name5, 0, strlen($name5) - 1) . $num;
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
        ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-secondary btn-lg btn-block add">add a Line</button>
    <br>
    <p class="h5">Autre Matériel</p>
    <br>
    <table class="table col-12 table-bordered" id="description">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Matériel</th>
            <th scope="col">Qté</th>
            <th scope="col">Code Equipement</th>
            <th scope="col">Remarques</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><input type="text" class="form-control" id="materiel_autres1"
                       name=<?php echo $name1 = "materiel_autres1"; ?>
                       value="<?php echo Input::get($name1);
                       ?>"></td>
            <td><input type="text" class="form-control" id="qte_autres1"
                       name=<?php echo $name2 = "qte_autres1"; ?>
                       value="<?php echo Input::get($name2);
                       ?>"></td>
            <td><input type="text" class="form-control" id="code_autres1"
                       name=<?php echo $name3 = "code_autres"; ?>
                       value="<?php echo Input::get($name3);
                       ?>"></td>
            <td><input type="text" class="form-control" id="remarque_autres1"
                       name=<?php echo $name4 = "remarque_autres1"; ?>
                       value="<?php echo Input::get($name4);
                       ?>"></td>
        </tr>
        <?php
        $num = substr($name1, -1);
        $num++;
        $test = 'materiel_autres2';
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
        ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-secondary btn-lg btn-block add">add a Line</button>
    <br>
    <p class="h5">Signatures</p>
    <table class="table col-12 table-bordered" >
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Chef de Projet</th>
            <th scope="col">Responsable Logistique</th>
        </tr>
        </thead>
        <tbody>
        <tr>
                <th>Date</th>
            <td><input type="date" class="form-control" id="date_chef"
                       name=<?php echo $name = "date_chef"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>" >
            </td><td><input type="date" class="form-control" id="date_responsable"
                       name=<?php echo $name = "date_responsable"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>"></td>
        </tr>
        <tr>
                <th>Validation</th>
            <td>
                                                <span class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input "
                       id="validation_chef" name=<?php echo $name="validation_chef";?> <?php isOn($name) ;?> <?php checkValidation('chef_projet',$fm->data()->id);
                ?>>
                <label class="custom-control-label" for="validation_chef"></label>
                                                </span>
            </td>
            <td>
                                                <span class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input "
                       id="validation_responsable" name=<?php echo $name="validation_responsable";?> <?php isOn($name) ;?><?php checkValidation('logistique',$fm->data()->id);
                ?>>
                <label class="custom-control-label" for="validation_responsable"></label>
                                                </span>
            </td>
        </tr>
        </tbody>
    </table>
    <p class="h5">Retour Matériel</p>
    <fieldset <?php checkValidation('logistique',$fm->data()->id);
    ?> >
        <div class="row">
            <div class="form-group col-2">
                <label for="date_retour">Date:</label>
                <input type="date" class="form-control" id="date_retour"
                       name=<?php echo $name = "date_retour"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
            <div>
                <label for="heure_retour">Heure:</label>
                <input type="time" class="form-control" id="heure_retour"
                       name=<?php echo $name = "heure_retour"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-8">
                <label for="degat">Fiche dégât matériel N° :</label>
                <input type="text" class="form-control" id="degat"
                       name=<?php echo $name = "degat"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
            <div class="custom-control custom-checkbox col-3">
                <input type="checkbox" class="custom-control-input"
                       id="etat_materiel" name=<?php echo $name ="etat_materiel" ; ?>
                       <?php isOn($name) ;?> >
                <label class="custom-control-label" for="etat_materiel">Etat matériel au retour validé </label>
            </div>
        </div>
    </fieldset>

<p class="h5">Validation:</p>
<table class="table col-12 table-bordered" >
    <thead >
    <tr>
        <th colspan="2">En cas de passation</th>
    </tr>
    <tr>
        <th scope="col">2ème DT Lead</th>
        <th scope="col">N°Fiche de Mission</th>
        <th scope="col">Chef de Projet</th>
        <th scope="col">Resp Logistique</th>
    </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" class="form-control" id="dt2"
                       name=<?php echo $name = "dt2"; ?>
                       value="<?php echo Input::get("dt");
                       $name = ""; ?>">
                </div>
            </td>
            <td>
                <input type="text" class="form-control" id="fiche"
                       name=<?php echo $name = "fiche"; ?>
                       value="<?php echo Input::get('FM_id');
                       $name = ""; ?>">
            </td>
            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="validation_chef_materiel" name=<?php echo $name="validation_chef_materiel";?> <?php isOn($name) ;?> <?php checkValidation('chef_projet',$fm->data()->id);
                                    ?>>
                                    <label class="custom-control-label" for="validation_chef_materiel">Validate</label>
                                </div></td>
            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="validation_responsable_materiel" name=<?php echo $name="validation_responsable_materiel";?> <?php isOn($name) ;?> <?php checkValidation('logistique',$fm->data()->id);
                                    ?> >
                                    <label class="custom-control-label" for="validation_responsable_materiel">Validate</label>
                                </div></td>
        </tr>
        </tbody>
</table>
    </fieldset>
    <input type="hidden" name="disabled" value="<?php  disable(Input::get('id')); ?>">
    <input type="hidden" name="id" value="<?php if (Input::get('id')){echo Input::get('id');}else{echo $fm->data()->id;} ?>">
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="hidden" id="add_lines" name="add_lines" value=0>
    <input type="hidden" id="FM_id" name="FM_id" value="<?php echo Input::get('FM_id'); ?>">
    <input type="hidden" id="lines" name="lines" value="<?php echo Input::get('add_lines'); ?>">
    <button type="submit" class="btn btn-primary btn-lg btn-block">Next</button>
</form>

<script type="text/javascript" src="editFMM_2.js"></script>
<?php
require_once ('_footer.php');
?>
