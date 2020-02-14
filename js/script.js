var toggleSnow = function(){
    if(localStorage.getItem("snow") == "true"){
        $("#snowstart").removeClass("snow");
        localStorage.setItem("snow", false); 
    }
    else if(localStorage.getItem("snow") == "false"){
        $("#snowstart").addClass("snow");
        localStorage.setItem("snow", true);
    }
}
  
$("#toggleSnowButton").ready(function() {
    $("#toggleSnowButton").click(toggleSnow);
});

$("#snowstart").ready(function() {
    if(localStorage.getItem("snow") === null){
        $("#snowstart").addClass("snow");
        localStorage.setItem("snow", true);
    }
    else if(localStorage.getItem("snow") == "true"){
        $("#snowstart").addClass("snow");
    }
});

function str_rand() {
    var result       = '';
    var words        = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
    var max_position = words.length - 1;
    for( i = 0; i < 10; ++i ) {
        position = Math.floor ( Math.random() * max_position );
        result = result + words.substring(position, position + 1);
    }
    return result;
}

$(document).on("ready", function () {
    $("body").on("click",".generate-rand", function () {
        $($(this).data('input')).val(str_rand()).trigger("change");
    });
});

$(document).ready(function(){
    addLiveHover();
    function scrolling(){
        var fullWidth = 0;
        var avalibleWidth = $(".scrollingWrap").width();

        $(".scrollingId li").each(function(){
            fullWidth += $(this).width();
            fullWidth += 36;
        });

        if(avalibleWidth<fullWidth){
            var retVal = avalibleWidth-fullWidth;
            return retVal;
        }
        return 1;
    }

    var offsetF = scrolling();

    $(window).resize(function(){
        offsetF = scrolling();
    });
    $('#leftScroll').click(function(){
        if(parseInt($('.scrollingId').css("left"))>offsetF){
            $('.scrollingId').animate({left: "-=65px"},200);

        }else{//alert("ClickFalse");
            $('.scrollingId').animate({left: "-=10px"},200);
            $('.scrollingId').animate({left: "+=10px"},200);}
    })

    $('#rightScroll').click(function(){
        if(parseInt($('.scrollingId').css("left"))<0){
            $('.scrollingId').animate({left: "+=65px"},200);
        }else{//alert("ClickFalse");
            $('.scrollingId').animate({left: "+=30px"},200);
            $('.scrollingId').animate({left: "-=30px"},200);}
    })

    $('#rightScroll').dblclick(function(){
        $('.scrollingId').animate({left: "0"}, 400);
    })
    $('#leftScroll').dblclick(function(){
        $('.scrollingId').animate({left: offsetF+"px"}, 400);
    })

    function addLiveHover(){
        $("tr").mouseenter(function(e){
            var rowNumber = "."+/row\d+/.exec($(this).attr("class"));
            $(rowNumber).addClass("hoverOn");})
        $("tr").mouseleave(function (e) {
            $(".hoverOn").removeClass("hoverOn");
        });
    }


    $(".opaciyFrom4to8").hover(
        function () {
            $(this).css("backgroundColor","rgba(204,204,204,0.1)").animate({opacity: "0.8"},250);
            },
        function (){
            $(this).css("backgroundColor","#FFF").animate({opacity: "0.4"},250);
    });


    $(".groups li").click(function(){
        $(".activeG").removeClass("activeG");
        $(this).addClass("activeG");
        var groupGroupId = $(this).attr("groupid");
        $(".subjects").css("display","none");
        $(".subjects[groupid=" + groupGroupId +"]").css("display","block");
        $(".currentGroup").html($(this).html()+":");
        $('#topScroll').fadeOut(250);
        $('.groupsWrapTwo').css('position','absolute').hide("slide", {direction: "up"}, 300);
        $('.subjectsWrapTwo').css('position','relative').show("slide", {direction: "down"}, 300,
        function(){ $('#bottomScroll').fadeIn(250);
                    $('.scrollingWrap, .scrollingId').removeClass("scrollingWrap, scrollingId");
                    $('.subjectsWrapTwo').addClass("scrollingWrap");
                    $('.subjects:visible').addClass("scrollingId");
                    offsetF = scrolling();});
    })

    $('#topScroll').click(function(){
        $(this).fadeOut(250);
        $('.groupsWrapTwo').css('position','absolute').hide("slide", {direction: "up"}, 300);
        $('.subjectsWrapTwo').css('position','relative').show("slide", {direction: "down"}, 300,
        function(){ $('#bottomScroll').fadeIn(250);
                    $('.scrollingWrap, .scrollingId').removeClass("scrollingWrap, scrollingId");
                    $('.subjectsWrapTwo').addClass("scrollingWrap");
                    $('.subjects:visible').addClass("scrollingId");
                    offsetF = scrolling();});
    })

    $('#bottomScroll, .currentGroup').click(function(){
        $('#bottomScroll').fadeOut(250);
        $('.subjectsWrapTwo').css('position','relative').hide("slide", {direction: "down"}, 300);
        $('.groupsWrapTwo').css('position','absolute').show("slide", {direction: "up"}, 300,
        function(){ $('.groupsWrapTwo').css('position','relative');
                    $('#topScroll').fadeIn(250);
                    $('.scrollingWrap, .scrollingId').removeClass("scrollingWrap, scrollingId");
                    $('.groupsWrapTwo').addClass("scrollingWrap");
                    $('.groups').addClass("scrollingId");
                    offsetF = scrolling();});
    })

    $('#vedomost').click(function(){
        var btn = $('.activeG');

        if (btn.length > 0 && btn.attr('groupid')) {
            var win = window.open('ajax.php?action=vedomost&group_id=' + btn.attr('groupid') + '&year=' + $('#vedomost_year').val() + '&month=' + $('#vedomost_month').val(),  '_blank');
            win.focus();
        } else {
            alert("Выберете группу");
        }
    });

    $('#lateness_vedomost').click(function(){
        var btn = $('.activeG');

        if (btn.length > 0 && btn.attr('groupid')) {
            var win = window.open('ajax.php?action=lateness_vedomost&group_id=' + btn.attr('groupid') + '&year=' + $('#vedomost_year').val() + '&month=' + $('#vedomost_month').val(),  '_blank');
            win.focus();
        } else {
            alert("Выберете группу");
        }
    });

});

function get(str)
{
    var str = str;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', str, false);
    xhr.send();
    $.blockUI();
    if (xhr.status != 200) {
        createNoty('Серверная ошибка: '+xhr.status, 'danger');
        $.unblockUI();
        return;
    }
    $("#ajax-block").html(xhr.responseText);
    $.unblockUI();
}
$("tr").mouseenter(function(e){
    var rowNumber = "."+/row\d+/.exec($(this).attr("class"));
    $(rowNumber).addClass("hoverOn");})
$("tr").mouseleave(function (e) {
    $(".hoverOn").removeClass("hoverOn");
});