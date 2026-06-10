<?php
session_start();
session_destroy();
echo "<script>alert('Anda telah logout, session berakhir'); window.location='login.php';</script>";
?>