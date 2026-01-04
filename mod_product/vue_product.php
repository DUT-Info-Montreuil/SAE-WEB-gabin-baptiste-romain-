<?php
class vue_product {
    public function displayProduct($p) {
        if(!$p) { echo "Product not found"; return; }
        ?>
        <h1><?php echo htmlspecialchars($p['name']); ?></h1>
        <p>Price : <?php echo $p['price']; ?> €</p>
        <p><?php echo nl2br(htmlspecialchars($p['description'])); ?></p>
        <?php if($p['quantity'] == 0) echo "<p>Out of stock</p>"; ?>
        <a href="index.php?module=home">Back</a>
        <?php
    }
}
?>