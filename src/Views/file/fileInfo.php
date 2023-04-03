<?php require('src/Views/partial/header.php'); ?>
<h3>Информация о файле</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">папка</th>
            <th scope="col">название</th>
            <th scope="col">доступ других пользователей</th>
            <th>поделиться</th>
            <th>прекратить доступ</th>
            <th>загрузить</th>
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
                         //   var_dump($user);
                            if (!empty($user->user_email)) {
                                $email = $user->user_email;
                                echo($email . "<br>");
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <a href="" class="btn shareBtn" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-share" viewBox="0 0 16 16">
                                <path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
                            </svg>
                        </a>
                    </td>
                    <td>
                        <a href="" class="btn unshareBtn" data-bs-toggle="modal" data-bs-target="#exampleModal3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sign-stop" viewBox="0 0 16 16">
                                <path d="M3.16 10.08c-.931 0-1.447-.493-1.494-1.132h.653c.065.346.396.583.891.583.524 0 .83-.246.83-.62 0-.303-.203-.467-.637-.572l-.656-.164c-.61-.147-.978-.51-.978-1.078 0-.706.597-1.184 1.444-1.184.853 0 1.386.475 1.436 1.087h-.645c-.064-.32-.352-.542-.797-.542-.472 0-.77.246-.77.6 0 .261.196.437.553.522l.654.161c.673.164 1.06.487 1.06 1.11 0 .736-.574 1.228-1.544 1.228Zm3.427-3.51V10h-.665V6.57H4.753V6h3.006v.568H6.587Z"/>
                                <path fill-rule="evenodd" d="M11.045 7.73v.544c0 1.131-.636 1.805-1.661 1.805-1.026 0-1.664-.674-1.664-1.805V7.73c0-1.136.638-1.807 1.664-1.807 1.025 0 1.66.674 1.66 1.807Zm-.674.547v-.553c0-.827-.422-1.234-.987-1.234-.572 0-.99.407-.99 1.234v.553c0 .83.418 1.237.99 1.237.565 0 .987-.408.987-1.237Zm1.15-2.276h1.535c.82 0 1.316.55 1.316 1.292 0 .747-.501 1.289-1.321 1.289h-.865V10h-.665V6.001Zm1.436 2.036c.463 0 .735-.272.735-.744s-.272-.741-.735-.741h-.774v1.485h.774Z"/>
                                <path fill-rule="evenodd" d="M4.893 0a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146A.5.5 0 0 0 11.107 0H4.893ZM1 5.1 5.1 1h5.8L15 5.1v5.8L10.9 15H5.1L1 10.9V5.1Z"/>
                            </svg>
                        </a>
                    </td>
                    <td>
                        <a class="btn" href="<?= URLROOT . '/uploads/' . $data['file']->directory . '/' . $data['file']->name; ?>" download>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-down" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M7.646 10.854a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 9.293V5.5a.5.5 0 0 0-1 0v3.793L6.354 8.146a.5.5 0 1 0-.708.708l2 2z"/>
                                <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
        </tbody>
    </table>

<!-- Modal -->
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <h5 class="mb-3">Выберите пользователя, с которым хотите поделиться файлом</h5>
            <form method="get" id="shareFileForm" action="<?php echo URLROOT; ?>/files/share/<?= $data['file']->id; ?>/">
                <select class="form-select share-select" aria-label="Default select example">
                    <option selected>Users</option>
                   <?php
                   foreach ($data['allUsers'] as $user) {   ?>
                       <option value="<?=$user->id; ?>"><?=$user->email; ?></option>
                   <?php     }
                   ?>
                </select>
                <div class="modal-footer" style="border-top: none;">
                    <button type="submit" class="btn btn-primary">Принять</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <h5 class="mb-3">Выберите пользователя, которому хотите прекратить доступ к файлу</h5>
            <form method="get" id="unshareFileForm" action="<?php echo URLROOT; ?>/files/unshare/<?= $data['file']->id; ?>/">
                <select class="form-select unshare-select" aria-label="Default select example">
                    <option selected>Users</option>
                    <?php
                    foreach ($data['allUsers'] as $user) {   ?>
                        <option value="<?=$user->id; ?>"><?=$user->email; ?></option>
                    <?php     }
                    ?>
                </select>
                <div class="modal-footer" style="border-top: none;">
                    <button type="submit" class="btn btn-primary">Принять</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require('src/Views/partial/footer.php'); ?>
