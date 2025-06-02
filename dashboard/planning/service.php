                       <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Planning de Congé 2025</title>
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
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
        }

        /* Sidebar styles */
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

        /* Main content styles */
        .content {
            margin-top: var(--nav-height);
            margin-left: 280px;
            padding: 30px;
            flex-grow: 1;
            background: var(--bg-color);
            min-height: calc(100vh - var(--nav-height));
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .company-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .info-card {
            background: white;
            padding: 15px;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            flex: 1;
            min-width: 250px;
        }

        .info-card h3 {
            color: var(--primary-color);
            font-size: 18px;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .info-card p {
            margin: 5px 0;
        }

        .table-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-x: auto;
            max-width: 1200px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
        }

        tr:hover {
            background-color: var(--hover-color);
        }

        /* Column widths */
        th:nth-child(1), td:nth-child(1) { width: 5%; }   /* N° */
        th:nth-child(2), td:nth-child(2) { width: 20%; }  /* Nom */
        th:nth-child(3), td:nth-child(3) { width: 15%; }  /* Grade */
        th:nth-child(4), td:nth-child(4) { width: 15%; }  /* Date */
        th:nth-child(5), td:nth-child(5) { width: 10%; }  /* Durée */
        th:nth-child(6), td:nth-child(6) { width: 15%; }  /* Type */
        th:nth-child(7), td:nth-child(7) { width: 20%; }  /* Actions */

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: var(--border-radius);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(102, 179, 255, 0.2);
        }

        /* Notification dropdown styles */
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

        /* Messenger dropdown styles */
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

        /* Animations */
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

        @media (max-width: 768px) {
            .company-info {
                flex-direction: column;
            }
            
            .info-card {
                margin-right: 0;
                margin-bottom: 10px;
            }
            
            th, td {
                padding: 8px 10px;
                font-size: 14px;
            }
            
            .table-container {
                padding: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
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
input[type="date"] {
  width: 140px;
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
                <a href="accueil.html" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Accueil</span>
                </a>
                <a href="profile_drh.html" class="menu-item">
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
                                <a href="annuel_drh.html" class="menu-item">
                                    <i class="fas fa-sun"></i>
                                    <span class="menu-text">Congé Annuel</span>
                                </a>
                                <a href="maladie_drh.html" class="menu-item">
                                    <i class="fas fa-hospital"></i>
                                    <span class="menu-text">Congé Maladie</span>
                                </a>
                                <a href="maternite_drh.html" class="menu-item">
                                    <i class="fas fa-baby"></i>
                                    <span class="menu-text">Congé Maternité</span>
                                </a>
                                <a href="rc_drh.html" class="menu-item">
                                    <i class="fas fa-clock"></i>
                                    <span class="menu-text">Congé RC</span>
                                </a>
                            </div>
                        </div>
                        <a href="formation_drh.html" class="menu-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span class="menu-text">Demande Formation</span>
                        </a>
                        <a href="mission_drh.html" class="menu-item">
                            <i class="fas fa-plane"></i>
                            <span class="menu-text">Demande Ordre Mission</span>
                        </a>
                        <a href="Déplacement_drh.html" class="menu-item">
                            <i class="fas fa-car"></i>
                            <span class="menu-text">Demande Déplacement</span>
                        </a>
                        <a href="sortie_drh.html" class="menu-item">
                            <i class="fas fa-door-open"></i>
                            <span class="menu-text">Demande Sortie</span>
                        </a>
                    </div>
                    <a href="etat_demande_drh.html" class="menu-item">
                        <i class="fas fa-tasks"></i>
                        <span class="menu-text">État de demande</span>
                    </a>
                    <a href="consulter_demande_drh.html" class="menu-item">
                        <i class="fas fa-eye"></i>
                        <span class="menu-text">Consulter Demande</span>
                    </a>
                    <a href="voir_pointage_drh.html" class="menu-item">
                        <i class="fas fa-clock"></i>
                        <span class="menu-text">Voir Pointage</span>
                    </a>
                </div>
                
                <div class="nav-title">Autres</div>
                <a href="support_drh.html" class="menu-item">
                    <i class="fas fa-question-circle"></i>
                    <span class="menu-text">Support</span>
                </a>
                <a href="calendrier_drh.html" class="menu-item active">
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
                <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
            </div>
            <div class="icon-wrapper" onclick="toggleMessenger()">
                <i class="fa-brands fa-facebook-messenger"></i>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="content">
        <div class="header">
            <h1><i class="fas fa-calendar-alt"></i> Planning de Congé 2025</h1>
        </div>
        
        <div class="company-info">
            <div class="info-card">
                <h3>Société</h3>
                <p><strong>Centre:</strong> CEPI</p>
                <p><strong>Tél:</strong> 0</p>
                <p><strong>Fax:</strong> 0</p>
            </div>
            
            <div class="info-card">
                <h3>Département</h3>
                <p><strong>Service:</strong> Générale</p>
                <p><strong>Régime:</strong> 0</p>
                <p><strong>Fax:</strong> 0</p>
            </div>
            
            <div class="info-card">
                <h3>Période</h3>
                <p><strong>Du:</strong> 14/01/2025</p>
                <p><strong>Au:</strong> 31/12/2025</p>
            </div>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom et Prénom</th>
                        <th>Grade</th>
                        <th>Date de Congé</th>
                        <th>Durée (jours)</th>
                        <th>Type de Congé</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>AABLI Bussi</td>
                        <td>Chef de Centre</td>
                        <td><input type="date" class="form-control"></td>
                        <td><input type="number" class="form-control" min="1"></td>
                        <td>
                            <select class="form-control">
                                <option>Annuel</option>
                                
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Enregistrer</button>
                            <button class="btn btn-secondary btn-sm"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>BOURBET Weld</td>
                        <td>Supervision</td>
                        <td><input type="date" class="form-control"></td>
                        <td><input type="number" class="form-control" min="1"></td>
                        <td>
                            <select class="form-control">
                                <option>Annuel</option>
                               
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Enregistrer</button>
                            <button class="btn btn-secondary btn-sm"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>SECANNIA Hueven</td>
                        <td>Supervision</td>
                        <td><input type="date" class="form-control"></td>
                        <td><input type="number" class="form-control" min="1"></td>
                        <td>
                            <select class="form-control">
                                <option>Annuel</option>
                               
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Enregistrer</button>
                            <button class="btn btn-secondary btn-sm"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Abbass Ayres</td>
                        <td>Apprenti</td>
                        <td><input type="date" class="form-control"></td>
                        <td><input type="number" class="form-control" min="1"></td>
                        <td>
                            <select class="form-control">
                                <option>Annuel</option>
                                
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Enregistrer</button>
                            <button class="btn btn-secondary btn-sm"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Vocal Oble</td>
                        <td>Apprenti</td>
                        <td><input type="date" class="form-control"></td>
                        <td><input type="number" class="form-control" min="1"></td>
                        <td>
                            <select class="form-control">
                                <option>Annuel</option>
                                
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Enregistrer</button>
                            <button class="btn btn-secondary btn-sm"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="action-buttons">
                <button class="btn btn-secondary"><i class="fas fa-print"></i> Imprimer</button>
              
<button class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Soumettre</button>

                <button class="btn btn-primary" id="addEmployeeBtn"><i class="fas fa-plus"></i> Ajouter Employé</button>
            </div>
        </div>
    </div>
    
    <!-- Notification dropdown -->
    <div class="notification-dropdown" id="notificationDropdown">
        <div class="no-notifications">
            Aucune notification pour le moment.
        </div>
    </div>
    
    <!-- Messenger dropdown -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        // Logout button
        document.getElementById('logoutButton').addEventListener('click', function() {
            window.location.href = 'loginAT1.html';
        });

        // Fonction pour ajouter un nouvel employé au tableau
        document.getElementById('addEmployeeBtn').addEventListener('click', function() {
            const table = document.querySelector('tbody');
            const newRow = document.createElement('tr');
            
            // Comptez le nombre de lignes existantes pour déterminer le nouveau numéro
            const rowCount = table.querySelectorAll('tr').length + 1;
            
            newRow.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" class="form-control" placeholder="Nom et Prénom"></td>
                <td><input type="text" class="form-control" placeholder="Grade"></td>
                <td><input type="date" class="form-control"></td>
                <td><input type="number" class="form-control" min="1" placeholder="Jours"></td>
                <td>
                    <select class="form-control">
                        <option>Annuel</option>
                   
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary btn-sm save-btn"><i class="fas fa-save"></i> Enregistrer</button>
                    <button class="btn btn-secondary btn-sm delete-btn"><i class="fas fa-trash"></i></button>
                </td>
            `;
            
            table.appendChild(newRow);
            
            // Ajouter un gestionnaire d'événements pour le bouton de suppression
            newRow.querySelector('.delete-btn').addEventListener('click', function() {
                table.removeChild(newRow);
                updateRowNumbers();
            });
            
            // Ajouter un gestionnaire d'événements pour le bouton d'enregistrement
            newRow.querySelector('.save-btn').addEventListener('click', function() {
                const inputs = newRow.querySelectorAll('input');
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!input.value) {
                        input.style.borderColor = 'red';
                        isValid = false;
                    } else {
                        input.style.borderColor = '#ddd';
                    }
                });
                
                if (isValid) {
                    alert('Employé enregistré avec succès!');
                } else {
                    alert('Veuillez remplir tous les champs obligatoires');
                }
            });
        });
        
        // Fonction pour mettre à jour les numéros de ligne
        function updateRowNumbers() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.cells[0].textContent = index + 1;
            });
        }
        
        // Ajouter des gestionnaires d'événements pour les boutons de suppression existants
        document.querySelectorAll('.btn-secondary').forEach(button => {
            if (!button.id) { // Pour ne pas sélectionner le bouton "Ajouter Employé"
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    if (confirm('Voulez-vous vraiment supprimer cette ligne?')) {
                        row.parentNode.removeChild(row);
                        updateRowNumbers();
                    }
                });
            }
        });
        
        // Validation des dates
        document.addEventListener('change', function(e) {
            if (e.target.type === 'date') {
                const dateInput = e.target;
                const selectedDate = new Date(dateInput.value);
                const today = new Date();
                
                if (selectedDate < today) {
                    alert('La date ne peut pas être dans le passé');
                    dateInput.value = '';
                }
            }
        });

        // Fonction pour afficher/masquer les notifications
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }

            // Vérifier s'il y a des contacts
            const contactList = document.getElementById('contactList');
            const noMessages = document.getElementById('noMessages');

            if (contactList.children.length === 0) {
                noMessages.style.display = 'flex'; // Afficher le message
        // Fonction pour ajouter un nouvel employé au tableau
        document.getElementById('addEmployeeBtn').addEventListener('click', function() {
            const table = document.querySelector('tbody');
            const newRow = document.createElement('tr');
            
            // Comptez le nombre de lignes existantes pour déterminer le nouveau numéro
            const rowCount = table.querySelectorAll('tr').length + 1;
            
            newRow.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" class="form-control" placeholder="Nom et Prénom"></td>
                <td><input type="text" class="form-control" placeholder="Grade"></td>
                <td><input type="date" class="form-control"></td>
                <td><input type="number" class="form-control" min="1" placeholder="Jours"></td>
                <td>
                    <select class="form-control">
                        <option>Annuel</option>
                        <option>Maladie</option>
                        <option>Maternité</option>
                        <option>RC</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary btn-sm save-btn"><i class="fas fa-save"></i> Enregistrer</button>
                    <button class="btn btn-secondary btn-sm delete-btn"><i class="fas fa-trash"></i></button>
                </td>
            `;
            
            table.appendChild(newRow);
            
            // Ajouter un gestionnaire d'événements pour le bouton de suppression
            newRow.querySelector('.delete-btn').addEventListener('click', function() {
                table.removeChild(newRow);
                updateRowNumbers();
            });
            
            // Ajouter un gestionnaire d'événements pour le bouton d'enregistrement
            newRow.querySelector('.save-btn').addEventListener('click', function() {
                const inputs = newRow.querySelectorAll('input');
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!input.value) {
                        input.style.borderColor = 'red';
                        isValid = false;
                    } else {
                        input.style.borderColor = '#ddd';
                    }
                });
                
                if (isValid) {
                    alert('Employé enregistré avec succès!');
                } else {
                    alert('Veuillez remplir tous les champs obligatoires');
                }
            });
        });
        
        // Fonction pour mettre à jour les numéros de ligne
        function updateRowNumbers() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.cells[0].textContent = index + 1;
            });
        }
        
        // Ajouter des gestionnaires d'événements pour les boutons de suppression existants
        document.querySelectorAll('.btn-secondary').forEach(button => {
            if (!button.id) { // Pour ne pas sélectionner le bouton "Ajouter Employé"
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    if (confirm('Voulez-vous vraiment supprimer cette ligne?')) {
                        row.parentNode.removeChild(row);
                        updateRowNumbers();
                    }
                });
            }
        });
        
        // Validation des dates
        document.addEventListener('change', function(e) {
            if (e.target.type === 'date') {
                const dateInput = e.target;
                const selectedDate = new Date(dateInput.value);
                const today = new Date();
                
                if (selectedDate < today) {
                    alert('La date ne peut pas être dans le passé');
                    dateInput.value = '';
                }
            }
        });
    </script>
</body>
</html>