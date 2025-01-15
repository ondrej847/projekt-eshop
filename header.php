<?php
/* kontrola sputeni session, jestli neni spustena tak ji my timto spustime */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eshop</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="hlastr">   
     <a href="index.php" class="eshop">Eshop</a>

        <div class="buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
            <!--    kdyz je uzivatel prihlasen bude mit moznost se odlhasit pomoci tlacitka a bude mit moznost na svuj ucet -->
                <a href="logout.php" class="button">Odhlásit</a>
                <a href="ucet.php" class="button">Můj účet</a>
            <?php else: ?>
            <!--     pokud prihlasen neni, bude mit moznost se jen prihlasit -->
                <a href="prihlaseni.php" class="button">Přihlásit</a>
            <?php endif; ?>
            
            <a href="cart.php" class="button">Košík</a>
        </div>
    </div>
    <style>
body {
    margin: 0;  /* vytvari prostor mezi elementy */
    padding: 0;
}

.hlastr {
    background-color: #333;  /* pozadi okraje  */
    position: fixed;  /* horni okraj fixovan */
    top: 0;  /* vrchol strany */
    width: 100%;  /* sirka horniho okraje */
    padding: 10px 20px;  /* vnitrni okraje pro tlacitka */
    display: flex;  /* flexbox pro umisteni tlacitek */
    justify-content: space-between;  /* to znamena ze eshop bude na leve a tlacitka na prave */
}

.eshop {
    padding: 10px 20px;
    color: white;  
    text-decoration: none;  /* aby nebyl text podtrzeny */
    font-size: 20px;  /* velikost pisma pro eshop */
    font-weight: bold;  /* vyraznejsi text */    

}
.buttons {
    margin-right: 30px;  /* tlacitka si posunu trochu doprava */
    display: flex;  /* aby byli tlacitka zarovnana vedle sebe */      
    }

.button {
    padding: 10px 20px;  /* urcuje vnitrni okraje mezi obsahem a hranicemi */
    margin-left: 15px;  /* mezera mezi tlacitky */ 
    background-color:rgb(136, 160, 141);  /* pozadi tlacitek najito na internetu */
    color: white;  /* Bílý text na tlačítkách */    
    text-decoration: none;  /* aby nebyl text podtrzeny */
    border-radius: 15px;  /* zaobleni rohu */

    
}

.button:hover {
    background-color: #45a049;  /* pri najeti mysi na tlacitka bude tmavsi zelena  */
} 

.eshop:hover {
    color:#4CAF50;   /* zmena barvy eshop, kdyz na to najedeme mysi  */
}


</style>

</body>


</html>

