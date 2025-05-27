<?php
include __DIR__ . '/../../vendor/autoload.php';

if (! session_id()) {
    session_start();
}
//{ id: 1, date: "2023-05-15", type: "Maladie", statut: "Justifiée", jours: 2, mois: 5, annee: 2023 },
redirect_if_not_auth();
$users = get_all_users();


$id = $_GET['id'] ?? false;
$user = fetch_user_information($id?:$_SESSION['user_id']);
$missions = fetch_user_missions($id?:$_SESSION['user_id']);
$deplacements = fetch_user_deplacements($id?:$_SESSION['user_id']);
$leaves = fetch_user_leaves($id?:$_SESSION['user_id']);
$absenses = fetch_absenses($id?:$_SESSION['user_id']);


$grouped = [];

foreach($absenses as $absense){
    $date = date('d-m-Y', strtotime($absense['date']));

    if( !array_key_exists($date, $grouped) ){
        $grouped[$date] = [];
    }
    $grouped[$date][$absense['day_part']] = $absense['justify'];
}

$count = count($grouped);

$absenseAsArray = array_map(function($absense, $key) use ($count){
    return [
        'id' => rand(1, $count),
        'date' => $key,
        'type' => $absense['morning'] == 'no' ? 'Non Justifieé' : $absense['morning'],
        'statut' => $absense['morning'] == 'no' ? 'Non Justifieé' : 'Justifieé',
        'jours' =>(int)  date('d', strtotime($key)),
        'mois' => date('m', strtotime($key)),
        'annee' => date('Y', strtotime($key)),
    ];
}, $grouped, array_keys($grouped));


$monthAbsenses = array_filter($grouped, function($absense, $key){
    return date('m', strtotime($key)) == date('m');
}, ARRAY_FILTER_USE_BOTH);


$yearAbsenses = array_filter($grouped, function($absense, $key){
    return date('Y', strtotime($key)) == date('Y');
}, ARRAY_FILTER_USE_BOTH);
// { id: 1, date: "2023-05-15", mission: "Visite client - Oran", duree: "3 jours", mois: 5, annee: 2023 } mut have an array like this
$missionAsArray = array_map(function($mission){
    return [
        'id' => $mission['id'],
        'date' => $mission['date_debut'],
        'mission' => $mission['info']['content']['motif'],
        'duree' => $mission['duree'] . " jours",
        'mois' => date('m', strtotime($mission['date_debut'])),
        'annee' => date('Y', strtotime($mission['date_debut'])),
    ];
}, $missions);

// same for deplacements : { id: 1, date: "2023-05-20", destination: "Oran", motif: "Visite client", duree: "2 jours", mois: 5, annee: 2023 },
$deplacementAsArray = array_map(function($deplacement){
    return [
        'id' => $deplacement['id'],
        'date' => $deplacement['date_debut'],
        'destination' => 'unknown',
        'motif' => $deplacement['info']['content']['motif'],
        'duree' => $deplacement['duree'] . " jours",
        'mois' => date('m', strtotime($deplacement['date_debut'])),
        'annee' => date('Y', strtotime($deplacement['date_debut'])),
    ];
}, $deplacements);

// same for leaves : { id: 1, date: "2023-05-18", heure_depart: "10:30", heure_retour: "11:45", motif: "Rendez-vous médical", mois: 5, annee: 2023 },
$leavesAsArray = array_map(function($leave){
    return [
        'id' => $leave['id'],
        'date' => $leave['date_debut'],
        'heure_depart' => $leave['info']['content']['leave hour'],
        'heure_retour' => $leave['info']['content']['come hour'],
        'motif' => $leave['info']['content']['motif'],
        'mois' => date('m', strtotime($leave['date_debut'])),
        'annee' => date('Y', strtotime($leave['date_debut'])),
    ];
}, $leaves);

// filter them by status accepted and this year
$missions = array_filter($missions, function($mission){
    return $mission['status'] == 'accepted' && date('Y', strtotime($mission['date_debut'])) == date('Y');
});

// filter deplacements by status accepted and this year
$deplacements = array_filter($deplacements, function($deplacement){
    return $deplacement['status'] == 'accepted' && date('Y', strtotime($deplacement['date_debut'])) == date('Y');
});

// filter leaves by status accepted and this month
$leaves = array_filter($leaves, function($leave){
    return $leave['status'] == 'accepted' && date('m', strtotime($leave['date_debut'])) == date('m');
});

// count the number of missions
$missions_count = count($missions);
$deplacements_count = count($deplacements);
$leaves_count = count($leaves);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Employé - DjazairRH</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
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
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --success-color: #2ecc71;
            --circle-color: #2c3e50;
            --rc-color: #9b59b6;
            --sortie-color: #1abc9c;
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

        /* Sidebar Styles */
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

        .sidebar a:hover, .sidebar .active {
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

        .submenu, .sub-submenu, .sub-sub-submenu {
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

        .submenu a, .sub-submenu a, .sub-sub-submenu a {
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

        /* Navbar Styles */
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

        /* Content Styles */
        .content {
            margin-top: var(--nav-height);
            margin-left: 280px;
            padding: 30px;
            flex-grow: 1;
            background: var(--bg-color);
            min-height: calc(100vh - var(--nav-height));
        }

        /* Stats Page Styles */
        .employee-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border: none;
        }

        .employee-header {
            background: linear-gradient(135deg, var(--primary-color), var(--circle-color));
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 20px;
        }

        .stat-card {
            border-radius: 8px;
            margin-bottom: 15px;
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .badge-absence {
            background-color: var(--danger-color);
        }

        .badge-mission {
            background-color: var(--success-color);
        }

        .badge-deplacement {
            background-color: var(--primary-color);
        }

        .badge-rc {
            background-color: var(--rc-color);
        }

        .badge-sortie {
            background-color: var(--sortie-color);
        }

        .broken-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: conic-gradient(
                var(--circle-color) 0deg 300deg, 
                transparent 300deg 360deg
            );
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin: 0 auto;
        }

        .broken-circle::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 80px;
            background-color: white;
            border-radius: 50%;
        }

        .circle-icon {
            position: relative;
            z-index: 1;
            color: var(--circle-color);
            font-size: 40px;
        }

        .circle-text {
            position: absolute;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            font-weight: bold;
            color: var(--circle-color);
            font-size: 14px;
        }

        .justified-text {
            color: var(--success-color);
            font-weight: bold;
        }

        .unjustified-text {
            color: var(--danger-color);
            font-weight: bold;
        }

        .small-text {
            font-size: 0.8rem;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
        }

        .tab-content {
            padding-top: 15px;
        }

        .month-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .compact-search {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
            border: none;
        }

        .compact-search .form-select {
            font-size: 0.9rem;
            padding: 0.3rem 0.5rem;
            width: auto;
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important;
            padding-right: 1.5rem;
            background-color: white;
            border: 1px solid #ccc;
        }

        select::-ms-expand {
            display: none;
        }

        .compact-search .btn {
            padding: 0.3rem 0.75rem;
            font-size: 0.9rem;
        }

        .search-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Messenger Dropdown Styles */
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

        /* Animation for submenus */
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

        .submenu.show, .sub-submenu.show, .sub-sub-submenu.show {
            animation: slideDown 0.3s ease forwards;
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
                    <span class="menu-text">my droirs</span>
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
    <div class="content">
        <div class="container">
            <!-- Carte principale de l'employé -->
            <div class="card employee-card">
                <div class="w-full py-4 text-center">
                    <select class="p-2 text-md" name="employee" id="employee">
                        <option selected disabled>chose employee</option>
                        <?php foreach($users as $employee): ?>
                            <option value="<?= $employee['id'] ?>" <?= $employee['id'] == $id ? 'selected' : "" ?>><?= $employee['nom'] . " " . $employee['prenom'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <script>
                        document.querySelector('#employee').addEventListener('change', function(elem) {
                            location.href = `<?= url('dashboard/statistics?id=') ?>${elem.target.value}`
                        })
                    </script>
                </div>
                <div class="card-header employee-header">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <!-- Cercle brisé avec icône -->
                            <div class="broken-circle">
                                <i class="fas fa-user-tie circle-icon"></i>
                                <span class="circle-text">EMP-125</span>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h3 class="mb-1"><?= $user['nom'] ?> <?= $user['prenom'] ?></h3>
                            <p class="mb-1"><strong>Matricule:</strong> EMP-<?= $user['matricule'] ?? '0000' ?></p>
                            <p class="mb-1"><strong>Département:</strong> <?= $user['department']['nom'] ?? 'Informatique' ?></p>
                            <p class="mb-0"><strong>Grade:</strong> <?= $user['role']['nom'] ?? 'Ingénieur Principal' ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Ligne des statistiques rapides -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <span class="stat-icon">📆</span>
                                        Absences mensuelles
                                    </h5>
                                    <p class="display-6 text-center text-danger"><?= count($monthAbsenses) ?> jours</p>
                                    <p class="text-muted text-center"><?= date('M') ?> <?= date('Y')?></p>
                                    <p class="small-text text-center">
                                        <span class="justified-text"><?= count(array_filter($monthAbsenses, fn($e) => $e['morning'] != 'no')) ?> justifiés</span> | 
                                        <span class="unjustified-text"><?= count(array_filter($monthAbsenses, fn($e) => $e['morning'] == 'no')) ?> non justifié</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <span class="stat-icon">📅</span>
                                        Absences annuelles
                                    </h5>
                                    <p class="display-6 text-center text-warning"><?= count($yearAbsenses) ?> jours</p>
                                    <p class="text-muted text-center"><?= date('Y') ?></p>
                                    <p class="small-text text-center">
                                        <span class="justified-text"><?= count(array_filter($yearAbsenses, fn($e) => $e['morning'] != 'no')) ?> justifiés</span> | 
                                        <span class="unjustified-text"><?= count(array_filter($yearAbsenses, fn($e) => $e['morning'] == 'no')) ?> non justifiés</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <span class="stat-icon">🧳</span>
                                        Missions
                                    </h5>
                                    <p class="display-6 text-center text-primary"><?= $missions_count ?></p>
                                    <p class="text-muted text-center">Cette année</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <span class="stat-icon">✈️</span>
                                        Déplacements
                                    </h5>
                                    <p class="display-6 text-center text-success"><?= $deplacements_count ?></p>
                                    <p class="text-muted text-center">Cette année</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nouvelle ligne pour RC et Sorties -->
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <span class="stat-icon">⏳</span>
                                        Repos Compensateur
                                    </h5>
                                    <p class="display-6 text-center" style="color: var(--rc-color);">12 heures</p>
                                    <p class="text-muted text-center">Acquis ce mois</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <span class="stat-icon">🚪</span>
                                        Sorties
                                    </h5>
                                    <p class="display-6 text-center" style="color: var(--sortie-color);"><?= $leaves_count ?></p>
                                    <p class="text-muted text-center">Ce mois</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Détails des absences avec recherche -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <div class="search-header">
                                        <h5 class="mb-0">Détail des absences</h5>
                                        <form id="absenceSearchForm" class="compact-search d-flex gap-2">
                                            <select class="form-select" id="absenceMonth">
                                                <option value="all">Tous</option>
                                                <option value="1">Jan</option>
                                                <option value="2">Fév</option>
                                                <option value="3">Mar</option>
                                                <option value="4">Avr</option>
                                                <option value="5">Mai</option>
                                                <option value="6">Juin</option>
                                                <option value="7">Juil</option>
                                                <option value="8">Août</option>
                                                <option value="9">Sep</option>
                                                <option value="10">Oct</option>
                                                <option value="11">Nov</option>
                                                <option value="12">Déc</option>
                                            </select>
                                            <select class="form-select" id="absenceYear">
                                                <option value="2023">2023</option>
                                                <option value="2022">2022</option>
                                                <option value="2021">2021</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary" title="Rechercher">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="absencesResults">
                                        <!-- Les résultats seront chargés ici via JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Détails des missions, déplacements, RC et sorties -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <div class="search-header">
                                        <h5 class="mb-0">Activités</h5>
                                        <form id="missionSearchForm" class="compact-search d-flex gap-2">
                                            <select class="form-select" id="missionMonth">
                                                <option value="all">Tous</option>
                                                <option value="1">Jan</option>
                                                <option value="2">Fév</option>
                                                <option value="3">Mar</option>
                                                <option value="4">Avr</option>
                                                <option value="5">Mai</option>
                                                <option value="6">Juin</option>
                                                <option value="7">Juil</option>
                                                <option value="8">Août</option>
                                                <option value="9">Sep</option>
                                                <option value="10">Oct</option>
                                                <option value="11">Nov</option>
                                                <option value="12">Déc</option>
                                            </select>
                                            <select class="form-select" id="missionYear">
                                                <option value="2023">2023</option>
                                                <option value="2022">2022</option>
                                                <option value="2021">2021</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary" title="Rechercher">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="missionsTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="missions-tab" data-bs-toggle="tab" data-bs-target="#missions" type="button" role="tab">Missions</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="deplacements-tab" data-bs-toggle="tab" data-bs-target="#deplacements" type="button" role="tab">Déplacements</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="formation-tab" data-bs-toggle="tab" data-bs-target="#formation" type="button" role="tab">Formation</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="sorties-tab" data-bs-toggle="tab" data-bs-target="#sorties" type="button" role="tab">Sorties</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="missionsTabsContent">
                                        <div class="tab-pane fade show active" id="missions" role="tabpanel">
                                            <div class="table-responsive mt-3" id="missionsTableContainer">
                                                <!-- Les missions seront chargées ici via JavaScript -->
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="deplacements" role="tabpanel">
                                            <div class="table-responsive mt-3" id="deplacementsTableContainer">
                                                <!-- Les déplacements seront chargées ici via JavaScript -->
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="formation" role="tabpanel">
                                            <div class="table-responsive mt-3" id="formationTableContainer">
                                                <!-- Les Formation seront chargées ici via JavaScript -->
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="sorties" role="tabpanel">
                                            <div class="table-responsive mt-3" id="sortiesTableContainer">
                                                <!-- Les sorties seront chargées ici via JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Graphiques -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <h5 class="mb-4">Répartition des absences par type</h5>
                                <canvas id="absenceTypeChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="chart-container">
                                <h5 class="mb-4">Évolution des activités</h5>
                                <canvas id="activityTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Navigation menu toggle functions
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

        // Données des absences (simulées)
        const absencesData = JSON.parse('<?= json_encode($absenseAsArray) ?>');

        // Données des missions (simulées)
        const missionsData = JSON.parse('<?= json_encode($missionAsArray) ?>')

        // Données des déplacements (simulées)
        const deplacementsData = JSON.parse('<?= json_encode($deplacementAsArray) ?>')

        // Données des RC (Repos Compensateur) (simulées)
        const formationData = [
            { id: 1, date: "2023-05-12", heures: 4, motif: "Heures supplémentaires", mois: 5, annee: 2023 },
            { id: 2, date: "2023-04-28", heures: 8, motif: "Travail week-end", mois: 4, annee: 2023 },
            { id: 3, date: "2023-03-15", heures: 6, motif: "Heures supplémentaires", mois: 3, annee: 2023 },
            { id: 4, date: "2023-02-10", heures: 3, motif: "Travail jour férié", mois: 2, annee: 2023 },
        ];

        // Données des sorties (simulées)
        const sortiesData = JSON.parse('<?= json_encode($leavesAsArray) ?>')

        // Fonction pour afficher les absences
        function displayAbsences(month = 'all', year = 'all') {
            const container = document.getElementById('absencesResults');
            
            // Filtrer les absences
            const filteredAbsences = absencesData.filter(absence => {
                return (month === 'all' || absence.mois === parseInt(month)) && 
                       (year === 'all' || absence.annee === parseInt(year));
            });
            
            if (filteredAbsences.length === 0) {
                container.innerHTML = '<div class="no-results">Aucune absence trouvée pour cette période</div>';
                return;
            }
            
            // Calculer les totaux
            const totalJours = filteredAbsences.reduce((sum, absence) => sum + absence.jours, 0);
            const totalJustifies = filteredAbsences
                .filter(a => a.statut === "Justifiée")
                .reduce((sum, absence) => sum + absence.jours, 0);
            const totalNonJustifies = filteredAbsences
                .filter(a => a.statut === "Non justifiée")
                .reduce((sum, absence) => sum + absence.jours, 0);
            
            // Grouper par mois
            const absencesByMonth = filteredAbsences.reduce((acc, absence) => {
                const monthYear = `${new Date(absence.date).toLocaleString('fr-FR', { month: 'long' })} ${absence.annee}`;
                if (!acc[monthYear]) {
                    acc[monthYear] = [];
                }
                acc[monthYear].push(absence);
                return acc;
            }, {});
            
            // Générer le HTML
            let html = '';
            
            for (const [monthYear, absences] of Object.entries(absencesByMonth)) {
                const monthTotal = absences.reduce((sum, a) => sum + a.jours, 0);
                const monthJustified = absences
                    .filter(a => a.statut === "Justifiée")
                    .reduce((sum, a) => sum + a.jours, 0);
                const monthUnjustified = absences
                    .filter(a => a.statut === "Non justifiée")
                    .reduce((sum, a) => sum + a.jours, 0);
                
                html += `
                    <div class="month-summary">
                        <h6>${monthYear}</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Total:</strong> ${monthTotal} jours
                            </div>
                            <div class="col-md-4">
                                <span class="justified-text">Justifiées: ${monthJustified} jours</span>
                            </div>
                            <div class="col-md-4">
                                <span class="unjustified-text">Non justifiées: ${monthUnjustified} jours</span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Jours</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                absences.forEach(absence => {
                    const badgeClass = absence.statut === "Justifiée" ? "bg-success" : "bg-danger";
                    const typeClass = absence.type === "Maladie" ? "bg-warning" : 
                                    absence.type === "Congé" ? "bg-info" : "bg-secondary";
                    
                    html += `
                        <tr>
                            <td>${absence.date}</td>
                            <td><span class="badge ${typeClass}">${absence.type}</span></td>
                            <td><span class="badge ${badgeClass}">${absence.statut}</span></td>
                            <td>${absence.jours}</td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                        </table>
                    </div>
                `;
            }
            
            // Ajouter le résumé global
            html += `
                <div class="alert alert-light mt-3">
                    <strong>Résumé global:</strong><br>
                    Total: <strong>${totalJours} jours</strong><br>
                    Justifiées: <span class="justified-text">${totalJustifies} jours</span><br>
                    Non justifiées: <span class="unjustified-text">${totalNonJustifies} jours</span>
                </div>
            `;
            
            container.innerHTML = html;
        }

        // Fonction pour afficher les missions
        function displayMissions(month = 'all', year = 'all') {
            const container = document.getElementById('missionsTableContainer');
            
            // Filtrer les missions
            const filteredMissions = missionsData.filter(mission => {
                return (month === 'all' || mission.mois === parseInt(month)) && 
                       (year === 'all' || mission.annee === parseInt(year));
            });
            
            if (filteredMissions.length === 0) {
                container.innerHTML = '<div class="no-results">Aucune mission trouvée pour cette période</div>';
                return;
            }
            
            // Générer le HTML du tableau
            let html = `
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Mission</th>
                            <th>Durée</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            filteredMissions.forEach(mission => {
                html += `
                    <tr>
                        <td>${mission.date}</td>
                        <td>${mission.mission}</td>
                        <td>${mission.duree}</td>
                    </tr>
                `;
            });
            
            html += `
                    </tbody>
                </table>
                <div class="text-end text-muted mt-2">
                    Total: ${filteredMissions.length} mission(s)
                </div>
            `;
            
            container.innerHTML = html;
        }

        // Fonction pour afficher les déplacements
        function displayDeplacements(month = 'all', year = 'all') {
            const container = document.getElementById('deplacementsTableContainer');
            
            // Filtrer les déplacements
            const filteredDeplacements = deplacementsData.filter(deplacement => {
                return (month === 'all' || deplacement.mois === parseInt(month)) && 
                       (year === 'all' || deplacement.annee === parseInt(year));
            });
            
            if (filteredDeplacements.length === 0) {
                container.innerHTML = '<div class="no-results">Aucun déplacement trouvé pour cette période</div>';
                return;
            }
            
            // Générer le HTML du tableau
            let html = `
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Destination</th>
                            <th>Motif</th>
                            <th>Durée</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            filteredDeplacements.forEach(deplacement => {
                html += `
                    <tr>
                        <td>${deplacement.date}</td>
                        <td>${deplacement.destination}</td>
                        <td>${deplacement.motif}</td>
                        <td>${deplacement.duree}</td>
                    </tr>
                `;
            });
            
            html += `
                    </tbody>
                </table>
                <div class="text-end text-muted mt-2">
                    Total: ${filteredDeplacements.length} déplacement(s)
                </div>
            `;
            
            container.innerHTML = html;
        }

        // Fonction pour afficher les Formation
        function displayFormation(month = 'all', year = 'all') {
            const container = document.getElementById('rcTableContainer');
            
            // Filtrer les Formation
            const filteredFormation = formationData.filter(formation => {
                return (month === 'all' || formation.mois === parseInt(month)) && 
                       (year === 'all' || formation.annee === parseInt(year));
            });
            
            if (filteredFormation.length === 0) {
                container.innerHTML = '<div class="no-results">Aucun repos compensateur trouvé pour cette période</div>';
                return;
            }
            
            // Calculer le total des heures
            const totalHeures = filteredFormation.reduce((sum, Formation) => sum + Formation.heures, 0);
            
            // Générer le HTML du tableau
            let html = `
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Heures</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            filteredFormation.forEach(formation => {
                html += `
                    <tr>
                        <td>${formation.date}</td>
                        <td>${formation.heures} h</td>
                        <td>${formation.motif}</td>
                    </tr>
                `;
            });
            
            html += `
                    </tbody>
                </table>
                <div class="text-end text-muted mt-2">
                    Total: ${totalHeures} heures
                </div>
            `;
            
            container.innerHTML = html;
        }

        // Fonction pour afficher les sorties
        function displaySorties(month = 'all', year = 'all') {
            const container = document.getElementById('sortiesTableContainer');
            
            // Filtrer les sorties
            const filteredSorties = sortiesData.filter(sortie => {
                return (month === 'all' || sortie.mois === parseInt(month)) && 
                       (year === 'all' || sortie.annee === parseInt(year));
            });
            
            if (filteredSorties.length === 0) {
                container.innerHTML = '<div class="no-results">Aucune sortie trouvée pour cette période</div>';
                return;
            }
            
            // Générer le HTML du tableau
            let html = `
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Heure départ</th>
                            <th>Heure retour</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            filteredSorties.forEach(sortie => {
                html += `
                    <tr>
                        <td>${sortie.date}</td>
                        <td>${sortie.heure_depart}</td>
                        <td>${sortie.heure_retour}</td>
                        <td>${sortie.motif}</td>
                    </tr>
                `;
            });
            
            html += `
                    </tbody>
                </table>
                <div class="text-end text-muted mt-2">
                    Total: ${filteredSorties.length} sortie(s)
                </div>
            `;
            
            container.innerHTML = html;
        }
        
        // Gestion du formulaire de recherche des absences
        document.getElementById('absenceSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const month = document.getElementById('absenceMonth').value;
            const year = document.getElementById('absenceYear').value;
            displayAbsences(month, year);
        });
        
        // Gestion du formulaire de recherche des activités
        document.getElementById('missionSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const month = document.getElementById('missionMonth').value;
            const year = document.getElementById('missionYear').value;
            displayMissions(month, year);
            displayDeplacements(month, year);
            displaySorties(month, year);
        });
        
        // Afficher toutes les données au chargement
        document.addEventListener('DOMContentLoaded', function() {
            displayAbsences();
            displayMissions();
            displayDeplacements();
            displaySorties();
        });

        // Graphique circulaire pour les types d'absence
        const typeCtx = document.getElementById('absenceTypeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: ['Non justifiée', 'conge_maladie', 'conge_annual', 'conge_maternity','conge_rc'],
                datasets: [{
                    data: [absencesData.filter((e) => e.type == 'Non Justifieé').length,
                    absencesData.filter((e) => e.type == 'conge_maladie').length,
                    absencesData.filter((e) => e.type == 'conge_annual').length,
                    absencesData.filter((e) => e.type == 'conge_maternity').length,
                    absencesData.filter((e) => e.type == 'conge_rc').length],
                    backgroundColor: [
                        '#e74c3c',
                        '#f39c12',
                        '#2ecc71',
                        '#3498db',
                        '##ffc0cb'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} jours (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Graphique linéaire pour l'évolution des activités
        const trendCtx = document.getElementById('activityTrendChart').getContext('2d');
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                datasets: [
                    {
                        label: 'Missions',
                        data: [1, 0, 1, 1, 2, 0, 0, 0, 0, 0, 1, 0],
                        fill: false,
                        backgroundColor: '#3498db',
                        borderColor: '#3498db',
                        tension: 0.1
                    },
                    {
                        label: 'Déplacements',
                        data: [1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0],
                        fill: false,
                        backgroundColor: '#2ecc71',
                        borderColor: '#2ecc71',
                        tension: 0.1
                    },
                    {
                        label: 'RC (heures)',
                        data: [0, 3, 6, 8, 4, 0, 0, 0, 0, 0, 0, 0],
                        fill: false,
                        backgroundColor: '#9b59b6',
                        borderColor: '#9b59b6',
                        tension: 0.1
                    },
                    {
                        label: 'Sorties',
                        data: [1, 1, 1, 1, 2, 0, 0, 0, 0, 0, 0, 0],
                        fill: false,
                        backgroundColor: '#1abc9c',
                        borderColor: '#1abc9c',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Activités'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>