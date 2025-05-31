<?php

include __DIR__ . '/../../vendor/autoload.php';

if (! session_id()) {
    session_start();
}

redirect_if_not_auth();

$user = fetch_user_information($_GET['id'] ?? 1);



$notifications = get_notifications($_SESSION['user_id']);

$user_requests = fetch_creation_demands();

$redPin = count(array_filter($notifications, function ($v, $i) {
    return $v['read_state'] == 0;
}, ARRAY_FILTER_USE_BOTH)) > 0;

$current_user = fetch_user_information($_SESSION['user_id']);
$_SESSION['user'] = get_user($current_user['id']);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Profil <?= $user['role']['nom'] ?></title>
    <!-- Navigation Sidebar -->
    <?php component('partials/include') ?>


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

        /* Profile Styles */
        :root {
            --primary-color: #003366;
            --secondary-color: #0066cc;
            --accent-color: #e6f3ff;
            --success-color: #10b981;
            --text-color: #2c3e50;
            --bg-color: #f8f9fa;
            --border-radius: 12px;
            --transition: all 0.3s ease;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
        }

        /* Profile Sidebar */
        .profile-sidebar {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--shadow);
            height: fit-content;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: var(--primary-color);
            position: relative;
        }

        .edit-photo {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--primary-color);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .edit-photo:hover {
            transform: scale(1.1);
        }

        .profile-name {
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .profile-title {
            text-align: center;
            color: #64748b;
            margin-bottom: 20px;
        }

        .profile-info {
            margin-top: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: var(--text-color);
        }

        .info-item i {
            width: 20px;
            margin-right: 10px;
            color: var(--primary-color);
        }

        .info-item input {
            flex: 1;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
        }

        .info-item input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
        }

        /* Main Content */
        .profile-content {
            display: grid;
            gap: 25px;
        }

        .content-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--accent-color);
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .edit-button {
            background: var(--accent-color);
            color: var(--primary-color);
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .edit-button:hover {
            background: var(--primary-color);
            color: white;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-field {
            margin-bottom: 15px;
        }

        .field-label {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .field-value {
            font-size: 16px;
            color: var(--text-color);
            font-weight: 500;
        }

        .field-value input,
        .field-value select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 5px;
            background-color: white;
        }

        .field-value input:focus,
        .field-value select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
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

            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
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

<body x-data="body">
    <!-- Navigation Sidebar -->
    <?php component('partials/sidebar') ?>

    <!-- Top Navbar -->
    <?php component('partials/navbar') ?>

    <!-- Menu déroulant des notifications -->
    <?php component('partials/notifications') ?>

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
        <div id="page-content" class="container">
            <div class="profile-grid">
                <!-- Profile Sidebar -->
                <div class="profile-sidebar">
                    <div class="profile-photo">
                        <i class="fas fa-user"></i>
                        <div class="edit-photo">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <h2 class="profile-name"><?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></h2>
                    <p class="profile-title"><?= htmlspecialchars($user['role']['nom']) ?></p>
                    
                    <div class="profile-info">
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span><?= htmlspecialchars($user['email_professionnel']) ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span><?= htmlspecialchars($user['phone'] ?? 'Non spécifié') ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-building"></i>
                            <span><?= htmlspecialchars($user['department']['nom'] ?? 'Non spécifié') ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <span>Date d'embauche: <?= htmlspecialchars($user['start_date'] ?? 'Non spécifié') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="profile-content">
                    <!-- Personal Information -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3><i class="fas fa-user-circle me-2"></i>Informations Personnelles</h3>
                            <!-- <button class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </button> -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email_professionnel']) ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">État Civil</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['etat_civil'] ?? 'Non spécifié') ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de Naissance</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['birth_day'] ?? 'Non spécifié') ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Lieu de Naissance</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['birth_place'] ?? 'Non spécifié') ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3><i class="fas fa-briefcase me-2"></i>Informations Professionnelles</h3>
                            <!-- <button class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </button> -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Département</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['department']['nom'] ?? 'Non spécifié') ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Poste</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['role']['nom']) ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date d'embauche</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['start_date'] ?? 'Non spécifié') ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Statut</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['statut'] ?? 'Actif') ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Salaire de Base</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['base_payment'] ?? 'Non spécifié') ?> DZD" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const btn = document.querySelector('#saveProfissionel')
        btn.onclick = () => {
            let data = new FormData
            data.append('action', 'save_prof')

            let keys = ["matricule", "role_id", "department_id", "start_date", "service_id", "superior_id"]


            keys.forEach(e => {
                try {
                    data.append(e, (document.querySelector(`input[name=${e}]`) || document.querySelector(`select[name=${e}]`)).value)
                } catch (err) {
                    console.log(e, err)
                }
            })
            fetch('<?= url('actions/account.php') ?>', {
                    method: "post",
                    body: data
                }).then(res => res.json())
                .then(js => {
                    if (js.success) {
                        Swal.fire({
                            title: 'effectué!',
                            text: 'les enregistrement effectué avec success',
                            icon: 'success'
                        })
                    } else {
                        Swal.fire({
                            title: 'erreur!',
                            text: js.message,
                            icon: 'error'
                        })
                    }
                }).catch(err => {
                    Swal.fire({
                        title: 'error!',
                        text: err,
                        icon: 'error'
                    })
                })
        }
    </script>

    <script>
        const warnInfo = (text = "complete vous informations pour bien utuliser votre compte") => {
            Swal.fire({
                title: "warning",
                text,
                icon: "warning"
            });
        }

        <?php if (($l = isProfileComplete($user)) !== true): ?>
            warnInfo()
        <?php endif; ?>
    </script>

    <script>
        const saveBtn = document.querySelector('#saveBtn')

        saveBtn.onclick = () => {
            let data = new FormData
            data.append('action', 'save')

            let keys = ["phone", "nom", "prenom", "birth_day", "birth_place", "etat_cevil", "matricule"]


            keys.forEach(e => {
                try {
                    data.append(e, (document.querySelector(`input[name=${e}]`) || document.querySelector(`select[name=${e}]`)).value)
                } catch (err) {
                    console.log(e, err)
                }
            })
            fetch('<?= url('actions/account.php') ?>', {
                    method: "post",
                    body: data
                }).then(res => res.json())
                .then(js => {
                    if (js.success) {
                        Swal.fire({
                            title: 'effectué!',
                            text: 'les enregistrement effectué avec success',
                            icon: 'success'
                        })
                    } else {
                        Swal.fire({
                            title: 'erreur!',
                            text: js.message,
                            icon: 'error'
                        })
                    }
                }).catch(err => {
                    Swal.fire({
                        title: 'erreur!',
                        text: err,
                        icon: 'error'
                    })
                })
        }
    </script>

    <!-- Navigation Sidebar -->
    <?php component('utils/status') ?>

    <script>
        // Navigation menu toggle functions
        document.getElementById('faireDemandeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('demandeSubmenu');
            submenu.style.display = submenu.style.display == 'none' ? 'block' : 'none';
        });

        document.getElementById('congeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('congeSubmenu');
            submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
        });

        document.getElementById('logoutButton').addEventListener('click', function() {
            window.location.href = 'loginAT1.html';
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