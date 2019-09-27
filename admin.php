<?php
require_once('classes/core.php');
$title = "Преподавательская";
require_once('classes/head.php');
require_once('classes/admin_class.php');
mode('admin');

if($admin['role'] != 0)
{
	header('Location:/ej/teather_journal.php');exit();
}

$m = new manager();

$login = $admin["login"];
$allGroups = $m->GetAllGroups();
$allSubjects = $m->GetAllSubjects();
$teachers = $m->GetTeacher();

include("templates/admin.php");


require_once('classes/foot.php');
?>