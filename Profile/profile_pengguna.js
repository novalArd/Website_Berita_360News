document.addEventListener('DOMContentLoaded', function () {
    // Data simulasi pengguna
    const user = {
      name: "Agus Permana",
      email: "agus@example.com",
      phone: "08123456789",
      address: "Jakarta, Indonesia",
      gender: "Laki-laki",
    };
  
    // Isi data profil secara dinamis
    document.querySelector('.profile-header h3').textContent = user.name;
    document.querySelector('.profile-header p').textContent = `Email: ${user.email}`;
    document.querySelector('.profile-details').innerHTML = `
      <p><strong>Nomor HP:</strong> ${user.phone}</p>
      <p><strong>Alamat:</strong> ${user.address}</p>
      <p><strong>Gender:</strong> ${user.gender}</p>
    `;
  });
  