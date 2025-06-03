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
            <a href="<?= url('/') ?>" class="menu-item">
                <i class="fas fa-home"></i>
                <span class="menu-text">Accueil</span>
            </a>

            <a href="<?= dashboard_url('planning') ?>" class="menu-item">
                <i class="fas fa-home"></i>
                <span class="menu-text">plan</span>
            </a>

            <?php if(if_user_is(['Chef de Service', 'Directeur'], null)): ?>
                <a href="<?= dashboard_url('planning/service.php') ?>" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">plan service</span>
                </a>
            <?php endif ?>

            <a href="<?= url('dashboard') ?>" class="menu-item active">
                <i class="fas fa-user-circle"></i>
                <span class="menu-text">Mon Profil</span>
            </a>

            <a href="<?= url('dashboard/statistics') ?>" class="menu-item">
                <i class="fas fa-chart-simple"></i>
                <span class="menu-text">statistics</span>
            </a>

            <a href="<?= url('dashboard/droits') ?>" class="menu-item">
                <i class="fas fa-list"></i>
                <span class="menu-text">mes droits</span>
            </a>

            <?php if (if_user_is(['Directeur', 'GRH'], null)): ?>
                <a href="<?= url('dashboard/employee/list.php') ?>" class="menu-item">
                    <i class="fas fa-list"></i>
                    <span class="menu-text">list d'employees</span>
                </a>
            <?php endif ?>

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
                            <a href="<?= url('dashboard/demands/conge/annual.php') ?>" class="menu-item">
                                <i class="fas fa-sun"></i>
                                <span class="menu-text">Congé Annuel</span>
                            </a>
                            <a href="<?= url('dashboard/demands/conge/malady.php') ?>" class="menu-item">
                                <i class="fas fa-hospital"></i>
                                <span class="menu-text">Congé Maladie</span>
                            </a>
                            <a href="<?= url('dashboard/demands/conge/maternity.php') ?>" class="menu-item">
                                <i class="fas fa-baby"></i>
                                <span class="menu-text">Congé Maternité</span>
                            </a>
                            <a href="<?= url('dashboard/demands/conge/rc.php') ?>" class="menu-item">
                                <i class="fas fa-clock"></i>
                                <span class="menu-text">Congé RC</span>
                            </a>
                        </div>
                    </div>
                    <a href="<?= url('dashboard/demands/formation') ?>" class="menu-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span class="menu-text">Demande Formation</span>
                    </a>
                    <a href="<?= url('dashboard/demands/mission') ?>" class="menu-item">
                        <i class="fas fa-plane"></i>
                        <span class="menu-text">Demande Ordre Mission</span>
                    </a>
                    <a href="<?= url('dashboard/demands/deplacement') ?>" class="menu-item">
                        <i class="fas fa-car"></i>
                        <span class="menu-text">Demande Déplacement</span>
                    </a>
                    <a href="<?= url('dashboard/demands/leave') ?>" class="menu-item">
                        <i class="fas fa-door-open"></i>
                        <span class="menu-text">Demande Sortie</span>
                    </a>
                </div>
                <a href="<?= url('dashboard/demands/list.php') ?>" class="menu-item">
                    <i class="fas fa-tasks"></i>
                    <span class="menu-text">État de demande</span>
                </a>
                <?php if (!if_user_is('Employé', null)): ?>
                    <a href="<?= url('dashboard/demands/consulte.php') ?>" class="menu-item">
                        <i class="fas fa-eye"></i>
                        <span class="menu-text">Consulter Demande</span>
                    </a>
                <?php endif ?>




            </div>

            <div class="nav-title">Autres</div>
            <?php if (if_user_is(['Directeur', 'GRH'], null)): ?>
                <a href="<?= dashboard_url('pointage') ?>" class="menu-item">
                    <i class="fas fa-clock"></i>
                    <span class="menu-text">Voir Pointage</span>
                </a>
            <?php endif ?>
            <a href="<?= url('dashboard/support') ?>" class="menu-item">
                <i class="fas fa-question-circle"></i>
                <span class="menu-text">Support</span>
            </a>
            <!-- Nouveau bouton "Calendrier RC d'Employé" -->
            <a href="<?= url('dashboard/calendrier') ?>" class="menu-item">
                <i class="fas fa-calendar"></i>
                <span class="menu-text">Calendrier RC d'Employé</span>
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

