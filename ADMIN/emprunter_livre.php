<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>emprunt-exeption</title>
</head>
<body>
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
                var_dump($sql);
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $valeur);

                // Exécuter la requête de mise à jour du stock
                if ($stmt->execute()) {
                    echo "<script>alert('Emprunt et mise à jour du stock réussis !');</script>";
                } else {
                    echo "<script>alert('Erreur lors de la mise à jour du stock : " . $stmt->error . "');</script>";
                }
            } else {
                echo "<script>alert('Erreur lors de l'enregistrement de l'emprunt : " . $stmt->error . "');</script>";
            }

            // Closing
            $stmt->close();
        }
        $conn->close();
    } else {
        echo "<script>alert('Erreur de SESSION!!');</script>";
    }

    header('location: user.php');
    exit();
?>
    
    </body>
</html>
