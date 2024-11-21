<?php

function roulette_rng() {
    return rand(0, 36);
}

function bereken_winst_of_verlies($keuze, $rouletteNummer, $inzet) {
    $lijst_voor_2tegen1_1 = [3, 6, 9, 12, 15, 18, 21, 24, 27, 30, 33, 36];
    $lijst_voor_2tegen1_2 = [2, 5, 8, 11, 14, 17, 20, 23, 26, 29, 32, 35];
    $lijst_voor_2tegen1_3 = [1, 4, 7, 10, 13, 16, 19, 22, 25, 28, 31, 34];
    $lijst_rode_nummers = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 25, 27, 30, 32, 34, 35, 36];
    $lijst_zwarte_nummers = [2, 4, 6, 8, 10, 11, 13, 15, 17, 20, 22, 23, 24, 26, 28, 29, 31, 33];

    if (is_numeric($keuze)) {
        if ($rouletteNummer == $keuze) {
            $_SESSION['resultaat'] = "Gewonnen";
            return 35 * $inzet;

        } else {
            $_SESSION['resultaat'] = "Verloren";
            return -$inzet;
        }
    }

    switch ($keuze) {
        case "2tegen1_1":
            if (in_array($rouletteNummer, $lijst_voor_2tegen1_1)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "2tegen1_2":
            if (in_array($rouletteNummer, $lijst_voor_2tegen1_2)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "2tegen1_3":
            if (in_array($rouletteNummer, $lijst_voor_2tegen1_3)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "eerste12":
            if (in_array($rouletteNummer, range(1, 12))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "tweede12":
            if (in_array($rouletteNummer, range(13, 24))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "derde12":
            if (in_array($rouletteNummer, range(25, 36))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "EVEN":
            if ($rouletteNummer % 2 == 0) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;  
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "ONEVEN":
            if ($rouletteNummer % 2 !== 0) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;  
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "1tot18":
            if (in_array($rouletteNummer, range(1, 18))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "19tot36":
            if (in_array($rouletteNummer, range(19, 36))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "ROOD":
            if (in_array($rouletteNummer, $lijst_rode_nummers)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "ZWART":
            if (in_array($rouletteNummer, $lijst_zwarte_nummers)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        default:
            throw new Exception("Invalid keuze");
    }
}

