<?php

require('classes/core.php');
require('classes/ajax_class.php');
$m = new ajax();

$stamp = date("Y-m-d : H:i:s", time());


############################################################


if(isset($_POST['action']) and $_POST['action']=="login_parent"){
	$student_name = YCS($_POST['student_name']);
	$group_id = YCS($_POST['group_id']);
	$birth_day = YCS($_POST['birth_day']);
	$S_Code = YCS($_POST['S_Code']);
	$vac = Security::verify_str();

if(isset($vac)) exit( Security::verify_str() );
if(empty($student_name)) exit('Неверные данные!');
if(!preg_match("/^[а-яА-Я]+$/iu", $student_name)) exit('Неверные данные!');
if(empty($group_id)) exit('Неверные данные!');
if(empty($birth_day)) exit('Неверные данные!');
if(!preg_match('/^(\d{2}).(\d{2}).(\d{4})$/', $birth_day)) exit('Неверные данные!');

$birth_day = date_format(date_create($_POST['birth_day']), "Y-m-d");
$result = DB::$dbs->queryFetch("SELECT students.id,
	concat_ws(' ',students.f,students.i,students.o) name,
	students.state+0 state,
	students.group_id
	FROM students 
	LEFT JOIN student_info 
	on student_info.student_id=students.id 
	WHERE students.f='".$student_name."' and 
	students.group_id='".$group_id."' and 
	student_info.birthday='".$birth_day."'");


if(isset($result['id']))
{
	if ($result['state']!=2) {
		exit("Уже не учится!");
	}
	$_SESSION['parent']['name'] = $result['name'];
    $_SESSION['parent']['id'] = $result['id'];
	exit('good');
}else{
	exit('Неверные данные!');
}

}


###############################################################################


if(isset($_POST['action']) and $_POST['action']=="login_teather"){
	$login = YCS($_POST['login']);
	$password = YCS($_POST['password']);
	$S_Code = YCS($_POST['S_Code']);
	$vac = Security::verify_str();

if(isset($vac)) exit( Security::verify_str() );
if(empty($login)) exit('Неверный данные!');
if(empty($password)) exit('Неверный данные!');

$result = DB::$dbs->queryFetch("SELECT `id`, `login`, `role` FROM `journal_users` WHERE `login`='".$login."' and `password`='".md5($password)."'");
if(isset($result['id']))
{
	$_SESSION['teacher']['id'] = $result['id'];
    $_SESSION['teacher']['login'] = $result['login'];
    $_SESSION['teacher']['role'] = $result['role'];
	exit('good');
}else{
	exit('Неверный данные!');
}


}


###############################################################################


if(isset($_GET['action']) and $_GET['action']=="vedomost"){
	mode('admin');

	require_once('classes/PHPExcel.php');
	require_once('classes/PHPExcel/Writer/Excel5.php');

	$xls = new PHPExcel();
	$xls->setActiveSheetIndex(0);
	$sheet = $xls->getActiveSheet();
	$sheet->setTitle('Таблица умножения');

// Вставляем текст в ячейку A1
$sheet->setCellValue("A1", 'Таблица умножения');
$sheet->getStyle('A1')->getFill()->setFillType(
    PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');

// Объединяем ячейки
$sheet->mergeCells('A1:H1');

// Выравнивание текста
$sheet->getStyle('A1')->getAlignment()->setHorizontal(
    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

for ($i = 2; $i < 10; $i++) {
	for ($j = 2; $j < 10; $j++) {
        // Выводим таблицу умножения
        $sheet->setCellValueByColumnAndRow($i - 2,$j,$i . "x" .$j . "=" . ($i*$j));
	    // Применяем выравнивание
	    $sheet->getStyleByColumnAndRow($i - 2, $j)->getAlignment()->
                setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	}
}

// Выводим HTTP-заголовки
 //header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
 //header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
 //header ( "Cache-Control: no-cache, must-revalidate" );
 //header ( "Pragma: no-cache" );
 //header ( "Content-type: application/vnd.ms-excel" );
 //header ( "Content-Disposition: attachment; filename=matrix.xls" );

// Выводим содержимое файла
 $objWriter = new PHPExcel_Writer_Excel5($xls);
 //$objWriter->save('php://output');


	if(is_numeric($_GET["group_id"]) and isset($_GET["year"]) and isset($_GET["month"])){
		$aGroup = $m->GetGroupById($_GET["group_id"]);
		$group_name = $aGroup[0]["group_name"];
		$subjects = $m->GetStudentsAndSkips($aGroup[0]["group_id"],$_GET["year"],(int)$_GET["month"]);
		$dates = $m->GetDatesForSkips($_GET["year"],(int)$_GET["month"]);
		$month_name = $aNumber_monthName[(int)$_GET['month']];
		$stady_year = (int)$_GET['month'] > 7 ? ($_GET['year'].'/'.substr($_GET['year']+1,2)) : (($_GET['year']-1).'/'.substr($_GET['year'],2));
		$aNumber_value = $aNumber_value;
		include('templates/vedomost.php');
	}else{
		echo "error";
	}
	die();
}


###############################################################################


if(isset($_POST['action']) and $_POST['action']=="show_table"){
	mode('admin');
	if(!is_numeric($_POST["subject_id"]) && !is_numeric($_POST["group_id"])) die("error");

	$dates = $m->GetDates($_POST["subject_id"],$admin['id'],$_POST["group_id"]);
	$students = $m->GetSemesterMarks($_POST["group_id"],$_POST["subject_id"],$m->GetStudentsAndMarks($_POST["group_id"],$_POST["subject_id"],true));
	$labExists = $m->labExists($_POST["subject_id"],$_POST["group_id"]);
	$aNumber_monthName = $aNumber_monthName;
	$aNumber_value = $aNumber_value;
	include('templates/journal_table.php');
}

################################################################################

if(isset($_POST['action']) and $_POST['action']=="add_date"){
	mode('admin');
	if(!is_numeric($_POST["subject_id"]) && is_numeric($_POST["group_id"]) && !isset($_POST["pair_disc"]) && !is_numeric($_POST["pair_type"]) && preg_match("/\d{4}-\d{2}-\d{2}/", $_POST["new_date"])==0) die("error");

	DB::$dbs->query("INSERT INTO `journal_pairs` SET 
		subject_id = '".$_POST["subject_id"]."',
		on_date = '".$_POST["new_date"]."',
		stamp = '".$stamp."',
		type_id = '".$_POST["pair_type"]."',
		pair_disc = '".$_POST["pair_disc"]."',
		owner_id = '".$admin['id']."',
		group_id = '".$_POST["group_id"]."'
	");

	$labExists = $m->labExists($_POST["subject_id"],$_POST["group_id"]);
	$dates = $m->GetDates($_POST["subject_id"],$admin['id'],$_POST["group_id"]);
	$students = $m->GetStudentsAndMarks($_POST["group_id"],$_POST["subject_id"]);
	$aNumber_monthName = $aNumber_monthName;
	$aNumber_value = $aNumber_value;

	include('templates/journal_table.php');
}

##################################################################################

if($_POST['action']=="edit_date"){
	mode('admin');
	$date = \DateTime::createFromFormat('Y-n-j',$_POST["new_date"]);

	if($date instanceof \DateTime && !is_numeric($_POST["subject_id"]) && !is_numeric($_POST["group_id"]) && !is_numeric($_POST["pair_type"]) && !is_numeric($_POST["reset_id"]) && !isset($_POST["pair_disc"])) die("error");

	DB::$dbs->query("UPDATE `journal_pairs` SET `on_date` = '".$_POST["new_date"]."', `type_id` = '".$_POST["pair_type"]."', `pair_disc` = '".$_POST["pair_disc"]."' WHERE `id` = '".$_POST["reset_id"]."'");

	$dates = $m->GetDates($_POST["subject_id"],$admin['id'],$_POST["group_id"]);
	$students = $m->GetStudentsAndMarks($_POST["group_id"],$_POST["subject_id"]);
	$aNumber_monthName = $aNumber_monthName;
	$aNumber_value = $aNumber_value;
	$labExists = $m->labExists($_POST["subject_id"],$_POST["group_id"]);

	include('templates/journal_table.php');
}

##################################################################################

if ($_POST['action']=='set_mark'){
	mode('admin');

	$prooved = Check::checkEditMark($_POST["pair_id"],$_POST["student_id"],$admin['id'],$admin['role']);//вернёт prooved или FALSE

	$only_del = FALSE; // mark == X
	$values = (array)$_POST['value'];
	$arraySize = count($values);
	$mark_type_id = 0;

	for ($i=0; $i < $arraySize; $i++) {
		if(!in_array($values[$i], $aValue_number)) die("error: inval value");
		if($values[$i]=="X" or $only_del) $only_del=TRUE;
		if ($values[$i] == 'з') {
			$mark_type_id = 1;
		}
	}

	if($prooved !== FALSE){
		//удаляем вне зависимости от того что и сколько пришло.
		DB::$dbs->query("DELETE FROM `journal_marks` WHERE `pair_id` = '".$_POST["pair_id"]."' AND `student_id` = '".$_POST["student_id"]."' AND `id` = '".$_POST["mark_id"]."'");

	  	if(!$only_del){// выставляем отметку/отметки
	  		foreach ($values as $mark) {
	  			DB::$dbs->query("INSERT INTO `journal_marks` SET `pair_id` = '".$_POST["pair_id"]."', `student_id` = '".$_POST["student_id"]."', `owner_id` = '".$admin['id']."', `value` = '".$aValue_number[$mark]."', `stamp` = '".$stamp."', `prooved` = '".$prooved."', `type_id` = '".$mark_type_id."'");
		  	}
		  	echo DB::$dbs->lastInsertId();

		  	$laba = DB::$dbs->queryFetch("SELECT * FROM `journal_pairs` WHERE `id` = '".$_POST["pair_id"]."'");
	  			if($laba['type_id'] == 2 and $aValue_number[$mark] > 3)
	  			{
	  				DB::$dbs->query("INSERT INTO `journal_marks` SET `pair_id` = '".$_POST["pair_id"]."', `student_id` = '".$_POST["student_id"]."', `owner_id` = '".$admin['id']."', `value` = 11, `stamp` = '".$stamp."', `prooved` = '".$prooved."', `type_id` = 1");
	  			}
		} else {
			echo "0";
		}
	}else{
		echo "error";
	}

}

######################################################################################

if($_POST['action']=="show_labs"){
	mode('admin');
	if(!is_numeric($_POST["subject_id"]) && !is_numeric($_POST["group_id"])) die("error");

		$dates = $m->GetDatesLabs($_POST["subject_id"],$admin['id'],$_POST["group_id"]);
		$students = $m->GetSemesterMarks($_POST["group_id"],$_POST["subject_id"],$m->GetStudentsAndLabs($_POST["group_id"],$_POST["subject_id"]));
		$labExists = $m->labExists($_POST["subject_id"],$_POST["group_id"]);
		$aNumber_monthName = $aNumber_monthName;
		$aNumber_value = $aNumber_value;
		include('templates/journal_table_labs.php');

}

########################################################################################

if($_POST['action']=="show_lateness"){
	mode('admin');
	if(!is_numeric($_POST["subject_id"]) && !is_numeric($_POST["group_id"])) die("error");
	$o = new parent_manager_o();

		$dates = $m->GetDates($_POST["subject_id"],$admin['id'],$_POST["group_id"]);
		$students = $m->GetSemesterMarks($_POST["group_id"],$_POST["subject_id"],$o->GetSubjectsAndMarks($_POST["group_id"],$_POST["subject_id"]));
		$labExists = $m->labExists($_POST["subject_id"],$_POST["group_id"]);
		$aNumber_monthName = $aNumber_monthName;
		$aNumber_value = $aNumber_value;
		include('templates/journal_table_lateness.php');

}

##########################################################################################

if ($_POST['action']=='set_lateness'){
	mode('admin');

	$prooved = Check::checkEditMark($_POST["pair_id"],$_POST["student_id"],$admin['id'],$admin['role']);//вернёт prooved или FALSE

	$only_del = FALSE; // mark == X
	$values = (array)$_POST['value'];
	$arraySize = count($values);
	$mark_type_id = 0;

	for ($i=0; $i < $arraySize; $i++) {
		if($values[$i] == "Х") $only_del = null;
		if($values[$i] == "0") die("00");
	}

	if($prooved !== FALSE){
		//удаляем вне зависимости от того что и сколько пришло.
		DB::$dbs->query("DELETE FROM `journal_lateness_marks` WHERE `pair_id` = '".$_POST["pair_id"]."' AND `student_id` = '".$_POST["student_id"]."' AND `id` = '".$_POST["mark_id"]."'");

	  	if(isset($only_del)){// выставляем отметку/отметки
	  		DB::$dbs->query("INSERT INTO `journal_lateness_marks` SET `pair_id` = '".$_POST["pair_id"]."', `student_id` = '".$_POST["student_id"]."', `owner_id` = '".$admin['id']."', `value` = '".$values[0]."', `stamp` = '".$stamp."', `prooved` = '".$prooved."', `type_id` = '".$mark_type_id."'");
		  	echo DB::$dbs->lastInsertId();
		} else {
			echo "0";
		}
	}else{
		echo "error";
	}

}

#####################################################################################

if($_POST['action']=="select_teacher"){
	if(!is_numeric($_POST["teacher_id"])) die("error");

	require_once('classes/admin_class.php');
	$ma = new manager();

	$teacher_id = $_POST["teacher_id"];
	$info_teacher = DB::$dbs->queryFetch("SELECT `id`, `login`, `role`, `password` FROM `journal_users` WHERE `id` = '".$_POST["teacher_id"]."'");
	$subjects = $m->GetSubjectsForTeacher($_POST["teacher_id"]);
	$allSubjects = $ma->GetAllSubjects();
	$allGroups = $ma->GetAllGroups();

	include("templates/allSubjects.php");
}

#######################################################################################

if($_POST['action']=="add_subject_for_groups"){
	if(!is_numeric($_POST["teacher_id"]) and !is_numeric($_POST["subject_id"]) and !is_array($_POST["groups_id"])) die("error");

		$m->addSubjectsForGroups($_POST["teacher_id"],$_POST["subject_id"],$_POST["groups_id"]);
		echo "ok!";
}

#######################################################################################

if($_POST['action']=="delete_per"){
	if(!is_numeric($_POST["permission_id"])) die("error");

		$m->deletePermissions($_POST["permission_id"]);
		echo "ok!";
}

########################################################################################

if($_POST['action']=="teacher_load"){
	if(!is_numeric($_POST["teacher_id"])) die("error");

		$teacher_id = $_POST["teacher_id"];
		$info_teacher = DB::$dbs->queryFetch("SELECT `id`, `login`, `role`, `password` FROM `journal_users` WHERE `id` = '".$_POST["teacher_id"]."'");

		$_SESSION['teacher']['id'] = $info_teacher['id'];
	    $_SESSION['teacher']['login'] = $info_teacher['login'];
	    $_SESSION['teacher']['role'] = $info_teacher['role'];

		echo "ok!";
}

#######################################################################################

if($_POST['action']=="teacher_delete"){
	if(!is_numeric($_POST["teacher_id"]) or $_POST["teacher_id"] == $admin['id']) die("error");

		$teacher_id = $_POST["teacher_id"];
		DB::$dbs->query("DELETE FROM `journal_users` WHERE `id` = '".$_POST["teacher_id"]."'");


		echo "ok!";
}




?>