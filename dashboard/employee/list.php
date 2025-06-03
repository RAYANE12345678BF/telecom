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

$users = get_all_users();




$user_requests = fetch_creation_demands();


$user = fetch_user_information($_SESSION['user_id']);
$_SESSION['user'] = get_user($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Profil <?= $user['role']['nom'] ?></title>
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
    </nav>


    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1 class="font-bold text-blue-900 text-3xl">
                Management D'employees
            </h1>

            <div class="table-wrapper p-2 flex flex-col mt-10 bg-white border-indigo-500 border-2 rounded" 
            x-init='users = JSON.parse(`<?= json_encode($users) ?>`)'
            x-data="{ 
                users: [],
                selectedUsers: [],
                selectAll: false,
                searchQuery: '',
                get filteredUsers() {
                    if (!this.searchQuery) return this.users;
                    const query = this.searchQuery.toLowerCase();
                    return this.users.filter(user => 
                        (user.nom && user.nom.toLowerCase().includes(query)) ||
                        (user.prenom && user.prenom.toLowerCase().includes(query)) ||
                        (user.email_professionnel && user.email_professionnel.toLowerCase().includes(query)) ||
                        (user.matricule && user.matricule.toString().toLowerCase().includes(query))
                    );
                },
                toggleSelectAll() {
                    if (this.selectAll) {
                        this.selectedUsers = this.users.map(user => user.id);
                    } else {
                        this.selectedUsers = [];
                    }

                    console.log(this.selectAll, this.selectedUsers, this.users)
                },
                toggleUser(userId) {
                    const index = this.selectedUsers.indexOf(userId);
                    if (index === -1) {
                        this.selectedUsers.push(userId);
                    } else {
                        this.selectedUsers.splice(index, 1);
                    }
                    this.selectAll = this.selectedUsers.length === this.users.length;
                },
                sendBulkEmail() {
                    if (this.selectedUsers.length === 0) {
                        Swal.fire({
                            title: 'Attention!',
                            text: 'Veuillez sélectionner au moins un employé',
                            icon: 'warning'
                        });
                        return;
                    }
                    Swal.fire({
                        title: 'Envoyer un email',
                        html: `
                            <div class='mb-3'>
                                <label class='form-label'>Sujet</label>
                                <input type='text' id='emailSubject' class='form-control' placeholder='Sujet de l'email'>
                            </div>
                            <div class='mb-3'>
                                <label class='form-label'>Message</label>
                                <textarea id='emailMessage' class='form-control' rows='4' placeholder='Votre message'></textarea>
                            </div>
                            <div class='mb-3'>
                                <label class='form-label'>Pièce jointe</label>
                                <input type='file' id='emailAttachment' class='form-control' multiple>
                                <small class='text-gray-500'>Vous pouvez sélectionner plusieurs fichiers</small>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Envoyer',
                        cancelButtonText: 'Annuler',
                        preConfirm: () => {
                            const subject = document.getElementById('emailSubject').value;
                            const message = document.getElementById('emailMessage').value;
                            const attachment = document.getElementById('emailAttachment').files;
                            
                            if (!subject || !message) {
                                Swal.showValidationMessage('Veuillez remplir tous les champs obligatoires');
                                return false;
                            }
                            return { subject, message, attachment };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let d = new FormData();
                            d.append('employee_ids', this.selectedUsers);
                            d.append('subject', result.value.subject);
                            d.append('content', result.value.message);
                            
                            // Append each file to the FormData
                            if (result.value.attachment.length > 0) {
                                d.append('attachment', result.value.attachment[0]);
                            }
                            
                            fetch('<?= url('actions/enqueue_emails.php') ?>', {
                                method: 'post',
                                body: d
                            }).then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Succès!',
                                        text: `Email envoyé à ${this.selectedUsers.length} employé(s)`,
                                        icon: 'success'
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Erreur!',
                                        text: data.message || 'Une erreur est survenue lors de l\'envoi des emails',
                                        icon: 'error'
                                    });
                                }
                            }).catch(error => {
                                Swal.fire({
                                    title: 'Erreur!',
                                    text: 'Une erreur est survenue lors de l\'envoi des emails',
                                    icon: 'error'
                                });
                            });
                        }
                    });
                }
            }">
                <div class="table-header flex flex-row justify-between items-center p-2">
                    <div class="flex flex-col">
                        <h1 class="table-heading text-lg font-bold text-slate-800 tracking-wide capitalize">
                            tous les employees
                        </h1>
                        <p class="table-subheading text-lg font-normal text-slate-600">
                            vous pouvez voir tous les emplyees et decidez
                        </p>
                    </div>
                    <div class="table-actions flex items-center gap-4">
                        <div class="bulk-actions" x-show="selectedUsers.length > 0">
                            <button @click="sendBulkEmail" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                                <i class="fas fa-envelope mr-2"></i>
                                Envoyer un email (<span x-text="selectedUsers.length"></span>)
                            </button>
                        </div>
                        <div class="table-search">
                            <div class="rounded bg-slate-50 border relative w-96 h-10 overflow-hidden">
                                <input type="text"
                                    x-model="searchQuery"
                                    placeholder="chercher d'employees..."
                                    class="w-full h-full border-none focus:ring-transparent pl-2 bg-transparent outline-none placeholder:text-sm text-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
                    <table class="w-full text-left table-auto min-w-max text-slate-800">
                        <thead>
                            <tr class="text-slate-500 border-b border-slate-300 bg-slate-50">
                                <th class="p-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               x-model="selectAll" 
                                               @change="toggleSelectAll()"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    </div>
                                </th>
                                <th class="p-4">
                                    <p class="text-sm leading-none font-normal">
                                        emp-no
                                    </p>
                                </th>
                                <th class="p-4">
                                    <p class="text-sm leading-none font-normal">
                                        nom
                                    </p>
                                </th>
                                <th class="p-4">
                                    <p class="text-sm leading-none font-normal">
                                        prenom
                                    </p>
                                </th>
                                <th class="p-4">
                                    <p class="text-sm leading-none font-normal">
                                        superieur
                                    </p>
                                </th>
                                <th class="p-4">
                                    <p class="text-sm leading-none font-normal">
                                        email professionel
                                    </p>
                                </th>
                                <th class="p-4">
                                    <p class="text-sm leading-none font-normal">
                                        phone
                                    </p>
                                </th>
                                <th class="p-4">
                                    <p class="text-sm leading-none font-normal">
                                        deprtement
                                    </p>
                                </th>
                                <th class="p-4">
                                    <p class="text-sm leading-none font-normal">
                                        service
                                    </p>
                                </th>
                                <th class="p-4">
                                    <p class="text-sm leading-none font-normal">
                                        role
                                    </p>
                                </th>
                                <th class="p-4">
                                    <p></p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="user in filteredUsers" :key="user.id">
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   :checked="selectedUsers.includes(user.id)"
                                                   @change="toggleUser(user.id)"
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm font-bold" x-text="user.matricule || '/'"></p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm" x-text="user.nom || '/'"></p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm" x-text="user.prenom || '/'"></p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm" x-text="user.superior ? (user.superior.nom + ' ' + user.superior.prenom) : '/'"></p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm" x-text="user.email_professionnel"></p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm" x-text="user.phone"></p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm" x-text="user.department.nom"></p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm" x-text="user.service.nom"></p>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm" x-text="user.role.nom"></p>
                                    </td>
                                    <td class="p-4">
                                        <?php if( if_user_is(['GRH']) ): ?>
                                            <a :href="'<?= dashboard_url('payment/index.php?id=') ?>' + user.id" class="text-sm font-semibold p-1 border rounded mx-1">
                                                fiche payment
                                            </a>
                                        <?php endif ?>
                                        <a :href="'<?= dashboard_url('employee/details.php?id=') ?>' + user.id" class="text-sm font-semibold p-1 border rounded mx-1">
                                            details
                                        </a>
                                        <a :href="'<?= dashboard_url('statistics?id=') ?>' + user.id" class="text-sm font-semibold p-1 border rounded mx-1">
                                            absences
                                        </a>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

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