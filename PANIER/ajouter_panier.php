<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ajout-exeption</title>
    </head>
    <body>
        <?php
        session_start();

        // Fonction pour vérifier si le livre est déjà dans le panier
        function verifieSiLivrePasDejaPris($IDBook, $idUtilisateur) {
            if (isset($_SESSION['panier'][$idUtilisateur])) {
                foreach ($_SESSION['panier'][$idUtilisateur] as $livreId) {
                    if ($livreId === $IDBook) {
                        return true;
                    }
                }
            }
            return false;
        }

        if (!isset($_SESSION['user_name']) || !isset($_SESSION['num_id'])) {
            header('location: log_in.php');
            exit();
        }

        $id = $_GET['id'];
        $stock = $_GET['stock'];
        $idUtilisateur = $_SESSION['num_id'];

        if (!isset($_SESSION['panier'][$idUtilisateur])) {
            $_SESSION['panier'][$idUtilisateur] = [];
        }

        if (!verifieSiLivrePasDejaPris($id, $idUtilisateur)) {
            if ($stock > 0) {
                $_SESSION['panier'][$idUtilisateur][] = $id;
            } else {
                echo "<script>alert('Stock insuffisant');</script>";
            }
        } else {
            echo "<script>alert('Livre déjà pris !!');</script>";
        }

        header('location: user.php');
        exit();
        ?>
    </body>
</html>
