function updateSections(target) {
    const sectionSelect = target.closest('tr').querySelector('.select-section');
    const categoryContainer = target.closest('tr').querySelector('.kategori-container');
    const selectedPage = target.value;

    // Clear existing options
    sectionSelect.innerHTML = '<option value="" disabled selected>Pilih Section</option>';

    // Populate options based on the selected page
    let sections = [];
    switch (selectedPage) {
        case 'Beranda':
            sections = ['konten-1', 'konten-2', 'konten-editor-pick', 'story-war', 'semua-class'];
            break;
        case 'Bisnis':
            sections = ['konten-1', 'konten-2', 'pilihan-untukmu', 'konten-editor-pick', 'sorotan-class'];
            break;
        case 'Keuangan':
            sections = ['konten-1', 'konten-2','pilihan-untukmu', 'konten-editor-pick', 'sorotan-class'];
            break;
        case 'Olahraga':
            sections = ['konten-1', 'konten-2','pilihan-untukmu', 'konten-editor-pick', 'sorotan-class'];
            break;
        case 'Internasional':
            sections = ['konten-1', 'konten-2','pilihan-untukmu', 'konten-editor-pick', 'sorotan-class'];
            break;
    }

    // Add options to the section select
    sections.forEach(section => {
        const option = document.createElement('option');
        option.value = section;
        option.textContent = section;
        sectionSelect.appendChild(option);
    });

    // Show or hide the category container based on the section
    if (sections.includes('semua-class')) {
        categoryContainer.style.display = 'block';
    } else {
        categoryContainer.style.display = 'none';
    }
}
