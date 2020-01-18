<?php
require_once "_header.php";
$user = new User();
if (!$user->isLoggedIn()){
    Redirect::to('index.php');
}elseif (!$user->hasPermission('admin')){
        Redirect::to('index.php');
    }
if (Input::exists()) {
    if (Session::exists(Config::get("session/token_name")) && (Session::get(Config::get("session/token_name")) === Input::get('token'))) {
        $db = DB::getInstance();
        $db->get('options',array('id','=','1'));
        $results= $db->first();
        foreach ($results as $name => $result){
            if ($name==="id"){
                continue ;
            }
            if (Input::get($name)!="..."){
               $result=(array)json_decode($result);
                if (($key = array_search(Input::get($name), $result)) !== false) {
                    unset($result[$key]);
                }
               $result=json_encode((array)$result);
               if($db->update('options','1',array($name=>$result))){
                    Session::flash('delete','one or more options have been deleted !');
                }
            }
            $other_name='new_'.$name;
            if (Input::get($other_name)){
                $result=(array)json_decode($result);
                array_push($result,Input::get($other_name));
                $result=json_encode($result);
                if ($db->update('options','1',array($name=>$result)))
                {
                    Session::flash('add','one or more options have been added !');
                }
            }

        }
    }
}

if(Session::exists('delete')) {
    echo "<div class='alert alert-success' role='alert'>";
    echo Session::flash('delete');
    echo "</div>";
}
if(Session::exists('add')) {
    echo "<div class='alert alert-success' role='alert'>";
    echo Session::flash('add');
    echo "</div>";
}
?>

    <table class="table col-6 table-bordered" align="center">
        <thead class="thead-light">
        <tr>
            <th scope="col"></th>
            <th scope="col">choose an option to delete it</th>
            <th scope="col">new Option</th>
        </tr>
        </thead>
        <tbody>
            <form action="" method="post" role="form">
                <tr>
                <th>projet</th>
                <td>
                    <select class="custom-select form-control" id="projet" name="projet">
                    <option selected>...</option>
                    <?php options('projet') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_projet">
                </td>
                </tr>
                <tr>
                <th>opérateur</th>
                <td>
                    <select class="custom-select form-control" id="operateur" name="operateur">
                    <option selected>...</option>
                    <?php options('operateur') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_operateur">
                </td>
                </tr>
                <tr>
                <th>Nature Véhicule</th>
                <td>
                    <select class="custom-select form-control" id="nature_vehicule" name="nature_vehicule">
                    <option selected>...</option>
                    <?php options('nature_vehicule') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_nature_vehicule">
                </td>
                </tr>
                <tr>
                <th>Type Véhicule</th>
                <td>
                    <select class="custom-select form-control" id="type_vehicule" name="type_vehicule">
                    <option selected>...</option>
                    <?php options('type_vehicule') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_type_vehicule">
                </td>
                </tr>
                <tr>
                <th>Paiement Carburant</th>
                <td>
                    <select class="custom-select form-control" id="paiement_carburant" name="paiement_carburant">
                    <option selected>...</option>
                    <?php options('paiement_carburant') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_paiement_carburant">
                </td>
                </tr>
                <tr>
                <th>Paiement Péage</th>
                <td>
                    <select class="custom-select form-control" id="paiement_peage" name="paiement_peage">
                    <option selected>...</option>
                    <?php options('paiement_peage') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_paiement_peage">
                </td>
                </tr>
                <tr>
                <th>Paiement Repas</th>
                <td>
                    <select class="custom-select form-control" id="paiement_repas" name="paiement_repas">
                    <option selected>...</option>
                    <?php options('paiement_repas') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_paiement_repas">
                </td>
                </tr>
                <tr>
                <th>Nature Repas</th>
                <td>
                    <select class="custom-select form-control" id="nature_repas" name="nature_repas">
                    <option selected>...</option>
                    <?php options('nature_repas') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_nature_repas">
                </td>
                </tr>
                <tr>
                <th>Paiement Hotel</th>
                <td>
                    <select class="custom-select form-control" id="paiement_hotel" name="paiement_hotel">
                    <option selected>...</option>
                    <?php options('paiement_hotel') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_paiement_hotel">
                </td>
                </tr>
                <tr>
                <th>Description autres frais</th>
                <td>
                    <select class="custom-select form-control" id="description_autres_frais" name="description_autres_frais">
                    <option selected>...</option>
                    <?php options('description_autres_frais') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_description_autres_frais">
                </td>
                </tr>
                <tr>
                <th>Paiement autres frais</th>
                <td>
                    <select class="custom-select form-control" id="paiement_autres_frais" name="paiement_autres_frais">
                    <option selected>...</option>
                    <?php options('paiement_autres_frais') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_paiement_autres_frais">
                </td>
                </tr>
                <tr>
                <th>Département</th>
                <td>
                    <select class="custom-select form-control" id="departement" name="departement">
                    <option selected>...</option>
                    <?php options('departement') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_departement">
                </td>
                </tr>
                <tr>
                <th>Service</th>
                <td>
                    <select class="custom-select form-control" id="service" name="service">
                    <option selected>...</option>
                    <?php options('service') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_service">
                </td>
                </tr>
                <th>Désignation</th>
                <td>
                    <select class="custom-select form-control" id="designation" name="designation">
                    <option selected>...</option>
                    <?php options('designation') ;?>
                </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="new_designation">
                </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <button type="submit" class="btn btn-success  btn-lg btn-block" >Change
                        </button></td>
                </tr>
            </form>
        </tbody>
    </table>
<?php
require_once "_footer.php";
?>