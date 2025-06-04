<?php
include __DIR__ . '/../../vendor/autoload.php';

if (! session_id()) {
    session_start();
}

redirect_if_not_auth();

$annual_demands = fetch_user_demands($_SESSION['user_id'], 'conge_annual');
$rc_days = calculate_rc_days($_SESSION['user_id'])['rc_days'];

// extract the total days from the demands 
$total_annual_days = array_sum(array_column($annual_demands, 'duree'));

// show the data to screen


// if total is 30 then the user has no remaining days
if($total_annual_days == 30){
    $remaining_days = 0;
}else{
    $remaining_days = 30 - $total_annual_days;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Gestion des Congés</title>
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
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --conge-color: #3b82f6;
            --sortie-color: #ec4899;
            --rc-color: #10b981;
            --conge-progress: 0%;
            --sortie-progress: 0%;
            --rc-progress: 0%;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 51, 102, 0.4); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 10px rgba(255, 51, 102, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 51, 102, 0); }
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

        /* Stats Section Styles */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .page-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-color);
            position: relative;
            display: inline-block;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .stats-section {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .section-title svg {
            width: 24px;
            height: 24px;
            color: var(--primary-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .stat-card {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .stat-circle-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(
                var(--stat-color) 0% var(--progress),
                #e2e8f0 var(--progress) 100%);
            box-shadow: inset 0 0 0 8px white;
        }

        .stat-circle-content {
            position: relative;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--stat-color);
            line-height: 1;
        }

        .stat-label {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-color);
            margin-top: 0.5rem;
        }

        .stat-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color);
            margin-top: 1rem;
            text-align: center;
        }

        /* Actions Section */
        .actions-section {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .action-card {
            background: var(--light);
            border-radius: 12px;
            padding: 1.8rem;
            transition: all 0.3s ease;
            border-left: 4px solid var(--action-color);
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .action-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .action-title svg {
            color: var(--action-color);
        }

        .action-desc {
            color: var(--text-color);
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            opacity: 0.8;
        }

        .action-btn {
            background: var(--action-color);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
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

        /* Animation for stats circles */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-circle {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .stat-card:nth-child(1) .stat-circle {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) .stat-circle {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) .stat-circle {
            animation-delay: 0.3s;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
    
    <!-- Main Content -->
    <main class="content">
        <div class="dashboard-header">
            <div>
                <h1 class="page-title">Consultez vos droits et effectuez des demandes</h1>
            </div>
        </div>
        
        <!-- Stats Section -->
        <section class="stats-section">
            <h2 class="section-title">
                <i class="fas fa-chart-pie"></i>
                Vos Droits Disponibles
            </h2>
            
            <div class="stats-grid">
                <!-- Congés Annuels -->
                <div class="stat-card">
                    <div class="stat-circle" style="--stat-color: var(--conge-color); --progress: var(--conge-progress)">
                        <div class="stat-circle-bg"></div>
                        <div class="stat-circle-content">
                            <span class="stat-value" id="conge-value"><?= $remaining_days ?></span>
                            <span class="stat-label" id="conge-total">/ 30 jours</span>
                        </div>
                    </div>
                    <h3 class="stat-title">Congés Annuels</h3>
                </div>
                
                <!-- Sorties Mensuelles -->
                <div class="stat-card">
                    <div class="stat-circle" style="--stat-color: var(--sortie-color); --progress: var(--sortie-progress)">
                        <div class="stat-circle-bg"></div>
                        <div class="stat-circle-content">
                            <span class="stat-value" id="sortie-value">0</span>
                            <span class="stat-label" id="sortie-total">/ 0 jours</span>
                        </div>
                    </div>
                    <h3 class="stat-title">Sorties Mensuelles</h3>
                </div>
                
                <!-- Jours RC -->
                <div class="stat-card">
                    <div class="stat-circle" style="--stat-color: var(--rc-color); --progress: var(--rc-progress)">
                        <div class="stat-circle-bg"></div>
                        <div class="stat-circle-content">
                            <span class="stat-value" id="rc-value">0</span>
                            <span class="stat-label">disponibles</span>
                        </div>
                    </div>
                    <h3 class="stat-title">Jours de Récupération</h3>
                </div>
            </div>
        </section>
        
        <!-- Actions Section -->
        <section class="actions-section">
            <h2 class="section-title">
                <i class="fas fa-rocket"></i>
                Actions Rapides
            </h2>
            
            <div class="actions-grid">
                <!-- Demande Congé -->
                <div class="action-card" style="--action-color: var(--conge-color)">
                    <h3 class="action-title">
                        <i class="fas fa-calendar-plus"></i>
                        Demander un congé
                    </h3>
                    <p class="action-desc">Effectuez une demande de congé annuel, maladie ou autre type d'absence</p>
                    <button class="action-btn" onclick="window.location.href='<?= url('dashboard/demands/conge/annual.php') ?>'">
                        Demander
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                
                <!-- Demande Sortie -->
                <div class="action-card" style="--action-color: var(--sortie-color)">
                    <h3 class="action-title">
                        <i class="fas fa-door-open"></i>
                        Demander une sortie
                    </h3>
                    <p class="action-desc">Demander une sortie autorisée (2 heurs maximum par mois)</p>
                    <button class="action-btn" onclick="window.location.href='<?= url('dashboard/demands/leave') ?>'">
                        Demander
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                
                <!-- Utiliser RC -->
                <div class="action-card" style="--action-color: var(--rc-color)">
                    <h3 class="action-title">
                        <i class="fas fa-clock"></i>
                        Utiliser jours RC
                    </h3>
                    <p class="action-desc">Utilisez vos jours de récupération accumulés</p>
                    <button class="action-btn" onclick="window.location.href='<?= url('dashboard/demands/conge/rc.php') ?>'">
                        Utiliser
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </section>
    </main>

    <script>
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }
        // Fonction pour récupérer les droits depuis l'API
        async function fetchDroitsEmploye() {
            try {
                // Afficher l'indicateur de chargement
                document.querySelectorAll('.stat-value').forEach(el => {
                    el.innerHTML = '<span class="loading"></span>';
                });
                
                // Simuler un appel API (remplacez par votre vrai endpoint)
                // const response = await fetch('/api/droits-employe');
                // const data = await response.json();
                
                // Simulation de données (à remplacer par l'appel réel)
                const data = {
                    success: true,
                    droits: {
                        conge_total: 30,
                        conge_restant: <?= $remaining_days ?>,
                        sortie_total: 2,
                        sortie_restant: 2,
                        rc_total: <?= $rc_days ?? 0 ?>,
                        rc_restant: <?= $rc_days ?? 0 ?>
                    }
                };
                
                // Petit délai pour simuler le chargement
                await new Promise(resolve => setTimeout(resolve, 800));
                
                if (data.success) {
                    updateStats(data.droits);
                } else {
                    console.error('Erreur lors de la récupération des droits:', data.message);
                    showError();
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError();
            }
        }

        // Fonction pour mettre à jour les statistiques
        function updateStats(droits) {
            // Calcul des pourcentages
            const congeProgress = (droits.conge_restant / droits.conge_total) * 100;
            const sortieProgress = (droits.sortie_restant / droits.sortie_total) * 100;
            const rcProgress = (droits.rc_restant / droits.rc_total) * 100;
            
            // Mise à jour des variables CSS
            document.documentElement.style.setProperty('--conge-progress', `${congeProgress}%`);
            document.documentElement.style.setProperty('--sortie-progress', `${sortieProgress}%`);
            document.documentElement.style.setProperty('--rc-progress', `${rcProgress}%`);
            
            // Mise à jour des valeurs affichées
            document.getElementById('conge-value').textContent = droits.conge_restant;
            document.getElementById('conge-total').textContent = `/ ${droits.conge_total} jours`;
            
            document.getElementById('sortie-value').textContent = droits.sortie_restant;
            document.getElementById('sortie-total').textContent = `/ ${droits.sortie_total} heurs`;
            
            document.getElementById('rc-value').textContent = droits.rc_restant;
        }

        // Fonction en cas d'erreur
        function showError() {
            document.querySelectorAll('.stat-value').forEach(el => {
                el.textContent = '--';
            });
            document.querySelectorAll('.stat-label').forEach(el => {
                if (el.textContent.includes('/')) {
                    el.textContent = '/ -- jours';
                }
            });
        }

        // Au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des cercles
            const circles = document.querySelectorAll('.stat-circle');
            circles.forEach((circle, index) => {
                setTimeout(() => {
                    circle.style.opacity = '1';
                }, 100 * (index + 1));
            });
            
            // Récupération des données
            fetchDroitsEmploye();
            
            // Menu toggle functionality
            document.getElementById('faireDemandeBtn').addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = document.getElementById('demandeSubmenu');
                submenu.style.display = submenu.style.display === 'none' ? 'flex' : 'none';
            });

            // Logout functionality
            document.getElementById('logoutButton').addEventListener('click', function() {
                window.location.href = 'login.html';
            });
        });

        // Rafraîchir les données toutes les 5 minutes
        setInterval(fetchDroitsEmploye, 5 * 60 * 1000);
    </script>

    <!-- Navigation Sidebar -->
    <?php component('utils/status') ?>
</body>
</html>