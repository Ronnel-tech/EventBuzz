<?php

return function() {

    if (!isset($_SESSION['user'])) {
        header('Location: ' . url('/'));
        exit;
    }

    return true;
};