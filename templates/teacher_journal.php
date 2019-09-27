<?
require_once('../classes/core.php');
mode('admin');
require_once('../classes/teacher_class.php');
$m = new teacher_manager();
$months = array('01' => "январь",'02' => "февраль",'03' => "март",'04' => "апрель",'05' => "май",'06' => "июнь",	'07' => "июль",'08' => "август",'09' => "сентябрь",'10' => "октябрь",'11' => "ноябрь",'12' => "декабрь");
$avalible_groups = $m->GetGroupsByTeacher($admin['id']);
?>
<link rel="stylesheet" href="css/godstyle.css">
<link rel="stylesheet" href="css/teacherstyle.css">
<script type="text/javascript" src="js/openTable.js"></script>
<script type="text/javascript" src="js/addMark.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js"></script>



<div class="up">
	<div class="inf">
		<select name="year" id="vedomost_year">
			<option value="<?=date("Y",strtotime("-1 year"))?>"><?=date("Y",strtotime("-1 year"))?></option>
			<option value="<?=date("Y")?>" selected="selected"><?=date("Y")?></option>
		</select>
		<select name="month" id="vedomost_month">
			<? foreach($months as $monthNumber => $monthName): ?>
				<option value="<?=$monthNumber?>" <?if(date("m") == $monthNumber){?>selected="selected"<?}?>><?=$monthName?></option>
			<? endforeach;?>
		</select>
		<span style="color: blue;border-bottom: 1px blue dashed;cursor: pointer" id="vedomost">Ведомость пропусков</span>
	</div>
	<div class="options"></div>
</div>

<div class="preMiddle" style="height: 50px">
	<input type="button" class="cScroll opaciyFrom4to8" id="topScroll" value="">
	<div class="groupsAndSubjectsWrap" style="height: 50px;">
		<input type="button" class="cScroll opaciyFrom4to8" id="leftScroll" value="">
		<div class="groupsWrapTwo scrollingWrap">
			<ul class="groups scrollingId">
				<? foreach($avalible_groups as $group): ?>
					<li groupid="<?=$group['group_id']?>" class="group" style="height: 28px;"><?=$group['group_name']?></li>
				<? endforeach;?>
			</ul>
		</div>
		<div class="subjectsWrapTwo" checkNow="">
			<div class="currentGroup" style="height: 28px;"></div>
			<? foreach($avalible_groups as $group): ?>
			<ul class="subjects" groupid="<?=$group['group_id']?>" style="display: none;">
				<? foreach($group['subjects'] as $subject): ?>
					<li groupid="<?=$group['group_id']?>" subjectid="<?=$subject['subject_id']?>" class="subject" style="height: 28px;"><?=$subject['subject_name']?></li>
				<? endforeach;?>
			</ul>
			<? endforeach;?>
		</div>
		<input type="button" class="cScroll opaciyFrom4to8" id="rightScroll" value="">
	</div>
	<input type="button" class="cScroll opaciyFrom4to8" id="bottomScroll" value="">
</div>

<div class="middle"></div>
<div class="bottom"></div>