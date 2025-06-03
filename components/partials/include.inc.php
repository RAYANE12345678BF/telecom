<script src="<?= asset('js/libs/tailwind.min.js') ?>"></script>
<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script defer src="<?= asset('js/alpine/intersect.min.js') ?>"></script>
<script defer src="<?= asset('js/alpine/alpine.min.js') ?>"></script>
<script src="<?= asset('js/libs/sweetalert.js') ?>"></script>

<?php


if( !session_id() ){
    session_start();
}

//dd(count(fetch_month_absences(1, 2, 2025)));

$notifications = get_notifications($_SESSION['user_id']);

$redPin = count(array_filter($notifications, function ($v, $i) {
    return $v['read_state'] == 0;
}, ARRAY_FILTER_USE_BOTH)) > 0;

// here we will include the middlewares

component('middlewares/auth', false);
component('middlewares/role', false);
component('middlewares/information_complete', false);
?>

<script>
    function timeDiffInHours(start, end) {
        const [startHour, startMinute] = start.split(':').map(Number);
        const [endHour, endMinute] = end.split(':').map(Number);

        let startTotal = startHour + startMinute / 60;
        let endTotal = endHour + endMinute / 60;

        let diff = endTotal - startTotal;

        // If end is before start, assume next day
        if (diff < 0) {
            diff += 24;
        }

        return Math.floor(diff);
    }
</script>

