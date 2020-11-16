<?php
/*
 * Test with
 * https://jsfiddle.net/bootstrapious/j6zkyog8/
 */

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
                $out .= "\n\n";
                // on augmente l'indentation (tab) suivant le level
                for ($i = 0; $i < $level; $i++) $out .= "       ";
                // début du sous-menu
                $out .= "<ul class='menu'>\n";
            }
            // affichage du lien avec indentation
            $out .= "\n";
            for ($i = 0; $i < $level; $i++) $out .= "       ";
            $out .= "<li><a href='?id={$item['idrubriques']}'>{$item['rubriques_name']}</a>";

            // mise à jour du level précédent (pas d'ul class='menu')
            $prevLevel = $level;

            // chargement récursif tant qu'on a des sous éléments
            $out .= createMenuMulti($item['idrubriques'], ($level + 1), $rub);

            // fermeture du li (fin de l'arborescence: plus d'enfants) avec indentation
            // Merci @McDibou pour la correction de cette erreur !
            $out .= "\n";
            for ($i = 0; $i < $level; $i++) $out .= "       ";
            $out .= "</li>";
        }
    }

    // fermeture du ul si on a pas de rubriques enfants du level actuel avec indentation
    if ($prevLevel == $level) {
        $out .= "\n";
        for ($i = 0; $i < $level; $i++) $out .= "      ";
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

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="img/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="icon" href="img/favicon.ico">

    <style>
        /*
       *
       * ==========================================
       * CUSTOM UTIL CLASSES
       * ==========================================
       *
       */

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu > a:after {
            content: "\f0da";
            float: right;
            border: none;
            font-family: 'FontAwesome';
        }

        .dropdown-submenu > .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: 0px;
            margin-left: 0px;
        }

        /*
        *
        * ==========================================
        * FOR DEMO PURPOSES
        * ==========================================
        *
        */

        body {
            background: #4568DC;
            background: -webkit-linear-gradient(to right, #4568DC, #B06AB3);
            background: linear-gradient(to right, #4568DC, #B06AB3);
            min-height: 100vh;
        }

        code {
            color: #B06AB3;
            background: #fff;
            padding: 0.1rem 0.2rem;
            border-radius: 0.2rem;
        }

        @media (min-width: 991px) {
            .dropdown-menu {
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        }
    </style>
    <!-- Custom styles for this template -->

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
    <div class="container">
        <a href="#" class="navbar-brand font-weight-bold">Multilevel Dropdown</a>
        <button type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbars"
                aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div id="navbarContent" class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <!-- Level one dropdown -->
                <li class="nav-item dropdown">
                    <a href="?h=5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                       class="nav-link dropdown-toggle">Dropdown</a>
                    <ul class="dropdown-menu border-0 shadow">
                        <li><a href="#" class="dropdown-item">Some action </a></li>
                        <li><a href="#" class="dropdown-item">Some other action</a></li>

                        <!-- Level two dropdown-->
                        <li class="dropdown-submenu">
                            <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false" class="dropdown-item dropdown-toggle">Hover for action</a>
                            <ul class="dropdown-menu border-0 shadow">
                                <li>
                                    <a tabindex="-1" href="#" class="dropdown-item">level 2</a>
                                </li>

                                <!-- Level three dropdown-->
                                <li class="dropdown-submenu">
                                    <a href="?t=3" role="button" data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">level
                                        2</a>
                                    <ul class="dropdown-menu border-0 shadow">
                                        <li><a href="?t=32" class="dropdown-item">3rd level</a></li>
                                        <li><a href="?t=32" class="dropdown-item">3rd level</a></li>
                                        <li><a href="#" class="dropdown-item">3rd level</a></li>
                                        <li class="dropdown-submenu">
                                            <a href="?t=3" role="button" data-toggle="dropdown"
                                               aria-haspopup="true" aria-expanded="false"
                                               class="dropdown-item dropdown-toggle">level
                                                2</a>
                                            <ul class="dropdown-menu border-0 shadow">
                                                <li><a href="?t=32" class="dropdown-item">3rd level</a></li>
                                                <li><a href="?t=32" class="dropdown-item">3rd level</a></li>
                                                <li><a href="#" class="dropdown-item">3rd level</a></li>
                                            </ul>
                                    </ul>
                                </li>
                                <!-- End Level three -->

                                <li><a href="#" class="dropdown-item">level 2</a></li>
                                <li><a href="#" class="dropdown-item">level 2</a></li>
                            </ul>
                        </li>
                        <!-- End Level two -->
                    </ul>
                </li>
                <!-- End Level one -->

                <li class="nav-item"><a href="#" class="nav-link">About</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Services</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- For demo purpose -->
<section class="py-5 text-white">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-9 mx-auto text-center">
                <h1 class="display-4">Bootstrap 4 Multilevel dropdown</h1>
                <p class="lead mb-0">Step by step building a multilevel dropdown using Bootstrap 4.</p>
                <p class="lead">Snippet by <a href="https://bootstrapious.com/snippets" class="text-white">
                        <u>Bootstrapious</u></a></p>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-lg-10 mx-auto">
                <p class="lead">The first level is built by the default Bootstrap's dropdown menu.</p>
                <p class="lead">The next levels are structurally similar to the first level, but they're wrapped into
                    <code>.dropdown-submneu</code> class instead of <code>.dropdown</code>.</p>
                <p class="lead">In the subsequent levels, We position the <code>.dropdown-menu</code> using css to
                    achieve the desired location.</p>
            </div>
        </div>
    </div>
</section>
<!-- End -->


<main role="main" class="container">

    <div class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a
            mostly barebones HTML document.</p>
    </div>

</main><!-- /.container -->
<script src="js/jquery-3.5.1.slim.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>

<script>
    $(function () {
        // ------------------------------------------------------- //
        // Multi Level dropdowns
        // ------------------------------------------------------ //
        $("ul.dropdown-menu [data-toggle='dropdown']").on("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            $(this).siblings().toggleClass("show");


            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
                $('.dropdown-submenu .show').removeClass("show");
            });

        });
    });
</script>
</body>
</html>