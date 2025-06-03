<?php

require_once __DIR__ . '/../../vendor/autoload.php';


$user = fetch_user_information($_GET['id']?:$_SESSION['user_id']);

$all_absences = fetch_all_absences($user['matricule']);


$month_statistics = get_monthly_absences_grouped(2025);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments & Absence Management</title>
    <?php component('partials/include') ?>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .appointments-container {
            max-width: var(--max-width);
            margin: 2rem auto;
            padding: 2rem;
        }

        .appointments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .appointments-header h1 {
            color: var(--text-dark);
            font-size: 2rem;
        }

        .appointments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .appointment-card {
            background: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .appointment-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .appointment-details {
            margin-bottom: 1rem;
        }

        .appointment-details p {
            margin: 0.5rem 0;
            color: var(--text-light);
        }

        .absence-summary {
            background: #fff3f3;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .absence-summary h2 {
            color: #e74c3c;
            margin-bottom: 1rem;
        }

        .pay-impact {
            font-size: 1.2rem;
            color: #e74c3c;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-secondary {
            background: #e74c3c;
            color: var(--white);
        }

        .btn:hover {
            opacity: 0.9;
        }

        .calendar-view {
            background: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .calendar-day {
            aspect-ratio: 1;
            padding: 0.5rem;
            border: 1px solid #eee;
            text-align: center;
            cursor: pointer;
        }

        .calendar-day:hover {
            background: #f5f5f5;
        }

        .calendar-day.has-appointment {
            background: var(--primary-color);
            color: var(--white);
        }

        .calendar-day.has-absence {
            background: #e74c3c;
            color: var(--white);
        }

        .charts-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .chart-title {
            font-size: 1.1rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .absence-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-box {
            background: var(--bg-color);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-box .label {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .stat-box .value {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }
    </style>
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
<?php component('partials/navbar') ?>
<?php component('partials/sidebar') ?>
<?php component('partials/notifications') ?>
<div class="main-content" x-data="page" x-init="$watch('year', v => updateData());$watch('month', v => updateData())">
    <div class="appointments-container">
        <div class="appointments-header">
            <h1>Work Attendance & Absence Management</h1>
            <div class="header-actions">
                <select x-init="$el.value = year" @change="year = +$el.value;console.log(year, month)" class="filter-select" id="yearFilter">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                </select>
                <select x-init="$el.value = month" @change="month = +$el.value ?? 1" class="filter-select" id="monthFilter">
                    <option value="1">Janvier</option>
                    <option value="2">Février</option>
                    <option value="3">Mars</option>
                    <option value="4">Avril</option>
                    <option value="5">Mai</option>
                    <option value="6">Juin</option>
                    <option value="7">Juillet</option>
                    <option value="8">Auot</option>
                    <option value="9">Septembre</option>
                    <option value="10">Octobre</option>
                    <option value="11">Novembre</option>
                    <option value="12">Decembre</option>
                </select>
            </div>
        </div>

        <div class="employee-info-card">
            <div class="employee-header">
                <div class="employee-avatar">
                    <img src="<?= $user['photo_profile'] ?? 'https://ui-avatars.com/api/?name=' . $user['nom'] . ' ' . $user['prenom'] ?>" alt="Employee Avatar">
                </div>
                <div class="employee-details">
                    <h2><?= $user['nom'] . ' ' . $user['prenom'] ?></h2>
                    <p>Employee Matricule: <?= $user['matricule'] ?></p>
                    <p>Department: <?= $user['department']['nom'] ?></p>
                    <p>Service: <?= $user['service']['nom'] ?></p>
                    <p>Position: <?= $user['role']['nom'] ?></p>
                </div>
                <div class="employee-stats">
                    <div class="stat-item">
                        <span class="stat-label">Base Salary</span>
                        <span class="stat-value" x-text="`${base_payment}da`"></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Current Month</span>
                        <span class="stat-value" x-text="`${calcNewPayment}da`">$<?= $user['base_salary'] ?? 500000 ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Monthly Work Hours</h3>
                </div>
                <div class="chart-container">
                    <canvas id="workHoursChart"></canvas>
                </div>
                <div class="absence-stats">
                    <div class="stat-box">
                        <div class="label">abcenses mensual (heur)</div>
                        <div class="value" x-text="`${hoursAbsent}h`"></div>
                    </div>
                    <div class="stat-box">
                        <div class="label">Required</div>
                        <div class="value" x-text="`${requiredHours}h`">176h</div>
                    </div>
                    <div class="stat-box">
                        <div class="label">Difference</div>
                        <div class="value" x-text="requiredHours - hoursAbsent"></div>
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Absence Distribution</h3>
                </div>
                <div class="chart-container">
                    <canvas id="absenceDistributionChart"></canvas>
                </div>
                <div class="absence-stats">
                    <div class="stat-box">
                        <div class="label">Late Arrivals</div>
                        <div class="value">60%</div>
                    </div>
                    <div class="stat-box">
                        <div class="label">Early Leaves</div>
                        <div class="value">30%</div>
                    </div>
                    <div class="stat-box">
                        <div class="label">Full Day</div>
                        <div class="value">10%</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="attendance-summary">
            <h2>Attendance Summary</h2>
            <div class="summary-grid">
                <div class="summary-card">
                    <h3>Total Work Hours</h3>
                    <div class="summary-value" x-text="`${requiredHours - hoursAbsent}h`">160h</div>
                    <div class="summary-trend negative" x-text="`${(requiredHours - hoursAbsent) - requiredHours}h from required`">-16h from required</div>
                </div>
                <div class="summary-card">
                    <h3>Pay Impact</h3>
                    <div class="summary-value" x-text="`${base_payment - (penalty * hoursAbsent)}da`">$200</div>
                    <div class="summary-trend negative" x-text="`${penalty * hoursAbsent}da`">-$200 from base</div>
                </div>
                <div class="summary-card">
                    <h3>Attendance Rate</h3>
                    <div class="summary-value" x-text="`${(((requiredHours - hoursAbsent) / requiredHours) * 100).toFixed(2)}%`">91%</div>
                    <div class="summary-trend">Current month</div>
                </div>
            </div>
        </div>

        <div class="attendance-report">
            <h2>Attendance Report</h2>
            <div class="report-filters">
                <button class="btn btn-primary">Generate Report</button>
            </div>
            <div class="report-table">
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Hours Worked</th>
                        <th>Status</th>
                        <th>Pay Impact</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template>

                    </template>
                    <tr>
                        <td></td>
                        <td>09:15 AM</td>
                        <td>06:00 PM</td>
                        <td>8.5</td>
                        <td><span class="status late">Late</span></td>
                        <td>-$25</td>
                    </tr>
                    <tr>
                        <td>March 2, 2024</td>
                        <td>08:45 AM</td>
                        <td>05:30 PM</td>
                        <td>8.5</td>
                        <td><span class="status early">Early Leave</span></td>
                        <td>-$25</td>
                    </tr>
                    <tr>
                        <td>March 3, 2024</td>
                        <td>08:00 AM</td>
                        <td>06:00 PM</td>
                        <td>10.0</td>
                        <td><span class="status approved">Complete</span></td>
                        <td>$0</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .filter-select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: white;
    }

    .employee-info-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .employee-header {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .employee-avatar img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
    }

    .employee-details h2 {
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .employee-details p {
        color: var(--text-light);
        margin: 0.25rem 0;
    }

    .employee-stats {
        margin-left: auto;
        display: flex;
        gap: 2rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-label {
        display: block;
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .stat-value {
        display: block;
        color: var(--primary-color);
        font-size: 1.2rem;
        font-weight: 600;
        margin-top: 0.25rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .summary-card {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
    }

    .summary-card h3 {
        color: var(--text-light);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .summary-value {
        color: var(--primary-color);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .summary-trend {
        font-size: 0.8rem;
        color: var(--text-light);
    }

    .summary-trend.positive {
        color: #10b981;
    }

    .summary-trend.negative {
        color: #ef4444;
    }

    .absence-report {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .report-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .report-table {
        overflow-x: auto;
    }

    .report-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table th,
    .report-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .report-table th {
        background: var(--bg-color);
        font-weight: 600;
    }

    .status {
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .status.late {
        background: #fef3c7;
        color: #f59e0b;
    }

    .status.early {
        background: #fee2e2;
        color: #ef4444;
    }

    .status.approved {
        background: #dcfce7;
        color: #10b981;
    }

    .attendance-summary {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin: 2rem 0;
    }

    .attendance-report {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .employee-header {
            flex-direction: column;
            text-align: center;
        }

        .employee-stats {
            margin: 1rem 0 0 0;
            justify-content: center;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>


    // Update charts when filters change
    function updateReport() {
        const year = document.getElementById('yearFilter').value;
        const month = document.getElementById('monthFilter').value;
        const reportType = document.getElementById('reportType').value;

        // Here you would typically make an AJAX call to fetch updated data
        console.log(`Updating report for ${year}-${month}, type: ${reportType}`);

        // Update charts with new data
        // This would be populated with actual data from your backend
        const newData = {
            hours: [170, 165, 160, 175, 168, 172],
            distribution: [60, 30, 10]
        };

        // Update charts with new data
        // (Implementation would depend on your data structure)
    }
</script>

<script>
    document.addEventListener('alpine:init', function(){
        Alpine.data('page', function (){
            return {
                absences: JSON.parse('<?= json_encode($all_absences) ?>'),
                absences_to_show : [],
                year: (new Date()).getFullYear(),
                month: (new Date()).getMonth() + 1,
                donutChart : null,
                barsChart: null,
                requiredHours : 0,
                hoursAbsent : 0,
                base_payment : <?= $user['base_salary'] ?? 50000 ?>,
                penalty : <?= 150 ?>,

                get calcNewPayment(){
                    return this.base_payment - (this.penalty * this.hoursAbsent);
                },
                init(){
                    this.absences_to_show = this.absences.filter(absence => {
                        return (new Date(absence.date)).getFullYear() === this.year && (new Date(absence.date)).getMonth() === this.month - 1;
                    })

                    this.requiredWorks()
                    this.monthlyAbsenses()

                    if( this.donutChart === null || this.barsChart === null ){
                        this.initCharts()
                    }else{
                        this.updateCharts()
                    }
                },

                updateData(){
                    this.init()
                },

                monthlyAbsenses(){
                    console.log(this.absences_to_show.reduce((e, f) => e['late_hours'] + f['late_hours'], 0))
                    this.hoursAbsent = this.absences_to_show.reduce( function(sum, abs){
                        console.log(sum, abs)
                        return sum + (abs['late_hours'] ?? 0)
                    }, 0)
                },

                requiredWorks(){
                    this.requiredHours =  this.absences_to_show.reduce( function(sum, abs){
                        return sum + (timeDiffInHours(abs['on_duty'], abs['off_duty']))
                    }, 0)
                },

                updateCharts(){

                    this.barsChart.date = {
                        labels: ['Sam','Dim', 'Lun', 'Mar', 'Mer', 'Jeu'],
                        datasets: [{
                            label: 'heur absents',
                            data: [...this.groupByDay()],
                            backgroundColor: 'rgba(0, 102, 204, 0.2)',
                            borderColor: 'rgba(0, 102, 204, 1)',
                            borderWidth: 1
                        }]
                    }
                    this.barsChart.update()

                    this.donutChart.data = {
                        labels: ['Late Arrivals', 'Early Leaves', 'Full Day'],
                        datasets: [{
                            data: [this.lateArrivals().length, this.earlyLeaves().length, this.fullDay().length],
                            backgroundColor: [
                                'rgba(245, 158, 11, 0.2)',
                                'rgba(239, 68, 68, 0.2)',
                                'rgba(16, 185, 129, 0.2)'
                            ],
                            borderColor: [
                                'rgba(245, 158, 11, 1)',
                                'rgba(239, 68, 68, 1)',
                                'rgba(16, 185, 129, 1)'
                            ],
                            borderWidth: 1
                        }]
                    }
                    this.donutChart.update()

                },

                lateArrivals(){
                    return this.absences_to_show.filter((late) => {
                        if( late['is_absent'] == 0 && late['late_hours'] > 0 && late['on_duty'] < late['clock_in'] ){

                            return true;
                        }
                    })
                },

                earlyLeaves(){
                    return  this.absences_to_show.filter((late) => {
                        if( late['is_absent'] == 0 && late['late_hours'] > 0 && late['clock_out'] < late['off_duty'] ){
                            return true;
                        }
                    })
                },

                fullDay(){
                    return this.absences_to_show.filter((late) => {
                        if( late['is_absent'] && !late['justification'] && late['late_hours'] > 0){
                            return true;
                        }
                    })
                },

                groupByDay(){
                    let d = (new Array(6)).fill(0)
                    this.absences_to_show.forEach(absence => {
                        let date = new Date(absence.date)
                        let day = date.getDay()
                        d[day] += absence['late_hours']
                    })
                    console.log(d)

                    return d
                },


                initCharts(){
                    const hoursCtx = document.getElementById('workHoursChart').getContext('2d');
                    let c1 = new Chart(hoursCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Sam','Dim', 'Lun', 'Mar', 'Mer', 'Jeu'],
                            datasets: [{
                                label: 'heur absents',
                                data: [...this.groupByDay()],
                                backgroundColor: 'rgba(0, 102, 204, 0.2)',
                                borderColor: 'rgba(0, 102, 204, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jours'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });

                    const distributionCtx = document.getElementById('absenceDistributionChart').getContext('2d');
                    let c2 = new Chart(distributionCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Late Arrivals', 'Early Leaves', 'Full Day'],
                            datasets: [{
                                data: [this.lateArrivals().length, this.earlyLeaves().length, this.fullDay().length],
                                backgroundColor: [
                                    'rgba(245, 158, 11, 0.2)',
                                    'rgba(239, 68, 68, 0.2)',
                                    'rgba(16, 185, 129, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(245, 158, 11, 1)',
                                    'rgba(239, 68, 68, 1)',
                                    'rgba(16, 185, 129, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });

                    Object.seal(c2)
                    Object.seal(c1)
                    this.barsChart = c1
                    this.donutChart = c2
                }
            }
        })
    })
</script>

<?php component('utils/status') ?>
</body>

</html>