<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="?">Home</a></li>
            <li><a href="?kontakt=true">Kontakt</a></li>
        </ul>
    </nav>
    <details class="login">
        <summary>Login</summary>
        <form method="POST">
            <input type="email" name="e_mail" placeholder="E-Mail" required><br>
            <input type="password" name="pass" placeholder="Passwort" required><br>
            <input type="hidden" name="login">
            <button>send</button>
        </form>
        <a href="?anmelden=true">neu Anmelden</a>
    </details>