<header>
    <h1>Account neu anmelden</h1>
</header>
<main>
    <?php echo $_SESSION['info'] ?>
    <form method="post">
        <input type="text" name="name" placeholder="Username" required><br>
        <input type="email" name="e_mail" placeholder="E-Mail" required><br>
        <input type="password" name="pass" placeholder="Passwort" required><br>
        <input type="hidden" name="anmeldeformular" value="true"> 
        <button>send</button>
    </form>
</main>