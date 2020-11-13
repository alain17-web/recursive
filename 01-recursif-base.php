<?php

function lignes(int $seconde){
    // affichage d'une ligne
    echo "<br>Attention : départ dans $seconde secondes";
    // décrémentation du niveau
    $seconde--;
    // pour éviter la boucle infinie
    if($seconde<=0){
        // et lancer le départ
        echo "<br>Départ!";
        return true;
    }
    // récursivité
    lignes($seconde);

}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>01 Fonctionne comme une boucle</title>
</head>
<body>
<h1>Fonction récursive</h1>
<p>Elle fonctionne comme une boucle</p>
<p>
    <?php
    lignes(10);
    ?>
</p>
</body>
</html>
