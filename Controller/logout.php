<?php
session_start();
session_unset();
session_destroy();
// Thoát xong quay về trang login
header("Location: ../View/index.php");
exit();
?>