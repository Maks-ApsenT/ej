<?
class parent_manager
{

	public function GetSubjectsAndMarks($group_id,$student_id,$withLabs = false){

		$subjects2 = DB::$dbs->query("SELECT journal_permissions.subject_id as subject_id, subject.long as subject_name, subject.lek as lek FROM journal_permissions LEFT JOIN subject on subject.id=journal_permissions.subject_id WHERE group_id=?",$group_id)->fetchAll();

		foreach ($subjects2 as $value) {
			$subjects[$value["subject_id"]] = 
			[
				"subject_id" => $value['subject_id'],
				"subject_name" => $value['subject_name'],
				"lek" => $value['lek']
			];
		}

		$keyS = 0;
		foreach ($subjects as $keyS => $valS) {
			$subjects[$keyS]["marks"]=DB::$dbs->query("SELECT journal_pairs.on_date as `on_date`, journal_marks.value as `value`, journal_pairs.type_id as `type_id`, CONCAT(IF(journal_pairs.type_id = 0,'Лекция','Практика'),IF(journal_pairs.pair_disc <> '',CONCAT(', ',journal_pairs.pair_disc),'')) as `desc` FROM journal_pairs left join journal_marks on journal_marks.pair_id=journal_pairs.id where journal_pairs.group_id='".$group_id."' AND journal_pairs.subject_id='".$subjects[$keyS]["subject_id"]."' and journal_marks.student_id='".$student_id."'".($withLabs ? '' : ' and journal_pairs.type_id<2 ')."order by journal_pairs.on_date")->fetchAll();
		}

		$marksStat = array();
		$aTempMarks = array();
		$iCountSkip =  array();

		foreach ($subjects as $keyS => $valS) {//открыли предмет
			foreach ($valS["marks"] as $valMark) {//открыли оценки
				if ($valMark["value"]<11) {
					$aTempMarks[]=$valMark["value"];

					$iCountSkip[$valMark["on_date"]] = false;
				}elseif ($valMark["value"]==12 and (int)$valMark["type_id"] >= 1) {
					if (!isset($iCountSkip[$valMark["on_date"]]) or (isset($iCountSkip[$valMark["on_date"]]) and $iCountSkip[$valMark["on_date"]] !== false)) {
						$iCountSkip[$valMark["on_date"]] = true;
					}
				}
			}
			$iCountSkip = array_filter($iCountSkip);

			if(count($aTempMarks)>0){
				$subjects[$keyS]["avg"]=round(array_sum($aTempMarks)/count($aTempMarks),1);
			}else{
				$subjects[$keyS]["avg"]="-";
			}

			$subjects[$keyS]["skip_count"]=$iCountSkip;

			if ($subjects[$keyS]["avg"]<(float)4 or count($subjects[$keyS]["skip_count"]) > 0) {
				$subjects[$keyS]["the_galaxy_is_in_danger"] = TRUE;
			}else{
				$subjects[$keyS]["the_galaxy_is_in_danger"] = FALSE;
			}

			$aTempMarks=array();
			$iCountSkip= array();
		}

		return $subjects;
	}

	public function GetDates($group_id){
		$raw_dates = DB::$dbs->query("SELECT `id`, year(on_date) as `year`, month(on_date) as `month`, day(on_date) as `date`, `type_id` as `type`, on_date FROM `journal_pairs` WHERE `group_id` = {$group_id} GROUP BY on_date ORDER BY on_date,stamp")->fetchAll();

		$dates = array();

		foreach ($raw_dates as $itemRawDates) {
	            $dates[$itemRawDates["year"]][$itemRawDates["month"]][] = array(
	            	"date"=>$itemRawDates["date"],
	            	"type"=>$itemRawDates["type"],
	            	'id'=>$itemRawDates["id"],
	            	'on_date'=>$itemRawDates["on_date"]
	            );
	    }

	    ksort($dates);

	    return $dates;
	}

}

class parent_manager_z
{


public function GetSubjectsAndMarks($subject_id,$student_id,$group_id,$withLabs = false){

	$subjects = DB::$dbs->query("SELECT `id`,`long` FROM subject WHERE id=?",$subject_id)->fetchAll();

	foreach ($subjects as $keyS => $valS) {
		$subjects[$keyS]["marks"]=DB::$dbs->query("SELECT journal_pairs.on_date, journal_marks.value, journal_pairs.type_id, CONCAT(IF(journal_pairs.type_id = 0,'Лекция','Практика'),IF(journal_pairs.pair_disc <> '',CONCAT(', ',journal_pairs.pair_disc),'')) as `desc` FROM journal_pairs left join journal_marks on journal_marks.pair_id = journal_pairs.id where journal_pairs.subject_id={$subject_id} and journal_marks.student_id={$student_id} and journal_pairs.type_id=2 and (journal_marks.value=11 or journal_marks.value=12) order by journal_pairs.on_date")->fetchAll();
	}

	$marksStat = array();
	$aTempMarks = array();
	$iCountSkip =  array();

	foreach ($subjects as $keyS => $valS) {//открыли предмет
		$subjects[$keyS]["avg"] = 0;
		foreach ($valS["marks"] as $valMark) {//открыли оценки
			if ($valMark["value"]==12 and (int)$valMark["type_id"] == 2) {
					$iCountSkip[$valMark["on_date"]] = true;
			}
			if ($valMark["value"]==11 and (int)$valMark["type_id"] == 2) {
					$subjects[$keyS]["avg"] += 1;
			}
		}
		$iCountSkip = array_filter($iCountSkip);

		$check2 = DB::$dbs->querySingle("SELECT count(id) FROM journal_pairs where subject_id={$subject_id} and group_id={$group_id} and type_id=2");
		$subjects[$keyS]["avg2"] = $check2;


		$subjects[$keyS]["skip_count"]=$iCountSkip;

		$aTempMarks=array();
		$iCountSkip= array();
	}

	return $subjects;
}

public function GetDates($subject_id,$group_id){
	$raw_dates = DB::$dbs->query("SELECT id, year(on_date) `year`, month(on_date) `month`, day(on_date) `date`, type_id type, on_date, pair_disc FROM journal_pairs WHERE subject_id={$subject_id} and type_id=2 and `group_id`={$group_id} GROUP BY on_date ORDER BY on_date, stamp")->fetchAll();

	$dates = array();

	foreach ($raw_dates as $itemRawDates) {
        $dates[$itemRawDates["year"]][$itemRawDates["month"]][] = array(
        	"date"=>$itemRawDates["date"],
        	"type"=>$itemRawDates["type"],
        	'id'=>$itemRawDates["id"],
        	'on_date'=>$itemRawDates["on_date"],
        	'pair_disc'=>$itemRawDates["pair_disc"]
        );
    }

    ksort($dates);

    return $dates;
}


}


class parent_manager_o
{

public function GetDates($group_id){
	$raw_dates = DB::$dbs->query("SELECT id, year(on_date) `year`, month(on_date) `month`, day(on_date) `date`, on_date, pair_disc FROM journal_pairs WHERE `group_id`={$group_id} GROUP BY on_date ORDER BY on_date, stamp")->fetchAll();

	$dates = array();

	foreach ($raw_dates as $itemRawDates) {
        $dates[$itemRawDates["year"]][$itemRawDates["month"]][] = array(
        	"date"=>$itemRawDates["date"],
        	'id'=>$itemRawDates["id"],
        	'on_date'=>$itemRawDates["on_date"],
        	'pair_disc'=>$itemRawDates["pair_disc"]
        );
    }

    ksort($dates);

    return $dates;
}	



public function GetSubjectsAndMarks($group_id,$student_id,$withLabs = false){

	$subjects2 = DB::$dbs->query("SELECT journal_permissions.subject_id as subject_id, subject.long as subject_name FROM journal_permissions LEFT JOIN subject on subject.id=journal_permissions.subject_id WHERE group_id=?",$group_id)->fetchAll();

	foreach ($subjects2 as $value) {
		$subjects[$value["subject_id"]] = 
		[
			"subject_id" => $value['subject_id'],
			"subject_name" => $value['subject_name']
		];
	}

	$keyS = 0;
	foreach ($subjects as $keyS => $valS) {
		$subjects[$keyS]["marks"]=DB::$dbs->query("SELECT journal_pairs.on_date as `on_date`, journal_lateness_marks.value as `value`, journal_lateness_marks.pair_disc as `pair_disc` FROM journal_pairs left join journal_lateness_marks on journal_lateness_marks.pair_id=journal_pairs.id where journal_pairs.group_id='".$group_id."' AND journal_pairs.subject_id='".$subjects[$keyS]["subject_id"]."' and journal_lateness_marks.student_id='".$student_id."' order by journal_pairs.on_date")->fetchAll();
	}

	$marksStat = array();$aTempMarks = array();$iCountSkip =  array();

	//print_r($subjects);

	foreach ($subjects as $keyS => $valS) {//открыли предмет
		foreach ($valS["marks"] as $valMark) {//открыли оценки
			$aTempMarks[]=$valMark["value"];
		}
		$iCountSkip = array_filter($iCountSkip);

		if(count($aTempMarks)>0){
			$subjects[$keyS]["avg"]=array_sum($aTempMarks);
		}else{
			$subjects[$keyS]["avg"]="-";
		}

		$subjects[$keyS]["skip_count"]=$iCountSkip;

		$aTempMarks=array();$iCountSkip= array();
	}

	return $subjects;
}


}