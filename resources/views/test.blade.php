<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Willkommen bei Juls network!</title>
    <meta name="description" content="Hier entsteht die Seite von Juls">
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js"></script>

    <!-- css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
            crossorigin="anonymous"></script>
    <!-- Bootstrap js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
            integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
            crossorigin="anonymous"></script>
</head>
<body>

@php
    $meinArray = [];
    foreach (range(1, $usercount*2) as $i) {
        foreach (range(1, $usercount*2) as $j) {
            if($i == $usercount) {
                $meinArray[$i][$j] = '0';
            } else {
                $meinArray[$i][$j] = '#';
            }
        }
    }
    foreach($islands as $island) {
        $x = $island['position_x'];
        $y = $island['position_y'];
        $meinArray[$x+$usercount][$y+$usercount] = 'X';
    }

    foreach (range(1, $usercount*2) as $i) {
        foreach (range(1, $usercount*2) as $j) {
            echo $meinArray[$i][$j];
        }
        echo "\r\n";
    }

@endphp

</body>
</html>