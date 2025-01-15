<?php
session_start(); // Start session pro košík

require_once "header.php";
require_once "footer.php";


$server = "localhost";
$login = "root";
$passwd = "";
$schema = "eshop";

$conn = new mysqli($server, $login, $passwd, $schema);


if ($conn->connect_error) {
    die("Připojení k databázi selhalo: " . $conn->connect_error);
}

/* kodovani na utf8 kvuli diakritice */
$conn->set_charset("utf8mb4");

/* pridani do kosiku, kdyz na to prijde pozadavek */
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; /* id produktu */

/*     timto pridame kosik do session tudíž vlastne do kosiku, bude tam ulozenn */
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

   
    /* if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    } */
}

/*  nacteni produktu */
$sql = "SELECT * FROM produkty WHERE stav = 'aktivni' ORDER BY datum_pridani DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 sloupce */
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .product {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;

        }
        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product h3 {
            font-size: 1.2em;
            margin: 10px 0;
            color: #333;
        }
        .product p {
            margin: 5px ;
            color: #666;
        }
        .product .price {
            font-weight: bold;
            color: rgb(136, 160, 141);
        }
        .add-to-cart {
            margin-top: 15px;
            background-color: rgb(136, 160, 141);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-to-cart:hover { 
            background-color:#45a049; /* po najeti na tlacitko pridat do kosiku se nam mirne zmeni barva */
        }
    </style>
</head>

<body>
    <h1>Produkty</h1>

    <div class="product-grid">
        <?php if ($result->num_rows > 0): ?> <!-- zda SQL dotaz obsahuje alespon jeden radek -->
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <h3><?= htmlspecialchars($row['nazev']) ?></h3>
                    <img src="<?= htmlspecialchars($row['obrazek_url']) ?>" alt="<?= htmlspecialchars($row['nazev']) ?>"> <!-- htmlspecialchars ochrana stránky -->
                    <p><?= htmlspecialchars($row['popis']) ?></p>
                    <p class="price"><?= htmlspecialchars($row['cena']) ?> Kč</p>
                    <p>Skladem: <?= htmlspecialchars($row['mnozstvi']) ?> ks</p>

                <!--  pridani do kosiku, formular, dodelat -->
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <button type="submit" name="add_to_cart" class="add-to-cart">Přidat do košíku</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>