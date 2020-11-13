<?php
// fonction récursive qui liste le nom des dossiers
function listFolders(string $dir="./"){
    // création de la variable de sortie
    $out = "<h3>Mes Dossiers</h3>";

    // utilisation de opendir pour ouvrir le dossier
    $folder = opendir($dir);

    // Tant que j'ai des éléments dans ce dossier, on les affiches sans distinctions, le . représente la racine du dossier et le .. représente "remonter d'un niveau dans l'arborescence"
    while ($files = readdir($folder)){
        // si l'élément n'est pas un répertoire
        if(!is_dir($files)){
            $out .= "$files<br>";
        // l'élément est un répertoire
        }else {
            // si le dossier est le .(l'actuel) ou le .. (niveau parent) continue permet de passer à l'itération suivante de la boucle while sans exécuter le code qui suit ce continue
            if($files=="."||$files=="..") continue;
            // s'exécute si le continue n'est pas appelé
            $out .= "-- $files<br>";
        }
    }

    // sortie
    return $out;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>listFolders</title>
</head>
<body>
<h1>listFolders</h1>
<p>
    <?php
    echo listFolders();
    ?>
</p>
</body>
</html>
