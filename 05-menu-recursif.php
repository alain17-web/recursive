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
    if (!$level && !$prevLevel) $out .= "<ul id='startmenu'>";

    // tant qu'on a des rubriques
    foreach ($rub as $item) {
        // si enfant de l'id de la rubrique actuelle (0 pour les menus de l'accueil)
        if ($parent == $item['rubriques_idrubriques']) {
            // si on a un sous-menu
            if ($prevLevel < $level) {
                // 2 retour à la ligne
                $out .="\n\n";
                // on augmente l'indentation (tab) suivant le level
                for($i=0;$i<$level;$i++) $out .="       ";
                // début du sous-menu
                $out .= "<ul class='menu'>\n";
            }
            // affichage du lien avec indentation
            $out .="\n";
            for($i=0;$i<$level;$i++) $out .="       ";
            $out .= "<li><a href='?id={$item['idrubriques']}'>{$item['rubriques_name']}</a>";

            // mise à jour du level précédent (pas d'ul class='menu')
            $prevLevel = $level;

            // chargement récursif tant qu'on a des sous éléments
            $out .= createMenuMulti($item['idrubriques'], ($level + 1), $rub);

            // fermeture du li (fin de l'arborescence: plus d'enfants) avec indentation
            // Merci @McDibou pour la correction de cette erreur !
            $out .="\n";
            for($i=0;$i<$level;$i++) $out .="       ";
            $out .= "</li>";
        }
    }

    // fermeture du ul si on a pas de rubriques enfants du level actuel avec indentation
    if ($prevLevel == $level){
        $out .="\n";
        for($i=0;$i<$level;$i++) $out .="      ";
        $out .= "</ul>";
    }

    // sortie des données dans un $out .= $out, ou si c'est le dernier appel sortie finale
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
    <title>Menu récursif</title>

    <style>
        /*
        Merci @McDibou pour cette version du CSS
         */
        body, nav a {
            background: #FFF;
            color: #2e3740;
        }

        nav ul {
            list-style: none outside none;
            width:100%;
        }

        nav ul li {
            float: left;

        }
        nav ul:before, .navbar ul:after {
            display:table; content:''; clear:both;
            /* permet de remettre les li float:left dans le flux */
        }

        nav ul li ul {
            position: absolute;
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
            border: 1px solid #000;
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
