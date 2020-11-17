<?php
// fonction récursive pour obtenir des ul > li imbriqués pour un menu multi-niveau
function createMenuMultiBootstrap(int $parent, int $level, array $rub)
{
    // initialisation à chaque récursivité
    $out = ""; // chaîne vide
    $prevLevel = 0;// niveau précédent, 0 au démarage

    // premier passage (ouverture du premier menu)
    if (!$level && !$prevLevel) $out .= "<ul class='navbar-nav mr-auto'>";

    // tant qu'on a des rubriques
    foreach ($rub as $item) {
        // si enfant de l'id de la rubrique actuelle (0 pour les menus de l'accueil)
        if ($parent == $item['rubriques_idrubriques']) {
            // si le level vaut 0 et qu'on a un sous-menu
            if(!$level&& $prevLevel < $level) {
                $out .= "<li class='nav-item dropdown'>
                    <a href='?h=5' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'
                       class='nav-link dropdown-toggle'>Dropdown</a><ul class='dropdown-menu border-0 shadow'>";
            }
            // si on a un sous-menu
            elseif ($prevLevel < $level) {

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
            $out .= "<li>$level $prevLevel <a href='?id={$item['idrubriques']}'>{$item['rubriques_name']}</a>";

            // mise à jour du level précédent (pas d'ul class='menu')
            $prevLevel = $level;

            // chargement récursif tant qu'on a des sous éléments
            $out .= createMenuMultiBootstrap($item['idrubriques'], ($level + 1), $rub);

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
