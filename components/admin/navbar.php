<nav class="navbar">
    <div class="nav-icons">
        <div class="icon-wrapper relative" onclick="toggleNotifications()">
            <i class="fa-solid fa-bell"></i>
            <span
                :class="{'hidden' : (notifications.filter(vn=> vn.read_state == '0')).length < 1 }" id="notify-red" class="w-1.5 h-1.5 bg-red-500 rounded-full top-1/4 right-1/4 absolute <?= $redPin ? 'block' : 'hidden' ?>">

            </span>
            <!-- Badge pour les notifications non lues -->
            <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
        </div>
        <div class="icon-wrapper" onclick="toggleMessenger()">
            <i class="fa-brands fa-facebook-messenger"></i>
        </div>
    </div>
</nav>