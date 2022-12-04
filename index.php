<?php
session_start();
$msg = "";
$rigioca = "hidden";
function setSession()
{
    $numbers = array_rand(range(0, 9), 2);
    $_SESSION["firstTreasure"] = strval($numbers[0]);
    $_SESSION["secondTreasure"] = strval($numbers[1]);
    $_SESSION["tentativi"] = 3;
    $_SESSION["mosseVincenti"] = 0;
    $_SESSION["vittoria"] = false;
    $rigioca = "hidden";
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
    session_destroy();
    session_start();
}
$_SESSION['LAST_ACTIVITY'] = time();

if (isset($_SESSION["tentativi"]) and $_SESSION["tentativi"] <= 0 || $_SESSION["vittoria"] == true) {
    setSession();
};
if (!isset($_SESSION["tentativi"])) {
    setSession();
};
?>



<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Caccia al tesoro</title>
</head>

<body>
    <h1 class="main-title">Caccia al tesoro</h1>
    <p class="tentativi">
        <?php echo $_SESSION["tentativi"] . " " ?>Tentativ<?php echo (($_SESSION["tentativi"]  <= 1) ? "o" :  "i") ?>
    </p>
    <form method="post" id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="game">
            <button class="aiuola" value="0" name="move"></button>
            <button class="aiuola" value="1" name="move"></button>
            <button class="aiuola" value="2" name="move"></button>
            <button class="aiuola" value="3" name="move"></button>
            <button class="aiuola" value="4" name="move"></button>
            <button class="aiuola" value="5" name="move"></button>
            <button class="aiuola" value="6" name="move"></button>
            <button class="aiuola" value="7" name="move"></button>
            <button class="aiuola" value="8" name="move"></button>
            <button class="aiuola" value="9" name="move"></button>
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["rigioca"])) {
            $rigioca = "hidden";
        } else if (isset($_POST["move"])) {
            $move = $_POST["move"];
            unset($_POST);
            gameplay($move);
        }
    }
    function gameplay(string $move)
    {
        if ($_SESSION["firstTreasure"] == $move || $_SESSION["secondTreasure"] == $move) {
            $_SESSION["mosseVincenti"]++;
        }
        $_SESSION["tentativi"]--;
        if ($_SESSION["mosseVincenti"] == 2) {
            $GLOBALS["msg"] = "Vittoria";
            $_SESSION["vittoria"] = true;
            $GLOBALS["rigioca"] = "visible";
            setSession();
        }
        if ($_SESSION["tentativi"] == 0) {
            $GLOBALS["msg"] = "Perso";
            $GLOBALS["rigioca"] = "visible";
        }
    }
    ?>

    <div class="rigioca" id="<?php echo $rigioca ?>">
        <p class="giudizio" id="<?php echo $rigioca ?>">
            <?php
            echo $msg;
            ?>
        </p>
        <form method="post" id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" value="true" name="rigioca" />
            <button>Rigioca</button>
        </form>
    </div>
</body>

</html>