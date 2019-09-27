<?
require_once('../classes/core.php');
if(!isset($user['id'])) header("Location:/ej/");
require_once('../classes/parent_class.php');
$m = new parent_manager_z();

if(!isset($_GET['subject_id']) and !is_numeric($_GET['subject_id']))
{ header('Location: parent_journal.php'); die(); }
$check = DB::$dbs->query("SELECT `id`,`long` FROM subject WHERE id=?",$_GET['subject_id'])->fetchAll();
if(!isset($check[0]['id'])){ header('Location: parent_journal.php');die(); }
$check2 = DB::$dbs->query("SELECT `subject_id`,`group_id` FROM journal_permissions WHERE subject_id=? and group_id={$user["group_id"]}",$check[0]['id'])->fetchAll();
if(!isset($check2[0]["group_id"])){ header('Location: parent_journal.php'); die(); }

$aNumber_monthName = array(1 => "январь",2 => "февраль",3 => "март",4 => "апрель",5 => "май",6 => "июнь",
	7 => "июль",8 => "август",9 => "сентябрь",10 => "октябрь",11 => "ноябрь",12 => "декабрь");
$aNumber_value = array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",
				9=>"9",10=>"10",11=>"зч",12=>"н");
$subjects = $m->GetSubjectsAndMarks($check[0]['id'],$user['id'],$user["group_id"],true);
$dates = $m->GetDates($check[0]['id'],$user["group_id"]);
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
					<?foreach($subjects as $subject):?>
						<tr class="row<?=$subject['id']?>">
							<td><div class="pupilName"><?=$subject['long']?></div></td>
						</tr>
					<? endforeach; ?>
					<tr>
						<td colspan="2"><a onclick="get('templates/parent_journal.php');" href="#parent_journal.php" style="text-decoration: none;"><button id="viewLabs">Вернуться назад</button></a></td>
					</tr>
				</table>
				<div style="height: 41px;border-right: 1px solid #CCC;"></div>
			</div>
			<div class="rightColumn">
				<table class="rightTable heightRow27" cellspacing="0" cellpading="0" border="0">
					<tr id="months">
						<? foreach ($dates as $numberYear => $year):
							foreach ($year as $numberMonth => $month):?>
								<td colspan="<?=count($month)?>">
									<div class="<? if(count($month) == 1){?>nameMonthOneChar<?}elseif(count($month) == 2){?>nameMonthOneChar<?}else{?>nameMonth<?}?>" title="<?=$aNumber_monthName[$numberMonth]?>"><?=$aNumber_monthName[$numberMonth]?></div>
								</td>
							<?php endforeach ?>
						<?php endforeach ?>
						<td rowspan="2"  style="border-left: 3px solid #CCC; border-bottom: 3px solid #CCC;"><div class="WrapPairDisc"><div class="pairDisc">Зач.</div></div></td>
					</tr>
					<tr id="dateOfMonth">
						<?foreach ($dates as $numberYear => $year):
							foreach ($year as $numberMonth => $month):
								foreach ($month as $day):?>
									<td><div pair-id="<?=$day['id']?>" type-id="<?=$day['type']?>" on-date="<?=$numberYear?>-<?=$numberMonth?>-<?=$day['date']?>"><?=$day['date']?></div></td>
								<? endforeach ?>
							<? endforeach ?>
						<? endforeach ?>
					</tr>
					<?foreach ($subjects as $subject):?>
					<tr class="mark row<?=$subject['id']?>">
						<?foreach ($dates as $numberYear => $year):?>
							<?foreach ($year as $numberMonth => $month):?>
								<?foreach ($month as $day):?>
									<?$marks_in_day = [];
									$alert = 0;
									foreach($subject['marks'] as $mark):
										if ($mark['on_date']==$day['on_date']){
											$marks_in_day[] = $mark;
										}
									endforeach;
									if(count($marks_in_day) == 1 and (($marks_in_day[0]['type_id'] == '2' and $marks_in_day[0]['value'] == '12') or $marks_in_day[0]['type_id'] == '1' and $marks_in_day[0]['value'] < 4)){
										$alert = 1;
									}
									$q = abs(strtotime(date("Y-m-d"))) - abs(strtotime($day['on_date']));?>
									<td <?=($q>1209600 && count($marks_in_day) == 0 ? 'style="background:#fde3e3"' : null)?>>
										<div data-count-mark="<?=count($marks_in_day)?>" title="<?=(count($marks_in_day) > 0 ? $marks_in_day[0]['desc'] : null)?>" class="<?=$alert?> <?=$marks_in_day[0]['type_id']?> <?=$alert == '1' ? ' alert_m' : null?>">
											<?foreach ($marks_in_day as $m_id => $m):?>
												<?if(isset($m['value'])){?>
												<span class="mar" data-mark-id="<?=$m_id?>"><?=$aNumber_value[$m['value']]?></span>
											<?}else{?>
												<span class="mar" data-mark-id="0">&nbsp;</span>
											<?}?>
											<? endforeach ?>
										</div>
									</td>
								<? endforeach ?>
							<? endforeach ?>
							<td style="border-left: 3px solid #CCC;"><div><?=($subject['avg'] > 0 ? $subject['avg'] : '0')?>/<?=$subject['avg2']?></div></td>
						<? endforeach ?>
					</tr>
					<? endforeach ?>
					<tr class="rowPairDisc">
						<?foreach ($dates as $numberYear => $year):?>
							<?foreach ($year as $numberMonth => $month):?>
								<?foreach ($month as $day):?>
									<td><div class="WrapPairDisc" title="<?=$day['disc']?>" p-id="<?=$day['id']?>"><div class="pairDisc"><?=$day['pair_disc']?></div></div></td>
								<? endforeach ?>
							<? endforeach ?>
						<? endforeach ?>
					</tr>
				</table>

			</div>
		</div>
		<div class="bottom"></div>