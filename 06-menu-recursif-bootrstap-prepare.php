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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">

    <title>Starter Template · Bootstrap</title>


    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="img/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="icon" href="img/favicon.ico">
    <meta name="theme-color" content="#563d7c">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">

        <?=$menu?>

        <!--
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
        -->
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>

<main role="main" class="container">

    <div class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
    </div>

</main><!-- /.container -->
<script src="js/jquery-3.5.1.slim.min.js" ></script>
<script src="js/bootstrap.bundle.min.js" ></script></body>
</html>