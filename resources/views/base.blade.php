<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
  <meta name="Keywords" content="ФК, футбол, дрогобич, дрогобиччина, дрогобича, Футбол Дрогобиччини"> 
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Soccer</title>
  <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
  <!-- Scripts -->
  <script>
      window.chempionat = <?php echo json_encode([
          'csrfToken' => csrf_token(),
      ]); ?>
  </script>
</head>
<body>
    @yield('content')
</body>

</html>
