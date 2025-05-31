<?php
include __DIR__ . '/../../vendor/autoload.php';

if (!session_id()) {
    session_start();
}

redirect_if_not_auth();

$employee_id = $_GET['id'] ?? 1;
if (!$employee_id) {
    header('Location: ' . dashboard_url('employee/list.php'));
    exit;
}

$employee = get_user($employee_id);
if (!$employee) {
    header('Location: ' . dashboard_url('employee/list.php'));
    exit;
}

// Get all absences for the employee
$absences = get_absences($employee_id);

// Convert absences to JSON for Alpine.js
$absences_json = json_encode($absences);

// Get all years and months for the filter
$years = range(2020, date('Y'));
$months = [
    '01' => 'Janvier',
    '02' => 'Février',
    '03' => 'Mars',
    '04' => 'Avril',
    '05' => 'Mai',
    '06' => 'Juin',
    '07' => 'Juillet',
    '08' => 'Août',
    '09' => 'Septembre',
    '10' => 'Octobre',
    '11' => 'Novembre',
    '12' => 'Décembre'
];

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

<body x-data="body" class="bg-gray-100">
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
    </nav>

    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Statistiques des absences - <?= $employee['nom'] . ' ' . $employee['prenom'] ?>
                    </h1>
                    <a href="<?= dashboard_url('employee/list.php') ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>

                <div
                    x-init='absences = JSON.parse(`<?= $absences_json ?>`);currentPage = 1;
                updateCharts();$watch("selectedYear", () => {
                currentPage = 1;
                updateCharts();
            });$watch("selectedMonth", () => {
                currentPage = 1;
                updateCharts();
            });'
                    x-data="{
                absences: [],
                selectedYear: '',
                selectedMonth: '',
                currentPage: 1,
                itemsPerPage: 10,
                absencesByPartChart: null,
                absencesByJustificationChart: null,

                updateCharts() {
            const filteredAbsences = this.filteredAbsences;
            const statistics = this.statistics;

            console.log(filteredAbsences)
            console.log(statistics)

            // Update Absences by Part Chart
            if (this.absencesByPartChart) {
                this.absencesByPartChart.data.datasets[0].data = [statistics.morning ?? 0, statistics.evening ?? 0];
                this.absencesByPartChart.update();
            } else {
                const absencesByPartCtx = document.getElementById('absencesByPartChart').getContext('2d');
                let chart = new Chart(absencesByPartCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Matin', 'Soir'],
                        datasets: [{
                            data: [statistics.morning ?? 0, statistics.evening ?? 0],
                            backgroundColor: ['#3B82F6', '#F59E0B']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                Object.seal(chart)

                this.absencesByPartChart = chart
            }

                console.log('before', )

            // Update Absences by Justification Chart
            if (this.absencesByJustificationChart) {
                this.absencesByJustificationChart.data.datasets[0].data = [
                    statistics.justified ?? 0,
                    statistics.total && statistics.justified ? statistics.total - statistics.justified : 0
                ];
                console.log('just', this.absencesByJustificationChart)
                this.absencesByJustificationChart.update();
            } else {
                const absencesByJustificationCtx = document.getElementById('absencesByJustificationChart').getContext('2d');
                let chart = new Chart(absencesByJustificationCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Justifiées', 'Non justifiées'],
                        datasets: [{
                            data: [
                                statistics.justified ?? 0,
                                statistics.total && statistics.justified ? statistics.total - statistics.justified : 0
                            ],
                            backgroundColor: ['#10B981', '#EF4444']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                })
                Object.seal(chart);
                this.absencesByJustificationChart = chart
            }
        },
                
                get filteredAbsences() {
                    return this.absences.filter(absence => {
                        const date = new Date(absence.date);
                        const year = date.getFullYear().toString();
                        const month = (date.getMonth() + 1).toString().padStart(2, '0');
                        
                        if (this.selectedYear && year !== this.selectedYear) return false;
                        if (this.selectedMonth && month !== this.selectedMonth) return false;
                        
                        return true;
                    });
                },
                
                get paginatedAbsences() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    return this.filteredAbsences.slice(start, start + this.itemsPerPage);
                },
                
                get totalPages() {
                    return Math.ceil(this.filteredAbsences.length / this.itemsPerPage);
                },
                
                get statistics() {
                    const filtered = this.filteredAbsences;
                    return {
                        total: filtered.length,
                        morning: filtered.filter(a => a.day_part === 'morning').length,
                        evening: filtered.filter(a => a.day_part === 'evening').length,
                        justified: filtered.filter(a => a.justify === 'yes').length
                    };
                },
                
                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                },
                
                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },
                
                goToPage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                }
            }">
                    <!-- Filters -->
                    <div class="mb-6 flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                            <select x-model="selectedYear" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Toutes les années</option>
                                <?php foreach ($years as $year): ?>
                                    <option value="<?= $year ?>"><?= $year ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mois</label>
                            <select x-model="selectedMonth" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Tous les mois</option>
                                <?php foreach ($months as $value => $name): ?>
                                    <option value="<?= $value ?>"><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800">Total des absences</h3>
                            <p class="text-3xl font-bold text-blue-600" x-text="statistics.total"></p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800">Absences matin</h3>
                            <p class="text-3xl font-bold text-green-600" x-text="statistics.morning"></p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-800">Absences soir</h3>
                            <p class="text-3xl font-bold text-yellow-600" x-text="statistics.evening"></p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800">Absences justifiées</h3>
                            <p class="text-3xl font-bold text-purple-600" x-text="statistics.justified"></p>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Absences by Part of Day -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition des absences</h3>
                            <canvas id="absencesByPartChart"></canvas>
                        </div>

                        <!-- Absences by Justification -->
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Absences justifiées vs non justifiées</h3>
                            <canvas id="absencesByJustificationChart"></canvas>
                        </div>
                    </div>

                    <!-- Absences Table -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Détail des absences</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Justifiée</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="absence in paginatedAbsences" :key="absence.id">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="new Date(absence.date).toLocaleDateString('fr-FR')"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="absence.day_part === 'morning' ? 'Matin' : 'Soir'"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': absence.justify === 'yes',
                                                'bg-red-100 text-red-800': absence.justify === 'no'
                                            }" x-text="absence.justify === 'yes' ? 'Oui' : 'Non'"></span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                                <div class="flex-1 flex justify-between sm:hidden">
                                    <button @click="prevPage()" :disabled="currentPage === 1"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }">
                                        Précédent
                                    </button>
                                    <button @click="nextPage()" :disabled="currentPage === totalPages"
                                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }">
                                        Suivant
                                    </button>
                                </div>
                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-700">
                                            Affichage de <span class="font-medium" x-text="(currentPage - 1) * itemsPerPage + 1"></span> à
                                            <span class="font-medium" x-text="Math.min(currentPage * itemsPerPage, filteredAbsences.length)"></span> sur
                                            <span class="font-medium" x-text="filteredAbsences.length"></span> résultats
                                        </p>
                                    </div>
                                    <div>
                                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                            <button @click="prevPage()" :disabled="currentPage === 1"
                                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }">
                                                <span class="sr-only">Précédent</span>
                                                <i class="fas fa-chevron-left"></i>
                                            </button>

                                            <template x-for="page in totalPages" :key="page">
                                                <button @click="goToPage(page)"
                                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
                                                    :class="{
                                                    'text-blue-600 bg-blue-50': currentPage === page,
                                                    'text-gray-700 hover:bg-gray-50': currentPage !== page
                                                }"
                                                    x-text="page">
                                                </button>
                                            </template>

                                            <button @click="nextPage()" :disabled="currentPage === totalPages"
                                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }">
                                                <span class="sr-only">Suivant</span>
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    </script>

    <!-- Navigation Sidebar -->
    <?php component('utils/status') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>