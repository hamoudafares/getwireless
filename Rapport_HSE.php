<?php
require_once('_header.php');
if (Input::exists()){
    if (Session::exists(Config::get("session/token_name")) && (Session::get(Config::get("session/token_name")) === Input::get('token'))) {
        try {
                if (Input::get('disabled')==='disabled') {
                        Redirect::to("material_file.php",array('visit=true&id'=>Input::get('id')));
                }
                $validate = new Validate();
            if (!((Input::get('check_extincteur_ok')==='on')||(Input::get('check_extincteur_n/a')==='on'))) {
            $_POST['check_extincteur_ok']='off';
            $_POST['check_extincteur_n/a']='off';
            }
                $check = array(
                    'check_extincteur_ok' => array("checked" => true),
                    'check_Roue_secours_ok' => array("checked" => true),
                    'check_fonctionnalite_ceinture_ok' => array("checked" => true),
                    'check_etat_pneus_ok' => array("checked" => true),
                    'check_etat_huiles_ok' => array("checked" => true),
                    'check_couleur_fume_ok' => array("checked" => true),
                    'check_triangle_secours_ok' => array("checked" => true),
                    'check_boite_pharmacie_ok' => array("checked" => true),
                    'check_vitesse_deplacement_ok' => array("checked" => true),
                    'check_gilets_securite_ok' => array("checked" => true)
                );
                $validation = $validate->check($_POST, $check);
                if ($validation->passed()) {
                $fields = array(
                    'rapport_hse' => json_encode($_POST)
                );
                $fm = new FM(Input::get('id'));
                $fm->update($fields,Input::get('id'));
                if ($fm->is_visiting()) {
                        Redirect::to("material_file.php",array('visit=true&id'=>Input::get('id')));
                }
                Redirect::to("material_file.php",array('id'=>Input::get('id')));}
                else{
                    $str="";
                    foreach ($validation->errors() as $error){
                        $str.=$error."<br>" ;
                    }
                    echo Session::flash('failed',$str);                }
            } catch (Exception $e) {
                die($e->getMessage());
            }
    }
}else if (Input::exists('get')) {
    $fm = new FM(Input::get('projet_id'));
    if (Input::get('id')){$fm=new FM((Input::get('id')));
        secure(Input::get('id'));
    }
    if ($fm->is_visiting()==='on'){
        $fm->visited(Input::get('id')) ;
        if (Input::get('visit')==='true') {
            $fm->visit(Input::get('id'));
        }
    }

    if ($fm->data()->rapport_hse){
        $datas = json_decode($fm->data()->rapport_hse);
        foreach ($datas as $name => $data) {
            $_POST[$name] = $data;
        }
    }
}
?>
    <h1 class="display-4 align-middle" align="center">Rapport HSE</h1>
<?php if (Session::exists('failed')){
    echo "<div class='alert alert-danger' role='alert'>";
    echo Session::flash('failed');
    echo "</div>";
}?>
    <form action="" method="post" role="form">
        <fieldset <?php disable(Input::get('id')) ;?>>
        <div class="form-row col-4 ">
            <label for="client">Client</label>
            <input type="text" class="form-control" id="client"
                   name=<?php echo $name="client"; ?> value="<?php echo Input::get($name);
                   $name = ""; ?>">
        </div>
        <div class="form-row  col-4">
            <label for="support">Support technique et correspondant HSE</label>
            <input type="text" class="form-control" id="support"
                   name=<?php echo $name="support"; ?> value="<?php echo Input::get($name);
                   $name = ""; ?>">
        </div>
        <div class="form-row col-4">
            <label for="autres">Autres</label>
            <input type="text" class="form-control" id="autres"
                   name=<?php echo $name="autres"; ?> value="<?php echo Input::get($name);
                   $name = ""; ?>">
        </div>
        <div class="form-row">
            <table class="table col-12 table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Véhicule</th>
                </tr>
                </thead>
                <tbody>
                <table class="table col-12 table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Points à Contrôler</th>
                        <th scope="col">fréquence de vérification</th>
                        <th scope="col">OK</th>
                        <th scope="col">NOT OK</th>
                        <th scope="col">Constat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Extincteur</td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="check_extincteur_ok" name=<?php echo $name ="check_extincteur_ok" ; ?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_extincteur_ok"></label>
                                </span></td>
                        <td>
                                                                <span class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="check_extincteur_n/a" name=<?php echo $name="check_extincteur_n/a" ; ?>
                                    <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_extincteur_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_extincteur" name=<?php echo $name="constat_extincteur";?> value=<?php echo escape(Input::get($name));
                            $name = ""; ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Roue de secours</td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="check_Roue_secours_ok" name=<?php echo $name="check_Roue_secours_ok";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_Roue_secours_ok"></label>
                                </span></td>
                        <td>
                                                                <span class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="check_Roue_secours_n/a" name=<?php echo $name="check_Roue_secours_n/a";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_Roue_secours_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_Roue_secours"
                                   name=<?php echo $name="constat_Roue_secours";?>
                                   value=<?php echo Input::get($name);
                            $name = ""; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Fonctionnalité de ceinture</td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="check_fonctionnalite_ceinture_ok"
                                           name=<?php echo $name="check_fonctionnalite_ceinture_ok";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_fonctionnalite_ceinture_ok"></label>
                                </span>
                        </td>
                        <td>
                            <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="check_fonctionnalite_ceinture_n/a"
                                           name=<?php echo $name="check_fonctionnalite_ceinture_n/a";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_fonctionnalite_ceinture_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_fonctionnalite_ceinture"
                                   name=<?php echo $name="constat_fonctionnalite_ceinture" ;?> value=<?php echo Input::get($name);
                            $name = ""; ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Etat des pneus</td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input "
                                           id="check_etat_pneus_ok" name=<?php echo $name="check_etat_pneus_ok";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_etat_pneus_ok"></label>
                                </span></td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input "
                                       id="check_etat_pneus_n/a" name=<?php echo $name="check_etat_pneus_n/a";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_etat_pneus_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_etat_pneus"
                                   name=<?php echo $name="constat_etat_pneus";?>
                                   value=<?php echo Input::get($name);
                            $name = ""; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Etat des huiles et du liquide de refroidissement</td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input "
                                           id="check_etat_huiles_ok" name=<?php echo $name="check_etat_huiles_ok";?>
                                        <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_etat_huiles_ok"></label>
                                </span>
                        </td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input "
                                       id="check_etat_huiles_n/a" name=<?php echo $name="check_etat_huiles_n/a";?>
                                    <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_etat_huiles_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_etat_huiles"
                                   name=<?php echo $name="constat_etat_huiles";?>
                                   value=<?php echo Input::get($name);
                            $name = ""; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Couleur de la fumé dégagée</td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input "
                                           id="check_couleur_fume_ok" name=<?php echo $name="check_couleur_fume_ok";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_couleur_fume_ok"></label>
                                </span></td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input "
                                       id="check_couleur_fume_n/a" name=<?php echo $name="check_couleur_fume_n/a";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_couleur_fume_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_couleur_fume"
                                   name=<?php echo $name="constat_couleur_fume";?>
                                   value=<?php echo Input::get($name);
                            $name = ""; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Triangle de secours</td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input "
                                           id="check_triangle_secours_ok" name=<?php echo $name="check_triangle_secours_ok";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_triangle_secours_ok"></label>
                                </span></td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input "
                                       id="check_triangle_secours_n/a" name=<?php echo $name="check_triangle_secours_n/a";?> <?php isOn($name) ;?> >
                                    <label class="custom-control-label" for="check_triangle_secours_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_triangle_secours"
                                   name=<?php echo $name="constat_triangle_secours";?>
                                   value=<?php echo Input::get($name);
                            $name = ""; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Boite à pharmacie</td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input "
                                           id="check_boite_pharmacie_ok" name=<?php echo $name="check_boite_pharmacie_ok";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_boite_pharmacie_ok"></label>
                                </span></td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input "
                                       id="check_boite_pharmacie_n/a" name=<?php echo $name="check_boite_pharmacie_n/a";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_boite_pharmacie_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_boite_pharmacie"
                                   name=<?php echo $name="constat_boite_pharmacie";?> value=<?php echo Input::get($name);
                            $name = ""; ?>>
                        </td>
                    </tr>
                     <tr>
                        <td>9</td>
                        <td>Vitesse de déplacement vers le site ≤ 100 Km/h</td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input "
                                           id="check_vitesse_deplacement_ok" name=<?php echo $name="check_vitesse_deplacement_ok";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_vitesse_deplacement_ok"></label>
                                </span></td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input "
                                       id="check_vitesse_deplacement_n/a" name=<?php echo $name="check_vitesse_deplacement_n/a";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_vitesse_deplacement_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_vitesse_deplacement"
                                   name=<?php echo $name="constat_vitesse_deplacement";?> value=<?php echo Input::get($name);
                            $name = ""; ?>>
                        </td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Gilets de sécurité </td>
                        <td>Hebdomadaire</td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input "
                                           id="check_gilets_securite_ok" name=<?php echo $name="check_gilets_securite_ok";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_gilets_securite_ok"></label>
                                </span></td>
                        <td>
                                <span class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input "
                                       id="check_gilets_securite_n/a" name=<?php echo $name="check_gilets_securite_n/a";?> <?php isOn($name) ;?>>
                                    <label class="custom-control-label" for="check_gilets_securite_n/a"></label>
                                </span>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="constat_gilets_securite"
                                   name=<?php echo $name="constat_gilets_securite";?> value=<?php echo Input::get($name);
                            $name = ""; ?>>
                        </td>
                        </tr>
                    </tbody>
                </table>
                </tbody>
            </table>
        </div>
        </fieldset>
        <input type="hidden" name="disabled" value="<?php  disable(Input::get('id')); ?>">
        <input type="hidden" name="id" value="<?php if (Input::get('id')){echo Input::get('id');}else{if (isset($fm)){echo $fm->data()->id;} }?>">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <input type="hidden" id="add_lines" name="add_lines" value=0>
        <input type="hidden" id="lines" name="lines" value="<?php echo Input::get('add_lines'); ?>">
        <button type="submit" class="btn btn-primary btn-lg btn-block">Next</button>
    </form>
<?php
require_once('_footer.php');
?>