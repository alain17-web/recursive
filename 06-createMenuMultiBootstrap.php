<?php
// fonction récursive pour obtenir des ul > li imbriqués pour un menu multi-niveau
function createMenuMultiBootstrap(int $parent, int $level, array $rub,string $nom_parent="")
{
    // initialisation à chaque récursivité
    $out = ""; // chaîne vide
    $prevLevel = 0;// niveau précédent, 0 au démarage

    // premier passage (ouverture du premier menu)
    if (!$level && !$prevLevel) $out .= "\n<ul class='navbar-nav mr-auto'>\n\n";

    // tant qu'on a des rubriques
    foreach ($rub as $item) {
        $sous0 = false;
        $nom_parent = $item['rubriques_name'];
        // si enfant de l'id de la rubrique actuelle (0 pour les menus de l'accueil)
        if ($parent == $item['rubriques_idrubriques']) {

            // si le level vaut 0
            if (!$level) {

                // on cherche les enfants
                foreach ($rub as $item2) {

                    // si on trouve un enfant on a un niveau 1 de menu
                    if ($item['idrubriques'] == $item2['rubriques_idrubriques']) {
                        $sous0 = true;
                    }

                }
                // si on trouve un enfant on a un niveau 1 de menu
                if ($sous0) {
                    // si on a des sous-menu de niveau 1
                    $out .= "<li class='nav-item dropdown'>
                    <a href='#' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'
                       class='nav-link dropdown-toggle'>{$item['rubriques_name']}</a><ul class='dropdown-menu border-0 shadow'>\n";

                }

            } // si on a un sous-menu
            elseif ($prevLevel < $level) {

                // début du sous-menu
                $out .= "\n<li class='dropdown-submenu'><a href='#' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' class='dropdown-item dropdown-toggle'>détail</a>
<ul class='dropdown-menu border-0 shadow'>";
            }
            // affichage du lien
            $out .= "\n<li class='nav-item'><a  class='nav-link' href='?id={$item['idrubriques']}'>{$item['rubriques_name']}</a>";

            // mise à jour du level précédent (pas d'ul class='menu')
            $prevLevel = $level;

            // chargement récursif tant qu'on a des sous éléments
            $out .= createMenuMultiBootstrap($item['idrubriques'], ($level + 1), $rub,$nom_parent);

            if ($sous0) {
                $out .= "</ul>";
            }

            // fermeture du li (fin de l'arborescence: plus d'enfants) avec indentation
            $out .= "</li>";

        }
    }

    // fermeture du ul si on a pas de rubriques enfants du level actuel avec indentation
    if ($prevLevel == $level) {
        $out .= "</ul>";
    }

    // sortie des données dans un $out .= $out, ou si c'est le dernier appel sortie finale
    return $out;
}
