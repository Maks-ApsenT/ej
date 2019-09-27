<?
require_once('../classes/core.php');
if(!isset($user['id'])) header("Location:/ej/");
require_once('../classes/parent_class.php');
$m = new parent_manager();

$aNumber_monthName = array(1 => "январь",2 => "февраль",3 => "март",4 => "апрель",5 => "май",6 => "июнь",
	7 => "июль",8 => "август",9 => "сентябрь",10 => "октябрь",11 => "ноябрь",12 => "декабрь");
$aNumber_value = array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",
				9=>"9",10=>"10",11=>"зч",12=>"н");
$subjects = $m->GetSubjectsAndMarks($user['group_id'],$user['id'],true);
$dates = $m->GetDates($user['group_id']);
?>


<div class="middle">
	<div class="leftColumn">
		<table class="leftTable heightRow27" cellspacing="0" cellpading="0" border="0">
			<tr>
				<td><div class="tableMonth">Месяц</div></td>
			</tr>
			<tr>
				<td class="b2b">
					<div class="o">
					    <div class="b"></div>
					    <div class="b2"></div>
					    <div class="u"></div>
					    <div class="dateChant">Дата</div>
					    <div class="nameio">Предмет</div>
					</div>
				</td>
			</tr>
			<?foreach($subjects as $subject){?>
				<tr class="row<?=$subject['subject_id']?>">
						<td>
							<div class="pupilName">
								<?if($subject['lek'] == 1){?><a title="Зачеты" onclick="get('templates/parent_journal_z.php?subject_id=<?=$subject['subject_id']?>');" href="#parent_journal_z.php?subject_id=<?=$subject['subject_id']?>"><?}?>
								<?=$subject['subject_name']?>
								<?if($subject['lek'] == 1){?></a><?}?>
								<?=($subject['the_galaxy_is_in_danger'] == true ? '<span class="danger">&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;</span>' : null)?>
							</div>
						</td>
					</tr>
				<?}?>
				<tr>
						<td colspan="2"><a onclick="get('templates/parent_journal_o.php');" href="#parent_journal_o.php" style="text-decoration: none;"><button id="viewLabs">Информация об опозданиях</button></a></td>
				</tr>
			</table>
		</div>
		<div class="rightColumn">
			<table class="rightTable heightRow27" cellspacing="0" cellpading="0" border="0">
				<tr id="months">
					<?foreach ($dates as $numberYear => $year):
						foreach ($year as $numberMonth => $month):?>
							<td colspan="<?=count($month)?>">
								<div title="<?=$aNumber_monthName[$numberMonth]?>" class="<? if(count($month) == 1){?>nameMonthOneChar<?}elseif(count($month) == 2){?>nameMonthOneChar<?}else{?>nameMonth<?}?>"><?=$aNumber_monthName[$numberMonth]?></div>
							</td>
						<? endforeach ?>
					<? endforeach ?>
					<td rowspan="2"  style="border-left: 3px solid #CCC; border-bottom: 3px solid #CCC;"><div class="WrapPairDisc"><div class="pairDisc">Срдн. знч.</div></div></td>
				</tr>
				<tr id="dateOfMonth">
					<?foreach ($dates as $numberYear => $year):
						foreach ($year as $numberMonth => $month):
							foreach ($month as $day):?>
								<td><div><?=$day['date']?></div></td>
							<? endforeach ?>
						<? endforeach ?>
					<? endforeach ?>
				</tr>
				<?foreach ($subjects as $subject):?>
				<tr class="mark mar row<?=$subject['subject_id']?>">
					<?foreach ($dates as $numberYear => $year):?>
						<?foreach ($year as $numberMonth => $month):?>
							<?foreach ($month as $day):?>
								<?
								$marks_in_day = [];
								$alert = 0;
								foreach($subject['marks'] as $mark):
									if ($mark['on_date']==$day['on_date']){
										$marks_in_day[] = $mark;
									}
								endforeach;
								if(count($marks_in_day) == 1 and (($marks_in_day[0]['type_id'] == '2' and $marks_in_day[0]['value'] == '12') or $marks_in_day[0]['type_id'] == '1' and $marks_in_day[0]['value'] < 4)){
									$alert = 1;
								}?>

								<td>
									<div data-count-mark="<?=count($marks_in_day)?>" title="<?=(count($marks_in_day) > 0 ? $marks_in_day[0]['desc'] : null)?>" class="<?=$alert?> <?=isset($marks_in_day[0]['type_id']) ? $marks_in_day[0]['type_id'] : null?> <?=$alert == '1' ? ' alert_m' : null?>">
										<?foreach ($marks_in_day as $m_id => $m):
											if(isset($m['value'])){?>
											<span class="mar" data-mark-id="<?=$m_id?>"><?=$aNumber_value[$m['value']]?></span>
										<?}else{?>
											<span class="mar" data-mark-id="0">&nbsp;</span>
										<?}?>
										<? endforeach ?>
									</div>
								</td>
							<? endforeach ?>
						<? endforeach ?>
					<? endforeach ?>
					<td style="border-left: 3px solid #CCC;"><div><?=$subject['avg']?></div></td>
				</tr>
				<? endforeach ?>
				<tr class="rowPairDisc">
					<?foreach ($dates as $numberYear => $year):?>
						<?foreach ($year as $numberMonth => $month):?>
							<?foreach ($month as $day):?>
								<td><div class="WrapPairDisc"><div class="pairDisc">////////</div></div></td>
							<? endforeach ?>
						<? endforeach ?>
					<? endforeach ?>
				</tr>
			</table>
		</div>
	</div>
	<div class="bottom"></div>
<script>
$("tr").mouseenter(function(e){
    var rowNumber = "."+/row\d+/.exec($(this).attr("class"));
    $(rowNumber).addClass("hoverOn");})
$("tr").mouseleave(function (e) {
    $(".hoverOn").removeClass("hoverOn");
});
</script>