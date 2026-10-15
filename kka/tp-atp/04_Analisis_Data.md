# Dokumen TP-ATP KKA: Elemen 4 (Analisis Data)

## 📌 A. Identitas Elemen
* **Elemen:** Analisis Data (AD)
* **Capaian Pembelajaran (CP):** Peserta didik mampu melakukan pemrosesan data tingkat lanjut, visualisasi data, serta mengekstrak informasi berharga dari sekumpulan dataset besar untuk keperluan pemodelan.
* **Alokasi Waktu:** 18 JP
* **Sasaran Kelas:** Kelas XII (Semester Ganjil - Awal Tahun Ajaran)
* **Alur Pembelajaran (ATP):** Elemen AD dipelajari pada awal Kelas 12 semester ganjil. Materi ini berfungsi sebagai jembatan penting yang menghubungkan logika pemrograman (Kelas 11) dengan kecerdasan artifisial khususnya Machine Learning, karena model Machine Learning yang baik membutuhkan kualitas data yang bersih dan representatif.

---

## 🎯 B. Tujuan Pembelajaran (TP) & Kriteria Ketercapaian (KKTP)

### 📌 TP 4.1: Melakukan data cleaning dan pra-pemrosesan pada dataset mentah menggunakan library analisis data (misal: Pandas).
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu memuat (*load*) data dari berbagai format file (CSV, JSON, Excel) ke dalam struktur data pemrograman (Dataframe Pandas).
  2. Siswa mampu mendeteksi dan menangani data yang hilang (*missing values*), data duplikat, dan data yang tidak konsisten.
  3. Siswa mampu melakukan transformasi data (mengubah tipe data, melakukan normalisasi, atau melakukan encoding variabel kategorikal).
* **Materi Utama:**
  * Pengenalan Ekosistem Analisis Data Python (Jupyter Notebook, Anaconda, Pandas, NumPy).
  * Teknik Memuat & Eksplorasi Awal Dataset.
  * *Data Cleaning*: Penanganan Missing Values, Duplikasi, dan Outlier.
  * Transformasi & Pra-Pemrosesan Data untuk Permodelan.
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Pembersihan Data Kunjungan Perpustakaan:** Siswa diberikan dataset mentah kunjungan perpustakaan sekolah yang berantakan (ada baris kosong, format tanggal tidak seragam, penulisan nama kelas yang salah). Siswa menulis kode Python menggunakan Pandas untuk membersihkan data tersebut hingga siap dianalisis.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Pengamatan aktivitas lab pemrograman saat mengolah baris dan kolom dataframe Pandas.
  * **Asesmen Sumatif:** Ujian praktik pemrograman mandiri membersihkan dataset pariwisata daerah yang berantakan dalam waktu yang ditentukan.

---

### 📌 TP 4.2: Membuat visualisasi data yang interaktif untuk menemukan pola tersembunyi (data insights).
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu memilih jenis grafik yang tepat (bar chart, line chart, scatter plot, histogram) sesuai dengan karakteristik dan dimensi data yang akan divisualisasikan.
  2. Siswa mampu membuat grafik statis maupun interaktif menggunakan library visualisasi data (seperti Matplotlib, Seaborn, atau Plotly).
  3. Siswa mampu memetakan hubungan korelasi antar variabel melalui visualisasi grafis (misalnya menggunakan heatmap korelasi).
* **Materi Utama:**
  * Teori Visualisasi Data & Desain Informasi.
  * Visualisasi dengan Matplotlib & Seaborn (Statis).
  * Visualisasi Interaktif dengan Plotly / Streamlit.
  * Analisis Korelasi Visual (Scatter Plot, Heatmap).
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Dashboard Visualisasi Tren Penjualan Kantin:** Siswa membuat beberapa visualisasi grafik interaktif untuk menunjukkan menu makanan terlaris per hari, jam-jam puncak kunjungan, serta hubungan antara suhu cuaca dengan penjualan minuman dingin menggunakan Matplotlib/Seaborn.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Review desain grafik dan estetika visualisasi data yang dirancang siswa.
  * **Asesmen Sumatif:** Pembuatan mini-dashboard visualisasi data interaktif menggunakan data kecelakaan lalu lintas atau data prestasi sekolah.

---

### 📌 TP 4.3: Menarik kesimpulan berbasis data untuk mendukung pengambilan keputusan.
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu membaca dan menafsirkan arti statistik deskriptif dasar (rata-rata, median, standar deviasi) dari data.
  2. Siswa mampu merumuskan hipotesis sederhana dan membuktikannya melalui analisis data eksploratif.
  3. Siswa mampu menuliskan laporan rekomendasi keputusan bisnis atau sosial yang didukung oleh bukti-bukti data (*data-driven decision making*).
* **Materi Utama:**
  * Statistik Deskriptif untuk Analisis Data.
  * Teknik Analisis Data Eksploratif (*Exploratory Data Analysis* - EDA).
  * Penyusunan Laporan Rekomendasi Keputusan Berbasis Data.
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Konsultan Data Sekolah:** Siswa bertindak sebagai konsultan data untuk kepala sekolah. Menganalisis korelasi nilai rapor siswa dengan jam belajar mandiri dan keikutsertaan bimbingan belajar, kemudian mempresentasikan rekomendasi kebijakan jam tambahan sekolah berbasis data tersebut.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Diskusi kelompok membahas temuan menarik (*insights*) dari suatu studi kasus dataset e-commerce.
  * **Asesmen Sumatif:** Laporan tertulis akhir proyek EDA lengkap dengan visualisasi dan rekomendasi keputusan taktis yang diajukan.

---

## 📖 C. Glosarium
* **Data Cleaning (Pembersihan Data):** Proses mendeteksi, memperbaiki, atau menghapus catatan data yang salah, rusak, atau tidak akurat dari kumpulan data.
* **Dataframe:** Struktur data 2 dimensi berbentuk tabel (terdiri dari baris dan kolom) yang digunakan dalam pemrograman analisis data Pandas.
* **Exploratory Data Analysis (EDA):** Pendekatan analisis kumpulan data untuk merangkum karakteristik utama mereka, seringkali dengan metode visual.
* **Data Insight:** Pemahaman mendalam dan berharga yang diperoleh dari hasil analisis data untuk memecahkan suatu masalah.
* **Missing Values:** Keadaan di mana data pada atribut tertentu kosong atau tidak bernilai dalam dataset.
