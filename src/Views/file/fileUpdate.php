<?php require('src/Views/partial/header.php'); ?>
<h3>Edit File</h3>

<form action="<?php echo URLROOT; ?>/file/<?= $data['file']->id ?>" method="post">
    <input type="hidden" name="_method" value="PUT">
    <span class=""
          style="width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: var(--bs-danger-text);"><?= (!empty($data['empty_err']) ? $data['empty_err'] : ""); ?></span>
    <div class="mb-3">
        <label for="name" class="form-label">File name</label>
        <input type="text" class="form-control <?= (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>"
               name="file_name" id="name" value="<?= $data['file']->name ?>">
        <span class="invalid-feedback"><?= $data['name_err']; ?></span>
    </div>
    <div class="mb-3">
        <label for="directory" class="form-label">Directory</label>
        <input type="text" class="form-control" name="directory" id="directory" value="<?= $data['file']->directory ?>">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php require('src/Views/partial/footer.php'); ?>
