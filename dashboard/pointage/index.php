<?php

include __DIR__ . '/../../vendor/autoload.php';

if (! session_id()) {
    session_start();
}

redirect_if_not_auth();

$services = get_services();
$departments = get_departments();
$employees = get_all_users();
$roles = get_roles();




$notifications = get_notifications($_SESSION['user_id']);

$user_requests = fetch_creation_demands();

$redPin = count(array_filter($notifications, function ($v, $i) {
    return $v['read_state'] == 0;
}, ARRAY_FILTER_USE_BOTH)) > 0;

$user = fetch_user_information($_SESSION['user_id']);
$_SESSION['user'] = get_user($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Pointage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
            --transition: all 0.3s ease;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            min-height: 100vh;
            background: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Navigation Styles */
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

        .logo-text {
            font-size: 26px;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .nav-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin: 20px 0 10px;
            padding-left: 18px;
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

        .submenu {
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

        .submenu a {
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

        /* Navbar Styles */
        .navbar {
            position: fixed;
            top: 0;
            right: 0;
            width: calc(100% - 280px);
            height: var(--nav-height);
            background: white;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 30px;
            box-shadow: var(--shadow-sm);
            z-index: 900;
        }

        .nav-icons {
            display: flex;
            gap: 20px;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            transition: var(--transition);
        }

        .icon-wrapper:hover {
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

        /* Main Content Styles */
        .main-content {
            margin-left: 280px;
            padding: 90px 30px 30px;
            width: calc(100% - 280px);
        }

        /* Pointage Styles */
        .dirli-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .dirli-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eaeaea;
        }

        .dirli-title {
            font-size: 26px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .dirli-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .dirli-btn-import {
            background: #2ecc71;
            color: white;
        }

        .dirli-btn-export {
            background: var(--primary-color);
            color: white;
        }

        .dirli-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dirli-table-container {
            overflow-x: auto;
            margin-top: 25px;
            border-radius: 8px;
            border: 1px solid #eaeaea;
        }

        .dirli-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .dirli-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        .dirli-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eaeaea;
        }

        .dirli-alert {
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            display: none;
            animation: fadeIn 0.4s;
        }

        .dirli-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .dirli-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
        }

        #fileInput {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .btn-group {
            display: flex;
            gap: 12px;
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

        @media (max-width: 1024px) {
            .sidebar {
                width: 80px;
            }

            .logo-text,
            .menu-text {
                display: none;
            }

            .main-content {
                margin-left: 80px;
                width: calc(100% - 80px);
            }

            .navbar {
                width: calc(100% - 80px);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <img src="logo_djazairRH.jpg" alt="DjazairRH Logo">
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
                <a href="<?= url('dashboard') ?>" class="menu-item active">
                    <i class="fas fa-user-circle"></i>
                    <span class="menu-text">Mon Profil</span>
                </a>

                <a href="<?= url('dashboard/statistics') ?>" class="menu-item">
                    <i class="fas fa-chart-simple"></i>
                    <span class="menu-text">statistics</span>
                </a>

                <a href="<?= url('dashboard/droits') ?>" class="menu-item">
                    <i class="fas fa-list"></i>
                    <span class="menu-text">my droits</span>
                </a>

                <?php if( if_user_is(['Directeur', 'GRH'], null) ): ?>
                <a href="<?= url('dashboard/employee/list.php') ?>" class="menu-item">
                    <i class="fas fa-list"></i>
                    <span class="menu-text">elist d'employees</span>
                </a>
                <?php endif ?>

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
                                <a href="<?= url('dashboard/demands/conge/annual.php') ?>" class="menu-item">
                                    <i class="fas fa-sun"></i>
                                    <span class="menu-text">Congé Annuel</span>
                                </a>
                                <a href="<?= url('dashboard/demands/conge/malady.php') ?>" class="menu-item">
                                    <i class="fas fa-hospital"></i>
                                    <span class="menu-text">Congé Maladie</span>
                                </a>
                                <a href="<?= url('dashboard/demands/conge/maternity.php') ?>" class="menu-item">
                                    <i class="fas fa-baby"></i>
                                    <span class="menu-text">Congé Maternité</span>
                                </a>
                                <a href="<?= url('dashboard/demands/conge/rc.php') ?>" class="menu-item">
                                    <i class="fas fa-clock"></i>
                                    <span class="menu-text">Congé RC</span>
                                </a>
                            </div>
                        </div>
                        <a href="<?= url('dashboard/demands/formation') ?>" class="menu-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span class="menu-text">Demande Formation</span>
                        </a>
                        <a href="<?= url('dashboard/demands/mission') ?>" class="menu-item">
                            <i class="fas fa-plane"></i>
                            <span class="menu-text">Demande Ordre Mission</span>
                        </a>
                        <a href="<?= url('dashboard/demands/deplacement') ?>" class="menu-item">
                            <i class="fas fa-car"></i>
                            <span class="menu-text">Demande Déplacement</span>
                        </a>
                        <a href="<?= url('dashboard/demands/leave') ?>" class="menu-item">
                            <i class="fas fa-door-open"></i>
                            <span class="menu-text">Demande Sortie</span>
                        </a>
                    </div>
                    <a href="<?= url('dashboard/demands/list.php') ?>" class="menu-item">
                        <i class="fas fa-tasks"></i>
                        <span class="menu-text">État de demande</span>
                    </a>
                    <?php if (!if_user_is('Employé', null)): ?>
                        <a href="<?= url('dashboard/demands/consulte.php') ?>" class="menu-item">
                            <i class="fas fa-eye"></i>
                            <span class="menu-text">Consulter Demande</span>
                        </a>
                    <?php endif ?>




                </div>

                <div class="nav-title">Autres</div>
                <?php if (if_user_is(['Directeur', 'GRH'], null)): ?>
                    <a href="<?= dashboard_url('pointage') ?>" class="menu-item">
                        <i class="fas fa-clock"></i>
                        <span class="menu-text">Voir Pointage</span>
                    </a>
                <?php endif ?>
                <a href="<?= url('dashboard/support') ?>" class="menu-item">
                    <i class="fas fa-question-circle"></i>
                    <span class="menu-text">Support</span>
                </a>
                <!-- Nouveau bouton "Calendrier RC d'Employé" -->
                <a href="<?= url('dashboard/calendrier') ?>" class="menu-item">
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="dirli-container">
            <div class="dirli-header">
                <h1 class="dirli-title">Pointage</h1>
                <div class="btn-group">
                    <div class="file-input-wrapper">
                        <button class="dirli-btn dirli-btn-import">
                            <i class="fas fa-upload"></i><i class="fas fa-spinner animate-spin hidden"></i> Importer
                        </button>
                        <input type="file" id="fileInput" />
                    </div>
                    <button id="exportBtn" class="dirli-btn dirli-btn-export">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                </div>
            </div>

            <div id="alertBox" class="dirli-alert"></div>

            <div class="dirli-table-container">
                <table class="dirli-table" id="dataTable">
                    <thead>
                        <tr></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
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

        // Pointage functionality
        const fileInput = document.getElementById('fileInput');
        const exportBtn = document.getElementById('exportBtn');
        const dataTable = document.getElementById('dataTable');
        const alertBox = document.getElementById('alertBox');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            Swal.fire({
                text: `vous etes sur vous voulez telecharger cette ficheie : ${file.name} ?`,
                title: 'Confirmer',
                icon: 'question',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "continué",
                denyButtonText: `annuler`,
                showCancelButton: false
            }).then(function(result) {
                if (!result.isConfirmed)
                    return

                let data = new FormData
                data.append('file', file)
                document.getElementsByClassName('fa-spinner')[0].classList.remove('hidden')
                document.getElementsByClassName('fa-upload')[0].classList.add('hidden')

                fetch("<?= url('actions/upload.php') ?>", {
                        body: data,
                        method: 'POST'
                    }).then(res => res.json())
                    .then(json => {
                        document.getElementsByClassName('fa-spinner')[0].classList.add('hidden')
                        document.getElementsByClassName('fa-upload')[0].classList.remove('hidden')

                        window.open(json.file_url)

                        const reader = new FileReader();

                        reader.onload = function(e) {
                            try {
                                const data = new Uint8Array(e.target.result);
                                const workbook = XLSX.read(data, {
                                    type: 'array'
                                });
                                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                                const jsonData = XLSX.utils.sheet_to_json(firstSheet, {
                                    header: 1
                                });

                                displayData(jsonData);
                                showAlert('Fichier importé avec succès!', 'dirli-success');

                            } catch (error) {
                                console.error("Erreur d'importation:", error);
                                showAlert("Erreur d'importation: " + error.message, 'dirli-error');
                            }
                        };

                        fetch(json.file_url).then(res => res.blob()).then(b => {
                            reader.readAsArrayBuffer(b);
                        })
                    }).catch(err => {
                        document.getElementsByClassName('fa-spinner')[0].classList.add('hidden')
                        document.getElementsByClassName('fa-upload')[0].classList.remove('hidden')
                        alert('failed :' + err)
                    })

            })




            /* const reader = new FileReader();
            reader.onload = function (e) {
                try {
                    alert('loaded')
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                    displayData(jsonData);
                    showAlert('Fichier importé avec succès!', 'dirli-success');

                } catch (error) {
                    console.error("Erreur d'importation:", error);
                    showAlert("Erreur d'importation: " + error.message, 'dirli-error');
                }
            };
            reader.readAsArrayBuffer(file); */
        });

        function displayData(data) {
            const thead = dataTable.querySelector('thead');
            const tbody = dataTable.querySelector('tbody');
            thead.innerHTML = '';
            tbody.innerHTML = '';

            if (data.length > 0) {
                const headerRow = document.createElement('tr');
                data[0].forEach(colName => {
                    const th = document.createElement('th');
                    th.textContent = colName;
                    headerRow.appendChild(th);
                });
                thead.appendChild(headerRow);
            }

            data.slice(1).forEach(rowData => {
                const tr = document.createElement('tr');
                rowData.forEach(cellData => {
                    const td = document.createElement('td');
                    td.textContent = cellData !== undefined ? cellData : '';
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
        }

        exportBtn.addEventListener('click', function() {
            try {
                const wb = XLSX.utils.table_to_book(dataTable);
                XLSX.writeFile(wb, 'export_pointage.xlsx');
                showAlert('Export réussi!', 'dirli-success');
            } catch (error) {
                console.error("Erreur d'exportation:", error);
                showAlert("Erreur d'exportation: " + error.message, 'dirli-error');
            }
        });

        function showAlert(message, type) {
            alertBox.textContent = message;
            alertBox.className = `dirli-alert ${type}`;
            alertBox.style.display = 'block';

            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 5000);
        }

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