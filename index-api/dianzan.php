<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-12-15 16:22:36
 * @version $Id$
 */

require_once '../functions.php';

$number = $_GET['number'];
$id = $_GET['id'];
xiu_execute("update posts set likes ={$number} where id = {$id}");


