<?php
    session_start(); // Démarre la session
    include 'includes/header.php'; // Inclusion du fichier header.php pour l'en-tête du site

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

        // Compte le nombre d'idées soumises par l'utilisateur actuel
        $countIdeasQuery = "SELECT COUNT(*) AS total FROM ideas WHERE username = '$username'";
        $countResult = $conn->query($countIdeasQuery);
        $countData = $countResult->fetch_assoc();
        $totalIdeas = $countData['total'];
    } else {
        // Erreur, utilisateur introuvable
        $error = "Erreur lors de la récupération des informations de l'utilisateur.";
    }
?>


    <style>
        /* Ajoutez vos styles personnalisés ici */
        .profile-info {
            margin-bottom: 10px;
        }

        .logout-link {
            display: inline-block;
            margin-top: 20px;
            color: #ff4949;
            text-decoration: none;
            border: 1px solid #ff4949;
            padding: 10px 20px;
            border-radius: 4px;
        }

        .logout-link:hover {
            background-color: #ff4949;
            color: #fff;
        }
    </style>
</head>
<body>


    <div class="container">
        <h1>Profil de <?php echo $user['username']; ?></h1>

        <?php if (isset($error)) { ?>
            <p><?php echo $error; ?></p>
        <?php } ?>

        <div class="profile-info">
            <strong>Nom d'utilisateur :</strong> <?php echo $user['username']; ?>
        </div>
        <div class="profile-info">
            <strong>Adresse e-mail :</strong> <?php echo $user['email']; ?>
        </div>
        <div class="profile-info">
            <strong>Nombre d'idées soumises :</strong> <?php echo $totalIdeas; ?>
        </div>

        <a class="logout-link" href="logout.php">Déconnexion</a>
    </div>

    <!-- Incluez le contenu du pied de page du site -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
