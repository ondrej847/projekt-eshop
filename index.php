<?php
session_start(); // Start session pro košík

require_once "layout/header.php";
require_once "funcs.php";

$conn = connect_db();


/* kodovani na utf8 kvuli diakritice */
$conn->set_charset("utf8mb4");

/* pridani do kosiku, kdyz na to prijde pozadavek */
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; /* id produktu */


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 1;
} else {
    $_SESSION['cart'][$product_id] += 1; 
}
    header("Location: index.php");
    exit;
}

/*  nacteni produktu */
$sql = "SELECT * FROM produkty WHERE stav = 'aktivni' ORDER BY datum_pridani DESC";
$result = $conn->query($sql);
?>

<style>
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 sloupce */
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        margin-top: 45px;

    }
    .product {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: center;
        display: block;
    }
    .product img {
        width: 220px;   /* sirka obrazku */
        height: 220px;   /* vyska */
    }
    @media (max-width: 1024px) {
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    }

    @media (max-width: 600px) {
    .product-grid {
        grid-template-columns: repeat(1, 1fr);
    }
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
        margin-top: 30px;
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



    <div class="product-grid">
        <?php if ($result->num_rows > 0): ?> <!-- zda SQL dotaz obsahuje alespon jeden radek -->
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <h3><?= htmlspecialchars($row['nazev']) ?></h3>
                    <img src="<?= htmlspecialchars($row['obrazek_url']) ?>" alt="<?= htmlspecialchars($row['nazev']) ?>"> <!-- htmlspecialchars ochrana stránky -->
                    <p><?= htmlspecialchars($row['popis']) ?></p>
                    <p class="price"><?= htmlspecialchars($row['cena']) ?> Kč</p>

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

<?php require_once "layout/footer.php"; ?>