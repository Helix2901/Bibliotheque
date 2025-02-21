<?php
session_start();

if (!isset($_SESSION['user_name']) || !isset($_SESSION['num_id'])) {
    header('location: log_in.php');
    exit();
}

$idUtilisateur = $_SESSION['num_id'];

if (isset($_SESSION['panier'][$idUtilisateur])) {
    $panier = $_SESSION['panier'][$idUtilisateur];
} else {
    $panier = [];
}

// func aux pour recuperer les livres a partir de leurs id.
function getLivreById($idLivre) {
  // Connexion à la base de données
  $serveur = "localhost";
  $utilisateur = "root";
  $mot_de_passe = "";
  $base_de_donnees = "projetWeb";

  $connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

  if ($connexion->connect_error) {
      die("Connexion à la base de données échouée : " . $connexion->connect_error);
  }

  $stmt = $connexion->prepare("SELECT * FROM Livres WHERE IdLivre = ?");
  $stmt->bind_param("i", $idLivre);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $livre = $result->fetch_assoc();
      $stmt->close();
      $connexion->close();
      return $livre;
  } else {
      $stmt->close();
      $connexion->close();
      return null;
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User</title>
  <link rel="stylesheet" href="CSS/user.css">

</head>
<body>
    <div class="background-image"></div>
    <header>
        <h1>MY LIBRARY</h1>
        <nav>
          <ul>
            <li><a href="MesLivres.php">Mes Livres</a></li>
            <li><a href="#panier" id="panier-btn">Panier</a></li>
            <li><a id="deconnexion" href="../acceuil.html" > Déconnexion</a></li>
          </ul>
        </nav>
      </header>

    
      <section id="catalogue">
    <h2 style="text-align: center; margin-bottom: 20px; color: #333;">Catalogue des livres</h2>
    <form id="search-form" action="search.php" method="get" style="display: flex; justify-content: center; margin-bottom: 20px;">
      <input type="text" name="search-title" id="search-title" placeholder="Rechercher par titre..." style="padding: 10px; border: 1px solid #ccc; border-radius: 4px 0 0 4px; outline: none;">
      <button type="submit" style="padding: 10px 20px; background-color: #333; color: #fff; border: none; border-radius: 0 4px 4px 0; cursor: pointer;">Rechercher</button>
    </form>
    
    <div class="livres-container">
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
      $requete = "SELECT * FROM Livres WHERE Titre is not null";

      // Exécution de la requête
      $resultat = $connexion->query($requete);

      // Vérification du résultat
      if ($resultat->num_rows > 0) {
        // Affichage des livres dans un tableau
        echo "<h2>Liste des livres disponibles :</h2>";
        echo "<table class='table table-striped table-hover'>";
        echo "<thead class='thead-dark'>";
        echo "<tr>";
        echo "<th>Titre</th>";
        echo "<th>Auteur</th>";
        echo "<th>Catégorie</th>";
        echo "<th>Stock</th>";
        echo "<th>Année de Parution</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $resultat->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Titre"] . "</td>";
            echo "<td>" . $row["Auteur"] . "</td>";
            echo "<td>" . $row["Catégorie"] . "</td>";
            echo "<td>" . $row["Stock"] . "</td>";
            echo "<td>" . $row["AnnéeDeParution"] . "</td>";
            echo "<td><a href='ajouter_panier.php?id=" . $row["IdLivre"] . "&stock=" . $row["Stock"] . "' class='btn btn-primary'>+</a></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
      } else {
        echo "<script>alert('Aucun livre n'est disponible actuellement.');</script>";
      }

      $connexion->close();
      ?>
    </div>
  </section>


<div id="panier" class="full-page-section modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Votre panier</h2>
              <div id="panier-contenu">
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

if (count($panier) > 0) {
    echo "<style>";
    echo ".panier {";
    echo "    background-color: #f5f5f5;";
    echo "    padding: 20px;";
    echo "    border-radius: 5px;";
    echo "    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);";
    echo "}";
    echo ".panier h2 {";
    echo "    margin-top: 0;";
    echo "    font-size: 24px;";
    echo "    font-weight: bold;";
    echo "    color: black;";
    echo "}";
    echo ".table-panier {";
    echo "    width: 100%;";
    echo "    border-collapse: collapse;";
    echo "    background-color: #fff;";
    echo "    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);";
    echo "}";
    echo ".table-panier th,";
    echo ".table-panier td {";
    echo "    padding: 10px;";
    echo "    text-align: left;";
    echo "    border-bottom: 1px solid #f5f5f5;";
    echo "    color: black;";
    echo "}";
    echo ".table-panier th {";
    echo "    background-color: #f5f5f5;";
    echo "    font-weight: bold;";
    echo "}";
    echo ".btn-supprimer {";
    echo "    display: inline-block;";
    echo "    width: 30px;";
    echo "    height: 30px;";
    echo "    line-height: 30px;";
    echo "    text-align: center;";
    echo "    background-color: #dc3545;";
    echo "    color: #fff;";
    echo "    border-radius: 50%;";
    echo "    font-size: 16px;";
    echo "    font-weight: bold;";
    echo "    text-decoration: none;";
    echo "}";
    echo ".btn-supprimer:hover {";
    echo "    background-color: #c82333;";
    echo "}";
    echo ".panier-vide {";
    echo "    text-align: center;";
    echo "    padding: 20px;";
    echo "    background-color: #f5f5f5;";
    echo "    border-radius: 5px;";
    echo "    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);";
    echo "}";
    echo ".panier-vide h2 {";
    echo "    margin-top: 0;";
    echo "    font-size: 24px;";
    echo "    font-weight: bold;";
    echo "    color: black;";
    echo "}";
    echo "</style>";

    echo "<div class='panier'>";
    echo "<h2>Votre panier</h2>";
    echo "<table class='table-panier'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Titre</th>";
    echo "<th>Auteur</th>";
    echo "<th>Éditeur</th>";
    echo "<th>Catégorie</th>";
    echo "<th>Stock</th>";
    echo "<th>Année de Parution</th>";
    echo "<th>Action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($panier as $produitId) {
        $requete = "SELECT Titre, Auteur, Editeur, Catégorie, Stock, AnnéeDeParution FROM Livres WHERE IdLivre = ?";
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param("i", $produitId);
        $stmt->execute();
        $resultat = $stmt->get_result();
        $livre = $resultat->fetch_assoc();

        echo "<tr>";
        echo "<td>" . $livre["Titre"] . "</td>";
        echo "<td>" . $livre["Auteur"] . "</td>";
        echo "<td>" . $livre["Editeur"] . "</td>";
        echo "<td>" . $livre["Catégorie"] . "</td>";
        echo "<td>" . $livre["Stock"] . "</td>";
        echo "<td>" . $livre["AnnéeDeParution"] . "</td>";
        echo "<td><a href='supprimer_du_panier.php?id=" . $produitId . "' class='btn-supprimer'>&#10006;</a></td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
} else {
    echo "<style>";
    echo ".panier-vide {";
    echo "    text-align: center;";
    echo "    padding: 20px;";
    echo "    background-color: #f5f5f5;";
    echo "    border-radius: 5px;";
    echo "    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);";
    echo "}";
    echo ".panier-vide h2 {";
    echo "    margin-top: 0;";
    echo "    font-size: 24px;";
    echo "    font-weight: bold;";
    echo "    color: black;";
    echo "}";
    echo ".panier-vide p {";
    echo "    margin-bottom: 0;";
    echo "    color: black;";
    echo "}";
    echo "</style>";

    echo "<div class='panier-vide'>";
    echo "<h2>Votre panier est vide</h2>";
    echo "<p>Ajoutez des livres pour commencer vos achats.</p>";
    echo "</div>";
}

$connexion->close();
?>

              </div>
              <a href="emprunter_livre.php" class="btn-submit">Valider le panier</a>
            </div>
          </div>
    
          <script src="user.js"></script>

    </body>
    </html>
