console.log("hello");
document.addEventListener('open-image-editor', () => {
    setTimeout(() => {

        const editBtn = document.querySelector('.filepond--action-edit-item');

        if (editBtn) {
            editBtn.click();
        }

    }, 400);
});
