<nav class="navbar">
    <div class="nav-icons">
        <div class="icon-wrapper relative" onclick="toggleNotifications()">
            <i class="fa-solid fa-bell"></i>
            <!-- Badge pour les notifications non lues -->
            <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
            <span :class="{'hidden': unreadNotifications.length === 0}" x-text="unreadNotifications.length" id="redPin" class="text-[.53rem] h-3 w-3 flex items-center justify-center rounded-full bg-red-500 text-white top-1 right-2 absolute <?= !$redPin ? 'hidden' : '' ?>"></span>
        </div>
        <div class="icon-wrapper" onclick="toggleMessenger()">
            <i class="fa-brands fa-facebook-messenger"></i>
        </div>
    </div>
</nav>