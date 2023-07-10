<?php require_once("config.php");
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_POST["login"])) {
    $sql = "SELECT * FROM user WHERE username='$_POST[username]' AND password='".md5($_POST["password"])."'";
    if ($query = $connection->query($sql)) {
        if ($query->num_rows) {
            while ($data = $query->fetch_array()) {
              $_SESSION["is_logged"] = true;
              $_SESSION["id"] = $data["id_user"];
              $_SESSION["username"] = $data["username"];
            }
            header('location: ?page=home');
        } else {
            echo alert("Username / Password tidak sesuai!", "index.php");
        }
    } else {
        echo "Query error!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>eKost</title>
  <script type="text/javascript">
    var IsDraggable = <?=$is=(isset($_GET["page"])) ? (($_GET["page"] == "home") ? "false" : "true") : "false"?>;
  </script>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
  <link href="assets/css/jumbotron.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

  <script src="assets/js/ie-emulation-modes-warning.js"></script>
  <script src="assets/js/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Optional, Add fancyBox for media, buttons, thumbs -->
  <link rel="stylesheet" href="assets/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="assets/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="assets/fancybox/source/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
  <script type="text/javascript" src="assets/fancybox/source/jquery.fancybox.pack.js"></script>
  <script type="text/javascript" src="assets/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
  <script type="text/javascript" src="assets/fancybox/source/helpers/jquery.fancybox-media.js"></script>
  <script type="text/javascript" src="assets/fancybox/source/helpers/jquery.fancybox-thumbs.js"></script><!-- Optional, Add mousewheel effect -->
  <script type="text/javascript" src="assets/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body onload="initialize()">
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php" style="color: white;">AYO KOS</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <?php if (isset($_SESSION['is_logged'])): ?>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$_SESSION["username"]?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="?page=user">Profil</a></li>
              </ul>
            </li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        <?php else: ?>
          <form class="navbar-form navbar-right" action="<?=$_SERVER["REQUEST_URI"]?>" method="post">
              <div class="input-group">
                <input type="text" name="username" class="form-control" placeholder="username">
                <span class="input-group-addon" style="border-left: 0; border-right: 0;"></span>
                <input type="password" name="password" class="form-control" placeholder="password">
                <span class="input-group-btn">
                  <button class="btn btn-success" type="submit">Login</button>
                  <a href="?page=user&register" class="btn btn-primary">Register</a>
                </span>
              </div>
              <input type="hidden" name="login" value="true">
          </form>
        <?php endif; ?>
      </div><!--/.navbar-collapse -->
    </div>
  </nav>
  <?php include page($_PAGE); ?>
  <div class="container">
    <hr>
    <footer>
      <p>&copy;</p>
    </footer>
  </div> <!-- /container -->
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  <script type="text/javascript">
    var markerImage = 'assets/img/marker.png';
    var myCurrentLocationMarker = 'assets/img/mylocation-marker.png';
  </script>
  
</body>
</html>
