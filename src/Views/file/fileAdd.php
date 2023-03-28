<?php require('src/Views/partial/header.php'); ?>
<h3>Add File</h3>

<form action="<?php echo URLROOT; ?>/file" enctype="multipart/form-data" method="post">
    <span class=""
          style="width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: var(--bs-danger-text);"><?php echo (!empty($data['empty_err']) ? $data['empty_err'] : ""); ?></span>
    <div class="mb-3">
        <label for="directory" class="form-label">Directory to save</label>
        <input type="text" class="form-control" name="directory" id="directory">
    </div>
    <div class="mb-3">
        <label for="formFile" class="form-label">File</label>
        <input class="form-control" name="file" type="file" id="formFile">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php require('src/Views/partial/footer.php'); ?>
