$(document).ready(function(){
	$('.subjects').load(function(){
        $(".chzn-select").chosen({no_results_text: "Не найдено..."});
	});

	function refreshPer(teacher_id){
				$.post('ajax.php',{
			action:'select_teacher',
			teacher_id: teacher_id
		},function(data){
			if (data!='error') {
				$(".allOption").remove();
				$(".middle").append(data);	
			}else{				
				alert("Что-то пошло не так. Оценка не добавлена");
			}
			$(".chzn-select").chosen({no_results_text: "Не найдено..."});
			$.unblockUI();
		});
	}

	$(".active-result").click(function(){
		$.blockUI();		
		refreshPer($("#teacherselect").val());
	});

	$("#addSubAndG").on("click",(function (e) {	
		$.blockUI();
		var teacher_id = $("#teacher_id").val(); 
		$.post('ajax.php',{
			action:'add_subject_for_groups',
			teacher_id: teacher_id,
			subject_id: $("#addSubject").val(),
			groups_id: $("#addGroup").val()
		},function(data){
			if(data!="error"){
				refreshPer(teacher_id);
			}else{
				alert("Что-то пошло не так! Предметы не добавлены");
			}
		});
	}))

	$(".deleteCon").on("click",(function (e) {
		if(confirm("Вы уверены что хотите удалить сие?")){
			$.blockUI();
			$.post('ajax.php',{
			action:'delete_per',
			permission_id: $(this).attr("del-id")
				},function(data){
					if (data!='error') {
						refreshPer($("#teacher_id").val())
					}else{				
						alert("Что-то пошло не так. Оценка не добавлена");
					}
				});
		}else{
			alert("Ну как хочешь");
		}
		///$(this).attr
	}))

	$("#TeacherLoad").on("click",(function (e) {	
		$.blockUI();
		var teacher_id = $("#teacher_id").val(); 
		$.post('ajax.php',{
			action:'teacher_load',
			teacher_id: teacher_id
		},function(data){
			if(data!="error"){
				location.reload();
			}else{
				alert("Что-то пошло не так!");
			}
		});
	}))

	$("#TeacherDelete").on("click",(function (e) {	
		$.blockUI();
		var teacher_id = $("#teacher_id").val(); 
		$.post('ajax.php',{
			action:'teacher_delete',
			teacher_id: teacher_id
		},function(data){
			if(data!="error"){
				location.reload();
			}else{
				alert("Что-то пошло не так!");
			}
		});
	}))
})