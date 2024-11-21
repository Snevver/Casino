<?php

session_start();

include 'dbcon.php';

if (isset($_POST['login'])) {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    // Maak een query om te kijken of de opgegeven username in de database zit
    $query = "SELECT * FROM gebruikers 
              WHERE gebruikersnaam = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $gebruikersnaam);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Als de gebruikersnaam voorkomt in de database
    if ($result && mysqli_num_rows($result) > 0) {

        // Haal de data van de user op uit de database (alles wat in dezelfde row staat als de overeenkomende username)
        $user_data = mysqli_fetch_assoc($result);
        
        // Als het wachtwoord overeenkomt met wachtwoord wat bij de username hoort in de database
        if (password_verify($wachtwoord, $user_data['wachtwoord'])) {
            // Zet in de sessie dat de gebruiker is ingelogd
            $_SESSION['ingelogde_gebruiker'] = $user_data['gebruiker_id'];

            // Redirect de gebruiker naar de home pagina
            header("Location: home.php"); 
            exit();
        } else {  
            // Als het wachtwoord niet overeenkomt met het wachtwoord wat bij de username hoort in de database
            echo "<h2> Onjuist wachtwoord! </h2>";
        }
    } else {   
        // Als de gebruikersnaam niet voorkomt in de database
        echo "<h2> Gebruiker niet gevonden!</h2>"; 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="center" style="height: 90%; width: 100%; text-align: center; display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <h2 style="margin-bottom: 40px;">Login</h2>
        <form action="login.php" method="post" style="text-align: center;">
            <h3><label for="gebruikersnaam">Gebruikersnaam:</label></h3>
            <p style="margin-bottom: 20px;"><input style="width: 250px; height: 35px; border-radius: 15px; border: 1px solid white; font-size: 20px; padding: 5px; font-family: 'andale mono', monospace;" type="text" id="gebruikersnaam" name="gebruikersnaam" required></p>
            <h3><label for="wachtwoord">Wachtwoord:</label></h3>
            <p style="margin-bottom: 20px;"><input style="width: 250px; height: 35px; border-radius: 15px; border: 1px solid white; font-size: 20px; padding: 5px; font-family: 'andale mono', monospace;" type="password" id="wachtwoord" name="wachtwoord" required></p>
            <input type="submit" value="Login" name="login" style="margin-bottom: 20px;">
            <p>Nog geen account? <a href="registratie.php">Maak een account aan!</a></p>
        </form>
    </div>
</body>
</html>