<?php
/**
 * File: includes/auth.php
 * Middleware autentikasi untuk halaman admin.
 * Wajib di-include di baris paling atas setiap halaman admin (kecuali login.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

/**
 * Fungsi bantu untuk membersihkan input
 */
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
