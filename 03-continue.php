<?php
function passePaire(int $entier){

    $out ="Affiche les chiffre paires<br>";

    for($i=$entier;$i<=100;$i++){
        $out.= "<br>-<br>";
        // si le chiffre est impaire, on utilise continue qui arrête le tour de la boucle ici et n'exécute donc pas le $out.=$i; continue n'arrête pas la boucle!
        if(($i%2)) continue;
        $out .= $i;
    }
    return $out;

}

echo passePaire(1);
