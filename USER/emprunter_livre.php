<?php
session_start();

$idUtilisateur = $_SESSION['num_id'];
$livresEmpruntes = $_SESSION['panier'][$idUtilisateur];

if (isset($livresEmpruntes)) {

    // Connexion à la base de données
    $serveur = "localhost";
    $utilisateur = "root";
    $mot_de_passe = "";
    $base_de_donnees = "projetWeb";

    // Établir la connexion
    $conn = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    foreach ($livresEmpruntes as $valeur) {
        // Vérifier si le livre est déjà emprunté par l'utilisateur
        $sqlCheck = "SELECT COUNT(*) FROM Emprunt WHERE LivreEmprunté = ? AND Usager = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $valeur, $idUtilisateur);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            unset($_SESSION['panier'][$idUtilisateur]);
            $_SESSION['panier'][$idUtilisateur] = [];
            $conn->close();
            echo '<script>
            alert("Vous avez déjà emprunté le livre.");
            window.location.href = "user.php"; 
          </script>';
        }

        // Récupérer la date actuelle
        $dateDebut = date('Y-m-d');

        // Calculer la date de fin.
        $dateFin = date('Y-m-d', strtotime('+15 days'));

        // Préparer la requête SQL pour insérer l'emprunt
        $sql = "INSERT INTO Emprunt (LivreEmprunté, Usager, DateDebut, DateFin) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $valeur, $idUtilisateur, $dateDebut, $dateFin);
        
        // Exécuter la requête d'insertion dans Emprunt
        if ($stmt->execute()) {
            // Préparer la requête SQL pour mettre à jour le stock dans Livres
            $sql = "UPDATE Livres SET Stock = Stock - 1 WHERE IdLivre = ?";
            $stmt1 = $conn->prepare($sql);
            $stmt1->bind_param("i", $valeur);
            // Exécuter la requête de mise à jour du stock
            $stmt1->execute();
            echo '<script>alert("Erreur lors de la mise à jour du stock : ' . $stmt->error . '");</script>';
            $stmt1->close();
        }
        // Closing
        $stmt->close();
    }

    // Vider le panier après avoir validé la commande
    unset($_SESSION['panier'][$idUtilisateur]);
    $_SESSION['panier'][$idUtilisateur] = [];

    $conn->close();
} else {
    echo "<script>alert('Erreur de SESSION!!');</script>";
}

header('location: user.php');
exit();
?>
