<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-12-26 16:12:37
 * @version $Id$
 */
session_start();
unset($_SESSION['current_login_user']);
header('Location:/index.php');
