<?php

return function() {

    if (!isset($_SESSION['user'])) {
        header('Location: ' . url('/'));
        exit;
    }

    if ($_SESSION['user']['role'] !== 'admin') {
        echo "Access denied";
        exit;
    }

    return true;
};