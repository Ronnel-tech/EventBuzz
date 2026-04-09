<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('/'));
    exit;
}

$_SESSION = [];
session_destroy();

header('Location: ' . url('/'));
exit;