<!DOCTYPE html>
<html lang="en">
<head>
  <title>Электронный журнал<?=isset($title) ? " - ".$title : null?></title> 
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Электронный журнал">
  <meta name="author" content="ApsenT">
  <link rel="icon" href="img/favicon.ico" sizes="16x16" type="image/ico">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="chosen/chosen.css" type="text/css">
  <link rel="stylesheet" href="css/style.css">
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/ie-emulation-modes-warning.js"></script>
  <script type="text/javascript" src="js/inputmask/jquery.inputmask.bundle.js"></script>
  <script type="text/javascript" src="js/inputmask/phone.js"></script>
  <script type="text/javascript" src="js/jquery.browser.js"></script>
  <script type="text/javascript" src="js/jquery.blockUI.js"></script>
  <script>
      $(function () {
          $('[data-toggle="tooltip"]').tooltip()
      })
  </script>
  <style>
    .bgr{width:100%;height:100%;position:fixed;top:0;left:0;background:url('img/snow/back_winter.jpg');background-size:cover;background-position:50% 50%;z-index:-1;}
    .bgr span{width:100%;height:100%;position:absolute;top:0;left:0;background:url('img/snow/dot.png');background-size:6px;background-repeat: repeat;opacity:0.32;}
  </style>
</head>
<body>
  <div id="snowstart"></div>
<script>
  $(function () {
      $('[data-toggle="tooltip"]').tooltip();
  })
</script>

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/ej/">Электронный журнал</a>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-right ">
        <?if(isset($user['id']))
        {
            echo '<li><a href="parent_journal.php">'.$user["group_name"].' – '.$user["name"].'</a></li>';
            echo '<li><a href="index.php?logout"><span class="glyphicon glyphicon-log-out"></span> Выйти</a></li>';
        }elseif(isset($admin['id'])){
          if($admin['role'] == 0) echo '<li><a href="admin.php">Управление</a></li>';
            echo '<li><a href="teather_journal.php">'.$admin["login"].'</a></li>';
            echo '<li><a href="index.php?logout"><span class="glyphicon glyphicon-log-out"></span> Выйти</a></li>';
        }else{
            if($_SERVER['PHP_SELF'] == '/ej/t.php'){
                echo '<li><a href="index.php">Вход для родителей</a></li>';
            }else{
                echo '<li><a href="t.php">Вход для преподавателeй</a></li>';
            }
        }?>
      </ul>
    </div>
  </div>
</nav>

<div id="noty-holder"></div>