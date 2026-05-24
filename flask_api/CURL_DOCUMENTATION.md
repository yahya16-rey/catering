# Dokumentasi API Recommendation (Flask) - Koleksi HTTP & cURL

Dokumentasi ini memberikan panduan lengkap mengenai cara melakukan request HTTP (menggunakan `cURL`) ke Flask API untuk mendapatkan rekomendasi makanan.

Flask API ini digunakan oleh aplikasi Laravel (`AiRecommendationService`) untuk mencari kesamaan menu berbasis deskripsi (*Content-Based Filtering* menggunakan TF-IDF & Cosine Similarity) dan menerapkan filter tambahan seperti budget dan alergi.

---

## ⚙️ Detail Server & Base URL

Secara default, Flask API berjalan pada port **5000**.
* **Base URL (Lokal):** `http://127.0.0.1:5000` atau `http://localhost:5000`
* **Port default:** `5000`

> [!NOTE]
> Jika Anda menggunakan Docker atau mengubah konfigurasi env Laravel (`FLASK_API_URL`), sesuaikan host dan port pada cURL di bawah ini.

---

## 🛣️ Endpoint Koleksi

### 1. Dapatkan Rekomendasi Menu (`GET /recommend`)
Endpoint ini digunakan untuk mencari rekomendasi makanan berdasarkan kemiripan konten deskripsi menu utama, dengan opsi filter harga (*budget*) dan bahan alergi.

#### Parameter Query

| Parameter | Tipe Data | Wajib | Keterangan | Contoh |
| :--- | :--- | :---: | :--- | :--- |
| `menu` | `string` | **Ya** | Nama menu acuan untuk dicari kemiripannya. | `Nasi Ayam Bakar` |
| `budget` | `number` / `float` | Tidak | Batas harga maksimal menu rekomendasi yang ditampilkan. | `24000` |
| `allergies` | `string` | Tidak | Kata kunci alergen (pisahkan dengan koma). Menu yang mengandung kata ini akan disaring keluar. | `kacang,kelapa` |
| `diet` | `string` | Tidak | *Diterima oleh routing tetapi saat ini belum digunakan dalam logika filter Python.* | `veggie` |

> [!IMPORTANT]
> * **Validasi `menu`:** Jika parameter `menu` kosong atau tidak dikirim, API akan langsung mengembalikan array kosong `[]`.
> * **Aturan Minimum Rekomendasi:** Jika filter (`budget` atau `allergies`) terlalu ketat sehingga menghasilkan kurang dari 3 menu rekomendasi, API akan otomatis mengabaikan sebagian filter tersebut dan mengembalikan **3 menu rekomendasi teratas** dari pencarian dasar agar hasil tidak kosong.
> * **Pencarian Case-Insensitive:** Jika menu acuan tidak ditemukan secara presisi, sistem akan mencoba mencocokkan huruf besar/kecil secara otomatis (*fallback*).

---

## 🚀 Koleksi Request cURL (Lengkap)

Berikut adalah kumpulan *use case* lengkap untuk melakukan pengujian hit API. Disediakan versi **Git Bash/Linux/macOS** (menggunakan single quote `'`) dan **Windows PowerShell** (menggunakan double quote `"` dan escape backtick atau `curl.exe`).

### Kasus A: Pencarian Rekomendasi Standar (Hanya Menu)
Mendapatkan rekomendasi murni berdasarkan kemiripan deskripsi dari menu acuan.

#### 💻 Perintah cURL
* **Bash / Linux / macOS / Git Bash:**
  ```bash
  curl -X GET "http://127.0.0.1:5000/recommend?menu=Nasi%20Ayam%20Bakar"
  ```
* **Windows Command Prompt (cmd):**
  ```cmd
  curl -X GET "http://127.0.0.1:5000/recommend?menu=Nasi%%20Ayam%%20Bakar"
  ```
* **Windows PowerShell:**
  ```powershell
  curl.exe -X GET "http://127.0.0.1:5000/recommend?menu=Nasi%20Ayam%20Bakar"
  ```

#### 📥 Respon JSON yang Diharapkan (`200 OK`)
```json
[
  "Nasi Ayam Geprek",
  "Nasi Ayam Uduk",
  "Nasi Kuning",
  "Nasi Katsu"
]
```

---

### Kasus B: Rekomendasi dengan Filter Budget (Harga Maksimal)
Mencari menu yang mirip dengan "Nasi Kuning" tetapi dengan batas harga maksimal Rp 23.000.

#### 💻 Perintah cURL
* **Bash / Linux / macOS / Git Bash:**
  ```bash
  curl -X GET "http://127.0.0.1:5000/recommend?menu=Nasi%20Kuning&budget=23000"
  ```
* **Windows PowerShell:**
  ```powershell
  curl.exe -X GET "http://127.0.0.1:5000/recommend?menu=Nasi%20Kuning&budget=23000"
  ```

#### 📥 Respon JSON yang Diharapkan (`200 OK`)
```json
[
  "Nasi Ayam Geprek",
  "Nasi Bakar",
  "Nasi Goreng"
]
```
*(Catatan: Menu "Nasi Ayam Bakar" (25k) dan "Nasi Katsu" (25k) disaring keluar karena melebihi budget 23k).*

---

### Kasus C: Rekomendasi dengan Filter Alergen
Mencari kemiripan dengan "Kue Lapis" tetapi mengecualikan menu yang mengandung bahan seperti `kelapa` atau `kacang` (contoh: onde-onde, kelepon).

#### 💻 Perintah cURL
* **Bash / Linux / macOS / Git Bash:**
  ```bash
  curl -X GET "http://127.0.0.1:5000/recommend?menu=Kue%20Lapis&allergies=kelapa,kacang"
  ```
* **Windows PowerShell:**
  ```powershell
  curl.exe -X GET "http://127.0.0.1:5000/recommend?menu=Kue%20Lapis&allergies=kelapa,kacang"
  ```

#### 📥 Respon JSON yang Diharapkan (`200 OK`)
```json
[
  "Kue Putu",
  "Bika Ambon",
  "Serabi",
  "Donat"
]
```
*(Catatan: "Kelepon" (ada kelapa) dan "Onde Onde" (ada kacang hijau) berhasil disaring keluar).*

---

### Kasus D: Kombinasi Lengkap Semua Parameter
Mengirim menu acuan, batas budget, kata kunci alergi, serta parameter diet.

#### 💻 Perintah cURL
* **Bash / Linux / macOS / Git Bash:**
  ```bash
  curl -X GET "http://127.0.0.1:5000/recommend?menu=Nasi%20Ayam%20Geprek&budget=24000&allergies=pedas&diet=normal"
  ```
* **Windows PowerShell:**
  ```powershell
  curl.exe -X GET "http://127.0.0.1:5000/recommend?menu=Nasi%20Ayam%20Geprek&budget=24000&allergies=pedas&diet=normal"
  ```

#### 📥 Respon JSON yang Diharapkan (`200 OK`)
```json
[
  "Nasi Ayam Uduk",
  "Nasi Bakar",
  "Nasi Goreng"
]
```

---

### Kasus E: Request Tidak Valid (Menu Kosong / Tidak Dikirim)
Jika parameter `menu` tidak disertakan di dalam request.

#### 💻 Perintah cURL
* **Bash / Linux / macOS / Git Bash:**
  ```bash
  curl -X GET "http://127.0.0.1:5000/recommend"
  ```
* **Windows PowerShell:**
  ```powershell
  curl.exe -X GET "http://127.0.0.1:5000/recommend"
  ```

#### 📥 Respon JSON yang Diharapkan (`200 OK`)
```json
[]
```

---

### Kasus F: Menu Tidak Ditemukan di Database (Fallback)
Jika nama menu yang dimasukkan tidak cocok dengan data apapun (misalnya typo atau menu baru), API akan memberikan fallback berupa beberapa menu teratas dari dataset utama.

#### 💻 Perintah cURL
* **Bash / Linux / macOS / Git Bash:**
  ```bash
  curl -X GET "http://127.0.0.1:5000/recommend?menu=MakananAnehBinAjaib"
  ```
* **Windows PowerShell:**
  ```powershell
  curl.exe -X GET "http://127.0.0.1:5000/recommend?menu=MakananAnehBinAjaib"
  ```

#### 📥 Respon JSON yang Diharapkan (`200 OK`)
```json
[
  "Kue Lapis",
  "Kelepon",
  "Onde Onde",
  "Kue Putu"
]
```

---

## 🛠️ Cara Menjalankan Flask API Secara Lokal

Sebelum Anda melakukan hit cURL di atas, pastikan Flask API Anda sudah berjalan.

### Opsi A: Menggunakan Python (Lokal Windows/macOS)
1. Buka terminal/command prompt di direktori project `flask_api`:
   ```bash
   cd flask_api
   ```
2. Buat & aktifkan virtual environment (jika belum ada):
   * **Windows (Command Prompt):**
     ```cmd
     python -m venv venv
     venv\Scripts\activate
     ```
   * **Windows (PowerShell):**
     ```powershell
     python -m venv venv
     .\venv\Scripts\Activate.ps1
     ```
   * **macOS / Linux:**
     ```bash
     python3 -m venv venv
     source venv/bin/activate
     ```
3. Install dependensi:
   ```bash
   pip install -r requirements.txt
   ```
4. Jalankan aplikasi:
   ```bash
   python app.py
   ```
   Aplikasi akan mulai mendengarkan request di `http://127.0.0.1:5000`.

### Opsi B: Menggunakan Docker
Jika Anda ingin menjalankannya di dalam container Docker:
1. Build image docker:
   ```bash
   docker build -t catering-recommendation-api ./flask_api
   ```
2. Jalankan container:
   ```bash
   docker run -d -p 5000:5000 --name food-rec catering-recommendation-api
   ```
