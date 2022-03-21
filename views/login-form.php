<h1><?= $title ?></h1>
<form method="post">
    <label for="username">Username</label>
    <input id="username" type="text" name="username" required />
    <label for="password">Password</label>
    <input id="password" type="password" name="password" required />
    <button type="submit">Se connecter</button>
</form>

<div>
    <a href="<?= $loginOrSignIn ?>">
        <button><?= $loginOrSignIn ?></button>
    </a>
</div>
