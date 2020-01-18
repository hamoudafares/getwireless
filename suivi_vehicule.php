<?php
require_once('_header.php');
if (Input::exists()){
    if (Session::exists(Config::get("session/token_name")) && (Session::get(Config::get("session/token_name")) === Input::get('token'))) {
        if (Input::get('disabled')==='disabled') {
            Session::flash('success', 'your visit is finished! ');
            Redirect::to("mission_files.php");
        }
        try {
            $user = new User();
            $fm = new FM(Input::get('id'));
            $test=false ;
            $finish=null;
            if ($fm->is_finished()==='0'&&$user->data()->username===$fm->data()->author){
                $finish='1';
                $test=true;
            }else if ($fm->is_finished()==='1'&&$user->data()->username===$fm->data()->author){
                $finish='2';
                $test=true;
            }
            if ($finish){
                $fields = array(
                    'suivi_vehicule' => json_encode($_POST),
                    'is_finished'=>$finish
                );
            }else{
                $fields = array(
                    'suivi_vehicule' => json_encode($_POST)
                );
            }
            if ($fm->is_finished()==='1'&&$test&&((!$fm->is_visiting())||($fm->is_visiting()==='off'))) {
            $fields['finish_date']=date('Y-m-d H:i:s');
            }
                $fm->update($fields,Input::get('id'));
            if ($fm->is_finished()==='0'&&$test&&((!$fm->is_visiting())||($fm->is_visiting()==='off'))){
                Session::flash('success', 'your first part FileMission is finished successfully ! ');
            }
            if ($fm->is_finished()==='1'&&$test&&((!$fm->is_visiting())||($fm->is_visiting()==='off'))){
                Session::flash('success', 'your FileMission is finished successfully ! ');
            }
            if ($fm->is_visiting()==='on'){
                $fm->visited(Input::get('id')) ;
                Session::flash('success', 'your visit is finished ! ');
            }
            Redirect::to("mission_files.php");

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}else if (Input::exists('get')) {
    $fm = new FM(Input::get('id'));
    secure(Input::get('id'));
    if ($fm->data()->suivi_vehicule){
        $datas = json_decode($fm->data()->suivi_vehicule);
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
<h1 class="display-4 align-middle" align="center">suivi vehicules</h1>
<form action="" method="post" role="form">
    <fieldset <?php disable(Input::get('id')) ;?>>
        <p class="h5">Informations sur le Véhicule</p>
        <div class="row">
            <div class="form-group col-4">
                <label for="nature_vehicule1">nature</label>
                <input type="text" class="form-control" id="nature_vehicule1"
                       name=<?php echo $name = "nature_vehicule1"; ?>
                       value="<?php echo Input::get($name); $name = ""; ?>">
            </div>
            <div class="form-group col-4">
                <label for="fournisseur1">fournisseur</label>
                <input type="text" class="form-control" id="fournisseur1"
                       name=<?php echo $name = "fournisseur1"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
            <div class="form-group col-4">
                <label for="type_vehicule1">marque</label>
                <input type="text" class="form-control" id="type_vehicule1"
                       name=<?php echo $name = "type_vehicule1"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
            <div class="form-group col-4">
                <label for="matricule1">immatriculation</label>
                <input type="text" class="form-control" id="matricule1"
                       name=<?php echo $name = "matricule1"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
        </div>
        <p class="h5">Informations sur le Chauffeur</p>
        <div class="row">
            <div class="form-group col-4">
                <label for="driver1">Nom</label>
                <input type="text" class="form-control" id="driver1"
                       name=<?php echo $name = "driver1"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
            <div class="form-group col-4">
                <label for="contact1">Contact</label>
                <input type="text" class="form-control" id="contact1"
                       name=<?php echo $name = "contact1"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
        </div>
        <p class="h5">Informations sur la mission</p>
        <div class="row">
            <div class="form-group col-4">
                <label for="FM_id">N°FM</label>
                <input type="text" class="form-control" id="FM_id"
                       name=<?php echo $name = "FM_id"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
            <div class="form-group col-4">
                <label for="dtlead">DT Lead</label>
                <input type="text" class="form-control" id="dtlead"
                       name=<?php echo $name = "dtlead"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
            <div class="form-group col-4">
                <label for="projet">Projet</label>
                <input type="text" class="form-control" id="projet"
                       name=<?php echo $name = "projet"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
        </div>
        <p class="h5">état de véhicule avant mission</p>
        <p class="h4">NB : Merci de cocher la zône endommagée et mettre un commentaire dans la case "Observations"</p>
        <table class="table col-12 table-bordered">
            <thead class="thead-light">
            <tr>
                <th scope="col"></th>
                <th scope="col">Parties</th>
                <th scope="col">Validation</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>ANTENNE</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input "
                                           id="antenne_1" name=<?php echo $name ="antenne_1" ; ?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antenne_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>2</td>
                <td>ANTIBROUILLARD G</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input "
                                           id="antibrouillardG_1" name=<?php echo $name="antibrouillardG_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antibrouillardG_1"></label>
                                </span></td>

            </tr>
            <tr>
                <td>3</td>
                <td>ANTIBROUILLARD D</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antibrouillardD_1"
                                           name=<?php echo $name="antibrouillardD_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antibrouillardD_1"></label>
                                </span>
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>AUTOCOLLANT-VIGNETTE+PAPIER</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="autocollant_1" name=<?php echo $name="autocollant_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="autocollant_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>5</td>
                <td>CENDRIER+ALLUME-CIGARETTES</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="cendrier_1" name=<?php echo $name="cendrier_1";?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="cendrier_1"></label>
                                </span>
                </td>
            </tr>
            <tr>
                <td>6</td>
                <td>CLIGNOTANT DROIT</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="clignotantD_1" name=<?php echo $name="clignotantD_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="clignotantD_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>7</td>
                <td>CLIGNOTANT GAUCHE</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="clignotantG_1" name=<?php echo $name="clignotantG_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="clignotantG_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>8</td>
                <td>CRIC+ACCESSOIRES</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="cric_1" name=<?php echo $name="cric_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="cric_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>9</td>
                <td>ENJOLIVEURS</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="enjoliveurs_1" name=<?php echo $name="enjoliveurs_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="enjoliveurs_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>10</td>
                <td>ESSUIE-GLACES</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="essuis_1" name=<?php echo $name="essuis_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="essuis_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>11</td>
                <td>HOUSSE SIEGE</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="housse_1" name=<?php echo $name="housse_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="housse_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>12</td>
                <td>PARE-SOLEIL</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_soleil_1" name=<?php echo $name="pare_soleil_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_soleil_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>13</td>
                <td>PARE-BOUE DROIT</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_bout_droit_1" name=<?php echo $name="pare_bout_droit_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_bout_droit_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>14</td>
                <td>PARE-BOUE GAUCHE</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_bout_gauche_1" name=<?php echo $name="pare_bout_gauche_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_bout_gauche_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>15</td>
                <td>ROUE DE SECOURS</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="roue_secours_1" name=<?php echo $name="roue_secours_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="roue_secours_1"></label>
                                </span></td>
            </tr>
            <tr>
                <td>16</td>
                <td>TAPIS</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="tapis_1" name=<?php echo $name="tapis_1";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="tapis_1"></label>
                                </span></td>
            </tr>
            </tbody>
        </table>
        <div class="form-group">
            <label for="observations_1">Observations</label>
            <textarea class="form-control" id="observations_1"  name=<?php echo $name="observations_1";?> value="<?php echo Input::get($name);
            $name = ""; ?>" rows="3"></textarea>
        </div>
        <p class="h5">état de véhicule après mission</p>
        <p class="h4">NB : Merci de cocher la zône endommagée et mettre un commentaire dans la case "Observations"</p>
        <table class="table col-12 table-bordered">
            <thead class="thead-light">
            <tr>
                <th scope="col"></th>
                <th scope="col">Parties</th>
                <th scope="col">Validation</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>ANTENNE</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antenne_1_2" name=<?php $name ="antenne_1_2" ; ?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antenne_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>2</td>
                <td>ANTIBROUILLARD G</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antibrouillardG_1_2" name=<?php echo $name="antibrouillardG_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antibrouillardG_1_2"></label>
                                </span></td>

            </tr>
            <tr>
                <td>3</td>
                <td>ANTIBROUILLARD D</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antibrouillardD_1_2"
                                           name=<?php echo $name="antibrouillardD_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antibrouillardD_1_2"></label>
                                </span>
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>AUTOCOLLANT-VIGNETTE+PAPIER</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="autocollant_1_2" name=<?php echo $name="autocollant_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="autocollant_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>5</td>
                <td>CENDRIER+ALLUME-CIGARETTES</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="cendrier_1_2" name=<?php echo $name="cendrier_1_2";?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="cendrier_1_2"></label>
                                </span>
                </td>
            </tr>
            <tr>
                <td>6</td>
                <td>CLIGNOTANT DROIT</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="clignotantD_1_2" name=<?php echo $name="clignotantD_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="clignotantD_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>7</td>
                <td>CLIGNOTANT GAUCHE</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="clignotantG_1_2" name=<?php echo $name="clignotantG_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="clignotantG_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>8</td>
                <td>CRIC+ACCESSOIRES</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="cric_1_2" name=<?php echo $name="cric_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="cric_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>9</td>
                <td>ENJOLIVEURS</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="enjoliveurs_1_2" name=<?php echo $name="enjoliveurs_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="enjoliveurs_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>10</td>
                <td>ESSUIE-GLACES</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="essuis_1_2" name=<?php echo $name="essuis_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="essuis_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>11</td>
                <td>HOUSSE SIEGE</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="housse_1_2" name=<?php echo $name="housse_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="housse_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>12</td>
                <td>PARE-SOLEIL</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_soleil_1_2" name=<?php echo $name="pare_soleil_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_soleil_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>13</td>
                <td>PARE-BOUE DROIT</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_bout_droit_1_2" name=<?php echo $name="pare_bout_droit_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_bout_droit_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>14</td>
                <td>PARE-BOUE GAUCHE</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_bout_gauche_1_2" name=<?php echo $name="pare_bout_gauche_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_bout_gauche_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>15</td>
                <td>ROUE DE SECOURS</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="roue_secours_1_2" name=<?php echo $name="roue_secours_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="roue_secours_1_2"></label>
                                </span></td>
            </tr>
            <tr>
                <td>16</td>
                <td>TAPIS</td>
                <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="tapis_1_2" name=<?php echo $name="tapis_1_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="tapis_1_2"></label>
                                </span></td>
            </tr>
            </tbody>
        </table>
        <div class="form-group">
            <label for="observations_1_2">Observations</label>
            <textarea class="form-control" id="observations_1_2"  name=<?php echo $name="observations_1_2";?> value="<?php echo Input::get($name);
            $name = ""; ?>" rows="3"></textarea>
        </div>
        <p class="h5">circonstances de l'accident en cas de dégats</p>

        <div class="row">
            <div class="form-group col-4">
                <label for="date_sisitre1">Date et heure du sinistre <br> le </label>
                <input type="date" class="form-control" id="date_sisitre1"
                       name=<?php echo $name = "date_sisitre1"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
            <div class="form-group col-4">
                <label for="heure_sisitre1"><br>à</label>
                <input type="time" class="form-control" id="heure_sisitre1"
                       name=<?php echo $name = "heure_sisitre1"; ?>
                       value="<?php echo Input::get($name);
                       $name = ""; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="faits_1">Description des faits : (Que s’est-il passé ?)</label>
            <textarea class="form-control" id="faits_1"  name=<?php echo $name="faits_1";?> value="<?php echo Input::get($name);
            $name = ""; ?>" rows="3"></textarea>
        </div>
        <fieldset <?php if (Input::get('nature_vehicule2')==='...'||!Input::get('nature_vehicule2')){ echo 'hidden' ; } ?>>
            <p class="h5">Informations sur le 2ème Véhicule</p>
            <div class="row">
                <div class="form-group col-4">
                    <label for="nature_vehicule2">nature</label>
                    <input type="text" class="form-control" id="nature_vehicule2"
                           name=<?php echo $name = "nature_vehicule2"; ?>
                           value="<?php echo Input::get($name);
                           $name = ""; ?>">
                </div>
                <div class="form-group col-4">
                    <label for="fournisseur2">fournisseur</label>
                    <input type="text" class="form-control" id="fournisseur2"
                           name=<?php echo $name = "fournisseur2"; ?>
                           value="<?php echo Input::get($name);
                           $name = ""; ?>">
                </div>
                <div class="form-group col-4">
                    <label for="type_vehicule1">marque</label>
                    <input type="text" class="form-control" id="type_vehicule2"
                           name=<?php echo $name = "type_vehicule2"; ?>
                           value="<?php echo Input::get($name);
                           $name = ""; ?>">
                </div>
                <div class="form-group col-4">
                    <label for="matricule2">immatriculation</label>
                    <input type="text" class="form-control" id="matricule2"
                           name=<?php echo $name = "matricule2"; ?>
                           value="<?php echo Input::get($name);
                           $name = ""; ?>">
                </div>
            </div>

            <p class="h5">état de véhicule avant mission</p>
            <p class="h4">NB : Merci de cocher la zône endommagée et mettre un commentaire dans la case "Observations"</p>
            <table class="table col-12 table-bordered">
                <thead class="thead-light">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Parties</th>
                    <th scope="col">Validation</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>ANTENNE</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antenne_2" name=<?php echo $name ="antenne_2" ; ?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antenne_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>ANTIBROUILLARD G</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antibrouillardG_2" name=<?php echo $name="antibrouillardG_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antibrouillardG_2"></label>
                                </span></td>

                </tr>
                <tr>
                    <td>3</td>
                    <td>ANTIBROUILLARD D</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antibrouillardD_2"
                                           name=<?php echo $name="antibrouillardD_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antibrouillardD_2"></label>
                                </span>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>AUTOCOLLANT-VIGNETTE+PAPIER</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="autocollant_2" name=<?php echo $name="autocollant_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="autocollant_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>CENDRIER+ALLUME-CIGARETTES</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="cendrier_2" name=<?php echo $name="cendrier_2";?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="cendrier_2"></label>
                                </span>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>CLIGNOTANT DROIT</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="clignotantD_2" name=<?php echo $name="clignotantD_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="clignotantD_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>CLIGNOTANT GAUCHE</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="clignotantG_2" name=<?php echo $name="clignotantG_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="clignotantG_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>CRIC+ACCESSOIRES</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="cric_2" name=<?php echo $name="cric_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="cric_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>ENJOLIVEURS</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="enjoliveurs_2" name=<?php echo $name="enjoliveurs_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="enjoliveurs_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>ESSUIE-GLACES</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="essuis_2" name=<?php echo $name="essuis_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="essuis_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>11</td>
                    <td>HOUSSE SIEGE</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="housse_2" name=<?php echo $name="housse_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="housse_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>12</td>
                    <td>PARE-SOLEIL</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_soleil_2" name=<?php echo $name="pare_soleil_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_soleil_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>13</td>
                    <td>PARE-BOUE DROIT</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_bout_droit_2" name=<?php echo $name="pare_bout_droit_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_bout_droit_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>14</td>
                    <td>PARE-BOUE GAUCHE</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_bout_gauche_2" name=<?php echo $name="pare_bout_gauche_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_bout_gauche_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>15</td>
                    <td>ROUE DE SECOURS</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="roue_secours_2" name=<?php echo $name="roue_secours_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="roue_secours_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>16</td>
                    <td>TAPIS</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="tapis_2" name=<?php echo $name="tapis_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="tapis_2"></label>
                                </span></td>
                </tr>
                </tbody>
            </table>
            <div class="form-group">
                <label for="observations_2">Observations</label>
                <textarea class="form-control" id="observations_2"  name=<?php echo $name="observations_2";?> value="<?php echo Input::get($name);
                $name = ""; ?>" rows="3"></textarea>
            </div>
            <p class="h5">état de véhicule après mission</p>
            <p class="h4">NB : Merci de cocher la zône endommagée et mettre un commentaire dans la case "Observations"</p>
            <table class="table col-12 table-bordered">
                <thead class="thead-light">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Parties</th>
                    <th scope="col">Validation</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>ANTENNE</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antenne_2_2" name=<?php echo $name ="antenne_2_2" ; ?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antenne_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>ANTIBROUILLARD G</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antibrouillardG_2_2" name=<?php echo $name="antibrouillardG_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antibrouillardG_2_2"></label>
                                </span></td>

                </tr>
                <tr>
                    <td>3</td>
                    <td>ANTIBROUILLARD D</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="antibrouillardD_2_2"
                                           name=<?php echo $name="antibrouillardD_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="antibrouillardD_2_2"></label>
                                </span>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>AUTOCOLLANT-VIGNETTE+PAPIER</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="autocollant_2_2" name=<?php echo $name="autocollant_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="autocollant_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>CENDRIER+ALLUME-CIGARETTES</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="cendrier_2_2" name=<?php echo $name="cendrier_2_2";?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="cendrier_2_2"></label>
                                </span>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>CLIGNOTANT DROIT</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="clignotantD_2_2" name=<?php echo $name="clignotantD_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="clignotantD_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>CLIGNOTANT GAUCHE</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="clignotantG_2_2" name=<?php echo $name="clignotantG_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="clignotantG_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>CRIC+ACCESSOIRES</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="cric_2_2" name=<?php echo $name="cric_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="cric_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>ENJOLIVEURS</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="enjoliveurs_2_2" name=<?php echo $name="enjoliveurs_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="enjoliveurs_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>ESSUIE-GLACES</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="essuis_2_2" name=<?php echo $name="essuis_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="essuis_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>11</td>
                    <td>HOUSSE SIEGE</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="housse_2_2" name=<?php echo $name="housse_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="housse_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>12</td>
                    <td>PARE-SOLEIL</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_soleil_2_2" name=<?php echo $name="pare_soleil_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_soleil_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>13</td>
                    <td>PARE-BOUE DROIT</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_bout_droit_2_2" name=<?php echo $name="pare_bout_droit_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_bout_droit_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>14</td>
                    <td>PARE-BOUE GAUCHE</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="pare_bout_gauche_2_2" name=<?php echo $name="pare_bout_gauche_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="pare_bout_gauche_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>15</td>
                    <td>ROUE DE SECOURS</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="roue_secours_2_2" name=<?php echo $name="roue_secours_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="roue_secours_2_2"></label>
                                </span></td>
                </tr>
                <tr>
                    <td>16</td>
                    <td>TAPIS</td>
                    <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="tapis_2_2" name=<?php echo $name="tapis_2_2";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="tapis_2_2"></label>
                                </span></td>
                </tr>
                </tbody>
            </table>
            <div class="form-group">
                <label for="observations_2_2">Observations</label>
                <textarea class="form-control" id="observations_2_2"  name=<?php echo $name="observations_2_2";?> value="<?php echo Input::get($name);
                $name = ""; ?>" rows="3"></textarea>
            </div>
            <p class="h5">circonstances de l'accident en cas de dégats</p>

            <div class="row">
                <div class="form-group col-4">
                    <label for="date_sisitre2">Date et heure du sinistre <br> le </label>
                    <input type="date" class="form-control" id="date_sisitre2"
                           name=<?php echo $name = "date_sisitre2"; ?>
                           value="<?php echo Input::get($name);
                           $name = ""; ?>">
                </div>
                <div class="form-group col-4">
                    <label for="heure_sisitre2"><br>à</label>
                    <input type="time" class="form-control" id="heure_sisitre2"
                           name=<?php echo $name = "heure_sisitre2"; ?>
                           value="<?php echo Input::get($name);
                           $name = ""; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="faits_2">Description des faits : (Que s’est-il passé ?)</label>
                <textarea class="form-control" id="faits_2"  name=<?php echo $name="faits_2";?> value="<?php echo Input::get($name);
                $name = ""; ?>" rows="3"> </textarea>
            </div>
        </fieldset>
        <table class="table col-12 table-bordered" >
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Responsable Logistique</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>Validation</th>
                <td>
                <span class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input "
                       id="validation_responsable" name=<?php echo $name="validation_responsable";?> <?php isOn($name) ;?><?php  if (isset($fm)){checkValidation('logistique',$fm->data()->id);}
                ?>>
                <label class="custom-control-label" for="validation_responsable"></label>
                </span>
                </td>
            </tr>
            </tbody>
        </table>
    </fieldset>
    <input type="hidden" name="disabled" value="<?php  disable(Input::get('id')); ?>">
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <button type="submit" class="btn btn-primary btn-lg btn-block">Next</button>
</form>
<?php
require_once ('_footer.php');
?>
