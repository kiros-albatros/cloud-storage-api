<?php require('src/Views/partial/header.php'); ?>

<h3>Файлы в папке</h3>
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">название файлы</th>
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

