<?
class teacher_manager
{

	public function GetSubjectsForTeacher($teacher_id){
        $subjects = db::simpleMysqli()->select("SELECT per.id, per.user_id, per.subject_id, per.group_id, s.`long` subject_name, ucase(g.group_name) as group_name
            FROM  journal_permissions per
            LEFT JOIN subject s ON s.id=per.subject_id
            LEFT JOIN groups g ON g.id=per.group_id
            WHERE per.user_id=?
            ORDER BY s.`long`",$teacher_id);
        return $subjects;
    }

    public function GetGroupsByTeacher($teacher_id) {//получаем все доступные группы и предметы для групп для нужного преподавателя
        $groups = DB::$dbs->query("SELECT journal_permissions.group_id, groups.group_name FROM journal_permissions LEFT JOIN groups ON journal_permissions.group_id = groups.id WHERE user_id = {$teacher_id} GROUP BY groups.id")->fetchAll();

        foreach ($groups as $key => $group) {
            $groups[$key]['subjects'] =  DB::$dbs->query("SELECT subject.id subject_id, subject.long subject_name FROM  journal_permissions LEFT JOIN subject ON journal_permissions.subject_id = subject.id WHERE user_id = {$teacher_id} and journal_permissions.group_id = {$group['group_id']}")->fetchAll();
        }
        return $groups;
    }
}



?>