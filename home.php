<?php 

// TO-DO LIST:
// 1. 18+ dob only fix

include "data.php";

if (!isset($_SESSION['ingelogde_gebruiker'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino - Home</title>
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
    <div class="home_inhoud">
        <!-- home content -->
        <div class="keuze_menu">
            <h1>Welkom op Svens casino, <?php try {
                echo $gebruikersnaam;
                } catch (Exception $e) {
                    echo 'Error: ',  $e->getMessage();
                } ?></h1>
            <p>Selecteer een optie om te beginnen:</p>
            <div class="start_opties">
                <a href="roulette.php">Roulette</a>
                <a href="blackjack.php">Blackjack</a>
                <a href="geld_toevoegen.php">Geld bijschrijven</a>
            </div>
        </div>
    </div>    
</body>
</html>