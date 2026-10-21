<?php
require_once __DIR__ . '/auth.php';

logout_user();
header("Location: ../pages/login.html?msg=logged_out");
exit;
