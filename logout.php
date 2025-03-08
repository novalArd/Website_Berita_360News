<?php
session_start(); // Memulai session
session_unset(); // Menghapus semua data session
session_destroy(); // Menghancurkan session
header('Location: pagelogin.html'); // Mengarahkan kembali ke halaman login
exit;
?>
