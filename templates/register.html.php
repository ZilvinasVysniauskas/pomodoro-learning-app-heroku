<form action="" method="post" name="login" id="login">
    <label for="email">email</label>
    <input type="text" name="email" id="email" value="<?= $_POST['email'] ?? null?>">
    <label for="password">password</label>
    <input type="password" name="password" id="password">
    <label for="password_repeat">password</label>
    <input type="password" name="password_repeat" id="password_password_repeat">
    <input type="checkbox" id="showHidePassword">Show Password
    <input type="submit">
</form>
<a href="/">Already have an account? Login</a>

<?php foreach ($errors as $error): ?>
    <p><?=$error?></p>
<?php endforeach; ?>
