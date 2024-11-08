// Array URL gambar dengan path folder assets
const gambarList = ["assets/elogohalloween.png","assets/cris.png",];
let indexGambar = 0;

// Fungsi untuk mengubah gambar setiap detik
function ubahGambar() {
  indexGambar = (indexGambar + 1) % gambarList.length;
  document.getElementById("gambarlogo").src = gambarList[indexGambar];
  console.log("Gambar berubah menjadi:", gambarList[indexGambar]); // Debug
}

// Set interval untuk mengganti gambar setiap 1 detik (1000ms)
setInterval(ubahGambar, 200);
