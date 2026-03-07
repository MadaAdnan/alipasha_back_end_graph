console.log("hello");
document.addEventListener('open-image-editor', () => {
    setTimeout(() => {
        document.querySelector('[data-image-editor] button')?.click();
    }, 300);
});
