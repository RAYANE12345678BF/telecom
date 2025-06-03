<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if( !session_id() ){
    session_start();
}

$user = fetch_user_information($_SESSION['user_id']);

if( if_user_is(['Chef de Service']) ){
    $all_planifications = get_all_planifications();
}elseif(if_user_is(['Directeur'])){
    $all_planifications = get_all_planifications(false);
}else{
    session([
            'status' => 'vous pouvez pas voir ca'
    ]);
    redirect(dashboard_url('/'));
}

usort($all_planifications, function ($a, $b) {
    // Check if score is null
    if (is_null($a['accepted_from_employee']) && !is_null($b['accepted_from_employee'])) {
        return -1; // $a comes before $b
    } elseif (!is_null($a['accepted_from_employee']) && is_null($b['accepted_from_employee'])) {
        return 1; // $b comes before $a
    } else {
        // Both null or both not null — sort by score ascending
        return ($a['accepted_from_employee'] ?? 0) <=> ($b['accepted_from_employee'] ?? 0);
    }
});

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Planning de Congé 2025</title>
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

        /* Main Content Styles */
        .main-content {
            padding: 90px 30px 30px;
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
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
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
<?php component('partials/sidebar') ?>

<!-- Top Navbar -->
<?php component('partials/navbar') ?>

<!-- Menu déroulant des notifications -->
<?php component('partials/notifications') ?>
    
    <!-- Main Content -->
    <div class="main-content" id="print">
        <div class="container">
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
                        <?php foreach($all_planifications as $plan): ?>
                            <tr>
                                <td>
                                    <?= $plan['id'] ?>
                                </td>
                                <td>
                                    <?= $plan['employee']['nom'] . ' ' . $plan['employee']['prenom'] ?>
                                </td>
                                <td>
                                    <?= $plan['employee']['role']['nom'] ?>
                                </td>
                                <td><input type="date" readonly value="<?= $plan['start_date'] ?>" class="form-control"></td>
                                <td><input readonly value="<?= daysBetweenDates($plan['start_date'], $plan['end_date']) ?>" type="number" class="form-control" min="1"></td>
                                <td>
                                    <select class="form-control">
                                        <option>Annuel</option>

                                    </select>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Enregistrer</button>
                                    <button class="btn btn-secondary btn-sm" onclick="deletePlan(<?= $plan['id'] ?>)"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="action-buttons">
                        <button id="printBtn" onclick="handlePrintButton()" class="btn btn-secondary"><i class="fas fa-print"></i> Imprimer</button>
                        <?php if(if_user_is(['Chef de Service'])): ?>
                        <button class="btn btn-secondary" onclick="confirm()"><i class="fas fa-paper-plane"></i> Soumettre</button>
                        <?php endif ?>
                        <button disabled class="btn btn-primary" id="addEmployeeBtn"><i class="fas fa-plus"></i> Ajouter Employé</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function deletePlan(id){
            Swal.fire({
                title: "Voulez vous rejeter/supprimer cette planification ?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Save",
                denyButtonText: `Don't save`
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    let data = new FormData
                    data.append('plan_id', id)
                    data.append('action', 'delete')
                    fetch("<?= url('actions/planify.php') ?>", {
                        method: 'POST',
                        body: data
                    }).then(res => res.json())
                        .then(json =>{
                            if( json.success ){
                                Swal.fire("Rejeter!", "le planification a ete rejeter", "success");
                            }else{
                                Swal.fire("Erreur!", json.message, "error");
                            }
                        })
                } else if (result.isDenied) {
                    Swal.fire("le planification ne hange pas", "", "info");
                }
            });
        }

        function confirm() {
            Swal.fire({
                title: "Voulez vous soumettre cette planification ?",
                showDenyButton: true,
                confirmButtonText: "soumettre",
                denyButtonText: `annuler`
            }).then((result) => {
                if (result.isConfirmed) {
                    let data = new FormData
                    data.append('action', 'confirm')
                    fetch('<?= url('actions/planify.php') ?>', {
                        method: 'POST',
                        body: data
                    }).then(res => res.json()).then(json => {
                        if (json['success']) {
                            Swal.fire("soumetté!", "le planification a ete soumettre vers le directeur", "success");
                        } else {
                            Swal.fire("Erreur!", json.message, "error");
                        }
                    }).catch(err => alert(err))
                }
            })
        }
    </script>

<script>
    function handlePrintButton(e) {
        let styles = document.getElementsByTagName('style')[0].innerText
        let divToPrint = document.getElementById('print')
        divToPrint.style.width = '100%'

        let button = divToPrint.getElementsByTagName('button')[0]
        let btnHtml = button.parentElement.innerHTML
        let printWindow = window.open('', '', 'height=500, width=500');
        printWindow.document.open();
        printWindow.document.write(`
                <html>
                <head>
                    <title>Print Div Content</title>
                    <style>
                        ${styles}
                    </style>
                </head>
                <body>
                    ${divToPrint.parentElement.innerHTML}
                </body>
                </html>
            `);
        printWindow.document.close();
        printWindow.print();
    }
</script>
    
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