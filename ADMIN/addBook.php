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
    $titre = $_POST['book-title'];
    $auteur = $_POST['book-author'];
    $editeur = $_POST['book-editor'];
    $anneeDeParution = $_POST['book-publication-year'];
    $categorie = $_POST['book-category'];
    $quantite = $_POST['book-quantity'];

    // Vérifier si le livre existe déjà
    $requeteCheck = "SELECT COUNT(*) FROM Livres WHERE Titre = ? AND Auteur = ?";
    $statementCheck = $connexion->prepare($requeteCheck);
    $statementCheck->bind_param("ss", $titre, $auteur);
    $statementCheck->execute();
    $statementCheck->bind_result($count);
    $statementCheck->fetch();
    $statementCheck->close();

    if ($count > 0) {
      $connexion->close();
      echo '<script>
      alert("Un livre avec le même titre et meme auteur éxiste déja dans la base de données");
      window.location.href = "admin.html";
    </script>';
  }else {
        // Préparation de la requête SQL pour insérer un nouveau livre
        $requete = "INSERT INTO Livres (Titre, Auteur, Editeur, AnnéeDeParution, Catégorie, Stock) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $connexion->prepare($requete);
        $statement->bind_param("sssisi", $titre, $auteur, $editeur, $anneeDeParution, $categorie, $quantite);

        // Exécution de la requête
        if ($statement->execute()) {
            echo '<span style="color: green;">Livre ajouté avec succès !</span>';
        } else {
            echo '<span style="color: red;">Erreur lors de l\'ajout du livre : ' . $connexion->error . '</span>';
        }

        $statement->close();
    }
}

$connexion->close();
header('Location: admin.html');
exit();
?>
