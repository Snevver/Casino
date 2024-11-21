<?php

include "data.php";
include "dbcon.php";  

// Check of de gebruiker is ingelogd
if (!isset($_SESSION['ingelogde_gebruiker'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['bijschrijven'])) {
    $geld = floatval($_POST['geld']); 
    
    // Update saldo in database
    $user_id = $_SESSION['ingelogde_gebruiker'];
    $geld_erbij = $geld;
    $nieuw_saldo = $saldo + $geld_erbij;
    
    $query = "UPDATE gebruikers SET saldo = ? WHERE gebruiker_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "di", $nieuw_saldo, $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Error updating saldo: " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);

    header("Location:geld_toevoegen.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino - Geld Bijschrijven</title>
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
    <div class="geld_toevoegen_container">
        <h1>Saldo opwaarderen</h1> 
        <div class="center">
            <form action="geld_toevoegen.php" method="post">
            <h3><label for="geld">Hoeveel geld wil je bijschrijven?</label></h3>
                <div class="center">
                    <input type="number" step="0.01" min="0" id="geld" name="geld" required>
                    <input type="submit" value="Opwaarderen" name="bijschrijven">
                </div>
            </form>
        </div>
    </div>
</body>
</html>