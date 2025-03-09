<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Navigation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #0066cc;
            --accent-color: #66b3ff;
            --text-color: #2c3e50;
            --bg-color: #f8f9fa;
            --hover-color: #f0f4f8;
            --border-radius: 12px;
            --nav-height: 70px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
        }

        .sidebar {
            width: 280px;
            background: white;
            padding: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            color: var(--text-color);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
        }

        .sidebar-header {
            height: var(--nav-height);
            display: flex;
            align-items: center;
            padding: 0 24px;
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .sidebar-content {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
        }

        .menu-items {
            margin-bottom: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            height: 100%;
        }

        .logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: var(--border-radius);
            background: transparent;
            padding: 0;
            transition: var(--transition);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .logo img:hover {
            transform: scale(1.05) rotate(-3deg);
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.15));
        }

        .logo-text {
            font-size: 26px;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .sidebar a {
            text-decoration: none;
            color: var(--text-color);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            border-radius: var(--border-radius);
            margin-bottom: 8px;
            transition: var(--transition);
            position: relative;
            font-weight: 500;
        }

        .sidebar a:hover,
        .sidebar .active {
            background: var(--hover-color);
            transform: translateX(5px);
            color: var(--primary-color);
            box-shadow: var(--shadow-sm);
        }

        .sidebar i {
            margin-right: 12px;
            font-size: 1.2em;
            min-width: 25px;
            text-align: center;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .sidebar a:hover i {
            transform: scale(1.1);
            color: var(--secondary-color);
        }

        .submenu,
        .sub-submenu,
        .sub-sub-submenu {
            display: none;
            flex-direction: column;
            padding-left: 20px;
            margin: 5px 0;
            position: relative;
        }

        .submenu::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            height: 100%;
            width: 2px;
            background: linear-gradient(to bottom, var(--hover-color) 0%, var(--accent-color) 100%);
            border-radius: 1px;
        }

        .submenu a,
        .sub-submenu a,
        .sub-sub-submenu a {
            font-size: 0.95em;
            padding: 12px 16px;
            opacity: 0.9;
        }

        .user-section {
            padding: 16px;
            background: var(--hover-color);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: var(--transition);
            color: var(--text-color);
            margin: 24px;
            box-shadow: var(--shadow-sm);
        }

        .user-section:hover {
            background: var(--bg-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: var(--border-radius);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            color: var(--primary-color);
            transition: var(--transition);
            border: 2px solid var(--hover-color);
            box-shadow: var(--shadow-sm);
        }

        .navbar {
            width: calc(100% - 280px);
            height: var(--nav-height);
            background: white;
            box-shadow: var(--shadow-lg);
            padding: 0 24px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            position: fixed;
            top: 0;
            left: 280px;
            right: 0;
            z-index: 999;
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: var(--hover-color);
            transition: var(--transition);
            position: relative;
        }

        .small-text {
            font-size: 14px;
            /* Réduit la taille de la police */
            font-weight: normal;
            /* Évite qu'il soit en gras */
            color: #555;
            /* Optionnel : une couleur plus douce */
        }

        .icon-wrapper:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: var(--shadow-md);
            background: var(--hover-color);
        }

        .icon-wrapper i {
            font-size: 1.2em;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .icon-wrapper:hover i {
            color: var(--secondary-color);
            transform: scale(1.1);
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ff3366;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: var(--shadow-sm);
            animation: pulse 2s infinite;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 20px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            padding: 10px;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid var(--hover-color);
            transition: var(--transition);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: var(--hover-color);
        }

        .no-notifications {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            padding: 20px;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 51, 102, 0.4);
            }

            70% {
                transform: scale(1.1);
                box-shadow: 0 0 0 10px rgba(255, 51, 102, 0);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 51, 102, 0);
            }
        }

        .content {
            margin-top: var(--nav-height);
            margin-left: 280px;
            padding: 30px;
            flex-grow: 1;
            background: var(--bg-color);
            min-height: calc(100vh - var(--nav-height));
        }

        /* Smooth slide animation for submenus */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .submenu.show,
        .sub-submenu.show,
        .sub-sub-submenu.show {
            animation: slideDown 0.3s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
            }

            .sidebar-header {
                padding: 0 16px;
            }

            .sidebar-content {
                padding: 16px;
            }

            .navbar {
                left: 80px;
                width: calc(100% - 80px);
            }

            .content {
                margin-left: 80px;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-color: #1a1a1a;
                --text-color: #e0e0e0;
                --hover-color: #2a2a2a;
            }
        }

        /* Remove search-related styles */
        .nav-left,
        .nav-right,
        .nav-search {
            display: none;
        }

        /* Base styles */
        body {
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(249, 250, 251);
            min-height: 100vh;
        }

        .container {
            max-width: 48rem;
            margin: 0 auto;
            padding: 3rem 1rem;
        }

        .form-card {
            background-color: white;
            padding: 2rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .title {
            text-align: center;
            /* Centrer le texte */
            color: #124170;
            /* Bleu foncé */
            font-size: 28px;
            /* Agrandir le texte */
            font-weight: bold;
            /* Texte en gras */
            text-transform: uppercase;
            /* Majuscules */
            letter-spacing: 1px;
            /* Espacement entre lettres */
            margin-bottom: 20px;
            /* Espacement sous le titre */
            padding-bottom: 5px;
            /* Espacement sous le titre */
            display: block;
            /* Empêcher toute ligne latérale */
            width: fit-content;
            /* Ajuster la largeur au texte */
            margin-left: auto;
            margin-right: auto;
            /* Centrer le bloc */
        }



        .section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 500;
            color: #5c75ac;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group-row {
            display: flex;
            gap: 20px;
            /* Espacement entre les champs */
        }

        .form-group-row .form-group {
            flex: 1;
            /* Permet aux champs de prendre un espace égal */
        }

        .form-group-row {
            display: flex;
            gap: 20px;
            /* Espacement entre les champs */
        }

        .form-group-row .form-group {
            flex: 1;
            /* Permet aux champs de prendre un espace égal */
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.25rem;
        }

        .form-input {
            width: 50%;
            padding: 0.5rem 0.75rem;
            padding-left: 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .success-message {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1.5rem;
            background-color: #ecfdf5;
            color: #047857;
            border-radius: 0.375rem;
            animation: fadeIn 0.3s ease-out;
        }

        .buttons-container {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            /* Espacement entre les boutons */
        }

        .button-primary {
            background-color: white;
            /* Fond blanc */
            color: #003366;
            /* Texte bleu foncé */
            font-size: 16px;
            font-weight: bold;
            padding: 12px 20px;
            border: 2px solid #003366;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            /* Espacement entre l'icône et le texte */
        }

        .button-primary:hover {
            background-color: #003366;
            color: white;
            transform: scale(1.05);
        }

        .button-primary:active {
            background-color: #002244;
            color: white;
            transform: scale(0.98);
        }

        .button-secondary {
            background-color: white;
            color: #444;
            font-size: 16px;
            font-weight: bold;
            padding: 12px 20px;
            border: 2px solid #444;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .button-secondary:hover {
            background-color: #444;
            color: white;
            transform: scale(1.05);
        }

        .button-secondary:active {
            background-color: #222;
            transform: scale(0.98);
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-0.5rem);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Styles pour le menu déroulant de messagerie */
        .messenger-dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 20px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            width: 350px;
            max-height: 500px;
            overflow-y: auto;
            z-index: 1000;
            padding: 10px;
        }

        .messenger-dropdown.show {
            display: block;
        }

        .messenger-header {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            padding: 10px;
            border-bottom: 1px solid var(--hover-color);
        }

        .messenger-body {
            padding: 10px;
        }

        .contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid var(--hover-color);
            cursor: pointer;
            transition: var(--transition);
        }

        .contact-item:hover {
            background-color: var(--hover-color);
        }

        .contact-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .contact-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-color);
        }

        .messenger-footer {
            padding: 10px;
            border-top: 1px solid var(--hover-color);
        }

        .messenger-footer input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .messenger-footer input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
        }

        /* Styles pour le message "Aucun message" */
        .no-messages {
            text-align: center;
            padding: 20px;
            color: #64748b;
            font-size: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .no-messages i {
            font-size: 24px;
            color: var(--accent-color);
        }

        .no-messages p {
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Navigation Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="logo_djazairRH.jpg" alt="DjazairRH   Logo">
                <span class="logo-text">DjazairRH</span>
            </div>
        </div>
        <div class="sidebar-content">
            <div class="menu-items">
                <div class="nav-title">Principal</div>
                <a href="<?= url('/') ?>" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Accueil</span>
                </a>
                <a href="<?= url('profiles') ?>" class="menu-item active">
                    <i class="fas fa-user-circle"></i>
                    <span class="menu-text">Mon Profil</span>
                </a>
                <div class="nav-title">Demandes</div>
                <div class="request-section">
                    <a href="#" class="menu-item" id="faireDemandeBtn">
                        <i class="fas fa-file-alt"></i>
                        <span class="menu-text">Faire une demande</span>
                    </a>
                    <div class="submenu" id="demandeSubmenu" style="display: none;">
                        <div>
                            <a href="#" class="menu-item" id="congeBtn">
                                <i class="fas fa-calendar-alt"></i>
                                <span class="menu-text">Demande Congé</span>
                            </a>
                            <div class="submenu" id="congeSubmenu" style="display: none;">
                                <a href="<?= url('profiles/drh/demands/conge/annual.php') ?>" class="menu-item">
                                    <i class="fas fa-sun"></i>
                                    <span class="menu-text">Congé Annuel</span>
                                </a>
                                <a href="<?= url('profiles/drh/demands/conge/malady.php') ?>" class="menu-item">
                                    <i class="fas fa-hospital"></i>
                                    <span class="menu-text">Congé Maladie</span>
                                </a>
                                <a href="<?= url('profiles/drh/demands/conge/maternity.php') ?>" class="menu-item">
                                    <i class="fas fa-baby"></i>
                                    <span class="menu-text">Congé Maternité</span>
                                </a>
                                <a href="<?= url('profiles/drh/demands/conge/rc.php') ?>" class="menu-item">
                                    <i class="fas fa-clock"></i>
                                    <span class="menu-text">Congé RC</span>
                                </a>
                            </div>
                        </div>
                        <a href="<?= url('profiles/drh/demands/formation') ?>" class="menu-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span class="menu-text">Demande Formation</span>
                        </a>
                        <a href="<?= url('profiles/drh/demands/mission') ?>" class="menu-item">
                            <i class="fas fa-plane"></i>
                            <span class="menu-text">Demande Ordre Mission</span>
                        </a>
                        <a href="<?= url('profiles/drh/demands/deplacement') ?>" class="menu-item">
                            <i class="fas fa-car"></i>
                            <span class="menu-text">Demande Déplacement</span>
                        </a>
                        <a href="<?= url('profiles/drh/demands/leave') ?>" class="menu-item">
                            <i class="fas fa-door-open"></i>
                            <span class="menu-text">Demande Sortie</span>
                        </a>
                    </div>
                    <a href="<?= url('profiles/drh/demands/list.php') ?>" class="menu-item">
                        <i class="fas fa-tasks"></i>
                        <span class="menu-text">État de demande</span>
                    </a>
                    <a href="<?= url('profiles/drh/demands/consulte.php') ?>" class="menu-item">
                        <i class="fas fa-eye"></i>
                        <span class="menu-text">Consulter Demande</span>
                    </a>
                    <a href="<?= url('profiles/drh/pointage') ?>" class="menu-item">
                        <i class="fas fa-clock"></i>
                        <span class="menu-text">Voir Pointage</span>
                    </a>
                </div>

                <div class="nav-title">Autres</div>
                <a href="<?= url('profiles/drh/support') ?>" class="menu-item">
                    <i class="fas fa-question-circle"></i>
                    <span class="menu-text">Support</span>
                </a>
                <!-- Nouveau bouton "Calendrier RC d'Employé" -->
                <a href="<?= url('profiles/drh/calenfrier') ?>" class="menu-item">
                    <i class="fas fa-calendar"></i>
                    <span class="menu-text">Calendrier RC d'Employé</span>
                </a>
            </div>
        </div>
        <form action="<?= url('actions/auth.php') ?>" method="post" class="user-section">
            <input type="hidden" value="logout" name="action" />
            <div class="user-avatar">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <button type="submit" style="border : none">Se déconnecter</button>
        </form>
    </div>

    <!-- Top Navbar -->
    <nav class="navbar">
        <div class="nav-icons">
            <div class="icon-wrapper" onclick="toggleNotifications()">
                <i class="fa-solid fa-bell"></i>
                <!-- Badge pour les notifications non lues -->
                <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
            </div>
            <div class="icon-wrapper" onclick="toggleMessenger()">
                <i class="fa-brands fa-facebook-messenger"></i>
            </div>
        </div>
    </nav>

    <!-- Menu déroulant des notifications -->
    <div class="notification-dropdown" id="notificationDropdown">
        <div class="no-notifications">
            Aucune notification pour le moment.
        </div>
    </div>

    <!-- Menu déroulant de messagerie -->
    <div class="messenger-dropdown" id="messengerDropdown">
        <div class="messenger-header">
            Messagerie
        </div>
        <div class="messenger-body">
            <ul class="contact-list" id="contactList">
                <!-- Aucun contact pour l'instant -->
            </ul>
            <div class="no-messages" id="noMessages">
                <i class="fas fa-comment-slash"></i>
                <p>Aucun message pour le moment.</p>
            </div>
        </div>
        <div class="messenger-footer">
            <input type="text" placeholder="Entrez un nom">
        </div>
    </div>
    </nav>


    <div class="content">
        <div class="container">
            <div class="form-card">
                <div class="header">
                    <h1 class="title">Demande déplacement </h1>
                </div>
                <form action="<?= url('actions/demand.php') ?>" method="post" id="formConvocation">
                    <input type="hidden" name="demand_type" value="deplacement">
                    <div class="section">
                        <h3 class="section-title">Informations Personnelles</h3>
                        <div class="form-group">
                            <label class="form-label" for="matricule">Matricule</label>
                            <input readonly value="<?= $user['matricule'] ?>" type="text" id="matricule" class="form-input" required>
                        </div>
                        <div class="form-group-row">
                            <div class="form-group">
                                <label class="form-label" for="nom">Nom</label>
                                <input readonly value="<?= $user['nom'] ?>" type="text" id="nom" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="prenom">Prénom</label>
                                <input readonly value="<?= $user['prenom'] ?>" type="text" id="prenom" class="form-input" required>
                            </div>
                        </div>

                        <h3 class="section-title">Informations Professionnelles</h3>

                        <div class="form-group">
                            <label class="form-label" for=" Fonction"> Fonction</label>
                            <input readonly value="<?= $user['role']['nom'] ?>" type="text" id=" Fonction" class="form-input" required>
                        </div>
                    </div>
                    <div class="section">
                        <h3 class="section-title">Détails de deplacement </h3>
                        <div class="form-group-row">
                            <div class="form-group">
                                <label class="form-label" for="date-Sortie">Date de sortie</label>
                                <input name="leave_date" type="date" id="date-sortie" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="date-entrée">date de entrée</label>
                                <input name="come_date" type="date" id="date-entrée" class="form-input" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="form-group">
                                <label class="form-label" for="heur-sortie">heur de sortie</label>
                                <input name="leave_hour" type="time" id="heur-sortie" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="heur-entrée">heur d'entrée</label>
                                <input name="come_hour" type="time" id="heur-entrée" class="form-input" required>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="form-label" for="Motif_mission">Motif de la deplacement </label>
                        <input name="motif" type="text" id="Motif_mission" class="form-input" required>
                    </div>

                    <div class="buttons-container" style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" id="printButton" class="button button-secondary">
                            <i class="fas fa-print"></i> Imprimer
                        </button>

                        <button type="submit" id="submitButton" class="button button-primary">
                            <span id="submitText">Soumettre</span>
                        </button>
                    </div>

                    <script>
                        const start_date = document.querySelector("input[name=leave_date]")
                        const end_date = document.querySelector("input[name=come_date]")
                        start_date.min = new Date().toISOString().split("T")[0];
                        end_date.min = new Date().toISOString().split("T")[0];


                        start_date.onchange = () => {
                            if (start_date.value.trim() != "") {
                                let date = new Date(start_date.value);
                                date.setDate(date.getDate() + 1)
                                end_date.min = date.toISOString().split("T")[0]
                            }
                        }
                    </script>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('faireDemandeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('demandeSubmenu');
            if (submenu.style.display === 'none') {
                submenu.style.display = 'block';
            } else {
                submenu.style.display = 'none';
            }
        });

        document.getElementById('congeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('congeSubmenu');
            if (submenu.style.display === 'none') {
                submenu.style.display = 'block';
            } else {
                submenu.style.display = 'none';
            }
        });

        document.querySelector(".menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            let submenu = document.querySelector(".submenu");
            let icon = this.querySelector(".fa-chevron-right");

            if (submenu.style.display === "flex") {
                submenu.style.display = "none";
                icon.style.transform = "rotate(0deg)";
            } else {
                submenu.style.display = "flex";
                icon.style.transform = "rotate(90deg)";
            }
        });

        document.querySelector(".sub-menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            let subSubmenu = document.querySelector(".sub-submenu");
            let icon = this.querySelector(".fa-chevron-right");

            if (subSubmenu.style.display === "flex") {
                subSubmenu.style.display = "none";
                icon.style.transform = "rotate(0deg)";
            } else {
                subSubmenu.style.display = "flex";
                icon.style.transform = "rotate(90deg)";
            }
        });

        document.querySelector(".conges-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            let subSubSubmenu = document.querySelector(".sub-sub-submenu");
            let icon = this.querySelector(".fa-chevron-right");

            if (subSubSubmenu.style.display === "flex") {
                subSubSubmenu.style.display = "none";
                icon.style.transform = "rotate(0deg)";
            } else {
                subSubSubmenu.style.display = "flex";
                icon.style.transform = "rotate(90deg)";
            }
        });

        function validateName(value) {
            // Validation logic here
        }

        function validateDates(depart, retour) {
            // Validation logic here
        }

        function showError(elementId, show) {
            // Show error logic here
        }

        function handleSubmit(event) {
            event.preventDefault();
            // Form submission logic here
        }

        function handlePrint() {
            // Print logic here
        }
        document.getElementById('printButton').addEventListener('click', function() {
            window.print();
        });
        bar_navigation.html
        // Navigation menu toggle functions
        document.getElementById('faireDemandeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('demandeSubmenu');
            submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
        });

        document.getElementById('congeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('congeSubmenu');
            submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
        });
        document.getElementById('faireDemandeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('demandeSubmenu');
            if (submenu.style.display === 'none') {
                submenu.style.display = 'block';
            } else {
                submenu.style.display = 'none';
            }
        });

        document.getElementById('congeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('congeSubmenu');
            if (submenu.style.display === 'none') {
                submenu.style.display = 'block';
            } else {
                submenu.style.display = 'none';
            }
        });

        document.querySelector(".menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            let submenu = document.querySelector(".submenu");
            let icon = this.querySelector(".fa-chevron-right");

            if (submenu.style.display === "flex") {
                submenu.style.display = "none";
                icon.style.transform = "rotate(0deg)";
            } else {
                submenu.style.display = "flex";
                icon.style.transform = "rotate(90deg)";
            }
        });

        document.querySelector(".sub-menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            let subSubmenu = document.querySelector(".sub-submenu");
            let icon = this.querySelector(".fa-chevron-right");

            if (subSubmenu.style.display === "flex") {
                subSubmenu.style.display = "none";
                icon.style.transform = "rotate(0deg)";
            } else {
                subSubmenu.style.display = "flex";
                icon.style.transform = "rotate(90deg)";
            }
        });
        document.getElementById('logoutButton').addEventListener('click', function() {
            window.location.href = 'loginAT1.html';
        });
        document.querySelector(".conges-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            let subSubSubmenu = document.querySelector(".sub-sub-submenu");
            let icon = this.querySelector(".fa-chevron-right");

            if (subSubSubmenu.style.display === "flex") {
                subSubSubmenu.style.display = "none";
                icon.style.transform = "rotate(0deg)";
            } else {
                subSubSubmenu.style.display = "flex";
                icon.style.transform = "rotate(90deg)";
            }
        });

        function validateName(value) {
            // Validation logic here
        }

        function validateDates(depart, retour) {
            // Validation logic here
        }

        function showError(elementId, show) {
            // Show error logic here
        }

        function handleSubmit(event) {
            event.preventDefault();
            // Form submission logic here
        }

        function handlePrint() {
            // Print logic here
        }
        document.getElementById('printButton').addEventListener('click', function() {
            window.print();
        });

        // Fonction pour afficher/masquer les notifications
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }

        // Fonction pour afficher/masquer la messagerie
        function toggleMessenger() {
            const dropdown = document.getElementById('messengerDropdown');
            dropdown.classList.toggle('show');

            // Vérifier s'il y a des contacts
            const contactList = document.getElementById('contactList');
            const noMessages = document.getElementById('noMessages');

            if (contactList.children.length === 0) {
                noMessages.style.display = 'flex'; // Afficher le message
            } else {
                noMessages.style.display = 'none'; // Masquer le message
            }
        }

        // Fermer les menus déroulants si on clique en dehors
        document.addEventListener('click', function(event) {
            const notificationDropdown = document.getElementById('notificationDropdown');
            const messengerDropdown = document.getElementById('messengerDropdown');
            const notificationIcon = document.querySelector('.icon-wrapper[onclick="toggleNotifications()"]');
            const messengerIcon = document.querySelector('.icon-wrapper[onclick="toggleMessenger()"]');

            if (!notificationDropdown.contains(event.target) && !notificationIcon.contains(event.target)) {
                notificationDropdown.classList.remove('show');
            }

            if (!messengerDropdown.contains(event.target) && !messengerIcon.contains(event.target)) {
                messengerDropdown.classList.remove('show');
            }
        });
    </script>
</body>

</html>