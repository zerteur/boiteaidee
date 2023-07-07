<?php
    session_start(); // Démarre la session
    include 'includes/header.php'; // Inclusion du header.php contenant db.php pour la connexion à la base de données

    // Vérifie si l'utilisateur est déjà connecté, le redirige vers la page de profil
    if (isset($_SESSION['user'])) {
        header('Location: profile.php');
        exit();
    }

    // Vérifie si le formulaire d'inscription a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Effectue la validation du formulaire et l'ajout de l'utilisateur à la base de données

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Vérifie si la table des utilisateurs existe, sinon la crée
        $createTableQuery = "CREATE TABLE IF NOT EXISTS users (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                username VARCHAR(255) NOT NULL,
                                email VARCHAR(255) NOT NULL,
                                password VARCHAR(255) NOT NULL
                            )";

        $conn->query($createTableQuery);

        // Vérifie si le nom d'utilisateur ou l'e-mail existent déjà dans la table
        $checkDuplicateQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($checkDuplicateQuery);

        if ($result->num_rows > 0) {
            $error = "Nom d'utilisateur ou adresse e-mail déjà utilisé.";
        } else {
            // Ajoute l'utilisateur à la table
            $insertUserQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            $conn->query($insertUserQuery);

            // Redirige vers la page de connexion après l'inscription réussie
            header('Location: login.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>

    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" action="">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Adresse e-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
