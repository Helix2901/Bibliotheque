<!DOCTYPE <!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Log_in</title>
    <link rel="icon" type="image/x-icon" href="images/logo.svg" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="CSS/log_in_admin.css" rel="stylesheet" />
    <title>MY LIBRARY Log In Form</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,500;1,600&display=swap"
      rel="stylesheet"
    />
  </head>

  <body>
    <header>
      <h1>MY LIBRARY</h1>
      <nav>
        <ul>
          <li><a href="acceuil.html">Accueil</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
    </header>
    <div class="background-image"></div>
    <div class="wrapper">
      <form method="post">
        <h2>Log In</h2>
        <div class="input-box">
          <input type="text" placeholder="Username" name="usr" required />
        </div>
        <div class="input-box">
          <input type="password" placeholder="Password" name="psw" required />
        </div>
        <button type="submit" class="btn">Log In</button>
      </form>
      <?php
// Démarrage de la session
session_start();

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

// Vérification des données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST['usr'];
    $mot_de_passe = $_POST['psw'];

    // Vérification des entrées pour éviter les attaques
    $pseudo = htmlspecialchars($pseudo);
    $mot_de_passe = htmlspecialchars($mot_de_passe);

    // Requête pour vérifier les identifiants
    $requete = "SELECT * FROM Administrateurs WHERE Username = ? AND MotDePasse = ?";
    $statement = $connexion->prepare($requete);
    $statement->bind_param("ss", $pseudo, $mot_de_passe);
    $statement->execute();
    $resultat = $statement->get_result();

    if ($resultat->num_rows > 0) {
        // Identifiants corrects, créer une session
        $row = $resultat->fetch_assoc();
        $_SESSION['user_id'] = $row['MotDePasse'];
        $_SESSION['user_name'] = $row['Username'];
        header("Location: admin.html");
        exit();
    } else {
        // Identifiants incorrects, redirection vers la page de connexion avec un message d'erreur

        echo '<span style="color: red;">Mot de passe est incorrect!!</span>';
        
        
        exit();
    }

    $statement->close();
}

$connexion->close();
?>



    </div>
  </body>
</html>

