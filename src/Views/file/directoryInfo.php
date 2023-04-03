<?php require('src/Views/partial/header.php'); ?>

<h3>Files in directory</h3>
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">file name</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (!empty($data)) {
        foreach ($data as $file) {
    ?>
        <tr>
            <td>
                <?= $file ?>
            </td>
        </tr>
    <?php } } else { ?>
        <tr>
            <td>
               В папке нет файлов
            </td>
        </tr>
    <?php  } ?>
    </tbody>
</table>

<?php require('src/Views/partial/footer.php'); ?>

