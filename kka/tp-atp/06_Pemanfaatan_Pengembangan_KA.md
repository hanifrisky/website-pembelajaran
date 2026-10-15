# Dokumen TP-ATP KKA: Elemen 6 (Pemanfaatan dan Pengembangan Kecerdasan Artifisial)

## 📌 A. Identitas Elemen
* **Elemen:** Pemanfaatan dan Pengembangan Kecerdasan Artifisial
* **Capaian Pembelajaran (CP):** Peserta didik mampu memahami dasar-dasar algoritma Machine Learning, menguasai konsep Natural Language Processing (NLP), mengembangkan model KA sederhana, serta membangun aplikasi dengan memanfaatkan model KA yang sudah ada.
* **Alokasi Waktu:** 36 JP
* **Sasaran Kelas:** Kelas XII (Semester Genap)
* **Alur Pembelajaran (ATP):** Elemen ini diletakkan di semester akhir Kelas 12 sebagai puncak kurikulum mata pelajaran Koding dan Kecerdasan Artifisial (KKA). Semua kemampuan berpikir komputasional, coding OOP/IoT, analisis data, dan kesadaran etika disatukan dalam pengerjaan proyek akhir (*capstone project*) berupa aplikasi cerdas yang bermanfaat bagi masyarakat.

---

## 🎯 B. Tujuan Pembelajaran (TP) & Kriteria Ketercapaian (KKTP)

### 📌 TP 6.1: Menjelaskan alur kerja dasar Machine Learning (Supervised dan Unsupervised Learning).
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu membedakan konsep dasar antara Supervised Learning (klasifikasi & regresi) dan Unsupervised Learning (clustering).
  2. Siswa mampu mendeskripsikan langkah-langkah alur kerja (*pipeline*) pengembangan model Machine Learning mulai dari pengumpulan data, pembagian dataset (train/test split), pelatihan model, hingga evaluasi akurasi.
  3. Siswa mampu membaca metrik evaluasi model sederhana seperti akurasi (*accuracy score*), presisi, dan *recall*.
* **Materi Utama:**
  * Pengenalan Machine Learning & Perbedaannya dengan Pemrograman Tradisional.
  * Supervised Learning vs Unsupervised Learning.
  * Pipeline Pembelajaran Machine Learning.
  * Metrik Evaluasi Model (Confusion Matrix, R-Squared).
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Klasifikasi Bunga Iris (Scikit-Learn):** Siswa menggunakan Google Colab untuk melatih model klasifikasi jenis bunga Iris menggunakan algoritma K-Nearest Neighbors (KNN) atau Decision Tree. Siswa membagi data menjadi 80% pelatihan dan 20% pengujian, lalu mengukur akurasinya.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Kuis interaktif mengenai konsep dasar supervised/unsupervised learning dan tahapan training model.
  * **Asesmen Sumatif:** Laporan analisis performa model regresi linier dalam memprediksi harga rumah berdasarkan luas bangunan.

---

### 📌 TP 6.2: Mengimplementasikan konsep dasar NLP atau Computer Vision sederhana menggunakan platform no-code/low-code maupun koding.
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu memproses data teks (NLP) melalui tahapan tokenisasi, filtering, dan stemming menggunakan library pemrograman (seperti NLTK atau Sastrawi).
  2. Siswa mampu memproses data gambar (Computer Vision) untuk deteksi objek atau klasifikasi citra sederhana.
  3. Siswa mampu memanfaatkan platform low-code/no-code (seperti Teachable Machine, Orange, atau Node-RED) untuk melatih dan mengekspor model visual/audio.
* **Materi Utama:**
  * Dasar Natural Language Processing (NLP): Text Preprocessing & Sentiment Analysis.
  * Dasar Computer Vision: Image Classification & Object Detection.
  * Pemodelan Cepat (*Fast Prototyping*) dengan Platform Low-Code/No-Code.
* **Aktivitas Pembelajaran:**
  * **Aktivitas 1 - Deteksi Masker Wajah (Computer Vision):** Siswa menggunakan platform Teachable Machine Google untuk melatih model klasifikasi gambar yang dapat mendeteksi apakah seseorang memakai masker atau tidak menggunakan webcam laptop mereka.
  * **Aktivitas 2 - Analisis Sentimen Ulasan Aplikasi (NLP):** Siswa memproses ulasan aplikasi di Google Play Store dengan python untuk mengelompokkan ulasan menjadi kategori positif atau negatif.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Praktik terbimbing mengekspor model klasifikasi dari Teachable Machine dan mengintegrasikannya dengan kode JavaScript/Python.
  * **Asesmen Sumatif:** Tugas pembuatan aplikasi klasifikasi penyakit daun tanaman berbasis gambar tanaman sehat vs terinfeksi.

---

### 📌 TP 6.3: Membangun aplikasi inovatif yang mengintegrasikan API model KA eksternal untuk memecahkan persoalan masyarakat.
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu mengintegrasikan layanan API model KA eksternal (seperti OpenAI API, Gemini API, atau Google Cloud Vision API) ke dalam aplikasi buatan sendiri.
  2. Siswa mampu merancang arsitektur aplikasi (web/mobile/desktop) yang responsif dan memiliki antarmuka pengguna yang ramah (*user-friendly*).
  3. Siswa mampu memecahkan masalah riil masyarakat dengan aplikasi cerdas yang dibangun (misal: chatbot tanya-jawab UMKM, translator bahasa daerah, atau asisten belajar ramah difabel).
* **Materi Utama:**
  * Arsitektur Aplikasi Berbasis Layanan Cloud AI.
  * Integrasi API Web (HTTP Request, JSON Parsing, API Keys Security).
  * Pengembangan Proyek Akhir (*Capstone Project*) Aplikasi KA Inovatif.
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Capstone Project (Aplikasi Bantuan UMKM):** Siswa bekerja dalam tim membuat aplikasi chatbot WhatsApp menggunakan Node.js/Python yang terintegrasi dengan Gemini API untuk menjawab otomatis pertanyaan pelanggan UMKM lokal seputar produk, jam buka, dan harga secara cerdas.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Bimbingan mingguan (*weekly progress check*) dan presentasi progres pengembangan fitur aplikasi kelompok.
  * **Asesmen Sumatif:** Uji publik aplikasi dan sidang proyek akhir di hadapan guru penguji serta perwakilan mitra industri, dinilai berdasarkan fungsionalitas, kegunaan sosial, orisinalitas, dan kualitas dokumentasi program.

---

## 📖 C. Glosarium
* **Machine Learning (Pembelajaran Mesin):** Cabang kecerdasan artifisial yang berfokus pada pengembangan sistem yang dapat belajar dari data untuk meningkatkan kinerjanya tanpa diprogram secara manual.
* **Natural Language Processing (NLP):** Bidang AI yang berfokus pada interaksi antara komputer dan bahasa manusia agar komputer mampu memahami teks dan ucapan.
* **Computer Vision:** Bidang AI yang memungkinkan komputer melihat dan mengidentifikasi objek, lalu memproses gambar digital seperti manusia.
* **API (Application Programming Interface):** Penghubung yang memungkinkan suatu aplikasi berinteraksi dan memanfaatkan fungsi dari aplikasi atau model kecerdasan artifisial luar secara terprogram.
* **Teachable Machine:** Platform berbasis web no-code yang ramah pemula untuk melatih model Machine Learning klasifikasi gambar, suara, dan gerakan tubuh.
