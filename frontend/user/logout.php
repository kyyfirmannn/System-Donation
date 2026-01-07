<?php
require_once __DIR__ . '/../../backend/config/session.php';

Session::destroy();

header('Location: index.php');
exit;
?>