<?php
if (isset($_GET['search-title'])) {
    $searchTitle = $_GET['search-title'];

    // Connexion à la base de données
    $serveur = "localhost";
    $utilisateur = "root";
    $mot_de_passe = "";
    $base_de_donnees = "projetWeb";
    $connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    // Vérification de la connexion
    if ($connexion->connect_error) {
        die("Erreur de connexion : " . $connexion->connect_error);
    }

    // Préparation de la requête SQL pour la recherche
    $stmt = $connexion->prepare("SELECT * FROM Livres WHERE Titre LIKE ?");
    $searchTerm = "%" . $searchTitle . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $resultat = $stmt->get_result();

    if ($resultat->num_rows > 0) {
        echo "<h2 style='color: #333; text-align: center; margin-bottom: 20px;'>Résultats de recherche pour '$searchTitle' :</h2>";
        echo "<table style='width: 100%; border-collapse: collapse; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>";
        echo "<thead style='background-color: #333; color: #fff;'>";
        echo "<tr>";
        echo "<th style='padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;'>Titre</th>";
        echo "<th style='padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;'>Auteur</th>";
        echo "<th style='padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;'>Catégorie</th>";
        echo "<th style='padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;'>Stock</th>";
        echo "<th style='padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;'>Année de Parution</th>";
        echo "<th style='padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;'>Action</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $resultat->fetch_assoc()) {
            echo "<tr style='border-bottom: 1px solid #ddd;'>";
            echo "<td style='padding: 12px 15px; text-align: left;'>" . $row["Titre"] . "</td>";
            echo "<td style='padding: 12px 15px; text-align: left;'>" . $row["Auteur"] . "</td>";
            echo "<td style='padding: 12px 15px; text-align: left;'>" . $row["Catégorie"] . "</td>";
            echo "<td style='padding: 12px 15px; text-align: left;'>" . $row["Stock"] . "</td>";
            echo "<td style='padding: 12px 15px; text-align: left;'>" . $row["AnnéeDeParution"] . "</td>";
            echo "<td style='padding: 12px 15px; text-align: left;'><a href='ajouter_panier.php?id=" . $row["IdLivre"] . "&stock=" . $row["Stock"] . "' style='display: inline-block; width: 30px; height: 30px; line-height: 30px; text-align: center; background-color: #4CAF50; color: #fff; text-decoration: none; border-radius: 50%;'>+</a></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>Aucun résultat trouvé pour '$searchTitle'.</p>";
    }

    $stmt->close();
    $connexion->close();
} else {
    echo "<p>Veuillez entrer un titre pour la recherche.</p>";
}
?>
