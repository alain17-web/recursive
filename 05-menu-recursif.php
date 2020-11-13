<?php
// dépendances
require_once "connect.php";

// fonction récursive pour obtenir des ul > li imbriqués pour un menu multi-niveau
function createMenuMulti(int $parent, int $level, array $rub)
{
    // initialisation à chaque récursivité
    $out = ""; // chaîne vide
    $prevLevel = 0;// niveau précédent, 0 au démarage

    // premier passage (ouverture du premier menu)
    if (!$level && !$prevLevel) $out .= "\n<ul id='startmenu'>\n";


    foreach ($rub as $item) {

        if ($parent == $item['rubriques_idrubriques']) {

            if ($prevLevel < $level) $out .= "\n  <ul class='menu'>\n";

            $out .= "\n<li><a href='?id={$item['idrubriques']}'>{$item['rubriques_name']}</a>";

            $prevLevel = $level;

            $out .= createMenuMulti($item['idrubriques'], ($level + 1), $rub);

            $out .= "</li>";
        }
    }

    if (($prevLevel != $level) && $prevLevel != 0) $out .= "\n</ul></li>";

    elseif ($prevLevel == $level) $out .= "\n</ul>";

    return $out;
}

// connexion
$db = connect();

// sélections de toutes les rubriques ordonnées par rubriques_order
$sql = "SELECT * FROM rubriques ORDER BY rubriques_order ASC";

// récupération des rubriques
$request = mysqli_query($db, $sql) or die(mysqli_error($db));

// si on récupère au moins une rubrique on la/les met dans un tableau indexé contenant des tableaux associatifs, sinon c'est un tableau vide
$rubriques = (mysqli_num_rows($request)) ? mysqli_fetch_all($request, MYSQLI_ASSOC) : [];

$menu = createMenuMulti(0, 0, $rubriques);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>

        body, nav a {
            background: #343a40;
            color: #2e3740;
        }

        nav ul {
            list-style: none outside none;
        }

        nav ul li {
            float: left;

        }

        nav ul li ul {
            position: relative;
            display: none; /* sous-menu masqués */
        }

        nav li:hover > ul {
            display: inline-block; /* sous-menu affiché */

        }

        nav a {
            display: block;
            position: relative;
            padding: 10px 15px;
            color: #000;
            text-decoration: none;
            border-right: 1px solid #000;
        }

        nav :hover > a {
            background: #c3c3c3;
            color: #000;
        }

    </style>

</head>
<body>
<nav>
    <?= $menu; ?>
</nav>

</body>
</html>
