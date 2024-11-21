<?php

include "data.php";
include "roulette_logic.php";

if (!isset($_SESSION['rouletteNummer'])) {
    $_SESSION['rouletteNummer'] = 0;  
}

// Wanneer er een keuze wordt gemaakt
if (isset($_POST['keuze'])) {
    // Geef de keuze een variabele
    $keuze = $_POST['keuze'];

    // Krijg een random nummer opo de tafel
    $_SESSION['rouletteNummer'] = roulette_rng();
    
    // Bereken nieuwe saldo
    $nieuw_saldo = $saldo;
    $nieuw_saldo += bereken_winst_of_verlies($keuze, $_SESSION['rouletteNummer'], $_SESSION['inzet']);
    $user_id = $_SESSION['ingelogde_gebruiker'];
    $query = "UPDATE gebruikers SET saldo = ? WHERE gebruiker_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "di", $nieuw_saldo, $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Error updating saldo: " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);

    header("Location:roulette.php");
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino - Roulette</title>
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
            $_SESSION['inzet'] = floatval($_POST['inzet']);
            if ($_SESSION['inzet'] > $saldo) {
                echo "<p class='fout'>Je inzet kan niet hoger zijn dan je saldo!</p>";
                $_SESSION['inzet'] = 0;
            } 
        }
        
        if (isset($_POST['aangepaste_inzet'])) {
            $_SESSION['inzet'] = floatval($_POST['aangepaste_inzet']);
            if ($_SESSION['inzet'] > $saldo) {
                echo "<p class='fout'>Je inzet kan niet hoger zijn dan je saldo!</p>";
                $_SESSION['inzet'] = 0;
            } 
        }

        if ($saldo - $_SESSION['inzet'] < 0) {
            echo "<p class='fout'>Je kan geen " . $_SESSION['inzet'] . " euro meer inzetten</p>";
            $_SESSION['inzet'] = 0;
        }
        
    ?>
    <h3>Inzet: <?php echo $_SESSION['inzet'] . " euro"; ?></h3>
    <div class="roulette_spel">
        <div class="<?php 
            if (!isset($_SESSION['resultaat'])) {
                echo 'roulette';
            } else if ($_SESSION['resultaat'] == "Gewonnen") {
                echo 'blackjack_win';
            } else if ($_SESSION['resultaat'] == "Verloren" ) {
                echo 'blackjack_verlies';
            } 
            ?>">
            <div class="container">
                <form method="POST" action="roulette.php">    
                    <table class="roulette_tafel">
                        <tr>
                            <td rowspan="3" style="border-radius: 20px 0 0 20px;"><button style="border-radius: 20px 0 0 20px;" class="<?php echo ($_SESSION['rouletteNummer'] == 0) ? 'markering' : 'groen'; ?>" type="submit" name="keuze" value="0">0</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 3) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="3">3</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 6) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="6">6</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 9) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="9">9</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 12) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="12">12</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 15) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="15">15</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 18) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="18">18</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 21) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="21">21</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 24) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="24">24</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 27) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="27">27</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 30) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="30">30</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 33) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="33">33</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 36) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="36">36</button></td>
                            <td class="NVTP" style="border-radius: 0 20px 0 0;"><button style="border-radius: 0 20px 0 0;" type="submit" name="keuze" value="2tegen1_1">2to1</button></td>
                        </tr>
                        <tr>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 2) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="2">2</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 5) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="5">5</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 8) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="8">8</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 11) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="11">11</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 14) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="14">14</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 17) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="17">17</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 20) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="20">20</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 23) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="23">23</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 26) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="26">26</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 29) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="29">29</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 32) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="32">32</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 35) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="35">35</button></td>
                            <td class="NVTP"><button type="submit" name="keuze" value="2tegen1_2">2to1</button></td>
                        </tr>
                        <tr>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 1) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="1">1</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 4) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="4">4</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 7) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="7">7</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 10) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="10">10</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 13) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="13">13</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 16) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="16">16</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 19) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="19">19</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 22) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="22">22</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 25) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="25">25</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 28) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="28">28</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 31) ? 'markering' : 'zwart'; ?>" type="submit" name="keuze" value="31">31</button></td>
                            <td><button class="<?php echo ($_SESSION['rouletteNummer'] == 34) ? 'markering' : 'rood'; ?>" type="submit" name="keuze" value="34">34</button></td>
                            <td class="NVTP" style="border-radius: 0 0 20px 0;"><button style="border-radius: 0 0 20px 0;" type="submit" name="keuze" value="2tegen1_3">2to1</button></td>
                        </tr>
                        <tr>
                            <td class="leeg"></td>
                            <td colspan="4"><button type="submit" name="keuze" value="eerste12">1st12</button></td>
                            <td class="abc" colspan="4"><button type="submit" name="keuze" value="tweede12">2nd12</button></td>
                            <td colspan="4"><button type="submit" name="keuze" value="derde12">3th12</button></td>
                            <td class="leeg"></td>
                        </tr>
                        <tr>
                            <td class="leeg"></td>
                            <td colspan="2" style="border-radius: 0 0 0 20px;"><button style="border-radius: 0 0 0 20px;" type="submit" name="keuze" value="1tot18">1to18</button></td>
                            <td colspan="2"><button type="submit" name="keuze" value="EVEN">EVEN</button></td>
                            <td colspan="2"><button class="rood margin" type="submit" name="keuze" value="ROOD"></button></td>
                            <td colspan="2"><button class="zwart margin" type="submit" name="keuze" value="ZWART"></button></td>
                            <td colspan="2"><button type="submit" name="keuze" value="ONEVEN">ODD</button></td>
                            <td colspan="2" style="border-radius: 0 0 20px 0;"><button style="border-radius: 0 0 20px 0;" type="submit" name="keuze" value="19tot36">19to36</button></td>
                            <td class="leeg"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="inzet_menu">
            <div class="inzet_munten">
                <h2>Kies je inzet:</h2>
                <form method="POST">
                    <table class="inzet">
                        <tr>
                            <td class="munten">
                                <button type="submit" name="inzet" value="0.01"><img alt="muntje" src="plaatjes/casino_1.png" class="munten"></button>
                            </td>
                            <td class="munten">
                                <button type="submit" name="inzet" value="0.05"><img alt="muntje" src="plaatjes/casino_5.png" class="munten"></button>
                            </td>
                            <td class="munten">
                                <button type="submit" name="inzet" value="0.10"><img alt="muntje" src="plaatjes/casino_10.png" class="munten"></button>
                            </td>
                            <td class="munten">
                                <button type="submit" name="inzet" value="0.25"><img alt="muntje" src="plaatjes/casino_25.png" class="munten"></button>
                            </td>
                            <td class="munten">
                                <button type="submit" name="inzet" value="1.00"><img alt="muntje" src="plaatjes/casino_100.png" class="munten"></button>
                            </td>
                            <td class="munten">
                                <button type="submit" name="inzet" value="5.00"><img alt="muntje" src="plaatjes/casino_500.png" class="munten"></button>
                            </td>
                            <td class="munten">
                                <button type="submit" name="inzet" value="10.00"><img alt="muntje" src="plaatjes/casino_1000.png" class="munten"></button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="of">
                <h2>of</h2>
            </div>
            <div class="inzet_aangepast">
                <h2>Vul je inzet in:</h2>
                <form method="POST">
                    <input type="number" name="aangepaste_inzet" placeholder="Aangepaste inzet" min="0.01" step="0.01">
                    <button style="border: solid white 1px; border-radius: 15px;" class="border-radius: 20px 20px 20px 20px" type="submit" name="aangepaste_inzet_verzenden" value="1">Selecteer</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>