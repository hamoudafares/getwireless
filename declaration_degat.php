<?php
require_once "_header.php";
$user = new User();
if (Input::exists()){
    if (Session::exists(Config::get("session/token_name")) && (Session::get(Config::get("session/token_name")) === Input::get('token'))) {
        if (Input::get('disabled')==='disabled') {
            Redirect::to("suivi_vehicule.php",array('visit=true&id'=>Input::get('id')));
        }
            try {
            $fields = array(
                'declaration_degat' => json_encode($_POST)
            );
            $fm = new FM(Input::get('id'));
            $fm->update($fields,Input::get('id'));

            Redirect::to("suivi_vehicule.php",array('id'=>Input::get('id')));
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
    if ($fm->data()->declaration_degat){
        $datas = json_decode($fm->data()->declaration_degat);
        foreach ($datas as $name => $data) {
            $_POST[$name] = $data;
        }
        if (Input::get("empty")==='on'){
            Redirect::to("suivi_vehicule.php",array('id'=>Input::get('id')));
        }
    }else{
        $datas = json_decode($fm->data()->mission_file);
        foreach ($datas as $name => $data) {
            $_POST[$name] = $data;
        }
    }
}
?>
<form action="" method="post" role="form">
    <fieldset <?php disable(Input::get('id')) ;?>>
    <span class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input"
               id="empty" name=<?php echo $name2 ="empty" ; ?>
            <?php isOn($name2) ;?>>
        <label class="custom-control-label" for="empty">rien à déclarer</label>
    </span>
<p class="h5">Annonceur</p>
<div class="form-row ">
<div class="col-4" >
    <label for="nom_annonceur">Nom</label>
    <select class="custom-select form-control" id="nom_annonceur" name="nom_annonceur">
        <option>...</option>
        <?php users('nom_annonceur');?>
    </select>
</div>
    <span class="col-4 float-right">
        <label for="service">Unité</label>
        <select class="custom-select form-control" id="service" name="service">
            <option>...</option>
            <?php options('service','service');?>
        </select>
    </span>
    </div>
<div class="form-row ">
    <div class="form-group col-4">
        <label for="projet">Projet</label>
        <input type="text" class="form-control" id="projet"
               name=<?php echo $name = "projet"; ?> value="<?php echo Input::get($name);
               $name = ""; ?>">
    </div>
    <div class="form-group col-4">
        <label for="operateur">Opérateur</label>
        <input type="text" class="form-control" id="operateur"
               name=<?php echo $name = "operateur"; ?> value="<?php echo Input::get($name);
               $name = ""; ?>">
    </div>
    </div>
<div class="form-group col-4">
    <label for="chef_projet">Coordinateur/Chef de Projet</label>
    <input type="text" class="form-control" id="chef_projet"
           name=<?php echo $name = "chef_projet"; ?> value="<?php echo Input::get($name);
           $name = ""; ?>">
</div>

<p class="h5">Informations sur le matériel</p>
<div class="form-row">
    <table class="table col-12 table-bordered" id="description">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Désignation</th>
            <th scope="col">Code</th>
            <th scope="col">Marque</th>
            <th scope="col">Modèle</th>
            <th scope="col">N° de série</th>
            <th scope="col">Spécification techniques</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <select class="custom-select form-control" id="designation1" name=<?php echo $name1 = "designation1";?>>
                    <option selected>...</option>
                    <?php options('designation','designation1'); ?>
                </select>
            </td>
            <td>
                <input type="text" class="form-control" id="code1"
                       name=<?php echo $name2 = "code1"; ?> value="<?php echo Input::get($name2);
                       ?>">
            </td>
            <td>
                <input type="text" class="form-control " id="marque1"
                       name=<?php echo $name3 = "marque1"; ?> value="<?php echo Input::get($name3);
                        ?>">
            </td>
            <td>
                <input type="text" class="form-control" id="modele1"
                       name=<?php echo $name4 = "modele1"; ?> value="<?php echo Input::get($name4);
                      ?>" >
            </td>
            <td>
                <input type="text" class="form-control" id="serie1"
                       name=<?php echo $name5 = "serie1"; ?> value="<?php echo Input::get($name5);
                       ?>">
            </td>
            <td>
                <textarea class="form-control" id="specification1"
                          name=<?php echo $name6="specification1";?>  rows="3"><?php echo Input::get($name6);
                    ?></textarea>
            </td>
        </tr>
        <?php
        $num = substr($name1, -1);
        $num++;
        $newname = substr($name1, 0, strlen($name1) - 1) . $num;
        $test = 'designation2';
        while (Input::get($test)) {
            echo " <tr>";

            $newname = substr($name1, 0, strlen($name1) - 1) . $num;
            echo "<td><select class='custom-select form-control ' id='";
            echo $newname, "'";
            echo "name='", $newname, "' >";
            echo " <option selected >...</option>";
            options('designation',$newname);

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

            $newname = substr($name6, 0, strlen($name6) - 1) . $num;
            echo "<td><textarea  class='form-control' id='", $newname, "'";
            echo "name='", $newname, "'";
            echo "rows='3'>",Input::get($name6),"</textarea></td>";




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
        $name6 = "";
        ?>
        </tbody>
    </table>
</div>
    <p class="h5">Circonstances exactes du dégât</p>

    <div class="form-row">
        <table class="table col-12 table-bordered" id="carburant">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Date du sini</th>
                <th scope="col">Heure du sini</th>
                <th scope="col">Lieu</th>
                <th scope="col">Description des faits </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="date" class="form-control" id="date_sinitre1"
                           name=<?php echo $name1 = "date_sinistre1"; ?>
                           value="<?php echo Input::get($name1);
                            ?>">
                </td>
                <td>
                    <input type="time" class="form-control" id="heure_sinitre1"
                           name=<?php echo $name2 = "heure_sinistre1"; ?>
                           value="<?php echo Input::get($name2);
                           ?>">
                </td>
                <td>
                    <input type="text" class="form-control" id="lieu1"
                           name=<?php echo $name3 = "lieu1"; ?> value="<?php echo Input::get($name3);
                           ?>">
                </td>
                <td>
                <textarea class="form-control" id="description1"  name=<?php echo $name4="description1";?> rows="3"><?php echo Input::get($name4);
                    ?></textarea>
                </td>
            </tr>
            <?php
            $num = substr($name1, -1);
            $num++;
            $newname = substr($name1, 0, strlen($name1) - 1) . $num;
            $test = 'date_sinistre2';
            while (Input::get($test)) {
                echo " <tr>";



                $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                echo "<td><input type='date' class='form-control' id='", $newname, "'";
                echo "name='", $newname, "'";
                echo "value='", Input::get($newname), "'";
                echo "></td>";

                $newname = substr($name2, 0, strlen($name2) - 1) . $num;
                echo "<td><input type='time' class='form-control' id='", $newname, "'";
                echo "name='", $newname, "'";
                echo "value='", Input::get($newname), "'";
                echo "></td>";

                $newname = substr($name3, 0, strlen($name3) - 1) . $num;
                echo "<td><input type='text' class='form-control' id='", $newname, "'";
                echo "name='", $newname, "'";
                echo "value='", Input::get($newname), "'";
                echo "></td>";


                $newname = substr($name4, 0, strlen($name4) - 1) . $num;
                echo "<td><textarea  class='form-control' id='", $newname, "'";
                echo "name='", $newname, "'";
                echo "rows='3'>",Input::get($newname),"</textarea></td>";


                echo "</tr>";

                $num++;
                $newname = substr($name1, 0, strlen($name1) - 1) . $num;
                $test = substr($test, 0, strlen($test) - 1) . $num;

            }
            $name1 = "";
            $name2 = "";
            $name3 = "";
            $name4 = "";
            ?>
            </tbody>
        </table>
    </div>
<div class="form-row">
    <table class="table col-12 table-bordered" id="hotel">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Propriétaire du matériel</th>
            <th scope="col">client informé</th>
            <th scope="col">date information du client</th>
            <th scope="col">Décision pour réparer</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" class="form-control" id="proprietaire1"
                       name=<?php echo $name1 = "proprietaire1"; ?>
                       value="<?php echo Input::get($name1);
                        ?>" placeholder="GET">
            </td>
            <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input form-control"
                                           id="informe1" name=<?php echo $name2 ="informe1" ; ?>
                                        <?php isOn($name2) ;?>>
                                    <label class="custom-control-label" for="informe1">Oui</label>
                                </span>
            </td>
            <td>
                <input type="date" class="form-control" id="date_infos1"
                       name=<?php echo $name3 = "date_infos1"; ?>
                       value="<?php echo Input::get($name3);
                       ?>">
            </td>
            <td>
                <span class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input form-control"
                           id="repare1" name=<?php echo $name4 ="repare1" ; ?>
                        <?php isOn($name4) ;?>>
                    <label class="custom-control-label" for="repare1">Oui</label>
                </span>
            </td>
        </tr>
        <?php
        $num = substr($name1, -1);
        $num++;
        $newname = substr($name1, 0, strlen($name1) - 1) . $num;

            $test='designation2';

        while (Input::get($test)) {
            echo " <tr>";

            $newname = substr($name1, 0, strlen($name1) - 1) . $num;
            echo "<td><input type='text' class='form-control' id='", $newname, "'";
            echo "name='", $newname, "'";
            echo "value='", Input::get($newname), "'  ";
            echo "></td>";

            $newname = substr($name2, 0, strlen($name2) - 1) . $num;

            echo "<td><span class=\"custom-control custom-checkbox\">
            <input type='checkbox' class='custom-control-input' id='", $newname, "'";
            echo "name='", $newname, "'";
            isOn($newname);
            echo "><label class=\"custom-control-label\" for='",$newname,"'>Oui</label>
            </span></td>";



            $newname = substr($name3, 0, strlen($name3) - 1) . $num;
            echo "<td><input type='date' class='form-control' id='", $newname, "'";
            echo "name='", $newname, "'";
            echo "value='", Input::get($newname), "'";
            echo "></td>";

            $newname = substr($name4, 0, strlen($name4) - 1) . $num;

            echo "<td><span class=\"custom-control custom-checkbox\">
            <input type='checkbox' class='custom-control-input' id='", $newname, "'";
            echo "name='", $newname, "'";
            isOn($newname);
            echo "><label class=\"custom-control-label\" for='",$newname,"'>Oui</label>
            </span></td>";


            echo "</tr>";

            $num++;
            $newname = substr($name1, 0, strlen($name1) - 1) . $num;
            $test = substr($test, 0, strlen($test) - 1) . $num;

        }
        $name1 = "";
        $name2 = "";
        $name3 = "";
        $name4 = "";
        ?>
        </tbody>
    </table>
</div>
<p class="h5">Suivi d'intervention</p>
<div class="form-row">
    <table class="table col-12 table-bordered" id="autres">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Type d'intervention</th>
            <th scope="col">Date</th>
            <th scope="col">Réf. DI</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" class="form-control" id="intervention1"
                       name=<?php echo $name1 = "intervention1"; ?>
                       value="<?php echo Input::get($name1);
                        ?>">
            </td>
            <td>
                <input type="date" class="form-control" id="date_intervention1"
                       name=<?php echo $name2 = "date_intervention1"; ?>
                       value="<?php echo Input::get($name2);
                        ?>">
            </td>
            <td>
                <input type="text" class="form-control" id="reference1"
                       name=<?php echo $name3 = "reference1"; ?>
                       value="<?php echo Input::get($name3);
                        ?>">
            </td>
        </tr>
        <?php
        $num = substr($name1, -1);
        $num++;
        $newname = substr($name1, 0, strlen($name1) - 1) . $num;
        $test = 'designation2';
        while (Input::get($test)) {
            echo " <tr>";

            $newname = substr($name1, 0, strlen($name1) - 1) . $num;
            echo "<td><input type='text' class='form-control' id='", $newname, "'";
            echo "name='", $newname, "'";
            echo "value='", Input::get($newname), "'";
            echo "></td>";

            $newname = substr($name2, 0, strlen($name2) - 1) . $num;
            echo "<td><input type='date' class='form-control' id='", $newname, "'";
            echo "name='", $newname, "'";
            echo "value='", Input::get($newname), "'";
            echo "></td>";



            $newname = substr($name3, 0, strlen($name3) - 1) . $num;
            echo "<td><input type='text' class='form-control' id='", $newname, "'";
            echo "name='", $newname, "'";
            echo "value='", Input::get($newname), "'";
            echo "></td>";

            echo "</tr>";

            $num++;
            $newname = substr($name1, 0, strlen($name1) - 1) . $num;
            $test = substr($test, 0, strlen($test) - 1) . $num;

        }
        $name1 = "";
        $name2 = "";
        $name3 = "";
        $name4 = "";
        ?>
        </tbody>
    </table>
</div>
<button type="button" class="btn btn-secondary btn-lg btn-block add">add a Line</button>
<br>
<br>
<br>
<p class="h5">Validation</p>
<br>
<table class="table col-8 table-bordered" align="center" >
    <thead class="thead-light">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Annonceur</th>
        <th scope="col">Chef de Projet</th>
        <th scope="col">Responsable Logistique</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th>Validation</th>
        <td>
            <input type="text" class="form-control" id="annonceur"
                   name=<?php echo $name = "annonceur"; ?>
                   value="<?php if (Input::get($name)){echo Input::get($name);}else{echo $user->data()->username ;}
                   $name = ""; ?>" hidden>
        <?php
        $name="annonceur";
        if (Input::get($name)){echo Input::get($name);}else{echo $user->data()->username ;}
        $name="";
        ?>
        </td>
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
    </fieldset>
    <input type="hidden" name="disabled" value="<?php  disable(Input::get('id')); ?>">
    <input type="hidden" name="id" value="<?php if (Input::get('id')){echo Input::get('id');}else{echo $fm->data()->id;} ?>">
<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
<input type="hidden" id="add_lines" name="add_lines" value=0>
<input type="hidden" id="FM_id" name="FM_id" value="<?php echo Input::get('FM_id'); ?>">
<input type="hidden" id="lines" name="lines" value="<?php echo Input::get('add_lines'); ?>">
<button type="submit" class="btn btn-primary btn-lg btn-block">Next</button>
</form>
<script type="text/javascript" src="editFM_declaration.js"></script>
<?php
require_once "_footer.php";
?>
