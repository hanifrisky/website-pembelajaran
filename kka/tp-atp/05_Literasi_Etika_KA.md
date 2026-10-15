# Dokumen TP-ATP KKA: Elemen 5 (Literasi dan Etika Kecerdasan Artifisial)

## 📌 A. Identitas Elemen
* **Elemen:** Literasi dan Etika Kecerdasan Artifisial (LEKA)
* **Capaian Pembelajaran (CP):** Peserta didik mampu menganalisis dampak sosial, aspek legal, bias, keamanan data, serta isu etika dalam pengembangan dan pemanfaatan sistem Kecerdasan Artifisial.
* **Alokasi Waktu:** 12 JP
* **Sasaran Kelas:** Kelas XII (Semester Ganjil & Genap)
* **Alur Pembelajaran (ATP):** Elemen LEKA diajarkan secara paralel atau disisipkan secara tematik di sela-sela pengerjaan proyek koding sepanjang Kelas 12. Tujuannya adalah untuk menyeimbangkan keterampilan teknis siswa dengan pemahaman moral, hukum, dan keadilan sosial agar aplikasi kecerdasan artifisial yang dikembangkan tidak merugikan pihak manapun.

---

## 🎯 B. Tujuan Pembelajaran (TP) & Kriteria Ketercapaian (KKTP)

### 📌 TP 5.1: Mengidentifikasi potensi bias data dan dampaknya terhadap keadilan sistem AI.
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu menjelaskan definisi bias dalam data latihan AI dan bagaimana bias tersebut dapat terbentuk.
  2. Siswa mampu menganalisis kasus diskriminasi sistematis yang disebabkan oleh model AI yang bias (misalnya bias gender/rasial pada seleksi rekrutmen kerja otomatis).
  3. Siswa mampu merumuskan cara-cara meminimalisasi bias saat mengumpulkan dan menyeimbangkan dataset.
* **Materi Utama:**
  * Bias dalam Algoritma & Dataset.
  * Dampak Sosial Bias AI terhadap Keadilan (*Fairness*).
  * Teknik Mitigasi Bias pada Fase Pengumpulan Data.
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Simulasi Rekrutmen Berbias:** Siswa memainkan game edukasi atau melakukan roleplay yang mensimulasikan sistem AI penyaringan CV yang bias karena data historis masa lalu didominasi laki-laki. Siswa berdiskusi bagaimana cara menyeimbangkan dataset tersebut agar adil untuk pelamar perempuan.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Keaktifan diskusi dan ketajaman argumen siswa dalam forum diskusi terarah (FGD) mengenai kasus bias AI.
  * **Asesmen Sumatif:** Esai kritis menganalisis potensi bias pada proyek Machine Learning sederhana yang sedang mereka kembangkan.

---

### 📌 TP 5.2: Menerapkan prinsip privasi data dan regulasi keamanan informasi dalam penggunaan teknologi cerdas.
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu menerapkan prinsip penyamaran identitas (*data anonymization*) sebelum mempublikasikan atau menggunakan data untuk melatih model AI.
  2. Siswa mampu mengidentifikasi regulasi hukum perlindungan data yang berlaku (seperti UU Pelindungan Data Pribadi / UU PDP di Indonesia).
  3. Siswa mampu merancang arsitektur penyimpanan data yang aman untuk mencegah kebocoran informasi dalam proyek aplikasi.
* **Materi Utama:**
  * Perlindungan Privasi Data (*Data Privacy*).
  * Regulasi Keamanan Informasi (UU PDP Indonesia & GDPR Global).
  * Keamanan Data dalam Pemanfaatan API KA.
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Praktik Anonimisasi Data Pasien:** Siswa diberikan dataset rekam medis fiktif yang mengandung nama lengkap, nomor KTP, alamat, dan diagnosis penyakit. Siswa melakukan teknik penyensoran data pribadi (*masking*) dan enkripsi nama menjadi ID acak sebelum data tersebut diumpankan ke model AI analisis kesehatan.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Lembar evaluasi pemahaman regulasi UU Perlindungan Data Pribadi (UU PDP).
  * **Asesmen Sumatif:** Penilaian terhadap file dataset proyek siswa untuk memastikan telah menerapkan metode anonimisasi data pribadi secara benar.

---

### 📌 TP 5.3: Mengevaluasi dilema etika dari pemanfaatan AI di berbagai bidang industri modern.
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu menganalisis dilema etika pada implementasi kendaraan otonom (*autonomous vehicles*), teknologi pengenalan wajah (*facial recognition*), dan senjata otonom.
  2. Siswa mampu menguraikan dampak AI terhadap pergeseran lapangan kerja dan kesiapan kompetensi tenaga kerja masa depan.
  3. Siswa mampu merancang panduan kode etik (*code of ethics*) sederhana untuk proyek sistem cerdas yang mereka bangun sendiri.
* **Materi Utama:**
  * Dilema Moral AI (Contoh: *The Trolley Problem* pada mobil otonom).
  * Otomasi Industri & Masa Depan Pekerjaan (*Future of Work*).
  * Penyusunan Kode Etik Pengembangan Teknologi (*AI Code of Conduct*).
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Debat Dilema Etika AI:** Siswa dibagi menjadi kelompok Pro dan Kontra untuk memperdebatkan penggunaan teknologi Facial Recognition oleh aparat penegak hukum untuk memantau area publik. Fokus perdebatan adalah keamanan versus kebebasan privasi warga negara.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Penilaian kinerja dan argumentasi logis siswa selama sesi debat etika.
  * **Asesmen Sumatif:** Tugas pembuatan dokumen kode etik (*ethical code guideline*) untuk produk perangkat lunak/AI yang dikembangkan oleh siswa sebagai syarat kelulusan proyek.

---

## 📖 C. Glosarium
* **Bias AI:** Kesalahan sistematis dalam model kecerdasan artifisial yang menghasilkan keputusan yang tidak adil atau diskriminatif terhadap kelompok tertentu.
* **UU PDP (Undang-Undang Pelindungan Data Pribadi):** Regulasi hukum di Indonesia yang mengatur hak-hak pemilik data pribadi dan kewajiban pengelola data untuk menjaga keamanan informasi tersebut.
* **Data Anonymization:** Proses menghapus atau mengubah informasi yang bersifat pribadi dari dataset sehingga individu yang bersangkutan tidak dapat diidentifikasi kembali.
* **Deepfake:** Teknik manipulasi gambar, suara, atau video manusia menggunakan kecerdasan artifisial sehingga terlihat dan terdengar sangat nyata padahal fiktif.
* **Trolley Problem:** Masalah etika klasik tentang pilihan sulit yang harus diambil oleh sistem pengambilan keputusan otomatis dalam situasi darurat yang mengancam nyawa.
