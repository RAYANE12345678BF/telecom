<div x-data class="sidebar">
    <div class="sidebar-header">
        <div @click="setTimeout(() => $notify('Nihil distinctio suscipit iste impedit magnam eius iure culpa mollitia tenetur', {
      wrapperId: 'bottomLeft',
      templateId: 'alertStandard',
      autoRemove: 3000
    }), 2000)" class="logo">
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
            <a href="<?= url('admin/profile.php') ?>" class="menu-item active">
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
                            <a href="<?= url('admin/demands/conge/annual.php') ?>" class="menu-item">
                                <i class="fas fa-sun"></i>
                                <span class="menu-text">Congé Annuel</span>
                            </a>
                            <a href="maladie_admin1.html" class="menu-item">
                                <i class="fas fa-hospital"></i>
                                <span class="menu-text">Congé Maladie</span>
                            </a>
                            <a href="maternite_admin1.html" class="menu-item">
                                <i class="fas fa-baby"></i>
                                <span class="menu-text">Congé Maternité</span>
                            </a>
                            <a href="rc_admin1.html" class="menu-item">
                                <i class="fas fa-clock"></i>
                                <span class="menu-text">Congé RC</span>
                            </a>
                        </div>
                    </div>
                    <a href="formation_admin1.html" class="menu-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span class="menu-text">Demande Formation</span>
                    </a>
                    <a href="mission_admin1.html" class="menu-item">
                        <i class="fas fa-plane"></i>
                        <span class="menu-text">Demande Ordre Mission</span>
                    </a>
                    <a href="déplacement_admin1.html" class="menu-item">
                        <i class="fas fa-car"></i>
                        <span class="menu-text">Demande Déplacement</span>
                    </a>
                    <a href="sortie_admin1.html" class="menu-item">
                        <i class="fas fa-door-open"></i>
                        <span class="menu-text">Demande Sortie</span>
                    </a>
                </div>
                <a href="<?= url('admin/demands/list.php') ?>" class="menu-item">
                    <i class="fas fa-tasks"></i>
                    <span class="menu-text">État de demande</span>
                </a>
                <a href="<?= url('admin/demands/consulte.php') ?>" class="menu-item">
                    <i class="fas fa-eye"></i>
                    <span class="menu-text">Consulter Demande</span>
                </a>
            </div>

            <div class="nav-title">Autres</div>
            <a href="support_admin1.html" class="menu-item">
                <i class="fas fa-question-circle"></i>
                <span class="menu-text">Support</span>
            </a>
        </div>
    </div>
    <form action="<?= url('actions/auth.php') ?>" method="post" class="user-section">
        <input type="hidden" value="logout" name="action" />
        <div class="user-avatar">
            <i class="fas fa-sign-in-alt"></i>
        </div>
        <button type="submit" style="border : none">Se déconnecter</button>
    </form>
</div>