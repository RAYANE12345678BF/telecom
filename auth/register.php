<?php

include __DIR__ . '/../vendor/autoload.php';

if (!session_id()) {
    session_start();
}


redirect_if_auth();

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjazairRH - Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #0066cc;
            --accent-color: #e6f3ff;
            --success-color: #00c851;
            --error-color: #ff3547;
            --text-primary: #2c3e50;
            --text-secondary: #64748b;
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .wrapper {
            width: 100%;
            max-width: 1200px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(10px);
            transform: translateY(0);
            transition: var(--transition);
        }

        .wrapper:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .left-side {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            position: relative;
            overflow: hidden;
        }

        .left-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            opacity: 0.03;
            border-radius: var(--border-radius);
        }

        .right-side {
            flex: 1;
            padding: 40px;
        }

        .features {
            list-style: none;
            margin-top: 20px;
        }

        .features li {
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 1.1em;
            transform: translateX(0);
            transition: var(--transition);
        }

        .features li:hover {
            transform: translateX(10px);
        }

        .features li i {
            color: var(--secondary-color);
            margin-right: 15px;
            font-size: 1.3em;
            background: var(--accent-color);
            padding: 10px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .features li:hover i {
            transform: scale(1.1);
            background: var(--secondary-color);
            color: white;
        }

        .form-container {
            background: white;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            max-width: 600px;
            margin: auto;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-group:nth-child(3) {
            animation-delay: 0.3s;
        }

        .form-group:nth-child(4) {
            animation-delay: 0.4s;
        }

        .form-group:nth-child(5) {
            animation-delay: 0.5s;
        }

        .input-field {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e1e8ed;
            border-radius: var(--border-radius);
            font-size: 15px;
            transition: var(--transition);
            color: var(--text-primary);
            background-color: #f8fafc;
        }

        .input-field:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
            outline: none;
            background-color: white;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.2em;
            transition: var(--transition);
            pointer-events: none;
        }

        .input-field:focus+i {
            color: var(--secondary-color);
        }

        .role-section {
            margin: 30px 0;
            padding: 20px;
            background: #f8fafc;
            border-radius: var(--border-radius);
            border: 1px solid #e1e8ed;
        }

        .role-title {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.1em;
            display: flex;
            align-items: center;
        }

        .role-title i {
            margin-right: 10px;
            color: var(--secondary-color);
        }

        .role-info {
            font-size: 0.9em;
            color: var(--text-secondary);
            margin-top: 10px;
            display: flex;
            align-items: center;
            padding: 10px;
            background: var(--accent-color);
            border-radius: var(--border-radius);
        }

        .role-info i {
            margin-right: 10px;
            color: var(--secondary-color);
        }

        select.input-field {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            padding-right: 45px;
        }

        .btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 102, 204, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn i {
            font-size: 1.2em;
            transition: var(--transition);
        }

        .btn:hover i {
            transform: scale(1.1);
        }

        .form-header {
            background-color: transparent;
        }

        .welcome-text {
            flex: 1;
            padding-right: 20px;
        }

        .welcome-text h2 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .welcome-text p {
            font-size: 1em;
            color: var(--text-secondary);
        }

        @media (max-width: 1024px) {
            .wrapper {
                max-width: 900px;
            }
        }

        @media (max-width: 900px) {
            .wrapper {
                flex-direction: column;
                max-width: 600px;
            }

            .left-side,
            .right-side {
                padding: 20px;
            }

            .features li {
                font-size: 1em;
            }

            .welcome-text {
                padding: 0;
            }
        }

        @media (max-width: 480px) {
            .wrapper {
                padding: 15px;
            }

            .form-container {
                padding: 20px;
            }

            .features li i {
                width: 35px;
                height: 35px;
                font-size: 1.1em;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="left-side" style="display: flex; flex-direction: column; justify-content: flex-start;">
            <div class="welcome-text" style="flex: 1; padding-right: 20px;">
                <h2 style="color: var(--primary-color); font-weight: 700; text-align: left;">Bienvenue!</h2>
                <p style="font-size: 1.1em; color: var(--text-primary); line-height: 1.5; text-align: left;">
                    Créez votre compte pour accéder à toutes les fonctionnalités de notre application. Remplissez le formulaire à droite pour commencer.
                </p>
                <img src="algtl.png" alt="Description of image" style="width: 100%; height: auto; margin-top: 20px;">
            </div>
        </div>
        <div class="right-side">
            <div class="form-container">
                <div class="form-header" style="display: flex; justify-content: center; align-items: center;">
                    <img src="logo_djazairRH.jpg" alt="Logo" class="logo" style="width: 100px; height: auto; margin-bottom: 20px;">
                </div>
                <form onsubmit="return validateForm()" action="<?php echo url('actions/auth.php') ?>" method="post">
                    <input type="hidden" value="register" name="action">
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input name="nom" type="text" class="input-field" id="nom" placeholder="Nom" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <input name="prenom" type="text" class="input-field" id="prenom" placeholder="Prénom" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-envelope"></i>
                        <input name="email" type="email" class="input-field" id="email" placeholder="Email professionnel" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock"></i>
                        <input name="password" type="password" class="input-field" id="password" placeholder="Mot de passe" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock"></i>
                        <input name="password_confirmation" type="password" class="input-field" id="confirmPassword" placeholder="Confirmer le mot de passe" required>
                    </div>
                    <div class="form-group">
                        <label for="departement">Département</label>
                        <select name="department" id="departement" class="input-field" onchange="populateServices()" required>
                            <option value="">Sélectionnez un département</option>
                            <option value="direction_op">Direction Opérationnelle</option>
                            <option value="sous_direction_tech">Sous-Direction Technique</option>
                            <option value="sous_direction_com">Sous-Direction Commerciale</option>
                            <option value="sous_direction_fonctions">Sous-Direction Fonctions</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service">Service</label>
                        <select name="service" id="service" class="input-field" required>
                            <option value="">Sélectionnez un service</option>
                        </select>
                    </div>
                    <div class="role-section">
                        <div class="role-title"><i class="fas fa-briefcase"></i> Position dans l'entreprise</div>
                        <div class="form-group">
                            <select name="role" class="input-field" id="role" required>
                                <option value="">Sélectionnez votre rôle</option>
                                <option value="employe">Employé</option>
                                <option value="chef_service">Chef de Service</option>
                                <option value="chef_departement">Chef de Département</option>
                                <option value="sous_directeur">Sous-Directeur</option>
                            </select>
                        </div>
                        <div class="role-info">
                            <i class="fas fa-info-circle"></i>
                            Votre compte devra être validé par la DRH avant activation
                        </div>
                    </div>
                    <button type="submit" class="btn">
                        <i class="fas fa-user-plus"></i> S'inscrire
                    </button>
                    <p style="text-align: center; margin-top: 10px;">
                        <a href="<?php echo url('actions/auth.php') ?>" target="_blank" style="color: var(--secondary-color);">J'ai déjà un compte</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script>
        function validateName(name) {
            const regex = /^[A-Za-z]+(?:\s[A-Za-z]+)*$/;
            return regex.test(name);
        }

        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const email = document.getElementById('email').value;
            const role = document.getElementById('role').value;
            const departement = document.getElementById('departement').value;
            const service = document.getElementById('service').value;
            const nom = document.getElementById('nom').value
            const prenom = document.getElementById('prenom').value

            if (password !== confirmPassword) {
                alert("Les mots de passe ne correspondent pas!");
                return false;
            }

            if (!validateName(nom)) {
                alert("Veuillez entrer un nom valide!");
                return false
            }

            if (!validateName(prenom)) {
                alert("Veuillez entrer un prénom valide!");
                return false
            }

            if (! validateEmail(email)) {
                alert("Veuillez entrer une adresse email valide!");
                return false;
            }

            if (!role) {
                alert("Veuillez sélectionner votre rôle dans l'entreprise!");
                return false;
            }

            if (!departement) {
                alert("Veuillez sélectionner votre département!");
                return false;
            }

            if (!service) {
                alert("Veuillez entrer votre service!");
                return false;
            }

            const userData = {
                nom: document.getElementById('nom').value,
                prenom: document.getElementById('prenom').value,
                email: email,
                role: role,
                departement: departement,
                service: service,
                status: 'pending',
                permissions: getRolePermissions(role)
            };

            console.log('Données utilisateur:', userData);
            alert('Inscription réussie! Votre compte est en attente de validation par la DRH.');
            return true
        }

        function getRolePermissions(role) {
            const basePermissions = {
                faire_demandes: true,
                consulter_demandes_perso: true,
                recevoir_notifications: true,
                voir_pointage_perso: true,
                messagerie_interne: true
            };

            const adminPermissions = {
                ...basePermissions,
                consulter_demandes_service: true,
                valider_demandes: true,
                voir_pointage_service: true
            };

            const directeurPermissions = {
                ...adminPermissions,
                voir_toutes_demandes: true,
                voir_tout_pointage: true
            };

            const drhPermissions = {
                ...directeurPermissions,
                gerer_comptes: true,
                valider_inscriptions: true,
                modifier_employes: true,
                attribuer_roles: true
            };

            switch (role) {
                case 'employe':
                    return basePermissions;
                case 'chef_service':
                case 'chef_departement':
                case 'sous_directeur':
                    return adminPermissions;
                case 'directeur':
                    return directeurPermissions;
                case 'drh':
                    return drhPermissions;
                default:
                    return basePermissions;
            }
        }

        function populateServices() {
            const departement = document.getElementById('departement').value;
            const serviceSelect = document.getElementById('service');
            serviceSelect.innerHTML = '<option value="">Sélectionnez un service</option>';

            let services;
            switch (departement) {
                case 'direction_op':
                    services = ["Service Sûreté", "Chargé de la communication", "Écoles Régionales Télécommunications", "Établissements Communaux des Systèmes d’Information"];
                    break;
                case 'sous_direction_tech':
                    services = ["Département Planification et Suivi", "Département Réseau d’Accès", "Département Réseau de Transport"];
                    break;
                case 'sous_direction_com':
                    services = ["Département Planification et Suivi", "Département Vente Grand Public", "Département Corporate", "Département Support Commercial"];
                    break;
                case 'sous_direction_fonctions':
                    services = ["Département Achats et Logistique", "Département Finance et Comptabilité", "Département RH", "Département Patrimoine et Moyens", "Service Juridique", "Service Support SI"];
                    break;
                default:
                    services = [];
            }

            services.forEach(service => {
                const option = document.createElement('option');
                option.value = service;
                option.textContent = service;
                serviceSelect.appendChild(option);
            });
        }
    </script>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            alert("<?php echo $_SESSION['error'] ?>")
        </script>
    <?php unset($_SESSION['error']);
    endif; ?>

<?php if (isset($_SESSION['info'])): ?>
        <script>
            alert("<?php echo $_SESSION['info'] ?>")
        </script>
    <?php unset($_SESSION['info']);
    endif; ?>

</body>

</html>