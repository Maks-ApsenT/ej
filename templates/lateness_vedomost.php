<?if(!isset($admin['id'])) header("Location:/ej/");?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="css/ui-lightness/jquery-ui-1.8.18.custom.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/vedomost.css">
		<link rel="stylesheet" type="text/css" href="css/godstyle.css">
		<link rel="stylesheet" type="text/css" href="css/marks.css">
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
	</head>
	<body>
		<div class="up">Ведомость учета опозданий учащихся за <span><?=$month_name?> </span> <?=$stady_year?> учебный год, группа <span><?=$group_name?></span></div>
		<div class="middle">
			<div class="leftColumn">
				<table class="leftTable heightRow27" cellspacing="0" cellpading="0" border="0">
					<tr>
						<td><div class="text-center">№</div></td>
						<td><div class="text-center">ФИО Учащихся</div></td>
					</tr>
					<? $i=0; ?>
					<? foreach ($subjects as $subject): ?>
						<tr>
							<td><div class="text-center"><?=++$i?></div></td>
							<td><div class="pupilName"><?=$subject['short_name']?></div></td>
						</tr>
					<? endforeach; ?>
				</table>
			</div>
			<div class="rightColumn">
				<table class="rightTable heightRow27" cellspacing="0" cellpading="0" border="0">
					<tr id="months">
						<? foreach ($dates as $day => $date): ?>
							<td>
								<div class="text-center" title="<?=$date?>"><?=$day+1?></div>
							</td>
						<? endforeach; ?>
						<td>
							<div class="text-center">кол-во</div>
						</td>
					</tr>
					<? $order = 0; foreach ($subjects as $subject): $count = 0;?>
					<tr class="mark">
						<? foreach ($dates as $date): ?>
							<td>
								<div data-date="<?=$date?>">
								<? foreach ($subject['marks'] as $mark): ?>
									<? $count += ($mark['on_date'] == $date ? (isset($mark['skip_hour']) ? $mark['skip_hour'] : 0) : 0); ?>
									<?=($mark['on_date'] == $date ? (isset($mark['skip_hour']) ? $mark['skip_hour'] : null) : null)?>
								<? endforeach; ?>
								</div>
							</td>
						<? endforeach; ?>
						<td>
							<div><?=$count?><? $order += $count?></div>
						</td>
					</tr>
					<? endforeach; ?>
					<tr>
						<td colspan="<?=count($dates)?>" style="text-align: right">ВСЕГО</td>
						<td><div class="text-center"><?=$order?></div></td>
					</tr>
				</table>
				<div style="padding-top: 15px">Подпись куратора _______________________ _______________________</div>
			</div>
		</div>
	</body>
</html>