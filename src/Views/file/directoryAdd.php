<?php require('src/Views/partial/header.php'); ?>
<h3>Добавить папку</h3>

<form action="<?php echo URLROOT; ?>/directory" enctype="multipart/form-data" method="post">
    <span class=""
          style="width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: var(--bs-danger-text);"><?php echo (!empty($data['empty_err']) ? $data['empty_err'] : ""); ?></span>
    <div class="mb-3">
        <label for="directory" class="form-label">Название папки</label>
        <input type="text" class="form-control" name="directory_name" id="directory_name">
    </div>

    <button type="submit" class="btn btn-primary">Принять</button>
</form>

<?php require('src/Views/partial/footer.php'); ?>
