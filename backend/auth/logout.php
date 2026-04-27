<?php
require_once __DIR__ . '/includes/auth.php';

logoutUser();
redirect('../../frontend/index.php');   // Change path according to your frontend location
?>