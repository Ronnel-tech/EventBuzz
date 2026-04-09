<?php

return function() {

    if (!isset($_SESSION['user'])) {
        header('Location: ' . url('/'));
        exit;
    }

    if ($_SESSION['user']['role'] !== 'organizer') {
        echo "Access denied";
        exit;
    }

    return true;
};