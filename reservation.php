<?php
include 'config.php';

if ($_POST) {
    $id_evenement = $_POST['id_evenement'];
    $nom_complet = $_POST['nom_complet'];
    $email = $_POST['email'];
    $quantite = $_POST['quantite'];
    
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Format d'email invalide");
    }

    try {
        // Récupération des infos de l'événement
        $stmt = $pdo->prepare("SELECT prix, titre FROM Evenement WHERE id_evenement = ?");
        $stmt->execute([$id_evenement]);
        $event = $stmt->fetch();
        
        if (!$event) {
            die("Événement non trouvé");
        }
        
        $prix_personne = $event['prix'];
        $prix_total = $prix_personne * $quantite;
        $code_unique = 'TK-' . strtoupper(uniqid());
        
        // Insertion du ticket
        $stmt = $pdo->prepare("
            INSERT INTO Ticket (code_unique, nom_complet, email, quantite, prix_personne, prix_total, date_reservation, id_evenement) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)
        ");
        
        $stmt->execute([
            $code_unique,
            $nom_complet,
            $email,
            $quantite,
            $prix_personne,
            $prix_total,
            $id_evenement
        ]);
        
        // Redirection vers page de confirmation
        header("Location: merci.php?code=" . urlencode($code_unique));
        exit;
        
    } catch (PDOException $e) {
        die("Erreur lors de la réservation : " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>
