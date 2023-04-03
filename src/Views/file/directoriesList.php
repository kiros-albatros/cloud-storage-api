<?php require('src/Views/partial/header.php'); ?>
<h3>Directories List</h3>
<?php if (!empty($data)) {; ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">name</th>
            <th scope="col">mode</th>
            <th scope="col">edit</th>
            <th scope="col">delete</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($data)) {
            foreach ($data as $dir) { ?>
                <tr>
                    <td scope="row"><?= $dir->id; ?></td>
                    <td><a href="<?php echo URLROOT; ?>/directory/<?= $dir->id; ?>"><?= $dir->name; ?></a></td>
                    <td><?php echo $dir->user_owner_id == $_SESSION['user_id'] ?  'read/delete' : 'read';?></td>
                    <?php if($dir->user_owner_id == $_SESSION['user_id']) { ?>
                        <td>
                            <button class="btn edit-dir-btn" data-id="<?php echo $dir->id; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal" style="--bs-icon-link-transform: translate3d(0, -.125rem, 0);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0d6efd" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </button>
                        </td>
                        <td>
                            <a class="btn icon-link icon-link-hover" style="--bs-icon-link-transform: translate3d(0, -.125rem, 0);" href="<?php echo URLROOT; ?>/directory/delete/<?php echo $dir->id; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </a>
                        </td>
                    <?php } ?>
                </tr>
                <?php
            }
        } ?>

        </tbody>
    </table>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <form method="post" id="dirForm" action="<?php echo URLROOT; ?>/directory/">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="mb-3">
                        <label for="1" class="form-label">Directory new name</label>
                        <input type="text" class="form-control" name="directory_name" id="1">
                    </div>
                    <div class="modal-footer" style="border-top: none;">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php } else { ?>
    <h5>No directories</h5>
<?php } ?>

<?php require('src/Views/partial/footer.php'); ?>
