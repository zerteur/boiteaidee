<?php
    session_start(); // Démarre la session
    include 'includes/header.php'; // Inclusion de l'en-tête du site
?>

<style>
    body {
        background-color: #f8f8f8;
    }

    .container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 30px;
    }

    .idea {
        background-color: #f2f2f2;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }
</style>

<div class="container">
    <h1 class="text-center">Bienvenue sur mon site</h1>

    <?php if (isset($_SESSION['user'])): ?>
        <div class="form-container">
            <h2 class="text-center">Partagez vos idées</h2>
            <form class="form" method="POST" action="">
                <div class="form-group">
                    <input class="input full-width" type="text" name="idea" placeholder="Saisissez votre idée" required>
                </div>
                <div class="form-group">
                    <button class="button-primary" type="submit">Soumettre</button>
                </div>
            </form>

            <?php
                // Traitement du formulaire d'idée soumise
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $idea = $_POST['idea'];
                    $date = date('Y-m-d'); // Date actuelle

                    // Création de la table ideas si elle n'existe pas
                    $createTableQuery = "CREATE TABLE IF NOT EXISTS ideas (
                                            id INT AUTO_INCREMENT PRIMARY KEY,
                                            idea VARCHAR(255) NOT NULL,
                                            date DATE NOT NULL,
                                            username VARCHAR(255) NOT NULL
                                        )";
                    $conn->query($createTableQuery);

                    // Enregistrement de l'idée dans la base de données
                    $insertIdeaQuery = "INSERT INTO ideas (idea, date, username) VALUES ('$idea', '$date', '$username')";
                    $conn->query($insertIdeaQuery);
                }
            ?>
        </div>
    <?php else: ?>
        <p class="text-center">Connectez-vous pour soumettre une idée et accéder à plus de fonctionnalités.</p>
    <?php endif; ?>

    <div class="content">
        <h2 class="text-center">Dernières idées</h2>
        <?php
            // Vérification de l'existence de la table ideas
            $checkTableQuery = "SHOW TABLES LIKE 'ideas'";
            $tableResult = $conn->query($checkTableQuery);

            if ($tableResult->num_rows == 0) {
                echo '<p class="text-center">Aucune idée disponible pour le moment.</p>';
            } else {
                // Récupération des dernières idées depuis la base de données
                $getIdeasQuery = "SELECT date, idea FROM ideas ORDER BY date DESC";
                $result = $conn->query($getIdeasQuery);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="idea">';
                        echo '<p class="date">' . $row['date'] . '</p>';
                        echo '<p class="idea-text">' . $row['idea'] . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-center">Aucune idée disponible pour le moment.</p>';
                }
            }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; // Inclusion du pied de page du site ?>
