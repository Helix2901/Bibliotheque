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
    // Récupération de l'ID de l'utilisateur à supprimer
    $id = $_POST['user_id'];

    // Vérifier si l'utilisateur existe
    $requeteCheck = "SELECT COUNT(*) FROM Utilisateur WHERE NbUsager = ?";
    $statementCheck = $connexion->prepare($requeteCheck);
    $statementCheck->bind_param("i", $id);
    $statementCheck->execute();
    $statementCheck->bind_result($count);
    $statementCheck->fetch();
    $statementCheck->close();


    if ($count == 0) {
        $connexion->close();
        echo '<script>
        alert("L\'utilisateur avec cet ID n\'existe pas");
        window.location.href = "admin.html";
      </script>';
    } else {
        // Préparation de la requête SQL pour supprimer l'utilisateur
        $requete = "DELETE FROM Utilisateur WHERE NbUsager = ?";
        $statement = $connexion->prepare($requete);
        $statement->bind_param("i", $id);

        // Exécution de la requête
        if ($statement->execute()) {
            echo '<span style="color: green;">Utilisateur supprimé avec succès !</span>';
        } else {
            echo '<span style="color: red;">Erreur lors de la suppression de l\'utilisateur : ' . $connexion->error . '</span>';
        }

        $statement->close();
    }
}

$connexion->close();
header('Location: admin.html');
exit();
?>
