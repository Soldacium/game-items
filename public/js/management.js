// image preview
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteConfirmModal');
    const deleteForm = document.getElementById('deleteItemForm');
    const closeButtons = modal.querySelectorAll('.modal-close');
    
    function showModal() {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    
    function hideModal() {
        modal.classList.remove('show');
        document.body.style.overflow = ''; 
    }
    
    closeButtons.forEach(button => {
        button.addEventListener('click', hideModal);
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            hideModal();
        }
    });

    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', () => {
            const itemId = button.dataset.itemId;
            deleteForm.action = `/management/items/delete/${itemId}`;
            showModal();
        });
    });

    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const fileInputTrigger = document.querySelector('.file-input-trigger');
    const fileName = document.querySelector('.file-name');

    if (imageInput && imagePreview) {
        fileInputTrigger?.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileInputTrigger.classList.add('has-file');
        });

        fileInputTrigger?.addEventListener('dragleave', (e) => {
            e.preventDefault();
            if (!imageInput.files.length) {
                fileInputTrigger.classList.remove('has-file');
            }
        });

        fileInputTrigger?.addEventListener('drop', (e) => {
            e.preventDefault();
            if (e.dataTransfer.files.length) {
                imageInput.files = e.dataTransfer.files;
                handleFileSelect(e.dataTransfer.files[0]);
            }
        });

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            handleFileSelect(file);
        });

        function handleFileSelect(file) {
            if (file) {
                fileName.textContent = file.name;
                fileInputTrigger.classList.add('has-file');

                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.add('show');
                }
                reader.readAsDataURL(file);
            } else {
                fileName.textContent = '';
                fileInputTrigger.classList.remove('has-file');
                imagePreview.src = '#';
                imagePreview.classList.remove('show');
            }
        }
    }
}); 