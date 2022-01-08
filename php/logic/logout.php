<?php
session_start();
session_destroy();
header('location: ../../html/pagine_principali/homepage.php');
session_unset();