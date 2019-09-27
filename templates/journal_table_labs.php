<?if(!isset($admin['id'])) header("Location:/ej/");?>
<link rel="stylesheet" type="text/css" href="css/marks.css">
<script type="text/javascript" src="js/addMark.js"></script>
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.20.custom1.css">
<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>

			<div class="leftColumn">
				<table class="leftTable heightRow27" cellspacing="0" cellpading="0" border="0">
					<tr>
						<td class="b2b nlbRb2" rowspan=2><div class="charNumber">&#8470;</div></td>
						<td style="position:relative">
							<input id="addDate" title="Добавить дату занятия" value="" type="button">
							<div class="tableMonth">Месяц</div>
							<span id="datepicker" class="wrapAddDate" style="display:none; z-index: 3; position: absolute;">
								<form style="margin-bottom: 5px; height: 40px" autocomplete="off">
									<div>
										<label>Описание:</label>
										<input type="text" autocomplete="off" name="pairDisc" id="pairDisc" maxlength="45" style="width: 90px; border: 1px solid #CCC">
										<select id="pairType" style="width: 80px;">
											<option value="0">Лекция</option>
											<option value="1">Практика</option>
											<option value="2">Лабораторная</option>
										</select>
										<input type="button" id="addDateButton" value="Добавить" style="display: block; margin: 4px auto; width: 70px;">
									</div>
								</form>
							</span>
						</td>
					</tr>
					<tr>
						<td class="b2b">
							<div class="o">
							    <div class="b"></div>
							    <div class="b2"></div>
							    <div class="u"></div>
							    <div class="dateChant">Дата</div>
							    <div class="nameio">Фамилия,&emsp;&emsp; инициалы учащегося</div>
							</div>
						</td>
					</tr>
					<? foreach($students as $studentEach => $student): ?>
						<?$nejach = $student['avg2'] - $student['avg']?>
						<tr class="row<?=$student['id']?>" <?=($nejach == 0 ? 'style="background:#5ffd0059"' : null)?>>
							<td class="nlbRb2"><div class="pupilNumber"><?=++$studentEach ?></div></td>
							<td>
								<div class="pupilName" title="<?=$student['long_name']?>">
									<?=$student['short_name']?>
								</div>
							</td>
						</tr>
					<? endforeach; ?>
					<tr>
						<td colspan="2"><button id="viewLabs">Вернуться</button></td>
					</tr>
				</table>
				<div style="height: 41px;border-right: 1px solid #CCC;"></div>
			</div>
			<div class="rightColumn">
				<table class="rightTable heightRow27" cellspacing="0" cellpading="0" border="0">
					<tr id="months">
						<? foreach($dates as $numberYear => $year): ?>
							<? foreach($year as $numberMonth => $month): ?>
								<td colspan="<?=count($month)?>">
									<div class="<?if(count($month)==1){?>nameMonthOneChar<?}elseif(count($month)==2){?>nameMonthTwoChar<?}else{?>nameMonth<?}?>" title="<?=$aNumber_monthName[$numberMonth]?>"><?=$aNumber_monthName[$numberMonth]?></div>
								</td>
							<? endforeach; ?>
						<? endforeach; ?>
						<td rowspan="2"  style="border-left: 6px solid #CCC; border-bottom: 3px solid #CCC;"><div class="WrapPairDisc"><div class="pairDisc">Не Зач.</div></div></td>
						<td rowspan="2"  style="border-left: 3px solid #CCC; border-bottom: 3px solid #CCC;"><div class="WrapPairDisc"><div class="pairDisc">Зач.</div></div></td>
					</tr>
					<tr id="dateOfMonth">
						<? foreach($dates as $numberYear => $year): ?>
							<? foreach($year as $numberMonth => $month): ?>
								<? foreach($month as $day): ?>
									<td><div pair-id="<?=$day['id']?>" type-id="<?=$day['type']?>" on-date="<?=$numberYear?>-<?=$numberMonth?>-<?=$day['date']?>"><?=$day['date']?></div></td>
								<? endforeach; ?>
							<? endforeach; ?>
						<? endforeach; ?>
					</tr>
					<? foreach($students as $student): ?>
					<tr class="labs mark row<?=$student['id']?>">
						<? foreach($dates as $numberYear => $year): ?>
							<? foreach($year as $numberMonth => $month): ?>
								<? foreach($month as $day): ?>
									<?
									$marks_in_day = [];
									foreach ($student['marks'] as $mark):
										if($mark['pair_id']==$day['id']){
											if($mark['value'] == 11)
											{
												$marks_in_day[$mark['id']] = $mark['value'];
											}
										}
									endforeach;
									if(count($marks_in_day) == 1 and current($marks_in_day) == 12){
										$markCount = count($marks_in_day)+1;
									}else{
										$markCount = count($marks_in_day);
									}
									$q = abs(strtotime(date("Y-m-d"))) - abs(strtotime($day['on_date']))
									?>
									<td <?=($q>1209600 && count($marks_in_day) == 0 ? 'style="background:#fde3e3"' : false)?>>
										<div st-id="<?=$student['id']?>" pair-id="<?=$day['id']?>" data-count-mark="<?=count($marks_in_day)?>" class="thisMark">
											<?foreach($marks_in_day as $m => $m_id):?>
												<?if ($m == 12){?>
													<span data-mark-id="<?=$m?>"><?=$aNumber_value[$m_id]?></span>
													<?if(counnt($marks_in_day) == 1 and current($marks_in_day) == 12){?>
														<span data-edit='1' data-mark-id="0">&nbsp;</span>
													<?}?>
												<?}else{?>
													<span data-edit='1' data-mark-id="<?=$m?>"><?=$aNumber_value[$m_id]?></span>
												<?}?>
											<? endforeach; ?>
												<?=(count($marks_in_day) == 0 ? '<span data-mark-id="0">&nbsp;</span>' : null)?>
										</div>
									</td>
								<? endforeach; ?>
							<? endforeach; ?>
							<?$nejach = $student['avg2'] - $student['avg']?>
							<td style="border-left: 6px solid #CCC;"><div><?=$nejach?></div></td>
							<td style="border-left: 3px solid #CCC; width: 40px;"><div><?=($student['avg'] > 0 ? $student['avg'] : 0 )?>/<?=$student['avg2']?></div></td>
						<? endforeach; ?>
					</tr>
					<? endforeach; ?>
					<tr class="rowPairDisc">
					<? foreach($dates as $numberYear => $year): ?>
						<? foreach($year as $numberMonth => $month): ?>
							<? foreach($month as $day): ?>
								<td><div class="WrapPairDisc" title="<?=$day['disc']?>" p-id="<?=$day['id']?>"><div class="pairDisc"><?=$day['disc']?></div></div></td>
							<? endforeach; ?>
						<? endforeach; ?>
					<? endforeach; ?>
				</tr>
				</table>

			</div>

		<markboard class="markBoard" chekendmark="">
			<input type="button" value="з" class="buttonsMark" id="mark11">
			<corner class="crb"></corner>
			<corner class="clt"></corner>
		</markboard>
		<script type="text/javascript">
			$(document).ready(function(){

			var addOrEditDate = 0;// 0 == добавить 1 == редактирование
			var selectedDate = '';
			var resetDateId = '';



			function addLiveHover(){
			 	$("tr").bind("mouseenter", function (e) {
				    	var rowNumber = "."+/row\d+/.exec($(this).attr("class"));
				   		$(rowNumber).addClass("hoverOn");})
				$("tr").bind("mouseleave", function (e) {
				 		$(".hoverOn").removeClass("hoverOn");
				});
			}

				$("#datepicker").datepicker({
					changeMonth: true,
					changeYear: true,
					onSelect: function(dateText, inst) {
						selectedDate = dateText;
					},
					dateFormat: "yy-mm-dd",
					showOtherMonths: true,
					selectOtherMonths: true,
					dayNames: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
					dayNamesMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
					dayNamesShort: ["Вск", "Пон", "Втр", "Срд", "Чет", "Пят", "Суб"],
					monthNames: ["Январь", "Февраль", "Март", "Апрель",
								"Май", "Июнь", "Июль", "Август",
								"Сентяюрь", "Октябрь", "Ноябрь", "Декабрь"],
					monthNamesShort: ["Янв", "Фев", "Мар", "Апр",
								"Май", "Июн", "Июл", "Авг",
								"Сен", "Окт", "Нояь", "Дек"],
					firstDay: 1
				});

				$("#addDateButton").on("click",(function (e) {
					if(selectedDate=="") return false;

					$("#datepicker").hide();


					var thisSubAndG = $(".activeS");
					var currentSubject = thisSubAndG.attr("subjectid");
					var currentGroup = thisSubAndG.attr("groupid");

					if(addOrEditDate==0){//добавление даты
							$.blockUI();
							$.post('ajax.php',{
								action:'add_date',
								new_date:selectedDate,
								subject_id:currentSubject,
								group_id:currentGroup,
								pair_disc: $("#pairDisc").val(),
								pair_type: $("#pairType").val()
							},function(data){
								if (data!="error") {
									$(".middle").empty();
									$(".middle").append(data);
									addLiveHover();
								}else{
									//alert("Что-то пошло не так...");
								}
								selectedDate="";
								resetDateId ="";
							$.unblockUI();
							})
					}else{//редактирование даты
						$.blockUI();
						$.post('ajax.php',{
								action:'edit_date',
								new_date:selectedDate,
								subject_id:currentSubject,
								group_id:currentGroup,
								pair_disc: $("#pairDisc").val(),
								pair_type: $("#pairType").val(),
								reset_id: resetDateId
							},function(data){
								if (data!="error") {
									$(".middle").empty();
									$(".middle").append(data);
									addLiveHover();
								}else{
									//alert("Что-то пошло не так...");
								}
							selectedDate="";
							resetDateId ="";
							$.unblockUI();
							})
					}
				}))

				$("#addDate").on("click",(function (e) {
					e.stopPropagation();
					$("#datepicker").show();
					addOrEditDate = 0;
				}))

				$(".wrapAddDate").on("click",(function (e) {
					e.stopPropagation()
				}))

				$('body').on("click",(function (e) {
					$("#datepicker").hide();
					$(".boardSemesterMark").hide();
				}))

				$("#dateOfMonth td div").on("click",(function (e) {
					e.stopPropagation();
					addOrEditDate = 1;

					resetDateId = $(this).attr("pair-id");
					selectedNowType = $(this).attr("type-id");

					$("#pairDisc").val($(".WrapPairDisc[p-id="+resetDateId+"] div").html());
					$("#pairType option[value="+selectedNowType+"]").attr("selected","selected");

					$("#datepicker").datepicker("setDate" ,$(this).attr("on-date"));

					selectedDate = $(this).attr("on-date");

					$("#datepicker").show();
				}))

				$("#viewLabs").on('click',function(e){
					var thisBlock = $('.activeS');
					var GroupId = thisBlock.attr('groupid');
					var SubjectId = thisBlock.attr('subjectid');

				$.blockUI();

				$.post('ajax.php',{
					action:'show_table',
					subject_id:SubjectId,
					group_id:GroupId
				},function(data){
					if (data!="error") {
						$(".activeS").removeClass("activeS");
						thisBlock.addClass("activeS");
						$(".middle").empty();
						$(".middle").append(data);
						addLiveHover();
					}else{
						alert("Что-то пошло не так...");
					}
				$.unblockUI();
				})
			})

				/**********************************************/
				/* ВЫСТАВЛЕНИЕ СЕМЕСТРОВЫХ ОТМЕТОК ГОУ-ГОУ!!!!*/
				$("#addSemesterMark").on("click",(function (e) {
					e.stopPropagation();
					$(".boardSemesterMark").show();
				}))

				$("#setSemesterMark").on("click",(function (e) {
						e.stopPropagation();
						$.blockUI();

						var thisSubAndG = $(".activeS");
						var currentSubject = thisSubAndG.attr("subjectid");
						var currentGroup = thisSubAndG.attr("groupid");
						var semesterNumber = $("#semesterNumber").val();

						$.post('ajax.php',{
							action:'set_semester_mark',
							subject_id:currentSubject,
							group_id:currentGroup,
							semester_number: semesterNumber
						},function(data){
							if (data!="error") {
								$(".middle").empty();
								$(".middle").append(data);
								addLiveHover();
							}else{
								//alert("Что-то пошло не так...");
							}
							$.unblockUI();
						})
				}))
			})
	</script>