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

$user = fetch_user_information($_SESSION['user_id']);

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Profil Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="./assets/css/main.css">
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
    </nav>


    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="profile-grid">
                <!-- Profile Sidebar -->
                <div class="profile-sidebar">
                    <div class="profile-photo">
                        <i class="fas fa-user"></i>
                        <div class="edit-photo">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <h1 class="profile-name">_____________</h1>
                    <div class="profile-title">_____________</div>
                    <div class="profile-info">
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <input value="<?php echo $_SESSION['user']['email_professionnel'] ?>" type="email" placeholder="Email professionnel">
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <input name="phone" value="<?php echo $user['phone'] ?? '' ?>" type="tel" placeholder="Nphoneuméro de télé">
                        </div>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <input value="<?php echo join_address($user['address']) ?? '' ?>" type="text" placeholder="Adresse">
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="profile-content">
                    <!-- Personal Information -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">Informations Personnelles</h2>
                            <button id="saveBtn" class="edit-button">
                                <i class="fas fa-floppy-disk"></i>
                                Save Changes
                            </button>
                        </div>
                        <div class="info-grid">
                            <div class="info-field">
                                <div class="field-label">Nom</div>
                                <div class="field-value">
                                    <input value="<?php echo $user['nom'] ?>" type="text" name="nom" placeholder="Entrez votre nom">
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Prénom</div>
                                <div class="field-value">
                                    <input value="<?php echo $user['prenom'] ?>" type="text" name="prenom" placeholder="Entrez votre prénom">
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Date de Naissance</div>
                                <div class="field-value">
                                    <input value="<?php echo $user['birth_day'] ?>" type="date" name="birth_day">
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Lieu de Naissance</div>
                                <div class="field-value">
                                    <input value="<?php echo $user['birth_place'] ?>" type="text" name="birth_place" placeholder="Entrez votre lieu de naissance">
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">État Civil</div>
                                <div class="field-value">
                                    <select name="etat_cevil">
                                        <option value="" <?= empty($user['etat_civil']) ? "selected" : "" ?>>Sélectionner</option>
                                        <option value="celibataire" <?= $user['etat_civil'] == 'celibataire' ? "selected" : "" ?>>Célibataire</option>
                                        <option value="marie" <?= $user['etat_civil'] == 'marie' ? "selected" : "" ?>>Marié(e)</option>
                                        <option value="divorce" <?= $user['etat_civil'] == 'divorce' ? "selected" : "" ?>>Divorcé(e)</option>
                                        <option value="veuf" <?= $user['etat_civil'] == 'veuf' ? "selected" : "" ?>>Veuf/Veuve</option>
                                    </select>
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Nombre d'Enfants</div>
                                <div class="field-value">
                                    <input type="number" name="nombreEnfants" min="0" placeholder="0">
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Mot de passe du compte</div>
                                <div class="field-value">
                                    <input type="password" name="accountPassword" placeholder="Entrez votre mot de passe" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <div class="content-card">
                        <div class="card-header">
                            <h2 class="card-title">Informations Professionnelles</h2>
                            <button id="saveProfissionel" class="edit-button">
                                <i class="fas fa-floppy-disk"></i>
                                save changes
                            </button>
                        </div>
                        <div class="info-grid">
                            <div class="info-field">
                                <div class="field-label">Matricule</div>
                                <div class="field-value">
                                    <input value="<?php echo $user['matricule'] ?? '' ?>" type="text" name="matricule" placeholder="Entrez votre matricule">
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Date d'Embauche</div>
                                <div class="field-value">
                                    <input type="date" name="start_date" value="<?php echo $user['start_date'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Département</div>
                                <div class="field-value">
                                    <select name="department_id">
                                        <option value="">Sélectionner</option>
                                        <?php foreach ($departments as $department): ?>
                                            <option <?php echo $user['department']['id'] == $department['id'] ? 'selected' : '' ?> value="<?php echo $department['id'] ?>"><?php echo $department['nom'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Services</div>
                                <div class="field-value">
                                    <select name="service_id">
                                        <option value="" disabled>Sélectionner</option>
                                        <?php foreach ($services as $service): ?>
                                            <option <?php echo $user['service']['id'] == $service['id'] ? 'selected' : '' ?> value="<?php echo $service['id'] ?>"><?php echo $service['nom'] ?></option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">superior director</div>
                                <div class="field-value">
                                    <select name="superior_id">
                                        <option value="" disabled selected>Sélectionner</option>
                                        <?php foreach ($employees as $employee): ?>
                                            <?php if ($employee['id'] != $user['id']): ?>
                                                <option <?php echo $user['superior']['id'] ?? -1 == $employee['id'] ? 'selected' : '' ?> value="<?php echo $employee['id'] ?>"><?php echo $employee['nom'] . ' ' . $employee['prenom'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
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
                            title: 'done!',
                            text: 'informations updated successfully',
                            icon: 'success'
                        })
                    }else{
                        Swal.fire({
                            title: 'error!',
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
        const warnInfo = (text = "to unlock all the sections you need to fill all the information in the profile") => {
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
        // Navigation menu toggle functions
        document.getElementById('faireDemandeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            <?php if (($l = isProfileComplete($user)) !== true): ?>
                warnInfo("please fill the <?= $l ?>")
                return
            <?php endif; ?>
            const submenu = document.getElementById('demandeSubmenu');
            submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
        });

        document.getElementById('congeBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('congeSubmenu');
            submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
        });
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

    <script>
        const saveBtn = document.querySelector('#saveBtn')

        saveBtn.onclick = () => {
            let data = new FormData
            data.append('action', 'save')

            let keys = ["phone", "nom", "prenom", "birth_day", "birth_place", "etat_cevil"]


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
                    Swal.fire({
                        title: 'done!',
                        text: 'informations updated successfully',
                        icon: 'success'
                    })
                }).catch(err => {
                    Swal.fire({
                        title: 'error!',
                        text: err,
                        icon: 'error'
                    })
                })
        }
    </script>



    <?php if (isset($_SESSION['status'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: "demand deposé!",
                text: "le demand deposé avec succes!",
                icon: "success"
            });
        </script>
    <?php unset($_SESSION['status']);
    endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: "error!",
                text: "<?= $_SESSION['error'] ?>",
                icon: "error"
            });
        </script>
    <?php unset($_SESSION['error']);
    endif; ?>
</body>

</html>