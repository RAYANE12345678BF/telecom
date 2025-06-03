<?php
require_once __DIR__ . '/../../vendor/autoload.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Demande de Congé</title>
    <?php component('partials/include'); ?>

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
            --leave-primary: #1e3a8a;
            --leave-primary-hover: #172554;
            --leave-secondary: #3b82f6;
            --leave-success: #10b981;
            --leave-error: #ef4444;
            --leave-light-gray: #f8fafc;
            --leave-dark-gray: #1e293b;
            --leave-border-radius: 10px;
            --leave-box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .main-content {
            margin-left: 280px;
            padding: 90px 30px 30px;
            width: calc(100% - 280px);
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

        /* Styles de la sidebar */
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

        /* Navbar styles */
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

        /* Content area */
        .content {
            margin-top: var(--nav-height);
            padding: 30px;
            flex-grow: 1;
            background: var(--bg-color);
            min-height: calc(100vh - var(--nav-height));
        }

        /* Styles pour le formulaire de congé */
        .leave-container {
            max-width: 650px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            border: 1px solid #e2e8f0;
        }

        .leave-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .leave-header h2 {
            color: var(--leave-primary);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .leave-header p {
            color: var(--leave-secondary);
            font-size: 0.9rem;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--leave-primary);
        }

        .form-group .input-icon {
            position: absolute;
            top: 38px;
            left: 12px;
            color: var(--leave-secondary);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #cbd5e1;
            border-radius: var(--leave-border-radius);
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--leave-light-gray);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--leave-secondary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            background-color: white;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
            padding-left: 1rem;
        }

        .date-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--leave-border-radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--leave-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--leave-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(30, 58, 138, 0.2);
        }

        .btn-secondary {
            background-color: white;
            color: var(--leave-primary);
            border: 1px solid var(--leave-primary);
        }

        .btn-secondary:hover {
            background-color: #eff6ff;
        }

        .btn-icon {
            margin-right: 8px;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-full {
            width: 100%;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            gap: 0.5rem;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e2e8f0;
            color: var(--leave-secondary);
            font-weight: 600;
            position: relative;
        }

        .step.active {
            background-color: var(--leave-primary);
            color: white;
        }

        .step.completed {
            background-color: var(--leave-secondary);
            color: white;
        }

        .step-line {
            height: 2px;
            background-color: #e2e8f0;
            flex: 1;
            margin-top: 14px;
        }

        .step-line.completed {
            background-color: var(--leave-secondary);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: var(--leave-primary);
        }

        .summary-value {
            font-weight: 500;
            color: var(--leave-dark-gray);
        }

        .days-count {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background-color: #dbeafe;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
            color: var(--leave-primary);
        }

        .alert {
            padding: 1rem;
            border-radius: var(--leave-border-radius);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .alert-info {
            background-color: #dbeafe;
            color: var(--leave-primary);
            border-left: 4px solid var(--leave-secondary);
        }

        .alert-icon {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            color: var(--leave-secondary);
        }

        .hidden {
            display: none;
        }

        .nav-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin: 20px 0 10px 18px;
            font-weight: 600;
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
        }

        @media (max-width: 768px) {
            .leave-container {
                padding: 1.5rem;
                margin: 1rem;
            }

            .date-inputs {
                grid-template-columns: 1fr;
            }
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

<!-- Content Area -->
<div class="main-content">
    <div class="container">
        <div class="content">
            <div class="leave-container">
                <div class="leave-header">
                    <h2><i class="fas fa-calendar-alt btn-icon"></i> Planing des Congés Annuels</h2>
                    <p>Remplissez le formulaire pour soumettre votre demande de Planing des Congés </p>
                </div>

                <div class="step-indicator">
                    <div class="step active">1</div>
                    <div class="step-line"></div>
                    <div class="step">2</div>
                    <div class="step-line"></div>
                    <div class="step">3</div>
                </div>

                <!-- Étape 1: Dates du congé -->
                <div id="step1" class="form-step active">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle alert-icon"></i>
                        <div>Votre solde actuel de congés est de <strong>22 jours</strong>.</div>
                    </div>

                    <div class="form-group">
                        <label>Type de congé</label>
                        <div class="input-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <input type="text" class="form-control" value="Congé annuel" readonly>
                        <input type="hidden" id="leave-type" value="annual">
                    </div>

                    <div class="date-inputs">
                        <div class="form-group">
                            <label for="start-date">Date de début</label>
                            <div class="input-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <input type="date" id="start-date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="end-date">Date de fin</label>
                            <div class="input-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <input type="date" id="end-date" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="destination">Destination <small>(facultatif)</small></label>
                        <div class="input-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <input type="text" id="destination" class="form-control" placeholder="Ex: Béjaïa, Alger, etc.">
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-full" onclick="nextStep(1, 2)">
                            Suivant <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Étape 2: Détails supplémentaires -->
                <div id="step2" class="form-step">
                    <div class="form-group">
                        <label for="note">Motif / Remarques</label>
                        <textarea id="note" class="form-control"
                                  placeholder="Décrivez la raison de votre congé..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact pendant le congé</label>
                        <div class="input-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <input type="tel" id="contact" class="form-control" placeholder="Numéro de téléphone">
                    </div>
                    <div class="form-group">
                        <label for="file">Joindre un document <small>(facultatif)</small></label>
                        <input type="file" id="file" class="form-control" style="padding: 0.5rem;">
                        <small class="text-muted">Format acceptés: PDF, JPG, PNG (max 5MB)</small>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(2, 1)">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(2, 3)">
                            Suivant <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                <!-- Étape 3: Récapitulatif -->
                <div id="step3" class="form-step">
                    <h3 style="margin-bottom: 1.5rem; text-align: center;">Récapitulatif de votre demande</h3>

                    <div class="summary-item">
                        <div><strong>Type de congé:</strong> <span id="summary-type">-</span></div>
                    </div>
                    <div class="summary-item">
                        <div><strong>Période:</strong> <span id="summary-dates">-</span></div>
                    </div>
                    <div class="summary-item">
                        <div><strong>Durée:</strong> <span id="summary-duration">-</span></div>
                    </div>
                    <div class="summary-item">
                        <div><strong>Destination:</strong> <span id="summary-destination">-</span></div>
                    </div>
                    <div class="summary-item">
                        <div><strong>Motif:</strong> <span id="summary-note">-</span></div>
                    </div>

                    <div class="alert alert-info" style="margin-top: 1.5rem;">
                        <i class="fas fa-info-circle alert-icon"></i>
                        <div>Votre demande sera soumise pour approbation à votre responsable.</div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(3, 2)">
                            <i class="fas fa-arrow-left"></i> Retour
                        </button>
                        <button type="submit" class="btn btn-primary" id="soumettre">
                            <i class="fas fa-paper-plane" ></i> Soumettre
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Navigation menu toggle functions
    document.getElementById('faireDemandeBtn').addEventListener('click', function (e) {
        e.preventDefault();
        const submenu = document.getElementById('demandeSubmenu');
        submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
    });

    document.getElementById('congeBtn').addEventListener('click', function (e) {
        e.preventDefault();
        const submenu = document.getElementById('congeSubmenu');
        submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
    });

    // Logout function
    document.getElementById('logoutButton').addEventListener('click', function () {
        window.location.href = 'loginAT1.html';
    });

    // Bloquer les dates avant demain
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    document.getElementById("start-date").min = tomorrow.toISOString().split('T')[0];

    function nextStep(current, next) {
        let leaveType, startDate, endDate, start, end, diffTime, diffDays, destination, note, contact, file;
        if (current === 1) {
            leaveType = document.getElementById('leave-type').value;
            startDate = document.getElementById('start-date').value;
            endDate = document.getElementById('end-date').value;

            if (!startDate || !endDate) {
                alert('Veuillez choisir une date de début et une date de fin.');
                return;
            }

            start = new Date(startDate);
            end = new Date(endDate);
            diffTime = Math.abs(end - start);
            diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            if (diffDays > 22) {
                alert("Vous ne pouvez pas demander plus de 22 jours de congé.");
                return;
            }

            document.getElementById('summary-type').textContent = "Congé annuel";
            document.getElementById('summary-dates').textContent = `${formatDate(startDate)} au ${formatDate(endDate)}`;
            document.getElementById('summary-duration').textContent = `${diffDays} jour(s)`;
            document.getElementById('summary-destination').textContent = document.getElementById('destination').value || 'Non spécifié';


        }

        if (current === 2) {
            note = document.getElementById('summary-note').textContent = document.getElementById('note').value || 'Non spécifié';
            var btn = document.getElementById('soumettre');

            console.log(btn)
            btn.onclick = function () {
                leaveType = document.getElementById('leave-type').value;
                startDate = document.getElementById('start-date').value;
                endDate = document.getElementById('end-date').value;

                let data = new FormData();
                data.append('leaveType', leaveType);
                data.append('startDate', startDate);
                data.append('endDate', endDate);
                data.append('destination', document.getElementById('destination').value || 'Non spécifié');
                data.append('motif', document.getElementById('note').value);
                data.append('contact', document.getElementById('contact').value);
                data.append('file', document.getElementById('file').files[0]);
                data.append('note', note);

                btn.disabled = true;

                fetch('http://localhost:8000/actions/planify.php', {
                    body: data,
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    }
                }).then(res => res.json())
                    .then(json => {
                        if (json.success) {
                            window.location.href = json.redirection_url;
                        } else {
                            Swal.fire({
                                icon: 'erreur',
                                text: json.message,
                                title: 'erreur'
                            })
                        }
                    }).catch(err => alert(err))

                btn.disabled = false
            }
        }


        document.getElementById(`step${current}`).classList.remove('active');
        document.getElementById(`step${next}`).classList.add('active');
        updateStepIndicator(current, next);
    }

    function prevStep(current, prev) {
        document.getElementById(`step${current}`).classList.remove('active');
        document.getElementById(`step${prev}`).classList.add('active');
        updateStepIndicator(current, prev, false);
    }

    function updateStepIndicator(from, to, forward = true) {
        const steps = document.querySelectorAll('.step');
        const lines = document.querySelectorAll('.step-line');
        if (forward) {
            steps[from - 1].classList.remove('active');
            steps[from - 1].classList.add('completed');
            steps[to - 1].classList.add('active');
            if (from > 1) {
                lines[from - 2].classList.add('completed');
            }
        } else {
            steps[from - 1].classList.remove('active');
            steps[to - 1].classList.add('active');
            steps[to - 1].classList.remove('completed');
            if (to < from) {
                lines[to - 1].classList.remove('completed');
            }
        }
    }

    function formatDate(dateString) {
        const options = {day: 'numeric', month: 'long', year: 'numeric'};
        return new Date(dateString).toLocaleDateString('fr-FR', options);
    }

    function calculateDuration() {
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;

        if (!startDate || !endDate) return;

        const start = new Date(startDate);
        const end = new Date(endDate);

        if (start > end) {
            alert("La date de fin doit être après la date de début");
            document.getElementById('end-date').value = '';
            return;
        }

        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

        if (diffDays > 22) {
            alert("Vous ne pouvez pas demander plus de 22 jours de congé.");
            document.getElementById('end-date').value = '';
        }
    }

    document.getElementById('start-date').addEventListener('change', calculateDuration);
    document.getElementById('end-date').addEventListener('change', calculateDuration);

    // Fonction pour afficher/masquer les notifications
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('show');
    }

    // Fonction pour afficher/masquer la messagerie
    function toggleMessenger() {
        const dropdown = document.getElementById('messengerDropdown');
        dropdown.classList.toggle('show');
    }
</script>

<?php component('utils/status'); ?>
</body>
</html>