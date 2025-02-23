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
            <a href="<?php echo url('employee/profile.php') ?>" class="menu-item">
                <i class="fas fa-home"></i>
                <span class="menu-text">Accueil</span>
            </a>
            <a href="profile_employe.html" class="menu-item active">
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
                            <a href="<?php echo url('employee/demands/conge/annual.php') ?>" class="menu-item">
                                <i class="fas fa-sun"></i>
                                <span class="menu-text">Congé Annuel</span>
                            </a>
                            <a href="maladie_employe.html" class="menu-item">
                                <i class="fas fa-hospital"></i>
                                <span class="menu-text">Congé Maladie</span>
                            </a>
                            <a href="maternite_employe.html" class="menu-item">
                                <i class="fas fa-baby"></i>
                                <span class="menu-text">Congé Maternité</span>
                            </a>
                            <a href="rc_employe.html" class="menu-item">
                                <i class="fas fa-clock"></i>
                                <span class="menu-text">Congé RC</span>
                            </a>
                        </div>
                    </div>
                    <a href="formation_employe.html" class="menu-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span class="menu-text">Demande Formation</span>
                    </a>
                    <a href="mission_employe.html" class="menu-item">
                        <i class="fas fa-plane"></i>
                        <span class="menu-text">Demande Ordre Mission</span>
                    </a>
                    <a href="Déplacement_employe.html" class="menu-item">
                        <i class="fas fa-car"></i>
                        <span class="menu-text">Demande Déplacement</span>
                    </a>
                    <a href="sortie_employe.html" class="menu-item">
                        <i class="fas fa-door-open"></i>
                        <span class="menu-text">Demande Sortie</span>
                    </a>
                </div>
                <a href="etat_demande_employe.html" class="menu-item">
                    <i class="fas fa-tasks"></i>
                    <span class="menu-text">État de demande</span>
                </a>
            </div>

            <div class="nav-title">Autres</div>
            <a href="support_employe.html" class="menu-item">
                <i class="fas fa-question-circle"></i>
                <span class="menu-text">Support</span>
            </a>
        </div>
    </div>
    <div class="user-section">
        <div class="user-avatar">
            <i class="fas fa-sign-in-alt"></i>
        </div>
        <span>Se déconnecter</span>
    </div>
</div>