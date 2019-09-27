<?if(!isset($admin['id'])) header("Location:/ej/");?>
<script type="text/javascript" src="js/admin.js"></script>
<div class="allOption">
    <input type="hidden" value="<?=$teacher_id?>" id="teacher_id" name="teacher_id">
    <div class="connection">
        <?if($teacher_id != $admin['id']){?>
            <h1>Действия:</h1>
            <input id="TeacherLoad" type="button" class="btn btn-lg btn-primary btn-block" value="Авторизоваться">
            <input id="TeacherDelete" type="button" class="btn btn-lg btn-primary btn-block" value="Удалить">
        <?}?>
    </div>
    <div class="connection">
        <h1>Текущее положение:</h1>
        <br>
        <?foreach($subjects as $sub):?>
            <div class="con">
                <?=$sub['subject_name']?>&nbsp;&rarr;&nbsp;<?=$sub['group_name']?><input class="deleteCon" del-id="<?=$sub['id']?>" type="button" style="display: inline" value="X">
            </div>
        <?endforeach;?>
    </div>

    <div class="addConnection">
        <h1>Добавить?</h1>
        <form class="addC">
            <table border="0" collspan="0">
                <tr>
                    <td><select id="addSubject" data-placeholder="Предмет..." style="width: 160px;" class="chzn-select" name="addSubject_id">
                    <option value=""></option>
                    <?foreach($allSubjects as $subject):?>
                         <option value="<?=$subject['id']?>"><?=$subject['subject_name']?></option>
                    <?endforeach;?>
            </select></td>
                    <td><div style="font-size: 20px;">&rarr;</div></td>
                    <td><select id="addGroup" multiple data-placeholder="Группа..." class="chzn-select" style="width:300px; margin-left: 40px" name="addGroup_id">
                    <option value=""></option>
                    <?foreach($allGroups as $group):?>
                         <option value="<?=$group['id']?>"><?=$group['name']?></option>
                    <?endforeach;?>
            </select></td>
                </tr>
            </table>
            <input type="button" value="Добавить" id="addSubAndG" class="btn btn-lg btn-primary btn-block">            
        </form>               
    </div>
</div>