<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma boîte à idées</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/milligram/dist/milligram.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <style>
        header {
            padding: 10px;
            background-color: #f8f8f8;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            position: relative;
            margin-right: 10px;
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #000;
        }

        nav .menu-icon {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .menu-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 4px;
            z-index: 99;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .menu-dropdown a {
            display: block;
            margin-bottom: 5px;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .show .menu-dropdown {
            opacity: 1;
            pointer-events: auto;
        }

        .show .menu-dropdown a {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            nav a {
                display: none;
            }

            nav .menu-icon {
                display: block;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php"><i class="las la-home"></i> Accueil</a>
            <?php
                include 'includes/config.php';
                include 'includes/db.php';

                if (isset($_SESSION['user'])) {
                    $username = $_SESSION['user'];
                    if (in_array($username, $allowedUsers)) {
                        echo '<a href="admin.php"><i class="las la-cog"></i> Admin</a>';
                    }
                    echo '<a href="profile.php"><i class="las la-user"></i> Profil</a>';
                    echo '<a href="logout.php"><i class="las la-power-off"></i> Déconnexion</a>';
                } else {
                    echo '<a href="login.php"><i class="las la-sign-in-alt"></i> Connexion</a>';
                    echo '<a href="register.php"><i class="las la-user-plus"></i> Inscription</a>';
                }
            ?>

            <div class="menu-icon">
                <i class="las la-bars"></i>
                <?php if (!isset($_SESSION['user'])): ?>
                    <div class="menu-dropdown">
                        <a href="login.php"><i class="las la-sign-in-alt"></i> Connexion</a>
                        <a href="register.php"><i class="las la-user-plus"></i> Inscription</a>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var menuIcon = document.querySelector('.menu-icon');
            var menuDropdown = document.querySelector('.menu-dropdown');

            menuIcon.addEventListener('click', function () {
                menuIcon.classList.toggle('show');
                menuDropdown.classList.toggle('show');
            });
        });
    </script>
</body>
</html>
