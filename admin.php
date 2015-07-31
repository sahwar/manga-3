<?php
/*
 *  MANGA PROJECT (http://manga-project.ga/)
 *  Created by 花木カズキ (Kazuki Hanaki)
 *  Released under the GNU GPLv2 license
 */
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
  $array['doubleSpread'] = $_POST['doubleSpread'];
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
    <link type="text/css" rel="stylesheet" href="skins/caramel/css/caramel.min.css" />
    <style>
      input[type="text"],select.dropdown {
       width: 100%;
       box-sizing: border-box;
       -webkit-box-sizing:border-box;
       -moz-box-sizing: border-box;
      }
      form label {
        font-weight: bold;
      }
    </style>
  </head>
<body>
  <header class="header fixed">
    <nav class="nav bar">
      <div class="container">
        <ul>
          <li class="collapse"><a href="#" class="menu"><i class="fa fa-bars"></i></a></li>
          <li><a href="admin?p=chapter">Chapters</a></li>
          <li><a href="admin?p=settings">Global Settings</a></li>
          <ul class="right">
            <li><a href="admin?p=version">Version</a></li>
            <li><a href="https://github.com/kyufox/manga" target="_blank"><i class="fa fa-github-alt fa-fw"></i> GitHub</a></li>
          </ul>
        </ul>
      </div>
    </nav>
  </header>
  <br>
  <main>
<?php
/* VERSION INFO */
if ($page == 'version'){
  function get_json($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Awesome-Octocat-App');
    $content = curl_exec($curl);
    curl_close($curl);
    return $content;
  }
  $github_latest = json_decode(get_json('https://api.github.com/repos/kyufox/manga/releases/latest'), true);
  $current_version = 'none';
  if (isset($github_latest['tag_name'])){ $latest_version = $github_latest['tag_name']; } else {$latest_version = 'none';}
  if ($latest_version != 'none' && $latest_version > $current_version){
    $new_version = true;
  } else {
    $new_version = false;
  }
  $github_commit = json_decode(get_json('https://api.github.com/repos/kyufox/manga/commits'), true);
?>
      <div class="row">

        <div class="box col-12">
          <div class="breadcrumbs">
            <ol>
              <li><a href="admin">Admin Panel</a></li>
              <li class="active">Version Info</li>
            </ol>
          </div>
        </div>

        <?php if ($new_version == true) { ?>
          <div class="notice success" id="note">
            <strong>New Version Available:</strong> Get <a href="<?php echo $github_latest['html_url']; ?>" target="_blank"><?php echo $github_latest['name']; ?></a>!
          </div>
        <?php } ?>

        <div class="box col-12">
          <p>
            Current Version: <strong><?php echo $current_version; ?></strong><br>
            Latest Version: <strong><?php echo $latest_version; ?></strong><br>
            Latest Commit: <strong><a href="<?php echo $github_commit[0]['html_url']; ?>" target="_blank"><?php echo substr($github_commit[0]['sha'], 0, 10); ?></a></strong>
              authored by <a href="<?php echo $github_commit[0]['author']['html_url']; ?>" target="_blank"><?php echo $github_commit[0]['author']['login']; ?></a>
              &mdash; <?php echo $github_commit[0]['commit']['message']; ?>
          </p>
        </div>

      </div>
<?php
/* GLOBAL SETTINGS */
} elseif ($page == 'settings'  && $action == ''){
?>

      <div class="row">

        <div class="box col-12">
          <div class="breadcrumbs">
            <ol>
              <li><a href="admin">Admin Panel</a></li>
              <li class="active">Global Settings</li>
            </ol>
          </div>
        </div>

        <?php if (isset($_GET['saved'])) { ?>
          <div class="alert success" id="note" style="margin-bottom: 16px;">
            <div class="dismiss"><i class="fa fa-close"></i></div>
            <strong>Chapter Saved</strong>
          </div>
        <?php } ?>

        <form method="post" action="admin?p=settings&go">
          <?php $array = unserialize(file_get_contents('data/settings')); ?>
          <div class="box col-10">
            <label for="lang">Language</label>
            <input type="text" id="lang" name="lang" value="<?php echo $array['lang']; ?>" placeholder="Language" required />
            <label for="timezone">Timezone</label>
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

        <div class="box col-12">
          <div class="breadcrumbs">
            <ol>
              <li><a href="admin">Admin Panel</a></li>
              <li class="active">Chapters</li>
            </ol>
          </div>
        </div>

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
                     'time' => '',
                     'doubleSpread' => 1);
      } ?>

      <div class="row">
          <div class="box col-12">
            <div class="breadcrumbs">
              <ol>
                <li><a href="admin">Admin Panel</a></li>
                <li><a href="admin?p=chapter">Chapters</a></li>
                <?php if ($action == 'edit') { ?>
                  <li><a href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">Chapter <?php echo $id; ?></a></li>
                  <li class="active">Edit</li>
                <?php } else { ?>
                  <li class="active">Add Chapter</li>
                <?php } ?>
              </ol>
            </div>
          </div>

          <?php if (isset($_GET['saved'])) { ?>
            <div class="box col-12">
              <div class="alert success" id="note" style="margin-bottom: 16px;">
                <div class="dismiss"><i class="fa fa-close"></i></div>
                <strong>Chapter Saved</strong>
              </div>
            </div>
          <?php } ?>

          <?php if (isset($_GET['added'])) { ?>
            <div class="box col-12">
              <div class="alert success" id="note" style="margin-bottom: 16px;">
                <div class="dismiss"><i class="fa fa-close"></i></div>
                <strong>New Chapter Added</strong>
              </div>
            </div>
          <?php } ?>

          <div class="box col-10">
            <?php if ($action == 'add') { ?>
              <label for="id">Chapter #*</label>
              <input type="text" id="id" name="id" pattern="[0-9]*" value="<?php echo (chapterTotal() + 1); ?>" placeholder="Chapter" required />
            <?php } ?>
            <label for="title">Chapter Title*</label>
            <input type="text" id="title" name="title" value="<?php echo $array['title']; ?>" placeholder="Title" required />
            <label for="pages"># of Pages*</label>
            <input type="text" id="pages" name="pages" pattern="[0-9]*" value="<?php echo $array['pages']; ?>" placeholder="Page Count" required />
            <label for="date">Release Date (YYYY-MM-DD)</label>
            <input type="text" id="date" name="date" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" value="<?php echo $array['date']; ?>" placeholder="Release Date (YYYY-MM-DD)" />
            <label for="time">Release Time (HH:MM:SS)</label>
            <input type="text" id="time" name="time" pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9]){2}" value="<?php echo $array['time']; ?>" placeholder="Release Time (HH:MM:SS)" />
            <label for="doubleSpread">Double Spread Page Start</label>
            <select id="doubleSpread" name="doubleSpread" class="dropdown">
              <option value="1"<?php if ($array['doubleSpread'] == 1){ echo ' selected'; } ?>>Start at Page 1</option>
              <option value="2"<?php if ($array['doubleSpread'] == 2){ echo ' selected'; } ?>>Start at Page 2</option>
            </select>
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

        <div class="box col-12">
          <div class="breadcrumbs">
            <ol>
              <li><a href="admin">Admin Panel</a></li>
              <li><a href="admin?p=chapter">Chapters</a></li>
              <li><a href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">Chapter <?php echo $id; ?></a></li>
              <li class="active">Manage Images (<?php echo $array['imagekey']; ?></li>
            </ol>
          </div>
        </div>

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

        <div class="box col-12">
          <div class="breadcrumbs">
            <ol>
              <li><a href="admin">Admin Panel</a></li>
              <li><a href="admin?p=chapter">Chapters</a></li>
              <li><a href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">Chapter <?php echo $id; ?></a></li>
              <li class="active">Add Images</li>
            </ol>
          </div>
        </div>

        <div class="box col-10">
          <script src="inc/dropzone.min.js"></script>
          <link rel="stylesheet" href="inc/dropzone.min.css">
          <form action="inc/imageupload.php?ik=<?php echo $array['imagekey']; ?>" class="dropzone" style="border: 2px dashed #525252;"></form>
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

        <div class="breadcrumbs">
          <ol>
            <li><a href="admin">Admin Panel</a></li>
            <li><a href="admin?p=chapter">Chapters</a></li>
            <li><a href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">Chapter <?php echo $id; ?></a></li>
            <li class="active">Delete All Images</li>
          </ol>
        </div>

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
/* CHAPTER EDIT: DELETE CHAPTER CONFIRMATION */
} elseif ($page == 'chapter' && $action == 'deleteall' && $id != '' && !isset($_GET['confirm'])){
?>

        <div class="breadcrumbs">
          <ol>
            <li><a href="admin">Admin Panel</a></li>
            <li><a href="admin?p=chapter">Chapters</a></li>
            <li><a href="admin?p=chapter&a=edit&id=<?php echo $id; ?>">Chapter <?php echo $id; ?></a></li>
            <li class="active">Delete Chaper</li>
          </ol>
        </div>

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
