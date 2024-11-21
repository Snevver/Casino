<?php
include 'dbCon.php';

$errors = [];

if (isset($_POST['registreer'])) {
    // zet de gegevens in variabelen
    $gebruikersnaam = ($_POST['gebruikersnaam']);
    $email = ($_POST['email']);
    $geboortedatum = $_POST['geboortedatum'];
    $wachtwoord = $_POST['wachtwoord'];
    $herhaal_wachtwoord = $_POST['herhaal_wachtwoord'];

    // Check de leeftijd
    $dob = new DateTime($geboortedatum);
    $vandaag = new DateTime(); 
    $leeftijd = $vandaag->diff($dob)->y;

    if ($leeftijd < 18) {
        $errors[] = "Je bent helaas te jong om door te gaan.";
    }

    // Check of het wachtwoord overeen komt
    if ($wachtwoord !== $herhaal_wachtwoord) {
        $errors[] = "De wachtwoorden komen niet overeen!";
    } 

    // Check of de gebruikersnaam al bestaat
    $stmt = $con->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = ?");
    $stmt->bind_param("s", $gebruikersnaam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Gebruikersnaam is al in gebruik.";
    }

    // Als er een errors zijn opgedoken
    if (empty($errors)) {
        // Hash het wachtwoord
        $hashed_wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

        // Prepared statements om de informatie in de database op te slaan
        $stmt = $con->prepare("INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord, geboortedatum, saldo) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("ssss", $gebruikersnaam, $email, $hashed_wachtwoord, $geboortedatum);
        
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Registratie mislukt. Probeer het opnieuw.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registratie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="border: none; margin-top: 50px;">
        <h2 style="margin-bottom: 50px;">Maak een account aan!</h2>
        
        <?php
        // Laat hier de errors zien
        if (!empty($errors)) {
            echo "<div class='error-container'>";
            foreach ($errors as $error) {
                echo "<p class='fout'>$error</p>";
            }
            echo "</div>";
        }
        ?>
        
        <form action="registratie.php" method="post" style="text-align: center;">
            <h3><label for="gebruikersnaam">Gebruikersnaam</label></h3>
            <input type="text" id="gebruikersnaam" name="gebruikersnaam" required 
                   value="<?php echo isset($gebruikersnaam) ? htmlspecialchars($gebruikersnaam) : ''; ?>"
                   style="width: 250px; height: 35px; border-radius: 15px; border: 1px solid white; font-size: 20px; padding: 5px; font-family: 'andale mono', monospace; margin-bottom: 30px;">
            
            <h3><label for="Email">Email</label></h3>
            <input type="email" id="email" name="email" required 
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                   style="width: 250px; height: 35px; border-radius: 15px; border: 1px solid white; font-size: 20px; padding: 5px; font-family: 'andale mono', monospace; margin-bottom: 30px;">

            <h3><label for="geboortedatum">Geboorte datum</label></h3>
            <input type="date" id="geboortedatum" name="geboortedatum" required 
                   value="<?php echo isset($geboortedatum) ? htmlspecialchars($geboortedatum) : ''; ?>"
                   style="width: 250px; height: 35px; border-radius: 15px; border: 1px solid white; font-size: 20px; padding: 5px; font-family: 'andale mono', monospace; margin-bottom: 30px;">
            
            <h3><label for="wachtwoord">Wachtwoord</label></h3>
            <input type="password" id="wachtwoord" name="wachtwoord" required
                   style="width: 250px; height: 35px; border-radius: 15px; border: 1px solid white; font-size: 20px; padding: 5px; font-family: 'andale mono', monospace; margin-bottom: 30px;">
            
            <h3><label for="herhaal_wachtwoord">Herhaal wachtwoord</label></h3>
            <input type="password" id="herhaal_wachtwoord" name="herhaal_wachtwoord" required
                   style="width: 250px; height: 35px; border-radius: 15px; border: 1px solid white; font-size: 20px; padding: 5px; font-family: 'andale mono', monospace; margin-bottom: 30px;">
            
            <h3><input style="margin-bottom: 20px;" type="submit" value="Registreer!" name="registreer"></h3>
            <p>Heeft u al een account? <a href="login.php">Klik hier</a></p>
        </form>
    </div>
</body>
</html>