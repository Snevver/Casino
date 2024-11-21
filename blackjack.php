<?php 

include "data.php";
include "blackjack_logic.php";

// Twee functies om de winst/verlies te updaten in de database
function resultaat_gewonnen($saldo, $con) {
    $_SESSION['resultaat'] = "Gewonnen!";
    $saldo += $_SESSION['inzet'];
    $_SESSION['spel_klaar'] = true;

    $user_id = $_SESSION['ingelogde_gebruiker'];
    $nieuw_saldo = $saldo;
    
    $query = "UPDATE gebruikers SET saldo = ? WHERE gebruiker_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "di", $nieuw_saldo, $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Error updating saldo: " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);

    return $_SESSION['resultaat'];
}
function resultaat_verloren($saldo, $con) {
    $_SESSION['resultaat'] = "Verloren!";
    $saldo -= $_SESSION['inzet'];
    $_SESSION['spel_klaar'] = true;

    $user_id = $_SESSION['ingelogde_gebruiker'];
    $nieuw_saldo = $saldo;
    
    $query = "UPDATE gebruikers SET saldo = ? WHERE gebruiker_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "di", $nieuw_saldo, $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Error updating saldo: " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);

    return $_SESSION['resultaat'];
}

// Zet de inzet in de sessie alleen als er een nieuwe inzet is opgegeven
if (isset($_POST['inzet']) && is_numeric($_POST['inzet']) && $_POST['inzet'] > 0) {
    $_SESSION['inzet'] = (int)$_POST['inzet'];
} 

// Zet de standaard inzet op 0
if (!isset($_SESSION['inzet'])) {
    $_SESSION['inzet'] = 0;
}

// Start een nieuwe ronde
if (isset($_SESSION['inzet']) && $_SESSION['inzet'] <= $saldo && isset($_POST['start'])) {

    // Reset de session     
    $_SESSION['start'] = true;
    $_SESSION['spel_klaar'] = false;
    unset($_SESSION['resultaat'], $_SESSION['dealer_totaal'], $_SESSION['speler_totaal'], $_SESSION['dealer_kaarten'], $_SESSION['speler_kaarten']);

    // Maak een variabelen met een geshudde lijst met alle getallen van de speelkaarten
    $kaarten = range(1, 52);
    shuffle($kaarten);

    // Geef de speler en de dealer allebij 2 kaarten en haal deze kaarten van de lijst met overige kaarten af
    $_SESSION['dealer_kaarten'] = array_splice($kaarten, 0, 2);
    $_SESSION['speler_kaarten'] = array_splice($kaarten, 0, 2);

    // Bereken het totaal van de kaarten
    $_SESSION['dealer_totaal'] = bereken_totaal($_SESSION['dealer_kaarten']);
    $_SESSION['speler_totaal'] = bereken_totaal($_SESSION['speler_kaarten']);

    // Check voor een black jack
    if ($_SESSION['speler_totaal'] == 21) {
        resultaat_gewonnen($saldo, $con);
        header("Location: blackjack.php");
    }
}

// Hit-knop
if (isset($_POST['hit']) && $_SESSION['spel_klaar'] == false) {

    // Geef de speler een nieuwe kaart
    $nieuwe_kaart = rand(1, 52);
    while (in_array($nieuwe_kaart, $_SESSION['speler_kaarten']) || in_array($nieuwe_kaart, $_SESSION['dealer_kaarten'])) {
        $nieuwe_kaart = rand(1, 52);
    }

    // Voeg de nieuwe kaart toe aan de speler kaarten en reken het totaal opnieuw uit
    $_SESSION['speler_kaarten'][] = $nieuwe_kaart;
    $_SESSION['speler_totaal'] = bereken_totaal($_SESSION['speler_kaarten']);

    // Check of de speler een totaal boven de 21 heeft of juist precies 21
    if ($_SESSION['speler_totaal'] > 21) {
        resultaat_verloren($saldo, $con);
        header("Location: blackjack.php");
    } elseif ($_SESSION['speler_totaal'] == 21) {
        resultaat_gewonnen($saldo, $con);
        header("Location: blackjack.php");
    }
}

// Stand-knop
if (isset($_POST['stand']) && $_SESSION['spel_klaar'] == false) {
    // Geef de dealer kaarten tot hij 17 of meer totale waarde heeft
    while ($_SESSION['dealer_totaal'] < 17) {
        do {
            $nieuwe_kaart = rand(1, 52);
        } while (in_array($nieuwe_kaart, $_SESSION['speler_kaarten']) || 
                in_array($nieuwe_kaart, $_SESSION['dealer_kaarten']));
        
        // Voeg de nieuwe kaart steeds toe aan de lijst met kaarten van de dealer en bereken steeds de nieuwe waarde
        $_SESSION['dealer_kaarten'][] = $nieuwe_kaart;
        $_SESSION['dealer_totaal'] = bereken_totaal($_SESSION['dealer_kaarten']);
    }

    // Bepaal het resulaat
    if ($_SESSION['dealer_totaal'] > 21 || $_SESSION['speler_totaal'] > $_SESSION['dealer_totaal']) {
        resultaat_gewonnen($saldo, $con);
    } elseif ($_SESSION['speler_totaal'] < $_SESSION['dealer_totaal']) {
        resultaat_verloren($saldo, $con);
    } else {
        $_SESSION['resultaat'] = "Gelijkspel!";
        $_SESSION['spel_klaar'] = true;
    }

    // Refresh de pagina om het saldo up-to-date te houden
    header("Location: blackjack.php");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino - Black Jack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!--  Navbar -->
    <div class="navigatiebalk">
        <div class="navigatiebalk_start">
            <!-- logo -->
            <h1><a href="home.php">Casino</a></h1>
        </div>
        <div class="navigatiebalk_midden">
            <!-- spelopties -->
            <div class="roulette_link">
                <h3><a href="roulette.php">Roulette</a></h3>
            </div>
            <div class="blackjack_link">
                <h3><a href="blackjack.php">Blackjack</a></h3>
            </div>
        </div>
        <div class="navigatiebalk_eind">
            <!-- saldo -->
             <div class="saldo">
                <h3>Saldo: €<?php echo $saldo;?></h3>
            </div>
            <div class="profiel">
                <h3><a href="profiel.php">Profiel</a></h3>
            </div>
            <div class="logout_link">
                <h3><a href="logout.php">Logout</a></h3>
            </div>
        </div>
    </div>

    <?php if ($saldo <= 0) {
        echo "<p class='fout'>Je balans is op, <a href='geld_toevoegen.php'>schrijf meer geld bij.</a></p>";
        }

        // Controleer of het inzetbedrag hoger is dan het huidige saldo
        if (isset($_POST['inzet'])) {
            $inzet_bedrag = floatval($_POST['inzet']);
            if ($inzet_bedrag > $saldo) {
                echo "<p class='fout'>Je inzet kan niet hoger zijn dan je saldo!</p>";
                $_SESSION['inzet'] = 0;
            } else {
                $_SESSION['inzet'] = $inzet_bedrag;
            }
        }
        
        if (isset($_POST['aangepaste_inzet'])) {
            $inzet_bedrag = floatval($_POST['aangepaste_inzet']);
            if ($inzet_bedrag > $saldo) {
                echo "<p class='fout'>Je inzet kan niet hoger zijn dan je saldo!</p>";
                $_SESSION['inzet'] = 0;
            } else {
                $_SESSION['inzet'] = $inzet_bedrag;
            }
        }

        if ($saldo - $_SESSION['inzet'] < 0) {
            echo "<p class='fout'>Je kan geen " . $_SESSION['inzet'] . " euro meer inzetten</p>";
            $_SESSION['inzet'] = 0;
        }
        
    ?>

    <!-- Blackjack container -->
    <div class="blackjack_container">
        <h1>Blackjack</h1>
        
        <!-- Een knop om het spel te starten als hij nog niet getsart is -->
        <?php if (!isset($_SESSION['start'])) { ?>
            <div class="begin_knop_blackjack">
                <form method="POST">
                    <input type="number" name="inzet" placeholder="Inzet">
                    <button style="border: silid white 1px; border-radius: 15px;" type="submit" name="start" value="Begin!">Begin!</button>
                </form>
            </div>
        <?php } else { ?>

            <?php
            try {
                if (!isset($_SESSION['inzet'])) {
                    throw new Exception("Geen inzet gevonden");
                } ?>
                <h3>Inzet: <?php echo $_SESSION['inzet'] . " euro"; ?></h3> 
            <?php } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
            ?>
            
            <!-- Wanneer het spel bezig is -->
            <!-- De code voor de kaarten van de dealer -->
            <div class="<?php 
            if (!isset($_SESSION['resultaat'])) {
                echo 'speelveld_blackjack';
            } else if ($_SESSION['resultaat'] == "Gewonnen!") {
                echo 'blackjack_win';
            } else if ($_SESSION['resultaat'] == "Verloren!" ) {
                echo 'blackjack_verlies';
            } 
            ?>">
                <div class="container">
                    <p>Dealer kaarten:</p>
                    <div class="dealer_kaarten">
                        <?php
                        // Laat de eerste kaart van de dealer zien
                        echo "<img src='speelkaarten/{$_SESSION['dealer_kaarten'][0]}.png' alt='Dealer kaart'>";
                        
                        // Laat de tweede kaart alleen zien wanneer het spel voorbij is
                        if ($_SESSION['spel_klaar'] == true) {
                            echo "<img src='speelkaarten/{$_SESSION['dealer_kaarten'][1]}.png' alt='Dealer kaart'>";
                        } else {
                            echo "<img src='speelkaarten/mystery.png' alt='Dealer kaart'>";
                        }
                        
                        // Laat de andere kaarten van de dealer zien als die er zijn
                        for ($i = 2; $i < count($_SESSION['dealer_kaarten']); $i++) {
                            echo "<img src='speelkaarten/{$_SESSION['dealer_kaarten'][$i]}.png' alt='Dealer kaart'>";
                        }
                        ?>
                    </div>

                    <!-- De code voor de kaarten van de speler -->
                    <p>Jouw kaarten:</p>
                    <div class="speler_kaarten">
                        <?php
                        // Laat de kaarten van de speler zien
                        foreach ($_SESSION['speler_kaarten'] as $player_card) {
                            echo "<img src='speelkaarten/{$player_card}.png' alt='Speler kaart'> ";
                        }
                        ?>
                    </div>
                </div>
            </div>
            
                
            <?php // Laat het dealer totaal alleen zien wanneer het spel voorbij is
                if ($_SESSION['spel_klaar'] == true) {
                    echo "<p>Dealer totaal: {$_SESSION['dealer_totaal']}</p>";
                }
            ?>

            <?php //Laat het totaal van de speler zien
                echo "<p>Jouw totaal: {$_SESSION['speler_totaal']}</p>";
            ?>
  
        <!-- De knoppen om te hitten of te standen  -->
        <?php if ($_SESSION['spel_klaar'] == false) { ?>
            <div class="knoppen_menu">
                <form method="POST" class="form_knoppen">
                    <button type="submit" name="hit" value="Hit">Hit</button>
                    <button type="submit" name="stand" value="Stand">Stand</button>
                </form> 
            </div>
        <?php } ?>
            
            <?php if ($_SESSION['spel_klaar'] == true && isset($_SESSION['resultaat'])) { ?>
                <!-- Wanneer de game voorbij is, print het resultaat en een knop om opnieuw te beginnen -->
                <p><?php echo $_SESSION['resultaat']; ?></p>
                <form method="POST">
                    <div class="nieuwe_inzet">
                        <input type="number" name="inzet" placeholder="Nieuwe inzet">
                    </div>
                    <button style="border: silid white 1px; border-radius: 15px;" type="submit" name="start" value="Opnieuw" id="opnieuw_spelen">Opnieuw Spelen</button>
                </form>
            <?php } ?>
        <?php } ?>
    </div>
    <?php mysqli_close($con); ?>
</body>
</html>

                