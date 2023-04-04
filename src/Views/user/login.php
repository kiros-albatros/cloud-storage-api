<?php require('src/Views/partial/header.php'); ?>
<div class="col-5 mx-auto">
    <h3>Вход</h3>
    <form action="<?php echo URLROOT; ?>/user/login" method="post">
        <span class=""
        style="width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: var(--bs-danger-text);"><?php echo (!empty($data['empty_err']) ? $data['empty_err'] : ""); ?></span>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Почта</label>
            <input type="text" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="exampleInputEmail1" name="email" value="<?php echo $data['email']; ?>">
            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Пароль</label>
            <input type="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="exampleInputPassword1" name="password" value="<?php echo $data['password']; ?>">
            <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Принять</button>
    </form>
</div>
<?php require('src/Views/partial/footer.php'); ?>