<?php require('src/Views/partial/header.php'); ?>
<h3>Переместить файл '<?= $data['file']->name ?>' в другую папку</h3>

<form action="<?php echo URLROOT; ?>/file/<?= $data['file']->id ?>" method="post">
    <input type="hidden" name="_method" value="PUT">
    <span class=""
          style="width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: var(--bs-danger-text);"><?= (!empty($data['empty_err']) ? $data['empty_err'] : ""); ?></span>
    <div class="mb-3">
        <label for="directory" class="form-label">Название папки</label>
        <select name="directory" class="form-select" id="directory">
            <option value="" selected>uploads</option>
            <?php
            if (isset($data['dirs']) && !empty($data['dirs'])) {
                foreach ($data['dirs'] as $dir) { ?>
                    <option value="<?= $dir->name ?>"><?= $dir->name ?></option>
                <?php }
            }
            ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Принять</button>
</form>

<?php require('src/Views/partial/footer.php'); ?>
