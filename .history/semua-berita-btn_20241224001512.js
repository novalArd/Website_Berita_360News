document.addEventListener("DOMContentLoaded", () => {
    const button = document.getElementById("semua-berita-btn");
    const kategoriLainnya = document.querySelectorAll(".kategori-lainnya");

    button.addEventListener("click", () => {
        kategoriLainnya.forEach(kategori => {
            kategori.style.display = "grid"; // Menampilkan semua kategori
        });
        button.style.display = "none"; // Sembunyikan tombol setelah ditekan
    });
});
