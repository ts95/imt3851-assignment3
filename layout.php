<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Giveaway</title>

    <link rel="stylesheet" href="/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/main.css">

    <script src="/public/js/bundle.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" defer></script>
    <script>
        window.$global = <?= json_encode($this->global) ?>;
    </script>
</head>
<body>
    <div id="app"></div>

    <noscript>
        This website needs JavaScript in order to function. Please enable it by following the instructions <a target="_blanc" href="http://enable-javascript.com/">here</a>.
    </noscript>
</body>
</html>
