<?
require_once('../classes/core.php');
$allgroups =  DB::$dbs->query("SELECT `id`,`group_name` FROM `groups` ORDER BY `id`");
?>
<link rel="stylesheet" href="css/login.css">
<script src="chosen/chosen.jquery.js" type="text/javascript"></script>

<div class="container">

    <div class="row" id="pwd-container">
        <div class="col-md-4"></div>
        <div class="col-md-4">

            <section class="login-form" id="data">
                <div id="logg">
                <form name="login" id="form" >
                    <input type="hidden" id="S_Code" value="<?=Security::rand_str()?>">
                    <input type="text" id="student_name" name="student_name" placeholder="Фамилия" required class="form-control input-lg" value=""/>
                    <select class="form-control chzn-select" id="group_id" name="group_id">
                        <?foreach ($allgroups as $group) {?>
                            <option value="<?=$group['id']?>"><?=$group['group_name']?></option>
                        <?}?>
                    </select>
                    <input type="text" name="birth_day" id="birth_day" class="form-control input-lg" id="datepicker" placeholder="Дата рождения (01.02.1993)" required=""/>
                </form>
                <button type="submit" onclick="check_login();" class="btn btn-lg btn-primary btn-block">Войти</button>
                </div>
            </section>
        </div>
    </div>


<script type="text/javascript">
function check_login(action) {
    $.blockUI();
    var student_name = $("#student_name").val();
    var group_id = $("#group_id").val();
    var birth_day = $("#birth_day").val();
    var S_Code = $("#S_Code").val();
    $.post("ajax.php", {action:'login_parent',student_name:student_name,group_id:group_id,birth_day:birth_day,S_Code:S_Code} ,function(data) {
        if(data == 'good')
        {
            window.location='parent_journal.php';
        }else{
            createNoty(data, 'danger');
        }
        $.unblockUI();
    });
    return false;
}

$(document).ready(function(){
    $('#form').keypress(function(e){
      if(e.keyCode==13)
        check_login();
    });
});

$(".chzn-select").chosen({no_results_text: "Не найдено..."});
</script>