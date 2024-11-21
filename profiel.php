<?php 

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
    <title>Casino - profiel</title>
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
    
    <h1>Profiel gegevens:</h1>

    <!-- Gebruiker gegevens -->
    <div class="container" style="border: none; margin-top: 40px;">
        <div class="container" style="display: flex; flex-direction: row; border: none; margin: 20px;">
            <h2 style="margin-right: 20px;">Gebruikersnaam: </h2>
            <h3><?=$gebruikersnaam;?></h3>
        </div>
        <div class="container" style="display: flex; flex-direction: row; border: none; margin: 20px;">
            <h2 style="margin-right: 20px;">Email: </h2>
            <h3><?=$email;?></h3></div>
        <div class="container" style="display: flex; flex-direction: row; border: none; margin: 20px;">
            <h2 style="margin-right: 20px;">Geboorte datum (yyyy/mm/dd): </h2>
            <h3><?=$geboortedatum;?></h3></div>
        <div class="container" style="display: flex; flex-direction: row; border: none; margin: 20px;">
            <h2 style="margin-right: 20px;">Saldo: </h2>
            <h3>€<?=$saldo;?></h3>
        </div> 
    </div>
    

</body>
</html>