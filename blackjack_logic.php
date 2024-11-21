<?php

// Zet alle kaarten in arrays volgens hun waarde
$twee = [1, 14, 27, 40];
$drie = [2, 15, 28, 41];
$vier = [3, 16, 29, 42];
$vijf = [4, 17, 30, 43];
$zes = [5, 18, 31, 44];
$zeven = [6, 19, 32, 45];
$acht = [7, 20, 33, 46];
$negen = [8, 21, 34, 47];
$tien = [9, 22, 35, 48];
$boer = [11, 24, 37, 50];
$koningin = [12, 25, 38, 51];
$koning = [13, 26, 39, 52];
$aas = [10, 23, 36, 49];


// Functie om de vaste waarde van een kaart op te halen (azen zijn altijd 1)
function vaste_kaart_waarde($kaart) {
    global $twee, $drie, $vier, $vijf, $zes, $zeven, $acht, $negen, 
           $tien, $boer, $koningin, $koning, $aas;
    if (in_array($kaart, $twee)) return 2;
    if (in_array($kaart, $drie)) return 3;
    if (in_array($kaart, $vier)) return 4;
    if (in_array($kaart, $vijf)) return 5;
    if (in_array($kaart, $zes)) return 6;
    if (in_array($kaart, $zeven)) return 7;
    if (in_array($kaart, $acht)) return 8;
    if (in_array($kaart, $negen)) return 9;
    if (in_array($kaart, $tien) || in_array($kaart, $boer) || 
        in_array($kaart, $koningin) || in_array($kaart, $koning)) return 10;
    if (in_array($kaart, $aas)) return 1;
}

// Functie om het totaal van de kaarten te berekenen
function bereken_totaal($kaarten) {
    global $aas;
    if (!isset($aas)) {
        $aas = [];
    }
    $totaal = 0;
    $aantal_azen = 0;
    foreach ($kaarten as $kaart) {
        if (in_array($kaart, $aas)) {
            $aantal_azen++;
        }
        $totaal += vaste_kaart_waarde($kaart);
    }
    for ($i = 0; $i < $aantal_azen; $i++) {
        if ($totaal + 10 <= 21) {
            $totaal += 10;
        }
    }
    return $totaal;
}

?>