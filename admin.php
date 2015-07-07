<?php
require_once('inc/functions.php');
$lang = 'en';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Admin Panel</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="admin.css">
  </head>
<body>
  
  <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="demo-header mdl-layout__header mdl-color--white mdl-color--grey-100 mdl-color-text--grey-600">
      <div class="mdl-layout__header-row">
        <span class="mdl-layout-title">Admin Panel</span>
        <div class="mdl-layout-spacer"></div>
        <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
          <i class="material-icons">more_vert</i>
        </button>
        <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
          <li class="mdl-menu__item">View Site</li>
        </ul>
      </div>
    </header>
    <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
      <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
        <a class="mdl-navigation__link" href="admin"><i class="mdl-color-text--blue-grey-400 material-icons">dashboard</i>Dashboard</a>
        <a class="mdl-navigation__link" href="admin?p=settings"><i class="mdl-color-text--blue-grey-400 material-icons">settings</i>Global Settings</a>
        <a class="mdl-navigation__link" href="admin?p=chapters"><i class="mdl-color-text--blue-grey-400 material-icons">photo_library</i>Chapters</a>
      </nav>
    </div>
    
    <main class="mdl-layout__content mdl-color--grey-100">
      <div class="mdl-grid demo-content">
        
        <?php
        $array = unserialize(file_get_contents('data/' . $lang . '/ch/1'));
        ?>
        
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col">
          Admin Panel / Chapters / Chapter 1 / Edit
        </div>
        
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--6-col">
          
          <form action="admin?p=chapters&a=edit&id=1&go" method="post">
            
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%">
              <input class="mdl-textfield__input" type="text" id="title" name="title" value="<?php echo $array['title']; ?>" />
              <label class="mdl-textfield__label" for="title">Title</label>
            </div>
            
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%">
              <input class="mdl-textfield__input" type="text" id="pages" name="pages" pattern="[0-9]*" value="<?php echo $array['pages']; ?>" />
              <label class="mdl-textfield__label" for="pages">Page Count</label>
              <span class="mdl-textfield__error">Must be a number</span>
            </div>
            
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%">
              <input class="mdl-textfield__input" type="text" id="date" name="date" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" />
              <label class="mdl-textfield__label" for="date">Release Date (YYYY-MM-DD)</label>
              <span class="mdl-textfield__error">Must be a valid date</span>
            </div>
            
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%">
              <input class="mdl-textfield__input" type="text" id="date" name="date" pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9]){2}" />
              <label class="mdl-textfield__label" for="date">Release Time (HH:MM:SS)</label>
              <span class="mdl-textfield__error">Must be a valid time</span>
            </div>
            
            <br>
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
              Edit
            </button>
            
          </form>
          
        </div>
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--6-col">
          
        </div>

      </div>
    </main>

  </div>
  <script src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
</body>
</html>
