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

if ($page == ''){
  header('Location: admin?p=chapter');
} elseif ($page == 'chapter' && $action == 'edit' && $id != '' && isset($_GET['go']) || $page == 'chapter' && $action == 'add' && isset($_GET['go'])) {
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
} elseif ($page == 'settings'  && $action == '' && isset($_GET['go'])){
  $array = unserialize(file_get_contents('data/settings'));
  $array['lang'] = $_POST['lang'];
  $array['timezone'] = $_POST['timezone'];
  file_put_contents('data/settings',serialize($array));
  header('Location: admin?p=settings&saved');
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
    <link type="text/css" rel="stylesheet" href="skins/caramel/css/font-awesome.min.css" />
    <link type="text/css" rel="stylesheet" href="skins/caramel/css/caramel.min.css" />
    <style>
      input[type="text"],select.dropdown {
       width: 100%;
       box-sizing: border-box;
       -webkit-box-sizing:border-box;
       -moz-box-sizing: border-box;
      }
    </style>
  </head>
<body>

  <header class="header fixed">
    <nav class="nav bar">
        <ul>
            <li class="collapse"><a href="#" class="menu"><i class="fa fa-bars"></i></a></li>
            <li><a href="admin?p=chapter">Chapters</a></li>
            <li><a href="admin?p=settings">Global Settings</a></li>
        </ul>

        <ul class="right">
            <li><a href="admin?p=version">Version</a></li>
            <li><a href="https://github.com/kyufox/manga" target="_blank"><i class="fa fa-github-alt fa-fw"></i></a></li>
        </ul>
    </nav>
  </header>
  <br>
  <main>
<?php
/* GLOBAL SETTINGS */
if ($page == 'settings'  && $action == ''){
?>

      <div class="row">

        <div class="nav bar breadcrumbs">
          Admin Panel &nbsp;/&nbsp; Global Settings
        </div><br>

        <?php if (isset($_GET['saved'])) { ?>
          <div class="alert success" id="note" style="margin-bottom: 16px;">
            <div class="dismiss"><i class="fa fa-close"></i></div>
            <strong>Chapter Saved</strong>
          </div>
        <?php } ?>

        <form method="post" action="admin?p=settings&go">
          <?php $array = unserialize(file_get_contents('data/settings')); ?>
          <div class="box col-10">
            <input type="text" id="lang" name="lang" value="<?php echo $array['lang']; ?>" placeholder="Language" required />
            <select name="timezone" id="timezone" class="dropdown">
              <?php foreach(DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $tzlist) {
                if ($array['timezone'] == $tzlist) {
                  echo '<option value="' . $tzlist . '" selected>' . $tzlist . '</option>';
                } else {
                  echo '<option value="' . $tzlist . '">' . $tzlist . '</option>';
                }
              } ?>
            </select>
          </div>
          <div class="box col-2">
            <button class="btn success clean" style="width: 100%;" type="submit">Save</button>
            <a class="btn default clean" style="display: block;" href="admin?p=settings">Cancel Changes</a>
            <a class="btn info clean" style="display: block;" href="admin?p=settings&a=skins">Skins</a>
          </div>
        </form>

      </div>

<?php
/* CHAPTER LIST */
} elseif ($page == 'chapter' && $action == '' && $id == '') {
?>

      <div class="row">

        <div class="nav bar breadcrumbs">
          Admin Panel &nbsp;/&nbsp; Chapters
        </div><br>

        <div class="box col-10">
        <?php if (chapterTotal() > 0) { ?>
          <table class="table">
            <thead>
              <tr>
                <th>Chapter</th>
                <th class="align-right">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $imageList = array_diff(scandir('data/' . $lang . '/ch'), array('..', '.'));
              foreach($imageList as $x){
                $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $x));
              ?>
              <tr>
                <td>
                  <a href="admin?p=chapter&a=edit&id=<?php echo $x; ?>">
                    <?php echo $x . ' - ' . $array['title']; ?>
                  </a>
                </td>
                <td class="align-right">
                  <a href="read?chapter=<?php echo $x; ?>" target="_blank">
                    <i class="fa fa-file-image-o"></i>
                  </a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        <?php } ?>
        </div>
        <div class="box col-2">
          <a class="btn success clean" style="display: block;" href="admin?p=chapter&a=add">Add Chapter</a>
        </div>
      </div>
<?php
/* CHAPTER EDIT: MAIN */
} elseif ($page == 'chapter' && $action == 'edit' && $id != '' || $page == 'chapter' && $action == 'add') {
?>
      <?php if ($action == 'edit') { ?>
        <form action="admin?p=chapter&a=edit&id=<?php echo $id; ?>&go" method="post">
      <?php
      $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
      } else { ?>
        <form action="admin?p=chapter&a=add&go" method="post">
      <?php
      $array = array('title' => '',
                     'pages' => '',
                     'date' => '',
                     'time' => '');
      } ?>

        <div class="nav bar breadcrumbs">
          Admin Panel &nbsp;/&nbsp; Chapters &nbsp;/&nbsp; Chapter <?php echo $id; ?> &nbsp;/&nbsp; Edit
        </div><br>

        <?php if (isset($_GET['saved'])) { ?>
          <div class="alert success" id="note" style="margin-bottom: 16px;">
            <div class="dismiss"><i class="fa fa-close"></i></div>
            <strong>Chapter Saved</strong>
          </div>
        <?php } ?>

        <?php if (isset($_GET['added'])) { ?>
          <div class="alert success" id="note" style="margin-bottom: 16px;">
            <div class="dismiss"><i class="fa fa-close"></i></div>
            <strong>New Chapter Added</strong>
          </div>
        <?php } ?>

        <div class="row">
          <div class="box col-10">
            <?php if ($action == 'add') { ?>
              <input type="text" id="id" name="id" pattern="[0-9]*" value="<?php echo (chapterTotal() + 1); ?>" placeholder="Chapter" required />
            <?php } ?>
            <input type="text" id="title" name="title" value="<?php echo $array['title']; ?>" placeholder="Title" required />
            <input type="text" id="pages" name="pages" pattern="[0-9]*" value="<?php echo $array['pages']; ?>" placeholder="Page Count" required />
            <input type="text" id="date" name="date" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" value="<?php echo $array['date']; ?>" placeholder="Release Date (YYYY-MM-DD)" />
            <input type="text" id="time" name="time" pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9]){2}" value="<?php echo $array['time']; ?>" placeholder="Release Time (HH:MM:SS)" />
          </div>
          <div class="box col-2">
            <a class="btn default clean" style="display: block;" href="admin?p=chapter">
                Chapter List
            </a>
            <button class="btn success clean" style="width: 100%;" type="submit">
                Save
            </button>
            <?php if ($action == 'edit'){ ?>
              <a class="btn default clean" style="display: block;" href="admin?p=chapter&a=images&id=<?php echo $id; ?>">
                  Manage Images
              </a>
              <a class="btn default clean" style="display: block;" href="admin?p=chapter&a=addimages&id=<?php echo $id; ?>">
                  Add Images
              </a>
              <a class="btn error clean" style="display: block;" href="admin?p=chapter&a=deleteall&id=<?php echo $id; ?>">
                  Delete Chapter
              </a>
            <?php } ?>
            </div>
          </div>

      </form>
<?php
/* CHAPTER EDIT: MANAGE IMAGES */
} elseif ($page == 'chapter' && $action == 'images' && $id != '') {
?>
      <div class="row">

        <?php
        $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
        ?>

        <div class="nav bar breadcrumbs">
          Admin Panel &nbsp;/&nbsp; Chapters &nbsp;/&nbsp; Chapter <?php echo $id; ?> &nbsp;/&nbsp; Edit &nbsp;/&nbsp; Manage Images (<?php echo $array['imagekey']; ?>)
        </div><br>

        <div class="box col-10">
          <table class="table">
            <thead>
              <tr>
                <th>Filename</th>
                <th>Filesize</th>
                <th class="align-right">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $imageList = array_diff(scandir('content/' . $array['imagekey']), array('..', '.'));
              foreach($imageList as $x){
              ?>
              <tr>
                <td><a href="content/<?php echo $array['imagekey'] . '/' . $x; ?>" target="_blank"><?php echo $x; ?></a></td>
                <td>
                  <?php echo round((filesize('content/' . $array['imagekey'] . '/' . $x) / 1024), 2) . ' KB'; ?>
                </td>
                <td class="align-right">
                  <a class="color error" href="admin?p=chapter&a=deleteimage&id=<?php echo $id; ?>&file=<?php echo $x; ?>">
                    <i class="fa fa-trash-o"></i>
                  </a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>

        <div class="box col-2">
          <a class="btn default clean" style="display: block;" href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">
              Back
          </a>
          <a class="btn default clean" style="display: block;" href="admin?p=chapter&a=addimages&id=<?php echo $id; ?>">
              Add Images
          </a>
          <a class="btn error clean" style="display: block;" href="admin?p=chapter&a=deleteimage&id=<?php echo $id; ?>&all">
              Delete All
          </a>
        </div>

      </div>
<?php
/* CHAPTER EDIT: ADD IMAGE(S) */
} elseif ($page == 'chapter' && $action == 'addimages' && $id != '') {
?>
      <div class="row">

        <?php
        $array = unserialize(file_get_contents('data/' . $lang . '/ch/' . $id));
        ?>

        <div class="nav bar breadcrumbs">
          Admin Panel &nbsp;/&nbsp; Chapters &nbsp;/&nbsp; Chapter <?php echo $id; ?> &nbsp;/&nbsp; Edit &nbsp;/&nbsp; Add Images
        </div><br>

        <div class="box col-10">
          <script src="inc/dropzone.js"></script>
          <link rel="stylesheet" href="inc/dropzone.css">
          <form action="inc/imageupload.php?ik=<?php echo $array['imagekey']; ?>" class="dropzone"></form>
        </div>

        <div class="box col-2">
          <a class="btn default clean" style="display: block;" href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">
              Back
          </a>
          <a class="btn default clean" style="display: block;" href="admin?p=chapter&a=images&id=<?php echo $id; ?>">
              Manage Images
          </a>
        </div>
<?php
/* CHAPTER EDIT: DELETE ALL IMAGES CONFIRMATION */
} elseif ($page == 'chapter' && $action == 'deleteimage' && $id != '' && !isset($_GET['file']) && isset($_GET['all']) && !isset($_GET['confirm'])){
?>

        <div class="nav bar breadcrumbs">
          Admin Panel &nbsp;/&nbsp; Chapters &nbsp;/&nbsp; Chapter <?php echo $id; ?> &nbsp;/&nbsp; Edit &nbsp;/&nbsp; Delete All Images
        </div><br>

        <div class="panel warn">
          <div class="panel-head">Are you sure you want to delete all images for this chapter?</div>
          <div class="panel-body">
            This action <strong>cannot</strong> be undone.
          </div>
        </div>

        <a class="btn default clean" href="admin?p=chapter&a=images&id=<?php echo $id; ?>">
          Go Back
        </a>
        <a class="btn error clean" href="admin?p=chapter&a=deleteimage&id=<?php echo $id; ?>&all&confirm">
          Delete All Images
        </a>

<?php
/* CHAPTER EDIT: DELETE ALL IMAGES CONFIRMATION */
} elseif ($page == 'chapter' && $action == 'deleteall' && $id != '' && !isset($_GET['confirm'])){
?>

        <div class="nav bar breadcrumbs">
          Admin Panel &nbsp;/&nbsp; Chapters &nbsp;/&nbsp; Chapter <?php echo $id; ?> &nbsp;/&nbsp; Edit &nbsp;/&nbsp; Delete Chapter
        </div><br>

        <div class="panel warn">
          <div class="panel-head">Are you sure you want to delete the entire chapter?</div>
          <div class="panel-body">
            This includes all images associated with this chapter. This action <strong>cannot</strong> be undone.
          </div>
        </div>

        <a class="btn default clean" href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">
          Go Back
        </a>
        <a class="btn error clean" href="admin?p=chapter&a=deleteall&id=<?php echo $id; ?>&confirm">
          Delete Entire Chapter
        </a>

<?php } ?>
  </main>
  <script src="skins/caramel/js/jquery-2.1.3.min.js"></script>
  <script src="skins/caramel/js/caramel.min.js" type="text/javascript"></script>
</body>
</html>
