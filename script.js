// var - потому что использовать сборщик и Babel слишком тяжело для 40 строк кода
document.addEventListener("DOMContentLoaded", function (event) {
    var dirEditBtns = document.querySelectorAll('.edit-dir-btn');
    var id = 5;
    var form = document.getElementById("dirForm");
    if (dirEditBtns && form) {
        var formAttr = form.getAttribute("action");
        console.log('formAttr ', formAttr);
        dirEditBtns.forEach((btn)=>btn.addEventListener('click', function () {
            var formAction = `http://cloud-storage.local/directory/`;
            id = this.getAttribute('data-id');
            console.log('id ', id);
            formAction += id;
            form.setAttribute('action', formAction);
            })
        )
    }

    var shareForm = document.querySelector('#shareFileForm');
    var unshareForm = document.querySelector('#unshareFileForm');
    var shareSelect = document.querySelector('.share-select');
    var unshareSelect = document.querySelector('.unshare-select');

    if (shareForm && shareSelect) {
        var shareAction = shareForm.getAttribute('action');
        shareSelect.addEventListener('change', function(){
            var selectValue = shareSelect.options[shareSelect.selectedIndex].value;
            var actionValue = shareAction + selectValue;
            shareForm.setAttribute('action', actionValue);
           // console.log(value);
        })
    }

    if (unshareForm && unshareSelect) {
        var unshareAction = unshareForm.getAttribute('action');
        unshareSelect.addEventListener('change', function(){
            var unselectValue = unshareSelect.options[unshareSelect.selectedIndex].value;
            var actionValue = unshareAction + unselectValue;
            unshareForm.setAttribute('action', actionValue);
            // console.log(value);
        })
    }


})