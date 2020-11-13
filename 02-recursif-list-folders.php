<?php
// fonction récursive qui liste le nom des dossiers
function listFolders(string $dir="./"){
    // création de la variable de sortie avec ouverture de UL
    $out = "<ul>";

    // utilisation de opendir pour ouvrir le dossier
    $folder = opendir($dir);

    // Tant que j'ai des éléments dans ce dossier, on les affiches sans distinctions, le . représente la racine du dossier et le .. représente "remonter d'un niveau dans l'arborescence"
    while ($files = readdir($folder)){
            // si on est pas sur le . ou le ..
            if($files!="."&&$files!=".."){
                // chemin vers les fichiers
                $path = $dir."/".$files;
                // affichage du fichier / dossier
                $out .= "<li>$files</li>";
                // si c'est un dossier, on va devoir ré-effectuer le while pour lister ce dossier, donc utilisation de la récursivité de cette variable
                if(is_dir($path)) $out .= listFolders($path);
            }

    }

    // fermeture des dossiers
    closedir($folder);

    $out.= "</ul>";

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
    echo "<hr>";
    echo listFolders("folder");
    ?>
</p>
</body>
</html>
