# Buat semua file HTML lengkap proyek AntamCuan

files = {
    "index.html": """<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AntamCuan - Login</title>
  <style>
    body { font-family: Arial, sans-serif; text-align: center; background: #fff8e1; padding: 50px; }
    h1 { color: #d4af37; }
    form { background: white; display: inline-block; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    input { margin: 10px 0; padding: 10px; width: 90%; border: 1px solid #ccc; border-radius: 5px; }
    button { padding: 10px 20px; background: #d4af37; color: white; border: none; border-radius: 5px; cursor: pointer; }
    a { display: block; margin-top: 15px; color: #555; }
  </style>
</head>
<body>
  <h1>Selamat Datang di AntamCuan</h1>
  <form action="dashboard.html">
    <input type="text" placeholder="Username" required><br>
    <input type="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
    <a href="#">Belum punya akun? Daftar</a>
  </form>
</body>
</html>
""",
    "dashboard.html": dashboard_html,  # dari sebelumnya
    "about.html": """<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tentang Kami - AntamCuan</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #fffaf0; color: #333; }
    h1 { color: #d4af37; }
    p { line-height: 1.6em; }
    a { display: inline-block; margin-top: 20px; color: #d4af37; text-decoration: none; }
  </style>
</head>
<body>
  <h1>Tentang PT Antam</h1>
  <p>PT Antam, atau PT Aneka Tambang Tbk, didirikan pada tahun 1968 sebagai Badan Usaha Milik Negara (BUMN). Perusahaan ini dibentuk melalui penggabungan beberapa perusahaan tambang milik pemerintah, termasuk perusahaan yang memproduksi komoditas tunggal menurut PT ANTAM Tbk.</p>
  <a href="dashboard.html">← Kembali ke Dashboard</a>
</body>
</html>
""",
    "produk.html": """<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Produk - AntamCuan</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; color: #333; }
    h1 { color: #d4af37; }
    .paket { margin: 20px 0; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 6px rgba(0,0,0,0.1); }
    .paket h2 { margin: 0 0 10px; color: #444; }
    .wa-btn {
      display: inline-block;
      background-color: #25D366;
      color: white;
      padding: 10px 15px;
      margin-top: 10px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }
    a.kembali {
      display: inline-block;
      margin-top: 30px;
      color: #555;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <h1>Daftar Produk Investasi</h1>

  <div class="paket">
    <h2>Paket Rp 100.000</h2>
    <p>Keuntungan harian: Rp 12.000<br/>Siklus: 25 hari</p>
    <a class="wa-btn" href="https://wa.me/6281234567890?text=Halo%2C%20saya%20ingin%20pesan%20Paket%20100rb%20AntamCuan" target="_blank">Pesan via WhatsApp</a>
  </div>

  <div class="paket">
    <h2>Paket Rp 200.000</h2>
    <p>Keuntungan harian: Rp 25.000<br/>Siklus: 25 hari</p>
    <a class="wa-btn" href="https://wa.me/6281234567890?text=Halo%2C%20saya%20ingin%20pesan%20Paket%20200rb%20AntamCuan" target="_blank">Pesan via WhatsApp</a>
  </div>

  <div class="paket">
    <h2>Paket Rp 350.000</h2>
    <p>Keuntungan harian: Rp 33.000<br/>Siklus: 25 hari</p>
    <a class="wa-btn" href="https://wa.me/6281234567890?text=Halo%2C%20saya%20ingin%20pesan%20Paket%20350rb%20AntamCuan" target="_blank">Pesan via WhatsApp</a>
  </div>

  <a class="kembali" href="dashboard.html">← Kembali ke Dashboard</a>
</body>
</html>
""",
    "README.md": """# 💰 AntamCuan - Website Investasi Emas Antam

Selamat datang di **AntamCuan**, platform sederhana dan elegan untuk menampilkan produk investasi emas dari merek terpercaya **Antam**. Website ini dirancang dengan desain elegan emas-putih, dan cocok digunakan sebagai landing page atau presentasi digital usaha jual-beli emas.

---

## 🏆 Fitur

- ✅ Daftar produk emas Antam (1g, 5g, 10g)
- ✅ Desain warna emas-putih yang elegan
- ✅ Form **Login** dan **Pendaftaran akun**
- ✅ Kontak WhatsApp langsung
- 🚀 Online dengan GitHub Pages

---

## 🌐 Demo Langsung

📍 [https://emas-antam.github.io](https://emas-antam.github.io)

---

## 📞 Kontak

📱 WhatsApp: [Klik untuk Chat](https://wa.me/6283129369097)
"""
}

# Simpan ke folder dan buat ZIP
folder = "/mnt/data/antamcuan-full"
os.makedirs(folder, exist_ok=True)

for filename, content in files.items():
    with open(os.path.join(folder, filename), "w", encoding="utf-8") as f:
        f.write(content)

zip_path = "/mnt/data/antamcuan-website.zip"
with ZipFile(zip_path, "w") as zipf:
    for filename in files.keys():
        zipf.write(os.path.join(folder, filename), arcname=filename)

zip_path

