<?
require_once('classes/core.php');
$title = "Журнал: {$user['group_name']} &ndash; {$user['name']}";
require_once('classes/head.php');
mode('user');
?>
<link rel="stylesheet" href="css/teacherstyle.css">
<link rel="stylesheet" href="css/marks.css">

<div id="ajax-block"></div>

<script>
$(function(){
	get('templates/parent_journal.php');
});
</script>

<?
require_once('classes/foot.php');
?>