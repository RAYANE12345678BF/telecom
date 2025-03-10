<?php

include __DIR__ . '/../../../vendor/autoload.php';

if (! session_id()) {
    session_start();
}

redirect_if_not_auth();

$user = fetch_user_information($_SESSION['user_id']);

$work_days = fetch_work_days($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Interface Complète</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
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
            background-color: #f9f9f9;
            border-left: 2px solid var(--accent-color);
            margin-left: 10px;
            padding-left: 10px;
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

        /* Main Content Styles */
        .main-content {
            margin-left: 280px;
            padding: 90px 30px 30px;
            width: calc(100% - 280px);
        }

        /* Calendar Styles */
        #calendar {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .fc-daygrid-day-number {
            color: #003366;
        }

        .fc-day-sat,
        .fc-day-fri {
            background-color: #e6f3ff !important;
        }

        .holiday {
            background-color: #ffcccc !important;
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
                <a href="<?= url('/') ?>" class="menu-item active">
                    <i class="fas fa-home"></i>
                    <span>Accueil</span>
                </a>
                <a href="<?= url('profiles') ?>" class="menu-item">
                    <i class="fas fa-user-circle"></i>
                    <span>Mon Profil</span>
                </a>

                <div class="nav-title">Demandes</div>
                <div class="request-section">
                    <a href="#" class="menu-item" id="faireDemandeBtn">
                        <i class="fas fa-file-alt"></i>
                        <span class="menu-text">Faire une demande</span>
                    </a>
                    <div class="submenu" id="demandeSubmenu" style="display: none; padding-left: 20px;">
                        <div>
                            <a href="#" class="menu-item" id="congeBtn">
                                <i class="fas fa-calendar-alt"></i>
                                <span class="menu-text">Demande Congé</span>
                            </a>
                            <div class="submenu" id="congeSubmenu" style="display: none; padding-left: 20px;">
                                <a href="<?= url('profiles/employee/demands/conge/annual.php') ?>" class="menu-item">
                                    <i class="fas fa-sun"></i>
                                    <span class="menu-text">Congé Annuel</span>
                                </a>
                                <a href="<?= url('profiles/employee/demands/conge/malady.php') ?>" class="menu-item">
                                    <i class="fas fa-hospital"></i>
                                    <span class="menu-text">Congé Maladie</span>
                                </a>
                                <a href="<?= url('profiles/employee/demands/conge/maternity.php') ?>" class="menu-item">
                                    <i class="fas fa-baby"></i>
                                    <span class="menu-text">Congé Maternité</span>
                                </a>
                                <a href="<?= url('profiles/employee/demands/conge/rc.php') ?>" class="menu-item">
                                    <i class="fas fa-clock"></i>
                                    <span class="menu-text">Congé RC</span>
                                </a>
                            </div>
                        </div>
                        <a href="<?= url('profiles/employee/demands/formation') ?>" class="menu-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span class="menu-text">Demande Formation</span>
                        </a>
                        <a href="<?= url('profiles/employee/demands/mission') ?>" class="menu-item">
                            <i class="fas fa-plane"></i>
                            <span class="menu-text">Demande Ordre Mission</span>
                        </a>
                        <a href="<?= url('profiles/employee/demands/deplacement') ?>" class="menu-item">
                            <i class="fas fa-car"></i>
                            <span class="menu-text">Demande Déplacement</span>
                        </a>
                        <a href="<?= url('profiles/employee/demands/leave') ?>" class="menu-item">
                            <i class="fas fa-door-open"></i>
                            <span class="menu-text">Demande Sortie</span>
                        </a>
                    </div>
                    <a href="<?= url('profiles/employee/demands/list') ?>" class="menu-item">
                        <i class="fas fa-tasks"></i>
                        <span class="menu-text">État de demande</span>
                    </a>
                </div>

                <div class="nav-title">Autres</div>
                <a href="<?= url('profiles/employee/support') ?>" class="menu-item">
                    <i class="fas fa-question-circle"></i>
                    <span class="menu-text">Support</span>
                </a>
                <!-- Nouveau bouton "Calendrier RC d'Employé" -->
                <a href="<?= url('profiles/employee/calendrier') ?>" class="menu-item">
                    <i class="fas fa-calendar"></i>
                    <span class="menu-text">Calendrier RC d'Employé</span>
                </a>
            </div>
        </div>
        <div class="user-section" id="logoutButton">
            <div class="user-avatar">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <span>Se déconnecter</span>
        </div>
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

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="text-center mb-4">Calendrier Employé</h1>
        <div id="calendar"></div>
        <div class="mt-4">
            <h3>Jours de Repos Compensatoire (RC) :</h3>
            <p id="rcDaysResult">Aucun jour de RC calculé pour le moment.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.min.js"></script>
    
    <script>
        async function insertDateToDatabse(dateStr) {
            let data = new FormData
            data.append('date', dateStr)
            data.append('action', 'add_work_day')
            return await fetch("<?= url('actions/rc.php') ?>", {
                method: 'POST',
                body: data
            })
        }

        async function removeDateFromDatabase(dateStr) {
            let data = new FormData
            data.append('date', dateStr)
            data.append('action', 'remove_work_day')
            return await fetch("<?= url('actions/rc.php') ?>", {
                method: 'POST',
                body: data
            })
        }
    </script>
    
    <script>
        const holidays = [
            '2024-01-01',
            '2024-05-01',
            '2024-07-05',
            '2024-12-25',
        ];

        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                selectable: true,
                selectAllow: function (selectInfo) {
                    const selectedDate = new Date(selectInfo.startStr);
                    selectedDate.setHours(0, 0, 0, 0);

                    if (selectedDate > today) {
                        alert("Vous ne pouvez pas sélectionner un jour futur !");
                        return false;
                    }

                    return true;
                },
                select: function (selectInfo) {
                    const selectedDate = new Date(selectInfo.startStr);
                    selectedDate.setHours(0, 0, 0, 0);
                    const dateStr = selectInfo.startStr;
                    const day = selectedDate.getDay();
                    let message = '';

                    if (holidays.includes(dateStr)) {
                        message = `⚠ Vous avez travaillé un jour férié (${dateStr}).`;
                    } else if (day === 5) {
                        message = `✔ Vous avez travaillé un vendredi (${dateStr}).`;
                    } else if (day === 6) {
                        message = `✔ Vous avez travaillé un samedi (${dateStr}).`;
                    }

                    let dates = calendar.getEvents().map(e => e.startStr)

                    if (dates.includes(dateStr)) {
                        alert("Vous avez déjà travaillé ce jour.")
                        return
                    }

                    insertDateToDatabse(dateStr)
                        .then(res => res.json())
                        .then(json => {
                            if (json.success) {
                                if (message) {
                                    alert(message);
                                }

                                calendar.addEvent({
                                    title: 'Jour travaillé',
                                    start: dateStr,
                                    backgroundColor: holidays.includes(dateStr) ? '#FF5733' : '#003366',
                                    borderColor: holidays.includes(dateStr) ? '#FF5733' : '#003366',
                                });
                            } else {
                                alert('some error occured please retry')
                            }
                        })

                    if (message) {
                        alert(message);
                    }

                    calculateRCDays();
                },
                eventClick: function (info) {
                    if (confirm('Voulez-vous supprimer ce jour travaillé ?')) {
                        
                        if( info.event.title.includes('(B)') ){
                            alert('you can not remove  date you benefited from')
                            return
                        }
                        
                        removeDateFromDatabase(info.event.startStr)
                            .then(res => res.json())
                            .then(json => {
                                if (json.success) {
                                    info.event.remove();
                                } else {
                                    alert('some error occured when trying to remove this day')
                                }
                            })
                        calculateRCDays();
                    }
                },
                dayCellDidMount: function (info) {
                    const dateStr = info.date.toISOString().split('T')[0];
                    if (holidays.includes(dateStr)) {
                        info.el.classList.add('holiday');
                    }
                }
            });

            var work_days = JSON.parse(`<?= json_encode($work_days['data']) ?>`);

            console.info('work', work_days)

            work_days.forEach(day => {
                calendar.addEvent({
                    benefited : day.benefited,
                    title: `Jour travaillé ${day.benefited ? '(B)' : ''}`,
                    start: day.date,
                    backgroundColor: holidays.includes(day.date) ? '#FF5733' : '#003366',
                    borderColor: holidays.includes(day.date) ? '#FF5733' : '#003366',
                });
            })

            calendar.render();

            function calculateRCDays() {
                const events = calendar.getEvents();
                let rcDays = 0;

                events.forEach(event => {
                    const date = event.start;
                    const day = date.getDay();

                    if (day === 5) {
                        rcDays += 2;
                    } else if (day === 6) {
                        rcDays += 1;
                    }
                });

                document.getElementById('rcDaysResult').textContent = `Jours de RC accumulés : ${rcDays}`;
            }
        });

        // Navigation menu toggle functions
        document.getElementById('faireDemandeBtn').addEventListener('click', function (e) {
            e.preventDefault();
            const submenu = document.getElementById('demandeSubmenu');
            submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
        });
        document.getElementById('logoutButton').addEventListener('click', function () {
            window.location.href = 'loginAT1.html';
        });
        document.getElementById('congeBtn').addEventListener('click', function (e) {
            e.preventDefault();
            const submenu = document.getElementById('congeSubmenu');
            submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
        });

        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function (e) {
                if (this !== document.getElementById('faireDemandeBtn') && this !== document.getElementById('congeBtn')) {
                    document.querySelectorAll('.submenu').forEach(sub => {
                        sub.style.display = 'none';
                    });
                }
            });
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
        document.getElementById('logoutButton').addEventListener('click', function () {
            window.location.href = 'loginAT1.html';
        });
        // Fermer les menus déroulants si on clique en dehors
        document.addEventListener('click', function (event) {
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

        // Consulter Demandes functions
        function toggleDropdown(requestId) {
            const dropdown = document.getElementById(`dropdown-${requestId}`);
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        function acceptRequest(requestId) {
            const statusCell = document.querySelector(`#request-${requestId} .status`);
            statusCell.textContent = "Accepté";
            statusCell.style.color = "#28a745"; // Vert pour indiquer l'acceptation
            closeDropdown(requestId);
        }

        function refuseRequest(requestId) {
            const statusCell = document.querySelector(`#request-${requestId} .status`);
            statusCell.textContent = "Refusé";
            statusCell.style.color = "#dc3545"; // Rouge pour indiquer le refus
            closeDropdown(requestId);
        }

        function closeDropdown(requestId) {
            const dropdown = document.getElementById(`dropdown-${requestId}`);
            dropdown.style.display = "none";
        }
    </script>
</body>

</html>