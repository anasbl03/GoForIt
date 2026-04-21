<?php
session_start();
session_unset();
session_destroy();

header("Location: /ProjetWeb2526/index.php");
exit;