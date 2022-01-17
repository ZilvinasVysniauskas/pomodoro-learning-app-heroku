
<form action="" method="post" name="login" id="login">
    <label for="email">email</label>
    <input type="text" name="email" id="email" value="<?=$_POST['email'] ?? null ?>">
    <label for="password">password</label>
    <input type="password" name="password" id="password">
    <input type="checkbox" id="showHidePassword">Show Password
    <input type="submit">
</form>
<a href="register">Dont have an account? Register</a>

<?php foreach ($errors as $error): ?>
<p><?=$error?></p>
<?php endforeach; ?>
