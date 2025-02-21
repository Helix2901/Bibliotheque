<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Livres</title>
    <link rel="stylesheet" href="CSS/meslivres.css">
    <style>
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-weight: bold;
        }
        .btn-green {
            background-color: green;
        }
        .btn-red {
            background-color: red;
        }
    </style>
</head>
<body>
<div class="background-image"></div>

<?php
    session_start();

    // Connexion à la base de données
    $serveur = "localhost";
    $utilisateur = "root";
    $mot_de_passe = "";
    $base_de_donnees = "projetWeb";

    $conn = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["action"]) && isset($_POST["idLivre"])) {
            $action = $_POST["action"];
            $idLivre = $_POST["idLivre"];
            $idUtilisateur = $_SESSION['num_id'];

            if ($action == "renouveler") {
                // Mettre à jour la date de fin de l'emprunt en ajoutant une semaine
                $sql = "UPDATE Emprunt SET DateFin = DATE_ADD(DateFin, INTERVAL 7 DAY) WHERE Usager = ? AND LivreEmprunté = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $idUtilisateur, $idLivre);
                $stmt->execute();
            } elseif ($action == "rendre") {
                // Supprimer l'emprunt de la base de données
                $sqlDelete = "DELETE FROM Emprunt WHERE Usager = ? AND LivreEmprunté = ?";
                $stmtDelete = $conn->prepare($sqlDelete);
                $stmtDelete->bind_param("ii", $idUtilisateur, $idLivre);
                $stmtDelete->execute();

                // Incrémenter le stock du livre rendu de 1
                $sqlUpdateStock = "UPDATE Livres SET Stock = Stock + 1 WHERE IdLivre = ?";
                $stmtUpdateStock = $conn->prepare($sqlUpdateStock);
                $stmtUpdateStock->bind_param("i", $idLivre);
                $stmtUpdateStock->execute();
            }
        }
    }

    // Récupérer les emprunts de l'utilisateur connecté
    $sql = "SELECT Titre, Auteur, Editeur, AnnéeDeParution, Catégorie, DateDebut, DateFin, IdLivre
            FROM Emprunt, Livres
            WHERE LivreEmprunté = IdLivre and Usager = ?";
    $idUtilisateur = $_SESSION['num_id'];
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUtilisateur);
    $stmt->execute();
    $result = $stmt->get_result();

    // Afficher les résultats
    if ($result->num_rows > 0) {
        echo "<div class='container'>";
        echo "<h2>Vos emprunts en cours :</h2>";
        echo "<table class='table table-striped table-hover'>";
        echo "<tr><th>Titre</th><th>Auteur</th><th>Éditeur</th><th>Année de parution</th><th>Catégorie</th><th>Date de début</th><th>Date de fin</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Titre"] . "</td>";
            echo "<td>" . $row["Auteur"] . "</td>";
            echo "<td>" . $row["Editeur"] . "</td>";
            echo "<td>" . $row["AnnéeDeParution"] . "</td>";
            echo "<td>" . $row["Catégorie"] . "</td>";
            echo "<td>" . $row["DateDebut"] . "</td>";
            echo "<td>" . $row["DateFin"] . "</td>";
            echo "<td>";
            echo "<button class='btn btn-green' onclick='renouveler(" . $row["IdLivre"] . ")'>Renouveler</button>";
            echo "<button class='btn btn-red' onclick='rendre(" . $row["IdLivre"] . ")'>Rendre</button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='container'>";
        echo "<p class='text-center'>Vous n'avez pas d'emprunts en cours.</p>";
        echo "</div>";
    }
    $stmt->close();
    $conn->close();
?>

<script>
    function renouveler(idLivre) {
        if (confirm("Voulez-vous renouveler cet emprunt ?")) {
            // Envoyer la requête AJAX pour renouveler
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    location.reload(); // Recharger la page après la mise à jour
                }
            };
            xhr.send("action=renouveler&idLivre=" + idLivre);
        }
    }

    function rendre(idLivre) {
        if (confirm("Voulez-vous rendre ce livre ?")) {
            // Envoyer la requête AJAX pour rendre
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    location.reload(); // Recharger la page après la mise à jour
                }
            };
            xhr.send("action=rendre&idLivre=" + idLivre);
        }
    }
</script>
</body>
</html>
