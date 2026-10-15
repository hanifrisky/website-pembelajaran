# Dokumen TP-ATP KKA: Elemen 3 (Algoritma Pemrograman)

## 📌 A. Identitas Elemen
* **Elemen:** Algoritma Pemrograman (AP)
* **Capaian Pembelajaran (CP):** Peserta didik mampu memahami algoritma dan menerapkan pemrograman berorientasi objek (OOP), menerapkan engineering process, serta mengembangkan aplikasi untuk perangkat IoT atau aplikasi kompleks lainnya.
* **Alokasi Waktu:** 36 JP
* **Sasaran Kelas:** Kelas XI (Semester Genap)
* **Alur Pembelajaran (ATP):** Elemen AP merupakan porsi pembelajaran praktis pemrograman terbesar di Kelas 11. Diajarkan secara mendalam mulai pertengahan kelas 11 hingga akhir semester genap untuk menanamkan pemahaman kuat mengenai paradigma pemrograman modern (OOP), alur kerja pengembangan perangkat lunak terstruktur, hingga pemrograman perangkat fisik (IoT).

---

## 🎯 B. Tujuan Pembelajaran (TP) & Kriteria Ketercapaian (KKTP)

### 📌 TP 3.1: Menerapkan konsep Pemrograman Berorientasi Objek (class, object, inheritance, polymorphism) menggunakan bahasa pemrograman seperti Python atau Java.
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu membedakan paradigma pemrograman prosedural dan pemrograman berorientasi objek.
  2. Siswa mampu membuat rancangan kelas (*class*), menginstansiasi objek (*object*), serta menetapkan atribut (*properties*) dan perilaku (*methods*).
  3. Siswa mampu menerapkan prinsip pewarisan sifat (*inheritance*) dan pengubahan bentuk (*polymorphism*) untuk mengoptimalkan penggunaan ulang kode (*code reusability*).
* **Materi Utama:**
  * Dasar Pemrograman Berorientasi Objek (OOP).
  * Kelas (*Class*), Objek (*Object*), Konstruktor, Atribut, dan Method.
  * Pilar OOP: *Encapsulation*, *Inheritance*, dan *Polymorphism*.
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Simulasi Karakter Game (RPG):** Siswa membuat kelas dasar `Karakter` dengan atribut `nama` dan `darah`. Kemudian membuat kelas turunan seperti `Ksatria` (dengan method `serang()`) dan `Penyihir` (dengan method `sihir()`) yang mewarisi kelas `Karakter` menggunakan Python.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Praktik langsung menulis kode (*live coding*) pembuatan kelas sederhana dan evaluasi bug struktural OOP.
  * **Asesmen Sumatif:** Ujian praktik membuat diagram kelas (Class Diagram UML) dan mengimplementasikannya menjadi program OOP fungsional.

---

### 📌 TP 3.2: Mempraktikkan tahapan software engineering process mulai dari analisis kebutuhan hingga pengujian program.
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu menyusun dokumen kebutuhan sistem sederhana (*Software Requirements Specification* - SRS) berdasarkan masalah pengguna.
  2. Siswa mampu merancang arsitektur sistem dan membuat prototipe desain antarmuka pengguna (UI/UX).
  3. Siswa mampu melakukan pengujian fungsionalitas program menggunakan metode pengujian sistem (seperti *Black Box Testing* atau *Unit Testing*).
* **Materi Utama:**
  * Siklus Hidup Pengembangan Perangkat Lunak (*Software Development Life Cycle* - SDLC).
  * Analisis Kebutuhan Pengguna & Penyusunan Dokumen Spesifikasi.
  * Pengujian Perangkat Lunak: Unit Testing & Black Box Testing.
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Mini-Project SDLC:** Siswa dalam tim kecil memilih satu ide aplikasi (misal: Sistem Reservasi Kantin). Mereka membuat analisis kebutuhan singkat, menulis kode program, dan membuat dokumen tabel skenario pengujian (*test case table*) untuk memastikan tidak ada fitur yang eror (*crash*).
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Review kelengkapan dokumen rencana pengujian program (*test plan*).
  * **Asesmen Sumatif:** Portofolio proyek pengembangan perangkat lunak yang dinilai dari fase analisis, kode program, hingga laporan hasil uji coba (*test report*).

---

### 📌 TP 3.3: Mengembangkan proyek aplikasi berbasis IoT (Internet of Things) atau aplikasi desktop/web kompleks yang menyelesaikan masalah riil.
* **KKTP / Indikator Keberhasilan:**
  1. Siswa mampu menghubungkan mikrokontroler (seperti ESP32/ESP8266) dengan sensor fisik (suhu, kelembapan, gerak) dan aktuator (LED, Relay, LCD).
  2. Siswa mampu memprogram mikrokontroler agar dapat mengirimkan data sensor ke platform IoT cloud (seperti Blynk, Thingspeak, atau broker MQTT).
  3. Siswa mampu membangun antarmuka monitoring web atau aplikasi desktop yang terintegrasi untuk menyelesaikan masalah nyata di lingkungan sekolah/rumah.
* **Materi Utama:**
  * Arsitektur Internet of Things (Sensor, Aktuator, Gateway, Cloud Platform).
  * Pemrograman Mikrokontroler (Arduino IDE dengan C++ atau MicroPython).
  * Integrasi API Cloud IoT dan Pembangunan Dashboard Monitoring.
* **Aktivitas Pembelajaran:**
  * **Aktivitas - Sistem Penyiraman Tanaman Otomatis (Smart Garden):** Siswa merakit ESP32 yang terhubung dengan sensor kelembapan tanah (*soil moisture sensor*) dan pompa air mini. Siswa memprogram ESP32 untuk menyalakan pompa saat tanah kering, serta mengirim notifikasi status penyiraman ke aplikasi ponsel siswa secara real-time.
* **Rencana Asesmen:**
  * **Asesmen Formatif:** Uji coba sirkuit kelistrikan dan fungsionalitas sensor di laboratorium komputer.
  * **Asesmen Sumatif:** Pameran (*showcase*) proyek IoT kelompok yang dinilai dari kegunaan solusi, kestabilan alat, estetika perakitan, dan kualitas presentasi kelompok.

---

## 📖 C. Glosarium
* **Pemrograman Berorientasi Objek (OOP):** Paradigma pemrograman yang berorientasi pada konsep "objek", yang dapat berisi data (atribut) dan kode (method).
* **Inheritance (Pewarisan):** Konsep di mana suatu kelas dapat mewarisi data dan method dari kelas lain (super-class/parent).
* **Polymorphism:** Kemampuan suatu objek untuk memiliki banyak bentuk, misalnya mendefinisikan ulang method dari super-class di sub-class (method overriding).
* **Software Engineering Process:** Pendekatan sistematis dan disiplin dalam pengembangan, operasi, dan pemeliharaan perangkat lunak.
* **Internet of Things (IoT):** Jaringan perangkat fisik yang tertanam dengan sensor, perangkat lunak, dan teknologi lain untuk menghubungkan dan bertukar data dengan perangkat lain melalui internet.
