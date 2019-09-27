<?

$aNumber_monthName = array(1 => "январь",2 => "февраль",3 => "март",4 => "апрель", 5 => "май",6 => "июнь",	7 => "июль",8 => "август", 9 => "сентябрь",10 => "октябрь",11 => "ноябрь",12 => "декабрь");

$aNumber_value = array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",9=>"9",10=>"10",11=>"з",12=>"н");

$aValue_number = array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, "з" => 11,"н" => 12, "X" => 13);

class ajax
{

public function GetGroupById($group_id){
    $group = DB::$dbs->query("SELECT id as group_id, group_name FROM groups WHERE id=?", $group_id)->fetchAll();

    return $group;

}

public function GetStudentsAndSkips($group_id, $year, $month){//получаем учащихся и их пропуски
    $students = DB::$dbs->query("SELECT st.id, concat(st.f,' ',left(st.i,1),'. ',left(st.o,1),'.') short_name, concat_ws(' ',st.f,st.i,st.o) long_name FROM students st LEFT JOIN groups g ON g.id = st.group_id WHERE g.id={$group_id} AND st.state='обучается' ORDER BY st.f, st.i")->fetchAll();

    foreach ($students as $key => $student) {
        $students[$key]['marks'] = DB::$dbs->query("SELECT DATE_FORMAT(p.on_date,'%Y-%m-%d') as on_date, count(p.id)*2 as skip_hour FROM journal_marks m INNER JOIN journal_pairs p ON m.pair_id = p.id where m.student_id='{$student['id']}' and value = 12 and p.on_date >= '".date('Y-m-d',mktime(0,0,0,$month,1,$year))."' and p.on_date < '".date('Y-m-d',mktime(0,0,0,$month+1,1,$year))."' GROUP BY DATE_FORMAT(p.on_date,'%Y-%m-%d')")->fetchAll();
    }

    return $students;

}

public function GetDatesForSkips($year, $month){// поолучаем диапазон дат для ведомости
    $day_count = date('t',mktime(0,0,0,$month,1,$year));
    $dates = array();

    for ($i=1; $i <= $day_count; $i++) {
        $dates[] = date('Y-m-d',mktime(0,0,0,$month,$i,$year));
    }

    return $dates;
}

public function GetDates($subject_id, $owner_id, $group_id) {//получаем даты занятий
    $raw_dates = DB::$dbs->query("SELECT id, year(on_date) `year`, month(on_date) `month`, day(on_date) `date`, type_id type, pair_disc discription, on_date FROM journal_pairs WHERE subject_id={$subject_id} AND group_id={$group_id} and type_id<3 ORDER BY on_date, stamp");

    $dates = array();

    foreach ($raw_dates as $itemRawDates) {
        $dates[$itemRawDates["year"]][$itemRawDates["month"]][] =
            array('id'=>$itemRawDates["id"],
            "date"=>$itemRawDates["date"],
            "type"=>$itemRawDates["type"],
            "disc"=>$itemRawDates["discription"],
            'on_date'=>$itemRawDates["on_date"]
        );
    }

    ksort($dates);

    return $dates;
}

public function GetDatesLabs($subject_id, $owner_id, $group_id) {//получаем даты занятий
    $raw_dates = DB::$dbs->query("SELECT id, year(on_date) `year`, month(on_date) `month`, day(on_date) `date`, type_id type, pair_disc discription, on_date FROM journal_pairs WHERE subject_id={$subject_id} AND group_id={$group_id} and type_id = 2 ORDER BY on_date, stamp");

    $dates = array();

    foreach ($raw_dates as $itemRawDates) {
        $dates[$itemRawDates["year"]][$itemRawDates["month"]][] =
            array('id'=>$itemRawDates["id"],
            "date"=>$itemRawDates["date"],
            "type"=>$itemRawDates["type"],
            "disc"=>$itemRawDates["discription"],
            'on_date'=>$itemRawDates["on_date"]
        );
    }

    ksort($dates);

    return $dates;
}

public function GetSemesterMarks($group_id, $subject_id, $all_st_and_marks){
    return $all_st_and_marks;

    $queryInfo = DB::$dbs->query("SELECT m.id as mark_id, m.student_id, p.id as pair_id, m.value, st.f FROM  journal_pairs p left join  journal_marks m on p.id=m.pair_id LEFT JOIN  students st ON st.id = m.student_id where p.type_id=2 and p.subject_id=? and p.group_id=? ORDER BY st.f, st.i", $subject_id, $group_id)->fetchAll();

    if($queryInfo['num_rows']==0) return $all_st_and_marks;

    $iArraySize = count($all_st_and_marks);
    for ($i=0; $i < $iArraySize; $i++) {
        $all_st_and_marks[$i]["semester_mark"] = $semesterMark[$i]["value"];
        $all_st_and_marks[$i]["semester_mark_id"] = $semesterMark[$i]["mark_id"];
        $all_st_and_marks[$i]["semester_number"] = $semesterMark[$i]["semester"];
    }

    return $all_st_and_marks;

}

public function GetStudentsAndMarks($group_id, $subject_id, $skip_pair_type = false){//получаем учащихся и их оценки
    $students = DB::$dbs->query("SELECT st.id, concat(st.f,' ',left(st.i,1),'. ',left(st.o,1),'.') short_name,
        concat_ws(' ',st.f,st.i,st.o) long_name
    FROM students st
    LEFT JOIN groups g ON g.id = st.group_id
    WHERE g.id={$group_id} AND st.state='обучается'
    ORDER BY st.f, st.i")->fetchAll();

    foreach ($students as $key => $student) {

        $students[$key]['marks'] = DB::$dbs->query("SELECT m.id, pair_id, student_id, m.owner_id, value, prooved, subject_id FROM journal_marks m left join journal_pairs p on m.pair_id=p.id where m.student_id='{$student['id']}'".($skip_pair_type ? '' : ' and m.type_id = 0 ')."and p.subject_id='{$subject_id}' and p.type_id<3")->fetchAll();
    }

        $marksStat = array();
        $aTempMarks = array();
        $iCountSkip = 0;

        foreach ($students as $keyS => $valS) {//открыли предмет
            foreach ($valS["marks"] as $valMark) {//открыли оценки
                if ($valMark["value"]<11) {
                    $aTempMarks[]=$valMark["value"];
                }
            }

            if(count($aTempMarks)>0){
                $students[$keyS]["avg"]=round(array_sum($aTempMarks)/count($aTempMarks),1);
            }else{
                $students[$keyS]["avg"]="-";
            }

            $aTempMarks=array();
        }
    return $students;
}

public function GetStudentsAndLabs($group_id, $subject_id){//получаем учащихся и их оценки
    $students = DB::$dbs->query("SELECT st.id, concat(st.f,' ',left(st.i,1),'. ',left(st.o,1),'.') short_name, concat_ws(' ',st.f,st.i,st.o) long_name FROM students st LEFT JOIN groups g ON g.id = st.group_id WHERE g.id={$group_id} AND st.state='обучается' ORDER BY st.f, st.i")->fetchAll();

    foreach ($students as $key => $student) {
        $students[$key]['marks'] = DB::$dbs->query("SELECT m.id, pair_id, student_id, m.owner_id, value, prooved, subject_id FROM journal_marks m left join journal_pairs p on m.pair_id=p.id AND p.type_id = 2 where m.student_id='{$student['id']}' and p.subject_id='{$subject_id}' and ((m.type_id = 0 AND m.value = 12) OR m.value = 11)")->fetchAll();
    }

        $marksStat = array();
        $aTempMarks = array();
        $iCountSkip = 0;

        //print_r($students);

        foreach ($students as $keyS => $valS) {//открыли предмет
            $students[$keyS]["avg"] = 0;
            foreach ($valS["marks"] as $valMark) {//открыли оценки
                if ($valMark["value"] == 11) {
                    $students[$keyS]["avg"] += 1;
                }
            }

            $check2 = DB::$dbs->querySingle("SELECT count(id) FROM journal_pairs where subject_id={$subject_id} and group_id={$group_id} and type_id=2");
            $students[$keyS]["avg2"] = $check2;

            $aTempMarks=array();
        }
    return $students;
}

public function labExists($subject_id,$group_id)
{
    $labs = DB::$dbs->querySingle("SELECT count(id) FROM journal_pairs WHERE subject_id={$subject_id} AND group_id={$group_id} AND type_id = 2");

    return (int)$labs > 0;
}

public function GetSubjectsForTeacher($teacher_id){
    $subjects = DB::$dbs->query("SELECT per.id, per.user_id, per.subject_id, per.group_id, s.`long` subject_name, ucase(g.group_name) as group_name
        FROM  journal_permissions per
        LEFT JOIN subject s ON s.id=per.subject_id
        LEFT JOIN groups g ON g.id=per.group_id
        WHERE per.user_id=?
        ORDER BY s.`long`",$teacher_id);
    return $subjects;
}

public function addSubjectsForGroups($teacher_id,$subject_id,$groups_id) {
    foreach ($groups_id as $group_id) {
        DB::$dbs->query("INSERT INTO `journal_permissions` SET `user_id` = '".$teacher_id."', `subject_id` = '".$subject_id."', `group_id` = '".$group_id."'");
    return true;
    }
}

public function deletePermissions($permission_id) {
    $subjects = DB::$dbs->query("DELETE FROM `journal_permissions` WHERE id= '".$permission_id."'");
    return true;
}



}


class Check {

    public static function checkEditMark($pair_id, $student_id, $user_id, $user_role){

        $mark = DB::$dbs->queryFetch("SELECT `id`,`pair_id`,`student_id`,`owner_id`,`prooved` FROM `journal_marks` WHERE `pair_id` = '".$pair_id."' AND `student_id` = '".$student_id."'");

        if($mark["owner_id"] == $user_id){//отметку меняет тот, кто её ставил
            // echo "1 == 1";
            return $user_role < 3 ? 1 : 0;
        }

        if($mark["owner_id"] == 0){//отметка ещё не выставлена
            // echo "Нет владельца отметки";
            return $user_role < 3 ? 1 : 0;
        }

        $role = DB::$dbs->queryFetch("SELECT `role` FROM `journal_users` WHERE `id` = '".$mark["owner_id"]."'");

        if($user_role<=$role){//куратор не может менять отметку препода, староста отметку куратора
            // echo "1 <= 2";
            return 1;
        }
        // echo "Роль юзера:$user_role, Роль владельца отметки:$role, Айдишник владельца отметки:". $mark['owner_id'];
        return false;
    }
}


class parent_manager_o
{

    public function GetSubjectsAndMarks($group_id,$subject_id){

        $students = DB::$dbs->query("SELECT st.id, concat(st.f,' ',left(st.i,1),'. ',left(st.o,1),'.') short_name, concat_ws(' ',st.f,st.i,st.o) long_name FROM students st LEFT JOIN groups g ON g.id = st.group_id WHERE g.id={$group_id} AND st.state='обучается' ORDER BY st.f, st.i")->fetchAll();

        foreach ($students as $key => $student) {
            $students[$key]['marks'] = DB::$dbs->query("SELECT m.id, pair_id, student_id, m.owner_id, value, prooved, subject_id FROM journal_lateness_marks m left join journal_pairs p on m.pair_id=p.id where m.student_id='{$student['id']}' and p.subject_id='{$subject_id}'")->fetchAll();
        }

        $marksStat = array();
        $aTempMarks = array();
        $iCountSkip = 0;


        foreach ($students as $keyS => $valS) {//открыли предмет
            foreach ($valS["marks"] as $valMark) {//открыли оценки
                $aTempMarks[]=$valMark["value"];
            }

        if(count($aTempMarks)>0){
            $students[$keyS]["avg"]=array_sum($aTempMarks);
        }else{
            $students[$keyS]["avg"]="-";
        }

        $aTempMarks=array();

        }

        return $students;
    }


}