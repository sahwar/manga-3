<?php
require_once('inc/functions.php');
$lang = 'en';
if (isset($_GET['p'])) {
  $page = $_GET['p'];
} else {
  $page = '';
}
if (isset($_GET['a'])) {
  $action = $_GET['a'];
} else {
  $action = '';
}
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  $id = '';
}
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
    <link rel="stylesheet" href="inc/admin.css">
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
<?php
/* CHAPTER EDIT: MAIN */
if ($page == 'chapter' && $action == 'edit' && $id != '') {
?>
      <form action="admin?p=chapter&a=edit&nid=1&go" method="post" class="mdl-grid demo-content">
        
        <?php
        $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
        ?>
        
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--grey-500">
          Admin Panel &gt; Chapters &gt; Chapter <?php echo $id; ?> &gt; Edit
        </div>
        
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--9-col">

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

        </div>

        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--3-col">
          <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" type="submit">
              Save
          </button><br>
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter&a=images&id=<?php echo $id; ?>">
              Manage Images
          </a><br>
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter&a=add&id=<?php echo $id; ?>">
              Add Images
          </a><br>
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="admin?p=chapter&a=deleteall&id=<?php echo $id; ?>">
              Delete Chapter
          </a>
        </div>

      </form>
<?php
/* CHAPTER EDIT: MANAGE IMAGES */
} elseif ($page == 'chapter' && $action == 'images' && $id != '') {
?>
      <div class="mdl-grid demo-content">
        
        <?php
        $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
        ?>
        
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--grey-500">
          Admin Panel &gt; Chapters &gt; Chapter <?php echo $id; ?> &gt; Edit &gt; Manage Images (<?php echo $array['imagekey']; ?>)
        </div>
        
        <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp mdl-cell mdl-cell--9-col">
          <thead>
            <tr>
              <th class="mdl-data-table__cell--non-numeric">Filename</th>
              <th>Filesize</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $imageList = array_diff(scandir('content/' . $array['imagekey']), array('..', '.'));
            foreach($imageList as $x){
            ?>
            <tr>
              <td class="mdl-data-table__cell--non-numeric"><a href="content/<?php echo $array['imagekey'] . '/' . $x; ?>" target="_blank"><?php echo $x; ?></a></td>
              <td>
                <?php echo round((filesize('content/ch1/' . $x) / 1024), 2) . ' KB'; ?>
              </td>
              <td>
                <a style="mdl-button--accent" href="admin?p=chapter&a=deleteimage&id=<?php echo $id; ?>&file=<?php echo $x; ?>">
                  <i class="material-icons">delete</i>
                </a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--3-col">
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">
              Back
          </a><br>
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter&a=add&id=<?php echo $id; ?>">
              Add Images
          </a><br>
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="admin?p=chapter&a=deleteimage&id=<?php echo $id; ?>&all">
              Delete All
          </a>
        </div>
        
      </div>
<?php
/* CHAPTER EDIT: ADD IMAGE(S) */
} elseif ($page == 'chapter' && $action == 'add' && $id != '') {
?>
      <div class="mdl-grid demo-content">
        
        <?php
        $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
        ?>
        
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--grey-500">
          Admin Panel &gt; Chapters &gt; Chapter <?php echo $id; ?> &gt; Edit &gt; Add Images
        </div>
        
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--9-col">
          <script src="inc/dropzone.js"></script>
          <link rel="stylesheet" href="inc/dropzone.css">
          <form action="inc/imageupload.php?ik=<?php echo $array['imagekey']; ?>" class="dropzone"></form>
        </div>
        
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--3-col">
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">
              Back
          </a><br>
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter&a=images&id=<?php echo $id; ?>">
              Manage Images
          </a><br>
        </div>
        
<?php } ?>
    </main>

  </div>
  <script src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
</body>
</html>
