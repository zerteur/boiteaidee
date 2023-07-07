<?php
    session_start(); // Démarre la session
    include 'includes/header.php'; // Inclusion du header.php contenant db.php pour la connexion à la base de données

    // Vérifie si l'utilisateur est déjà connecté, le redirige vers la page de profil
    if (isset($_SESSION['user'])) {
        header('Location: profile.php');
        exit();
    }

    // Vérifie si le formulaire de connexion a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Effectue la validation du formulaire et la vérification des informations de connexion

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Vérifie les informations de connexion dans la table des utilisateurs
        $checkUserQuery = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($checkUserQuery);

        if ($result->num_rows > 0) {
            // Authentification réussie, enregistre l'utilisateur dans la session
            $_SESSION['user'] = $username;
            header('Location: profile.php');
            exit();
        } else {
            // Authentification échouée, affiche un message d'erreur
            $error = "Identifiant ou mot de passe incorrect.";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>

    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" action="">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
