<?php
    session_start();
    include 'includes/header.php';
    include 'includes/config.php';
?>

<main>
    <?php
        // Votre logique de traitement ici
        if (isset($_SESSION['user'])) {
            $username = $_SESSION['user'];

            if (in_array($username, $allowedUsers)) {
                echo '<h1>Bienvenue sur la page admin, ' . $username . ' !</h1>';

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-ideas'])) {
                    $selectedIdeas = $_POST['selected-ideas'];

                    if (!empty($selectedIdeas)) {
                        foreach ($selectedIdeas as $ideaId) {
                            // Supprimer l'idée avec l'ID $ideaId
                            $deleteIdeaQuery = "DELETE FROM ideas WHERE id = $ideaId";
                            $conn->query($deleteIdeaQuery);
                        }

                        echo '<p>Les idées sélectionnées ont été supprimées avec succès.</p>';
                    } else {
                        echo '<p>Aucune idée sélectionnée à supprimer.</p>';
                    }
                }

                $getIdeasQuery = "SELECT * FROM ideas";
                $result = $conn->query($getIdeasQuery);

                if ($result && $result->num_rows > 0) {
                    echo '<form method="POST" action="">';
                    echo '<table>';
                    echo '<tr><th>ID</th><th>Username</th><th>Idée</th><th>Sélectionner</th></tr>';

                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['id'] . '</td>';
                        echo '<td>' . $row['username'] . '</td>';
                        echo '<td>' . $row['idea'] . '</td>';
                        echo '<td><input type="checkbox" name="selected-ideas[]" value="' . $row['id'] . '"></td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                    echo '<button class="button button-outline" type="submit" name="delete-ideas">Supprimer les idées sélectionnées</button>';

                    // Afficher le bouton d'exportation uniquement s'il y a des idées à exporter
                    if ($result->num_rows > 0) {
                        echo '<button class="button button-outline" type="button" onclick="exportToExcel()">Exporter les idées</button>';
                    }

                    echo '</form>';
                } else {
                    echo '<p>Aucune idée disponible.</p>';
                }
            } else {
                echo '<h1>Accès refusé</h1>';
                echo '<p>Vous n\'avez pas l\'autorisation d\'accéder à cette page.</p>';
            }
        } else {
            echo '<h1>Accès refusé</h1>';
            echo '<p>Connectez-vous pour accéder à cette page.</p>';
        }
    ?>
</main>

<script>
    function exportToExcel() {
        // Récupérer les données du tableau
        var ideasTable = document.getElementById('ideasTable');
        var ideasData = XLSX.utils.table_to_sheet(ideasTable);

        // Créer un classeur et ajouter la feuille de calcul avec les données
        var workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, ideasData, 'Idées');

        // Générer le fichier Excel
        var excelData = XLSX.write(workbook, { type: 'binary' });

        // Convertir les données binaires en tableau d'octets
        var byteArray = new Uint8Array(excelData.length);
        for (var i = 0; i < excelData.length; ++i) {
            byteArray[i] = excelData.charCodeAt(i) & 0xff;
        }

        // Créer un objet Blob à partir du tableau d'octets
        var blob = new Blob([byteArray], { type: 'application/octet-stream' });

        // Créer un lien de téléchargement
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'ideas.xlsx';
        link.click();
    }
</script>
