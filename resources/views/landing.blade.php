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

<div id="frontpage-header" class="text-md-center">
    <h1>Svente 94</h1>
</div>

<div class="container">

    <div class="row">
        <div class="col-sm">
            <h2>Login</h2>
            <hr>
            <form action="">
                <div class="form-group">
                    <label for="login-username">Username</label>
                    <input id="login-username" type="text" class="form-control" name="email"
                           placeholder="Svente94">
                </div>
                <div class="form-group">
                    <label for="login-password">Passwort</label>
                    <input id="login-password" type="password" class="form-control" name="password"
                           placeholder="passwort">
                </div>
                <button type="submit" class="btn btn-info">Einloggen</button>
            </form>
        </div>
        <div class="col-sm">
            <h2>Registrierung</h2>
            <hr>
            <form method="post" action="/register">
                @csrf
                <div class="form-group">
                    <label for="register-username">Username</label>
                    <input id="register-username" type="text" class="form-control" name="username"
                           placeholder="Svente94">
                </div>
                <div class="form-group">
                    <label for="register-email">Email-Adresse</label>
                    <input id="register-email" type="email" class="form-control" name="email"
                           placeholder="sven@example.org">
                </div>
                <div class="form-group">
                    <label for="register-password">Passwort</label>
                    <input id="register-password" type="password" class="form-control" name="password"
                           placeholder="passwort">
                </div>
                <div class="form-group">
                    <label for="register-password-confirmation">Passwort bestätigen</label>
                    <input id="register-password-confirmation" type="password" class="form-control"
                           name="password_confirmation" placeholder="passwort">
                </div>
                <button type="submit" class="btn btn-info">Registrieren</button>
            </form>
        </div>
    </div>
</div>


</body>
</html>