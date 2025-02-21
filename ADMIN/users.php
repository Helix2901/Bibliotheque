<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs</title>
    <style>
        /* Styles généraux */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        /* Styles pour l'en-tête */
        h2 { color: #333; font-size: 28px; margin-bottom: 20px; }
        /* Styles pour le tableau */
        .table { width: 100%; border-collapse: collapse; background-color: #fff; box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); }
        .table thead { background-color: #343a40; color: #fff; }
        .table th, .table td { padding: 15px; text-align: left; border-bottom: 1px solid #f2f2f2; }
        .table tr:hover { background-color: #f9f9f9; }
        /* Styles pour les messages d'erreur */
        .text-center { text-align: center; color: #666; font-size: 18px; margin-top: 40px; }
        /* Animations */
        @keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
        @keyframes slideInDown { 0% { transform: translateY(-100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        @keyframes slideInUp { 0% { transform: translateY(100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .utilisateurs-container { animation: fadeIn 0.8s ease-in-out; }
        .table th { animation: slideInDown 0.6s ease-in-out; }
        .table td { animation: slideInUp 0.6s ease-in-out; }
    </style>
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
        $requete = "SELECT * FROM Utilisateur WHERE Nom IS NOT NULL";

        // Exécution de la requête
        $resultat = $connexion->query($requete);

        // Vérification du résultat
        if ($resultat->num_rows > 0) {
            // Affichage des utilisateurs dans un tableau
            echo "<div class='container utilisateurs-container'>";
            echo "<h2>Liste des utilisateurs :</h2>";
            echo "<table class='table table-striped table-hover'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Nom</th>";
            echo "<th>Prénom</th>";
            echo "<th>Date de Naissance</th>";
            echo "<th>Adresse Mail</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = $resultat->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["NbUsager"] . "</td>";
                echo "<td>" . $row["Nom"] . "</td>";
                echo "<td>" . $row["Prenom"] . "</td>";
                echo "<td>" . $row["DateDeNaissance"] . "</td>";
                echo "<td>" . $row["AdresseMail"] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='container'>";
            echo "<p class='text-center'>Aucun utilisateur n'est disponible actuellement.</p>";
            echo "</div>";
        }

        $connexion->close();
    ?>
</body>
</html>
