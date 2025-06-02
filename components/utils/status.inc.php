<?php if (isset($_SESSION['status'])): ?>
    <script>
        Swal.fire({
            title: "information!",
            text: "<?= $_SESSION['status'] ?>",
            icon: "<?= $_SESSION['status_icon'] ?? "success" ?>"
        });
    </script>
<?php unset($_SESSION['status']);
    unset($_SESSION['status_icon']);
endif; ?>

<script>
    // Fonction pour afficher/masquer les notifications
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('show');
    }
</script>