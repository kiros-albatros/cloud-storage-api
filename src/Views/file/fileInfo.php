<?php require('src/Views/partial/header.php'); ?>
<h3>File info</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">directory</th>
            <th scope="col">name</th>
            <th scope="col">access for other users</th>
        </tr>
        </thead>
        <tbody>
                <tr>
                    <td scope="row"><?= $data['file']->id; ?></td>
                    <td>
                        <?= $data['file']->directory; ?>
                    </td>
                    <td><?= $data['file']->name; ?></td>
                    <td>
                        <?php
                        foreach ($data['users'] as $user) {
                            echo $user->name . ' ';
                        }
                        ?>
                    </td>
                </tr>
        </tbody>
    </table>


<?php require('src/Views/partial/footer.php'); ?>
