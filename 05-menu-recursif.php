<?php
// dépendances
require_once "connect.php";

// fonction récursive pour obtenir des ul > li imbriqués pour un menu multi-niveau
function createMenuMulti(int $parent, int $level, array $rub){
    // initialisation à chaque récursivité
    $out=""; // chaîne vide
    $prevLevel=0; // niveau précédent, 0 au démarage

    // premier passage (ouverture du premier menu)
    if(!$level&&!$prevLevel) $out .="\n<ul id='startmenu'>\n";

    // tant qu'on a des éléments dans le tableau
    foreach ($rub as $item){
        // si on est l'enfant d'une autre rubrique (ou 0 sur l'accueil)
        if($parent == $item['rubriques_idrubriques']){
            // si on est le premier enfant
            if($prevLevel<$level) $out .= "\n<ul class='menu'>\n";
            // pour tous les niveaux
            $out .="\n    <li><a href='?id={$item['idrubriques']}'>{$item['rubriques_name']}</a>";
            // si on n'est pas sur un parent on ferme le li
            if($level !=$parent) $out.="\n    </li>";
            // on garde dans $prevLevel le $level de cette itération de boucle
            $prevLevel = $level;
            // on va chercher les sous-menu de la rubrique actuelle (si existante)
            $out .= createMenuMulti($item['idrubriques'],($level+1),$rub);
        }
    }
    // fermeture d'un sous menu
    if(($prevLevel==$level)&&$prevLevel!=0) $out .="\n</ul></li>";
    // fermeture du menu principal
    elseif ($prevLevel==$level) $out .="\n</li></ul>";

    return $out;
}

// connexion
$db = connect();

// sélections de toutes les rubriques ordonnées par rubriques_order
$sql = "SELECT * FROM rubriques ORDER BY rubriques_order ASC";

// récupération des rubriques
$request = mysqli_query($db,$sql)or die(mysqli_error($db));

// si on récupère au moins une rubrique on la/les met dans un tableau indexé contenant des tableaux associatifs, sinon c'est un tableau vide
$rubriques = (mysqli_num_rows($request))? mysqli_fetch_all($request,MYSQLI_ASSOC):[];

$menu = createMenuMulti(0,0,$rubriques);

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
        nav{
            background:#343a40;
            color: #2e3740;
        }
        nav ul {
            position:relative;
            width:100%;
            list-style:none outside none;
        }
        nav ul li {
            float:left; /* menu, sous-menus horizontaux */
        }
        nav ul:before, .navbar ul:after {
            display:table; content:''; clear:both;
            /* permet de remettre les li float:left dans le flux */
        }
        nav ul li ul {
            display:none; /* sous-menu masqués */
            position:absolute;
            top:30px;
            left:0;
        }
        nav  li:hover > ul {
            display:block; /* sous-menu affiché */
        }

        /* ----------------------- */
        /* DECO */
        nav  ul { /* niveau 1 */
            background:#343a40;
        }
        nav  ul ul { /* niveau 2 */
            background:#343a40;
        }
        nav  ul ul ul { /* niveau 3 */
            background:#343a40;
        }
        nav  ul li a {
            display:block;
            padding:10px 15px;
            color:#000;
            text-decoration:none;
        }
        nav  ul li:hover > a {
            background:#666;
            color:#000;
        }
        nav  ul li a {
            border-right:1px solid #000;
        }
    </style>
</head>
<body>
<nav><a class="navbar-brand" href="#">Navbar</a><?php
    echo $menu;
    ?></nav>

</body>
</html>
