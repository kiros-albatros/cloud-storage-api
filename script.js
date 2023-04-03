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
})