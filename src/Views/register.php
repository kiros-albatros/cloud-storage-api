<?php require('src/Views/partial/header.php'); ?>
<div class="col-5 mx-auto">
    <h3>Register</h3>
    <form action="<?php echo URLROOT; ?>/user" method="post">
        <span class=""
              style="width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: var(--bs-danger-text);">
            <?php echo (!empty($data['empty_err']) ? $data['empty_err'] : ""); ?>
        </span>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email</label>
            <input type="text" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="exampleInputEmail1" name="email" value="<?php echo $data['email']; ?>">
            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php require('src/Views/partial/footer.php'); ?>
