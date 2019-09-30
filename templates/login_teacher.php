<?
require_once('../classes/core.php');
$teachers = DB::$dbs->query('SELECT `id`, `login` FROM `journal_users` ORDER BY `id`');
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
                    <select class="form-control chzn-select" id="login" name="login">
                        <?foreach ($teachers as $teacher) {?>
                            <option value="<?=$teacher['login']?>"><?=$teacher['login']?></option>
                        <?}?>
                    </select>
                    <input type="password" name="password" id="password" autocomplete="new-password" class="form-control input-lg" placeholder="Пароль" required=""/>
                </form>
                <button type="submit" onclick="check_login();" class="btn btn-lg btn-primary btn-block">Войти</button>
                </div>
            </section>
        </div>
    </div>


<script type="text/javascript">
function check_login(action) {
    $.blockUI();
    var login = $("#login").val();
    var password = $("#password").val();
    var S_Code = $("#S_Code").val();
    $.post("ajax.php", {action:'login_teather',login:login,password:password,S_Code:S_Code} ,function(data) {
        if(data == 'good')
        {
            window.location='teather_journal.php';
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