<?
class manager
{

public function GetAllGroups(){//array('id'=>I, 'name'=>S)
$allGroups = DB::$dbs->query("SELECT `id`, `group_name` name FROM `groups` WHERE `state`=1");
return $allGroups;
}

public function GetAllSubjects(){
	$allSubjects = DB::$dbs->query("SELECT `id`, `long` as subject_name FROM `subject`");
	return $allSubjects;
}

public function GetTeacher(){//получаем всех преподов
	$getTeacher = DB::$dbs->query('SELECT `id`, `login` FROM `journal_users` where `role` < 2');
	return $getTeacher;
}
}
?>