<?php
ob_start();
require __DIR__ . '/../View/BackOffice/assets/header.php';
$out = ob_get_clean();
echo (strpos($out, '<strong>DEBUG</strong>') !== false) ? 'BANNER_SHOWN' : 'NO_BANNER';
