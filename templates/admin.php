<?if(!isset($admin['id'])) header("Location:/ej/");?>
<link rel="stylesheet" type="text/css" href="css/godstyle.css">
<link href="css/admin.css" type="text/css" rel="stylesheet">
<script src="chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="chosen/chosen.css" type="text/css">
<script type="text/javascript" src="js/admin.js"></script>
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.20.custom1.css">
<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>

   
        <div class="middle">
            <div class="techers">
                <h1 style="margin-bottom: 5px;">Для преподавателя по фамилии:</h1>
                <form class="t">
                    <select id="teacherselect" data-placeholder="Преподаватель..." class="chzn-select" style="width:150px;" tabindex="1" name="teacher_id">
                        <option value=""></option>
                        <?foreach ($teachers as $teacher):?>
                            <option value="<?=$teacher['id']?>"><?=$teacher['login']?></option>
                        <?endforeach;?>
                    </select>                    
                </form>                
            </div>
        </div>     
        

<script type="text/javascript">
    $(".chzn-select").chosen({no_results_text: "Не найдено..."});
</script>