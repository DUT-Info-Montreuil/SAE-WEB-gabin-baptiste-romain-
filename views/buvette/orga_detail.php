<?php

class OrgaDetailView {

    public function display($organization, $products) {
        echo "<h1>" . htmlspecialchars($organization['name']) . "</h1>";
        echo "<p>" . htmlspecialchars($organization['address']) . "</p>";
        
        echo "<h2>Articles disponibles</h2>";
        if (!empty($products)) {
            echo "<ul>";
            foreach ($products as $product) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($product['name']) . "</strong> - ";
                echo htmlspecialchars($product['description']) . " - ";
                echo "<em>" . number_format($product['price'], 2, ',', ' ') . " €</em>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Aucun article disponible pour cette buvette.</p>";
        }

        echo '<br><a href="index.php?page=home">Retour à la liste des buvettes</a>';
    }
}
