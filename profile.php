<?php
    session_start(); // Démarre la session
    include 'includes/header.php'; // Inclusion du header.php contenant db.php pour la connexion à la base de données

    // Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }

    // Récupère les informations de l'utilisateur connecté depuis la base de données
    $username = $_SESSION['user'];
    $getUserQuery = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($getUserQuery);

    if ($result->num_rows > 0) {
        // Utilisateur trouvé, récupère les informations
        $user = $result->fetch_assoc();
    } else {
        // Erreur, utilisateur introuvable
        $error = "Erreur lors de la récupération des informations de l'utilisateur.";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
</head>
<body>
    <h1>Profil de <?php echo $user['username']; ?></h1>

    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>

    <p>Nom d'utilisateur : <?php echo $user['username']; ?></p>
    <p>Adresse e-mail : <?php echo $user['email']; ?></p>

    <a href="logout.php">Déconnexion</a>
</body>
</html>
