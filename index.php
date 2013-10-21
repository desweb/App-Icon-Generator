<!DOCTYPE html>
<html lang="fr">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Ic&ocirc;ne mobile</title>

  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="http://desweb-creation.fr/site/wp-content/themes/desweb_creation/favicon.ico">

  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="css/jumbotron.css" rel="stylesheet">
  <link href="css/dropzone.css" rel="stylesheet">

  <script type="text/javascript" src="//code.jquery.com/jquery.js"></script>

  <!--[if lt IE 9]>
    <script src="../../assets/js/html5shiv.js"></script>
    <script src="../../assets/js/respond.min.js"></script>
  <![endif]-->

  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/dropzone.min.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>
<body>
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="http://code.desweb-creation.fr/app-icon/">Ic&ocirc;nes mobile</a>
        <a class="navbar-brand" href="http://code.desweb-creation.fr/app-launcher/">Ecrans de lancement mobile</a>
      </div>
    </div>
  </div>

  <div class="jumbotron">
    <div class="container">
      <h1>G&eacute;n&eacute;ration d'ic&ocirc;ne pour application mobile <sup>b&ecirc;ta</sup></h1>

      <p>
        1. Glissez/d&eacute;posez votre ic&ocirc;ne<br />
        2. Patientez quelques instants<br />
        3. T&eacute;l&eacute;chargez votre fichier zip<br />
        4. Disposez de tous les ic&ocirc;nes dont vous aurez besoin pour votre application mobile iPhone & Android
      </p>
      <p><a href="https://developer.apple.com/library/ios/qa/qa1686/_index.html" target="_blank">Documentation iOS</a></p>
      <p><a href="http://developer.android.com/design/style/iconography.html" target="_blank">Documentation Android</a></p>
    </div>
  </div>

  <div class="container">
    <div class="alert alert-info" id="load">
      <img src="images/loader.gif"/> Chargement...
    </div>
    <div class="alert alert-success" id="upload_success">
      Création des ic&ocirc;nes effectu&eacute;s avec succ&egrave;s.<br />
      <a href="#" target="_blank" id="zip_link">T&eacute;l&eacute;charger mes ic&ocirc;nes</a>
    </div>
    <div class="alert alert-danger" id="upload_failed">
      Erreur lors de l'envoi de l'ic&ocirc;ne.
    </div>
    <div class="alert alert-danger" id="file_error">
      Attention, votre image ne respecte pas les conditions requises :
      <ul>
        <li>Poids maximum : 1Mo</li>
        <li>Format : PNG</li>
        <li>Dimensions : 1024px * 1024px</li>
        <li>R&eacute;solution : 72dpi</li>
      </ul>
    </div>

    <?php $now = time(); ?>
    <form method="post" action="upload.php?time=<?= $now ?>" data-time="<?= $now ?>" enctype="multipart/form-data" role="form" class="dropzone" id="dropzone"></form>
    </div>
  </div>
</body>
</html>