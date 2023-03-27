<?php require('src/Views/partial/header.php'); ?>
<h3>Users List</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">email</th>
        </tr>
    </thead>
    <tbody>

        <?php
        if (isset($data['users'])) {
            foreach ($data['users'] as $user) { ?>
                <tr>
                    <td scope="row"><?= $user->id; ?></td>
                    <td><?= $user->email; ?></td>
                </tr>
        <?php
            }
        } ?>

    </tbody>
</table>
<?php require('src/Views/partial/footer.php'); ?>