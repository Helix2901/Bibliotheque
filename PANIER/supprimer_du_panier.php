<?php
    session_start();

    $livreId = $_GET['id'];
    $idUtilisateur = $_SESSION['num_id'];

    if (isset($_SESSION['panier'][$idUtilisateur])) {
        $tabSupp = $_SESSION['panier'][$idUtilisateur];
        // func aux pour supprimer un livre du panier. 
        function supprimeLivreDePanier($tab, $livreSupp) {
            for($i = 0; $i < count($tab); $i += 1) {
                if ($tab[$i] == $livreSupp) {
                    var_dump($tab);
                    array_splice($tab, $i, 1);
                    var_dump($tab);
                    break;
                }
            }
            return $tab;
        }

        $_SESSION['panier'][$idUtilisateur] = supprimeLivreDePanier($_SESSION['panier'][$idUtilisateur], $livreId);
        //var_dump($_SESSION['panier'][$idUtilisateur]);
    } else {
        echo "<script>alert('Erreur de SESSION!!');</script>";
    }
    
    header('location: user.php');
    exit();
?>