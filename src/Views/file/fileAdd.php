<?php require('src/Views/partial/header.php'); ?>
<h3>Добавить файл</h3>

<form action="<?php echo URLROOT; ?>/file" enctype="multipart/form-data" method="post">
    <span class=""
          style="width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: var(--bs-danger-text);"><?php echo (!empty($data['empty_err']) ? $data['empty_err'] : ""); ?></span>
    <div class="mb-3">
        <label for="directory" class="form-label">Папка, в которую сохранить</label>
        <input type="text" class="form-control" name="directory" id="directory">
    </div>
    <div class="mb-3">
        <label for="formFile" class="form-label">Файл</label>
        <input class="form-control" name="file" type="file" id="formFile">
    </div>

    <button type="submit" class="btn btn-primary">Принять</button>
</form>

<?php require('src/Views/partial/footer.php'); ?>
