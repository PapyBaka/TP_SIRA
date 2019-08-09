<?php 
require "../req/modele.php";
require "../req/header.php";
$error = null;

try {
    $donnees = execRequete("SELECT id,pseudo,nom,prenom,email,civilite,statut, DATE_FORMAT(date_enregistrement, '%d/%m/%Y - %Hh%i') AS date_enregistrement FROM membres");
    $membres = $donnees->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}
echo "<pre>";
echo "</pre>";
?>

<div class="container">
<?= $error ?>
<table class="table">
    <thead>
        <tr>
        <?php foreach ($membres[0] as $k => $info): ?>
            <th><?= $k ?></th>
            <?php endforeach ?>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($membres as $membre): ?>
        <tr>
            <?php foreach ($membre as $info): ?>
            <td><?= $info ?></td>
            <?php endforeach ?>
            <td><?php afficher_actions($membre) ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<form method="post" action="">
    <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo">
    </div>
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom">
    </div>
    <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" class="form-control" id="prenom" name="prenom">
    </div>
    <div class="form-group">
        <label for="mail">Mail</label>
        <input type="email" class="form-control" id="mail" name="mail">
    </div>
    <div class="form-group">
        <select class="form-control">
            <label for="pseudo">Civilité</label>
            <option>--- Civilité ---</option>
            <option>Homme </option>
            <option>Femme </option>        
        </select>
    </div>
    <div class="form-group">
        <select class="form-control">
            <option>--- Statuts ---</option>
            <option>Admin </option>
            <option>Membre </option>        
        </select>
    </div>
    <div class="row justify-content-center">
           <button type="submit" class="btn btn-info btn-lg" name="enregistrer">
                      Enregistrer
             </button> 
    </div>    
</form>
</div>

<?php
require "../req/footer.php";