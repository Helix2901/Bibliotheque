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

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération de l'ID du livre à supprimer
    $id = $_POST['IdLivre'];

    // Vérifier si le livre existe
    $requeteCheck = "SELECT COUNT(*) FROM Livres WHERE IdLivre = ?";
    $stmtCheck = $connexion->prepare($requeteCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count == 0) {
        $connexion->close();
        echo '<script>
        alert("Le livre avec cet ID n\'existe pas");
        window.location.href = "admin.html";
      </script>';
    }
         else {
        // Préparation de la requête SQL pour supprimer le livre
        $requete = "DELETE FROM Livres WHERE IdLivre = ?";
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param("i", $id);

        // Exécution de la requête
        if ($stmt->execute()) {
            echo "Le livre a été supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression du livre : " . $connexion->error;
        }

        $stmt->close();
    }
}

$connexion->close();
header('Location: admin.html');
exit();
?>
