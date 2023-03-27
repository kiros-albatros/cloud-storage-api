<?php require('src/Views/partial/header.php'); ?>
<h3>User Info</h3>
<?php
if (!empty($data)) { ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <?php
            if (isset($data)) {
                foreach ($data as $key => $value) { ?>
                    <th scope="row"><?= $key; ?></th>
                <?php }
            } ?>
        </tr>
        </thead>

        <tbody>
        <tr>
            <?php
            if (isset($data)) {
                foreach ($data as $key => $value) { ?>
                    <td scope="row"><?= $value; ?></td>
                <?php }
            } ?>
        </tbody>
        </tbody>
    </table>
    <p><a href="<?php echo URLROOT; ?>/user/reset_password">
            Reset password
        </a></p>
<?php } ?>

<?php require('src/Views/partial/footer.php'); ?>
