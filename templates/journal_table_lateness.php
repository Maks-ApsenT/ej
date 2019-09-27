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
						<tr class="row<?=$student['id']?>">
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
						<td rowspan="2"  style="border-left: 3px solid #CCC; border-bottom: 3px solid #CCC;"><div class="WrapPairDisc"><div class="pairDisc">Сумма</div></div></td>
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
					<? foreach($students as $kkey => $student): ?>
					<tr class="mark lateness row<?=$student['id']?>">
						<? foreach($dates as $numberYear => $year): ?>
							<? foreach($year as $numberMonth => $month): ?>
								<? foreach($month as $day): ?>
									<?
									$marks_in_day = [];
									foreach ($student['marks'] as $mark):
										if($mark['pair_id']==$day['id']){
											$marks_in_day[$mark['id']] = $mark['value'];
										}
									endforeach;
									if(count($marks_in_day) > 1){
										$markCount = count($marks_in_day)+1;
									}else{
										$markCount = count($marks_in_day);
									}
									?>
									<td <?=(count($marks_in_day) > 0 ? 'style="background:#fde3e3"' : false)?>>
										<div st-id="<?=$student['id']?>" pair-id="<?=$day['id']?>" data-count-mark="<?=count($marks_in_day)?>" class="thisMark">
											<?foreach($marks_in_day as $m => $m_id):?>
												<span data-mark-id="<?=$m?>"><?=$m_id?></span>
											<? endforeach; ?>
												<?=(count($marks_in_day) == 0 ? '<span data-mark-id="0">&nbsp;</span>' : null)?>
										</div>
									</td>
								<? endforeach; ?>
							<? endforeach; ?>
							<td style="border-left: 3px solid #CCC;"><div><?=($student['avg'] > 0 ? $student['avg'] : '0')?></div></td>
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

		<markboard class="markBoard2" chekendmark="">
			<select class="buttonsMark2" id="mark10">
				<option value="0">-------</option>
				<option value="1">1 минута</option><option value="2">2 минуты</option><option value="3">3 минуты</option><option value="4">4 минуты</option><option value="5">5 минут</option><option value="6">6 минут</option><option value="7">7 минут</option><option value="8">8 минут</option><option value="9">9 минут</option><option value="10">10 минут</option>
				<option value="11">11 минут</option><option value="12">12 минут</option><option value="13">13 минут</option><option value="14">14 минут</option><option value="15">15 минут</option><option value="16">16 минут</option><option value="17">17 минут</option><option value="18">18 минут</option><option value="19">19 минут</option><option value="20">20 минут</option>
				<option value="21">21 минута</option><option value="22">22 минуты</option><option value="23">23 минуты</option><option value="24">24 минуты</option><option value="25">25 минут</option><option value="26">26 минут</option><option value="27">27 минут</option><option value="28">28 минут</option><option value="29">29 минут</option><option value="30">30 минут</option>
				<option value="31">31 минута</option><option value="32">32 минуты</option><option value="33">33 минуты</option><option value="34">34 минуты</option><option value="35">35 минут</option><option value="36">36 минут</option><option value="37">37 минут</option><option value="38">38 минут</option><option value="39">39 минут</option>
				<option value="40">40 минут</option><option value="41">41 минут</option><option value="42">42 минут</option><option value="43">43 минут</option><option value="44">44 минут</option><option value="45">45 минут</option><option value="46">46 минут</option><option value="47">47 минут</option><option value="48">48 минут</option><option value="49">49 минут</option><option value="50">50 минут</option>
				<option value="51">51 минут</option><option value="52">52 минут</option><option value="53">53 минут</option><option value="54">54 минут</option><option value="55">55 минут</option><option value="56">56 минут</option><option value="57">57 минут</option><option value="58">58 минут</option><option value="59">59 минут</option><option value="60">60 минут</option>
			</select>
			<input type="button" value="Х" style="width: 26px;" class="buttonsMark3" id="mark1">
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