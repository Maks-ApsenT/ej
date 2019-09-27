$(document).ready(function(){

	console.log("qqq1212q");

	function addLiveHover(){
	 	$("tr").bind("mouseenter", function (e) {
		    	var rowNumber = "."+/row\d+/.exec($(this).attr("class"));
		   		$(rowNumber).addClass("hoverOn");})
		$("tr").bind("mouseleave", function (e) {
		 		$(".hoverOn").removeClass("hoverOn");
		});
	}

	$(".subject").click(function(){
			var thisBlock = $(this);
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

	$("#viewLabs").on('click',function(e){
		console.log("qqqq");
			var thisBlock = $('.activeS');
			var GroupId = thisBlock.attr('groupid');
			var SubjectId = thisBlock.attr('subjectid');

		$.blockUI();

		$.post('ajax.php',{
			action:'show_labs',
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
})