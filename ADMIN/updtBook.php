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
    // Récupération des données du formulaire
    $id = $_POST['IdLivre'];
    $nouveauTitre = $_POST['new-book-title'];
    $nouveauAuteur = $_POST['new-book-author'];
    $nouvelleCategorie = $_POST['new-book-category'];
    $nouvelleQuantite = $_POST['new-book-quantity'];
    $nouvelledatedeparution = $_POST['new-book-date'];

    // Vérifier si le livre existe
    $requeteCheck = "SELECT COUNT(*) FROM Livres WHERE IdLivre = ?";
    $statementCheck = $connexion->prepare($requeteCheck);
    $statementCheck->bind_param("i", $id);
    $statementCheck->execute();
    $statementCheck->bind_result($count);
    $statementCheck->fetch();
    $statementCheck->close();

    if ($count == 0) {
        $connexion->close();
        echo '<script>
        alert("Le livre avec cet ID n\'existe pas");
        window.location.href = "admin.html";
      </script>';
    }  else {
        // Préparation de la requête SQL pour mettre à jour le livre
        $requete = "UPDATE Livres
                    SET Titre = ?, Auteur = ?, Catégorie = ?, Stock = ?, AnnéeDeParution = ?
                    WHERE IdLivre = ?";
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param("sssiii", $nouveauTitre, $nouveauAuteur, $nouvelleCategorie, $nouvelleQuantite, $nouvelledatedeparution, $id);

        // Exécution de la requête
        if ($stmt->execute()) {
            echo "Le livre a été mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du livre : " . $connexion->error;
        }

        $stmt->close();
    }
}

$connexion->close();
header('Location: admin.html');
exit();
?>
