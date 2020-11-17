<?php
// fonction récursive pour obtenir des ul > li imbriqués pour un menu multi-niveau
function createMenuMultiBootstrap(int $parent, int $level, array $rub)
{
    // initialisation à chaque récursivité
    $out = ""; // chaîne vide
    $prevLevel = 0;// niveau précédent, 0 au démarage

    // premier passage (ouverture du premier menu)
    if (!$level && !$prevLevel) $out .= "\n<ul class='navbar-nav mr-auto'>\n\n";

    // tant qu'on a des rubriques
    foreach ($rub as $item) {

        // si enfant de l'id de la rubrique actuelle (0 pour les menus de l'accueil)
        if ($parent == $item['rubriques_idrubriques']) {

            // si le level vaut 0
            if(!$level) {
                $sous0 = false;
                // on cherche les enfants
                foreach ($rub as $item2) {

                    // si on trouve un enfant on a un niveau 1 de menu
                    if ($item['idrubriques'] == $item2['rubriques_idrubriques']) {
                        $sous0 = true;
                    }

                }
                    // si on trouve un enfant on a un niveau 1 de menu
                    if($sous0){
                        // si on a des sous-menu de niveau 1
                        $out .= "<li class='nav-item dropdown'>
                    <a href='#' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'
                       class='nav-link dropdown-toggle'>{$item['rubriques_name']}</a><ul class='dropdown-menu border-0 shadow'>";
                    }else{
                        $out .= "<li class='nav-item'><a  class='nav-link' href='?id={$item['idrubriques']}'>{$item['rubriques_name']}</a>";

                    }
                }


            // si on a un sous-menu
            elseif ($prevLevel < $level) {

                // 2 retour à la ligne
                $out .= "\n\n";
                // on augmente l'indentation (tab) suivant le level
                for ($i = 0; $i < $level; $i++) $out .= "       ";
                // début du sous-menu
                $out .= "<li class='dropdown-submenu'>
                            <a href='#' role='button' data-toggle='dropdown' aria-haspopup='true'
                               aria-expanded='false' class='dropdown-item dropdown-toggle'>Hover for action</a>
<ul class='dropdown-menu border-0 shadow'>\n";
            }
            // affichage du lien avec indentation
            $out .= "\n";
            for ($i = 0; $i < $level; $i++) $out .= "       ";
            $out .= "<li>$parent $level $prevLevel <a href='?id={$item['idrubriques']}'>{$item['rubriques_name']}</a>";

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
        $out .= "</ul></li>";
    }

    // sortie des données dans un $out .= $out, ou si c'est le dernier appel sortie finale
    return $out;
}
