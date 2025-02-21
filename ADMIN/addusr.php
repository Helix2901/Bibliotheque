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
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $dateNaissance = $_POST['date_naissance'];
    $email = $_POST['email'];

    // Vérifier si l'utilisateur existe déjà
    $requeteCheck = "SELECT COUNT(*) FROM Utilisateur WHERE AdresseMail = ?";
    $statementCheck = $connexion->prepare($requeteCheck);
    $statementCheck->bind_param("s", $email);
    $statementCheck->execute();
    $statementCheck->bind_result($count);
    $statementCheck->fetch();
    $statementCheck->close();

    if ($count > 0) {
        $connexion->close();
        echo '<script>
        alert("Un utilisateur avec la même adresse mail éxiste déja dans la base de données");
        window.location.href = "admin.html";
      </script>';
    } else {
        // Préparation de la requête SQL
        $requete = "INSERT INTO Utilisateur (Nom, Prenom, DateDeNaissance, AdresseMail) VALUES (?, ?, ?, ?)";
        $statement = $connexion->prepare($requete);
        $statement->bind_param("ssss", $nom, $prenom, $dateNaissance, $email);

        // Exécution de la requête
        if ($statement->execute()) {
            echo '<span style="color: green;">Utilisateur ajouté avec succès !</span>';
        } else {
            echo '<span style="color: red;">Erreur lors de l\'ajout de l\'utilisateur : ' . $connexion->error . '</span>';
        }
        $statement->close();
    }
}

$connexion->close();
header('Location: admin.html');
exit();
?>
