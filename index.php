<?php
require_once "req/modele.php";
require 'req/header.php';

?>
<div class="container">
<?php if (!is_connected()): ?>
<a class="btn btn-primary" href="connexion.php">Connexion</a>
<a class="btn btn-primary" href="inscription.php">Inscription</a>
<?php endif ?>
</div>

<?php require "req/footer.php";