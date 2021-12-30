<?php
session_start();

if (!isset($_SESSION['remember'])) {
    $_SESSION['remember'] = false;
}