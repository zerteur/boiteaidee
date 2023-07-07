<?php
    session_start();
    include 'includes/header.php';
    include 'includes/config.php';
?>

<!-- Utilisation de la version 0.20.0 de SheetJS -->
<script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>

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

            if (isset($_POST['export-ideas'])) {
                $getIdeasQuery = "SELECT * FROM ideas";
                $result = $conn->query($getIdeasQuery);

                if ($result && $result->num_rows > 0) {
                    $ideasData = [];

                    while ($row = $result->fetch_assoc()) {
                        $ideasData[] = [
                            'ID' => $row['id'],
                            'Username' => $row['username'],
                            'Idée' => strip_tags($row['idea']) // Supprimer les balises HTML
                        ];
                    }

                    // Convertir les données des idées en JSON pour le transfert au JavaScript
                    $ideasJson = json_encode($ideasData);
                    echo '<script>var ideasData = ' . $ideasJson . ';</script>';
                } else {
                    echo '<p>Aucune idée disponible pour l\'export.</p>';
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
                echo '<button type="submit" name="delete-ideas">Supprimer les idées sélectionnées</button>';

                // Afficher le bouton d'exportation uniquement s'il y a des idées à exporter
                if ($result->num_rows > 0) {
                    echo '<button type="button" onclick="exportToExcel()">Exporter les idées</button>';
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

    include 'includes/footer.php';
?>

<script>
    function exportToExcel() {
        // Créer un nouveau classeur Excel
        var workbook = XLSX.utils.book_new();

        // Créer une feuille de calcul
        var worksheet = XLSX.utils.json_to_sheet(ideasData);

        // Ajouter la feuille de calcul au classeur
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Idées');

        // Générer les données binaires du fichier Excel
        var excelData = XLSX.write(workbook, { type: 'binary', bookType: 'xlsx' });

        // Convertir les données binaires en tableau d'octets
        var excelByteArray = s2ab(excelData);

        // Créer un objet Blob à partir du tableau d'octets
        var blob = new Blob([excelByteArray], { type: 'application/octet-stream' });

        // Créer un objet URL à partir du blob
        var url = URL.createObjectURL(blob);

        // Créer un lien de téléchargement
        var link = document.createElement('a');
        link.href = url;
        link.download = 'exported-ideas.xlsx';
        link.click();

        // Nettoyer l'URL de l'objet Blob
        URL.revokeObjectURL(url);
    }

    // Fonction pour convertir les données binaires en tableau d'octets
    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) {
            view[i] = s.charCodeAt(i) & 0xFF;
        }
        return buf;
    }
</script>
