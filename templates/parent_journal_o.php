<?
require_once('../classes/core.php');
if(!isset($user['id'])) header("Location:/ej/");
require_once('../classes/parent_class.php');
$m = new parent_manager_o();

$aNumber_monthName = array(1 => "январь",2 => "февраль",3 => "март",4 => "апрель",5 => "май",6 => "июнь",
	7 => "июль",8 => "август",9 => "сентябрь",10 => "октябрь",11 => "ноябрь",12 => "декабрь");
$aNumber_value = array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",
				9=>"9",10=>"10",11=>"зч",12=>"н");
$subjects = $m->GetSubjectsAndMarks($user["group_id"],$user['id'],true);
$dates = $m->GetDates($user["group_id"]);
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
						<tr class="row<?=$subject['subject_id']?>">
							<td><div class="pupilName"><?=$subject['subject_name']?></div></td>
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
						<td rowspan="2"  style="border-left: 3px solid #CCC; border-bottom: 3px solid #CCC;"><div class="WrapPairDisc"><div class="pairDisc">Сумма</div></div></td>
					</tr>
					<tr id="dateOfMonth">
						<?foreach ($dates as $numberYear => $year):
							foreach ($year as $numberMonth => $month):
								foreach ($month as $day):?>
									<td><div pair-id="<?=$day['id']?>" on-date="<?=$numberYear?>-<?=$numberMonth?>-<?=$day['date']?>"><?=$day['date']?></div></td>
								<? endforeach ?>
							<? endforeach ?>
						<? endforeach ?>
					</tr>

					<?foreach ($subjects as $subject):?>
					<tr class="mark row<?=$subject['subject_id']?>">
						<?foreach ($dates as $numberYear => $year):?>
							<?foreach ($year as $numberMonth => $month):?>
								<?foreach ($month as $day):?>
									<?$marks_in_day = [];
									$alert = 0;
									$coun = 0;
									foreach($subject['marks'] as $mark):
										if ($mark['on_date']==$day['on_date']){
											$marks_in_day[] = $mark;
											$coun++;
										}
									endforeach;
									$q = abs(strtotime(date("Y-m-d"))) - abs(strtotime($day['on_date']));?>
									<td>
										<div data-count-mark="<?=count($marks_in_day)?>" class="0">
											<?foreach ($marks_in_day as $m_id => $m):?>
												<?if(isset($m['value'])){?>
												<span class="mar" title="<?=$m['value']?> минут опоздания на пару" style="cursor: help;" data-mark-id="<?=$m_id?>"><?=$m['value']?></span>
											<?}else{?>
												<span class="mar" data-mark-id="0">&nbsp;</span>
											<?}?>
											<? endforeach ?>
										</div>
									</td>
								<? endforeach ?>
							<? endforeach ?>
							<td style="border-left: 3px solid #CCC;"><div><?=($subject['avg'] > 0 ? $subject['avg'] : '0')?></div></td>
						<? endforeach ?>
					</tr>
					<? endforeach ?>

					<tr class="rowPairDisc">
						<?foreach ($dates as $numberYear => $year):?>
							<?foreach ($year as $numberMonth => $month):?>
								<?foreach ($month as $day):?>
									<td><div class="WrapPairDisc" title="<?=$day['pair_disc']?>" p-id="<?=$day['id']?>"><div class="pairDisc">////////</div></div></td>
								<? endforeach ?>
							<? endforeach ?>
						<? endforeach ?>
					</tr>
				</table>

			</div>
		</div>
		<div class="bottom"></div>