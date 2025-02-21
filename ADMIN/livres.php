<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livres</title>
</head>
<body>
    <?php
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

        // Préparation de la requête SQL
        $requete = "SELECT * FROM Livres WHERE Titre IS NOT NULL";

        // Exécution de la requête
        $resultat = $connexion->query($requete);

        // Vérification du résultat
        if ($resultat->num_rows > 0) {
            // Affichage des livres dans un tableau
            echo "<style>";
            echo "/* Styles généraux */";
            echo "body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }";
            echo ".container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }";
            echo "/* Styles pour l'en-tête */";
            echo "h2 { color: #333; font-size: 28px; margin-bottom: 20px; }";
            echo "/* Styles pour le tableau */";
            echo ".table { width: 100%; border-collapse: collapse; background-color: #fff; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); }";
            echo ".table thead { background-color: #343a40; color: #fff; }";
            echo ".table th, .table td { padding: 15px; text-align: left; border-bottom: 1px solid #f2f2f2; }";
            echo ".table tr:hover { background-color: #f9f9f9; }";
            echo "/* Styles pour les messages d'erreur */";
            echo ".text-center { text-align: center; color: #666; font-size: 18px; margin-top: 40px; }";
            echo "/* Animations */";
            echo "@keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }";
            echo "@keyframes slideInDown { 0% { transform: translateY(-100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }";
            echo "@keyframes slideInUp { 0% { transform: translateY(100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }";
            echo ".livres-container { animation: fadeIn 0.8s ease-in-out; }";
            echo ".table th { animation: slideInDown 0.6s ease-in-out; }";
            echo ".table td { animation: slideInUp 0.6s ease-in-out; }";
            echo "</style>";

            echo "<div class='container'>";
            echo "<h2>Liste des livres disponibles :</h2>";
            echo "<table class='table table-striped table-hover'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Titre</th>";
            echo "<th>Auteur</th>";
            echo "<th>Catégorie</th>";
            echo "<th>Stock</th>";
            echo "<th>Année de Parution</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = $resultat->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["IdLivre"] . "</td>";
                echo "<td>" . $row["Titre"] . "</td>";
                echo "<td>" . $row["Auteur"] . "</td>";
                echo "<td>" . $row["Catégorie"] . "</td>";
                echo "<td>" . $row["Stock"] . "</td>";
                echo "<td>" . $row["AnnéeDeParution"] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='container'>";
            echo "<p class='text-center'>Aucun livre n'est disponible actuellement.</p>";
            echo "</div>";
        }

        $connexion->close();
    ?>
</body>
</html>
