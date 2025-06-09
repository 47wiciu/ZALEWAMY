<?php
session_start();
unset($_SESSION['koszyk']);//usuwa koszyk
header('Location: zamowienia.php');//przekierowywuje na stronę zamowienia.php
exit;
?>