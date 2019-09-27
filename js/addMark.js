$(document).ready(function(){
	var LastDiv = '';// span with mark
	var lastMarkVal = '';
	var setMarkHere = '';// тоже самое что и ластдив
	var countMark = 0;
	var typeMark = 0;
	//var massIdMarks = [0,1,2,3,4,5,6,7,8,9,10,"з","н","&nbsp;"];//массив соответствия айди оценки значению

	//выпадлака плшки
	$(".mark td div.thisMark").on("click", "span",(function (e) {
		console.log("qqqq");
		var offsetBlock = $(this).offset();
		typeMark=0
		e.stopPropagation();
		resetChekendMark();
		LastDiv = $(this);
		setMarkHere = $(this);//$("div[st-id=" + $(this).attr("st-id") + "][pair-id=" + $(this).attr("pair-id") + "]");
		countMark = parseInt(setMarkHere.parent().attr('data-count-mark'));
		lastMarkVal = LastDiv.text();
	  	$("input[value=" + lastMarkVal + "].buttonsMark").val("X");//изменяет выставленую оценку на плашке на Х
	  	$(".markBoard").css({"left": (offsetBlock.left - 144) + "px",
				 			"top": (offsetBlock.top - 176) + "px"});
	  	$(".markBoard2").css({"left": (offsetBlock.left - 185) + "px",
				 			"top": (offsetBlock.top - 48) + "px"});
	  	if ($(this).parent().find('span').length == 4) {
	  		$('#moreMark').attr('disabled','disabled'); $('#moreMark2').attr('disabled','disabled');
	  	} else {
	  		$('#moreMark').removeAttr('disabled'); $('#moreMark2').removeAttr('disabled');
	  	}
	}))
	//зачет-не-зачет
	$(".labs td div.thisMark").on("click", "span[data-edit='1']",(function (e) {
		var offsetBlock = $(this).offset();
		typeMark=3;//зачет
		e.stopPropagation();
		resetChekendMark();
		LastDiv = $(this);
		setMarkHere = $(this);//$("div[st-id=" + $(this).attr("st-id") + "][pair-id=" + $(this).attr("pair-id") + "]");
		countMark = parseInt(setMarkHere.parent().attr('data-count-mark'));
		lastMarkVal = LastDiv.text();
		// $("#mark11").addClass("hideButton").attr("disabled","disabled");//скрываем Н
	  	$("input[value=" + lastMarkVal + "].buttonsMark").val("X");//изменяет выставленую оценку на плашке на Х
	  	$(".markBoard").css({"left": (offsetBlock.left - 144) + "px",
				 			"top": (offsetBlock.top - 176) + "px"});
	  	if ($(this).parent().find('span').length == 4) {
	  		$('#moreMark').attr('disabled','disabled');
	  	} else {
	  		$('#moreMark').removeAttr('disabled');
	  	}
	}))

	$("#moreMark").on('click', function (e) {
		var len = parseInt(LastDiv.parent().attr('data-count-mark'));
		console.log(len);
		if (len < 4) {
			var block = $('<span data-mark-id="0">&nbsp;</span>');

			LastDiv.parent().attr('data-count-mark',len+1);
			LastDiv.parent().append(block);
			block.click();
		}
	});

	$(".mark td div.semesterMark").on("click",(function (e) {
		var offsetBlock = $(this).offset();
		typeMark=1;
		e.stopPropagation();
		resetChekendMark();
		LastDiv = $(this);
		$("#mark11").addClass("hideButton").attr("disabled","disabled");//скрываем Н
		setMarkHere = $("div[s-n=" + $(this).attr("s-n") + "][m-id=" + $(this).attr("m-id") + "]");

		lastMarkVal = LastDiv.text();
	  	$(".markBoard").css({"left": (offsetBlock.left - 144) + "px",
				 			"top": (offsetBlock.top - 176) + "px"});
	}))


	//убиралка плашки
	$('body').click(function(){
		$('.markBoard').css({"top":"-300px"});
	})

	function markBoard2Close()
	{
		$('.markBoard2').css({"top":"-300px"});
	}

	//сброс оценок в начальное состояние
	function resetChekendMark(){
		//$("#setMH").removeAttr("id");//удаляем айди куда ставить оценку
		$('input[value="X"]').val(lastMarkVal);//ставим такое значение какое должно быть
	}

	$(".buttonsMark3").on("click",(function (e){
		var newMark = $(this).val();
		if(typeMark==0){
			var thisBlock = $('.activeS');
			var StudentId = LastDiv.parent().attr('st-id');
			var PairId = LastDiv.parent().attr('pair-id');
			var markId = LastDiv.attr('data-mark-id');
			var GroupId = thisBlock.attr('groupid');
			var SubjectId = thisBlock.attr('subjectid');
		}else{
			var markId = LastDiv.parent().attr('m-id');
			var semesterNumber = LastDiv.parent().attr('s-n');
		}

		$.blockUI();

		if(typeMark == 0){
			$.post('ajax.php',{
					action:'set_lateness',
					student_id:StudentId,
					pair_id:PairId,
					mark_id:markId,
					value:newMark
			},function(data){
				var newMarkId = parseInt(data);
				if (data!='error') {
					setMarkHere.html('&nbsp;');
				}else{
					alert("Что-то пошло не так. Оценка не добавлена");
				}
				$.unblockUI();
			})
		}
	}));


	$(".buttonsMark2").on("click" , "option",(function (e){
	    var newMark = $("#mark10").find("option:selected").val();
		if(typeMark==0){
			var StudentId = LastDiv.parent().attr('st-id');
			var PairId = LastDiv.parent().attr('pair-id');
			var markId = LastDiv.attr('data-mark-id');
		}else{
			var markId = LastDiv.parent().attr('m-id');
			var semesterNumber = LastDiv.parent().attr('s-n');
		}

		$.blockUI();

		setMarkHere.removeClass("thisNewMark thisOblom");

		if(typeMark == 0){
			$.post('ajax.php',{
					action:'set_lateness',
					student_id:StudentId,
					pair_id:PairId,
					mark_id:markId,
					value:newMark
			},function(data){
				var newMarkId = parseInt(data);
				parentBlock = setMarkHere.parent();
				if (data!='error') {
					if (data != '00') {
						$("#mark10").val($("#mark10 option:first").val());
						console.log(newMark);
						setMarkHere.html(newMark);
						setMarkHere.attr('data-mark-id',newMarkId);
						parentBlock.addClass("thisNewMark");
						setMarkHere = "";
						markBoard2Close();
					}
				}else{
					alert("Что-то пошло не так. Оценка не добавлена");
				}
				$.unblockUI();
			})
		}
	}));

	//отправка оценки на сервер
	$(".buttonsMark").on("click",(function (e){
		var newMark = $(this).val();
		if(typeMark==0 || typeMark==3){
			var StudentId = LastDiv.parent().attr('st-id');
			var PairId = LastDiv.parent().attr('pair-id');
			var markId = LastDiv.attr('data-mark-id');
		}else{
			var markId = LastDiv.parent().attr('m-id');
			var semesterNumber = LastDiv.parent().attr('s-n');
		}

		$.blockUI();

		setMarkHere.html("");
		setMarkHere.removeClass("thisNewMark thisOblom");

		if(typeMark == 0){//простая оценка
			$.post('ajax.php',{
					action:'set_mark',
					student_id:StudentId,
					pair_id:PairId,
					mark_id:markId,
					value:newMark
			},function(data){
				var newMarkId = parseInt(data);
				parentBlock = setMarkHere.parent();
				if (data!='error') {
					if(newMark=="X"){
						setMarkHere.parent().attr('data-count-mark',countMark-1);
						if (countMark > 1) {
							setMarkHere.remove();
						} else {
							setMarkHere.html('&nbsp;');
						}
					}else{
						var len = setMarkHere.parent().find('span').length;
						setMarkHere.parent().attr('data-count-mark',len);
						setMarkHere.attr('data-mark-id',newMarkId);
						setMarkHere.html(newMark);
					}
					parentBlock.addClass("thisNewMark");
				}else{
					parentBlock.addClass("thisOblom");
					alert("Что-то пошло не так. Оценка не добавлена");
				}
				$.unblockUI();
				setMarkHere = "";
			})
		} else if (typeMark == 3) {
			$.post('ajax.php',{
					action:'set_mark',
					student_id:StudentId,
					pair_id:PairId,
					mark_id:markId,
					value:newMark
			},function(data){
				var newMarkId = parseInt(data);
				parentBlock = setMarkHere.parent();
				if (data!='error') {
					if(newMark=="X"){
						// setMarkHere.parent().attr('data-count-mark',countMark-1);
						setMarkHere.html('&nbsp;');
					}else{
						var len = setMarkHere.parent().find('span').length;
						setMarkHere.parent().attr('data-count-mark',len);
						setMarkHere.attr('data-mark-id',newMarkId);
						setMarkHere.html(newMark);
					}
					parentBlock.addClass("thisNewMark");
				}else{
					parentBlock.addClass("thisOblom");
					alert("Что-то пошло не так. Оценка не добавлена");
				}
				$.unblockUI();
				setMarkHere = "";
			});
		}else{//семестровая оценка (нах надо)
			$.post('ajax.php',{
				action:'edit_semester_mark',
				mark_id:markId,
				sementer_id:semesterNumber,
				value:newMark
			},function(data){
				if (data=='ok') {
					if(newMark=="X"){
						setMarkHere.html("");
					}else{
						setMarkHere.html(newMark);
					}
					setMarkHere.addClass("thisNewMark");
					$("#mark11").removeClass("hideButton");
				}else{
					setMarkHere.addClass("thisOblom");
					alert("Что-то пошло не так. Оценка не добавлена");
				}
				$.unblockUI();
				setMarkHere = "";})
			}
	}));

	function test(StudentId,PairId,newMark){
		$.post('ajax.php',{
					action:'set_mark',
					student_id:StudentId,
					pair_id:PairId,
					value:newMark
			},function(data){
				if (data=='ok') {
					if(newMark=="X"){
						setMarkHere.html("");
					}else{
						setMarkHere.html(newMark);
					}
					setMarkHere.addClass("thisNewMark");
				}else{
					setMarkHere.addClass("thisOblom");
					alert("Что-то пошло не так. Оценка не добавлена");
				}
				$.unblockUI();
				setMarkHere = "";
			})
	}
})