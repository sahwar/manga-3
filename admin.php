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

if ($page == 'chapter' && $action == 'edit' && $id != '' && isset($_GET['go']) || $page == 'chapter' && $action == 'add' && isset($_GET['go'])) {
  if ($action == 'edit') {
    $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
  } else {
    $array['imagekey'] = md5($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000));
    mkdir('content/' . $array['imagekey']);
    $id = $_POST['id'];
  }
  $array['title'] = $_POST['title'];
  $array['pages'] = $_POST['pages'];
  $array['date'] = $_POST['date'];
  $array['time'] = $_POST['time'];
  file_put_contents('data/' . $lang . '/ch/' . $id,serialize($array));
  if ($action == 'edit') {
    header('Location: admin?p=chapter&a=edit&id=' . $id . '&saved');
  } else {
    header('Location: admin?p=chapter&a=edit&id=' . $id . '&added');
  }
  exit;
} elseif ($page == 'chapter' && $action == 'deleteimage' && $id != '' && isset($_GET['file']) && !isset($_GET['all'])){
  $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
  unlink('content/' . $array['imagekey'] . '/' . $_GET['file']);
  header('Location: admin?p=chapter&a=images&id=' . $id);
  exit;
} elseif ($page == 'chapter' && $action == 'deleteimage' && $id != '' && !isset($_GET['file']) && isset($_GET['all']) && isset($_GET['confirm'])){
  $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
  $imageList = array_diff(scandir('content/' . $array['imagekey']), array('..', '.'));
  foreach($imageList as $x){
    unlink('content/' . $array['imagekey'] . '/' . $x);
  }
  header('Location: admin?p=chapter&a=images&id=' . $id);
  exit;
} elseif ($page == 'chapter' && $action == 'deleteall' && $id != '' && isset($_GET['confirm'])){
    $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
    $imageList = array_diff(scandir('content/' . $array['imagekey']), array('..', '.'));
    foreach($imageList as $x){
      unlink('content/' . $array['imagekey'] . '/' . $x);
    }
    rmdir('content/' . $array['imagekey']);
    unlink('data/' . $lang . '/ch/' . $id);
    header('Location: admin?p=chapter');
    exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Admin Panel</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.blue_grey-pink.min.css">
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
      </div>
    </header>
    <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
      <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
        <a class="mdl-navigation__link" href="admin"><i class="mdl-color-text--blue-grey-400 material-icons">dashboard</i>Dashboard</a>
        <a class="mdl-navigation__link" href="admin?p=settings"><i class="mdl-color-text--blue-grey-400 material-icons">settings</i>Global Settings</a>
        <a class="mdl-navigation__link" href="admin?p=chapter"><i class="mdl-color-text--blue-grey-400 material-icons">photo_library</i>Chapters</a>
      </nav>
    </div>

    <main class="mdl-layout__content mdl-color--grey-100">
<?php
/* CHAPTER LIST */
if ($page == 'chapter' && $action == '' && $id == '') {
?>
      <div class="mdl-grid demo-content">
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--grey-500">
          Admin Panel &gt; Chapters
        </div>

        <?php if (chapterTotal() > 0) { ?>
          <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp mdl-cell mdl-cell--12-col">
            <thead>
              <tr>
                <th class="mdl-data-table__cell--non-numeric">Chapter</th>
                <th>Edit</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $imageList = array_diff(scandir('data/' . $lang . '/ch'), array('..', '.'));
              foreach($imageList as $x){
                $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $x));
              ?>
              <tr>
                <td class="mdl-data-table__cell--non-numeric">
                  <a href="read?chapter=<?php echo $x; ?>" target="_blank">
                    <?php echo $x . ' - ' . $array['title']; ?>
                  </a>
                </td>
                <td>
                  <a style="mdl-button--accent" href="admin?p=chapter&a=edit&id=<?php echo $x; ?>">
                    <i class="material-icons">mode_edit</i>
                  </a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        <?php } ?>

        <div class="mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--grey-500 mdl-card__actions mdl-card--border">
          <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="admin?p=chapter&a=add">
            Add Chapter
          </a>
        </div>

      </div>
<?php
/* CHAPTER EDIT: MAIN */
} elseif ($page == 'chapter' && $action == 'edit' && $id != '' || $page == 'chapter' && $action == 'add') {
?>
      <?php if ($action == 'edit') { ?>
        <form action="admin?p=chapter&a=edit&id=<?php echo $id; ?>&go" method="post" class="mdl-grid demo-content">
      <?php
      $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
      } else { ?>
        <form action="admin?p=chapter&a=add&go" method="post" class="mdl-grid demo-content">
      <?php
      $array = array('title' => '',
                     'pages' => '',
                     'date' => '',
                     'time' => '');
      } ?>

        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--grey-500">
          Admin Panel &gt; Chapters &gt; Chapter <?php echo $id; ?> &gt; Edit
        </div>

        <?php if (isset($_GET['saved'])) { ?>
          <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--green-500">
            <strong>Chapter Saved</strong>
          </div>
        <?php } ?>

        <?php if (isset($_GET['added'])) { ?>
          <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--green-500">
            <strong>New Chapter Added</strong>
          </div>
        <?php } ?>

        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--9-col">

          <?php if ($action == 'add') { ?>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%">
              <input class="mdl-textfield__input" type="text" id="id" name="id" pattern="[0-9]*" value="<?php echo (chapterTotal() + 1); ?>" required />
              <label class="mdl-textfield__label" for="pages">Chapter</label>
              <span class="mdl-textfield__error">Must be a number</span>
            </div>
            <br>
          <?php } ?>

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
            <input class="mdl-textfield__input" type="text" id="date" name="date" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" value="<?php echo $array['date']; ?>" />
            <label class="mdl-textfield__label" for="date">Release Date (YYYY-MM-DD)</label>
            <span class="mdl-textfield__error">Must be a valid date</span>
          </div>

          <br>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%">
            <input class="mdl-textfield__input" type="text" id="time" name="time" pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9]){2}" value="<?php echo $array['time']; ?>" />
            <label class="mdl-textfield__label" for="time">Release Time (HH:MM:SS)</label>
            <span class="mdl-textfield__error">Must be a valid time</span>
          </div>

        </div>

        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--3-col">
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter">
              Chapter List
          </a><br>
          <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored" type="submit">
              Save
          </button><br>
          <?php if ($action == 'edit'){ ?>
            <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter&a=images&id=<?php echo $id; ?>">
                Manage Images
            </a><br>
            <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter&a=addimages&id=<?php echo $id; ?>">
                Add Images
            </a><br>
            <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="admin?p=chapter&a=deleteall&id=<?php echo $id; ?>">
                Delete Chapter
            </a>
          <?php } ?>
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
                <?php echo round((filesize('content/' . $array['imagekey'] . '/' . $x) / 1024), 2) . ' KB'; ?>
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
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" href="admin?p=chapter&a=addimages&id=<?php echo $id; ?>">
              Add Images
          </a><br>
          <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" href="admin?p=chapter&a=deleteimage&id=<?php echo $id; ?>&all">
              Delete All
          </a>
        </div>

      </div>
<?php
/* CHAPTER EDIT: ADD IMAGE(S) */
} elseif ($page == 'chapter' && $action == 'addimages' && $id != '') {
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
<?php
/* CHAPTER EDIT: DELETE ALL IMAGES CONFIRMATION */
} elseif ($page == 'chapter' && $action == 'deleteimage' && $id != '' && !isset($_GET['file']) && isset($_GET['all']) && !isset($_GET['confirm'])){
?>
        <div class="mdl-grid demo-content">
          <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--grey-500">
            Admin Panel &gt; Chapters &gt; Chapter <?php echo $id; ?> &gt; Edit &gt; Delete All Images
          </div>

          <div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col">
            <div class="mdl-card__supporting-text">
              Are you sure you want to delete all images for this chapter? This action <strong>cannot</strong> be undone.
            </div>
            <div class="mdl-card__actions mdl-card--border">
              <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="admin?p=chapter&a=images&id=<?php echo $id; ?>">
                Go Back
              </a>
              <a class="mdl-button mdl-button--accent mdl-js-button mdl-js-ripple-effect" href="admin?p=chapter&a=deleteimage&id=<?php echo $id; ?>&all&confirm">
                Delete All Images
              </a>
            </div>
          </div>

        </div>
<?php
/* CHAPTER EDIT: DELETE ALL IMAGES CONFIRMATION */
} elseif ($page == 'chapter' && $action == 'deleteall' && $id != '' && !isset($_GET['confirm'])){
?>
        <div class="mdl-grid demo-content">
          <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col mdl-color-text--grey-500">
            Admin Panel &gt; Chapters &gt; Chapter <?php echo $id; ?> &gt; Edit &gt; Delete Chapter
          </div>

          <div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col">
            <div class="mdl-card__supporting-text">
              Are you sure you want to delete the entire chapter? This includes all images associated with this chapter. This action <strong>cannot</strong> be undone.
            </div>
            <div class="mdl-card__actions mdl-card--border">
              <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">
                Go Back
              </a>
              <a class="mdl-button mdl-button--accent mdl-js-button mdl-js-ripple-effect" href="admin?p=chapter&a=deleteall&id=<?php echo $id; ?>&confirm">
                Delete Entire Chaper
              </a>
            </div>
          </div>

        </div>
<?php } ?>
    </main>

  </div>
  <script src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
</body>
</html>
