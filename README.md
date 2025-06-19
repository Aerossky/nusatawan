# 🌴 Nusatawan - Sistem Informasi Wisata Berbasis Cuaca

<div align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/OpenWeatherMap-FF6B35?style=for-the-badge&logo=openweathermap&logoColor=white" alt="OpenWeatherMap">
</div>

<div align="center">
  <h4>🇮🇩 Platform digital yang membantu wisatawan merencanakan perjalanan dengan informasi destinasi wisata Indonesia terintegrasi data cuaca real-time</h4>
</div>

---

## 📖 Tentang Proyek

**Nusatawan** adalah sistem informasi berbasis web yang dirancang khusus untuk membantu wisatawan dalam merencanakan perjalanan mereka di Indonesia. Platform ini mengintegrasikan informasi destinasi wisata dengan data cuaca real-time, memungkinkan wisatawan membuat keputusan yang lebih baik dalam perencanaan liburan mereka.

### 🎯 Mengapa Nusatawan?

Berdasarkan data BPS, jumlah perjalanan wisatawan domestik terus meningkat setiap tahun. Namun, banyak wisatawan yang mengalami kendala akibat cuaca yang tidak menentu. Nusatawan hadir sebagai solusi untuk mengatasi masalah tersebut dengan menyediakan informasi cuaca yang akurat dan terkini untuk setiap destinasi wisata.

## ✨ Fitur Utama

- 🏖️ **Database Destinasi Lengkap** - Informasi komprehensif tentang tempat wisata di seluruh Indonesia
- 🌤️ **Prakiraan Cuaca Real-time** - Data cuaca 5 hari ke depan dengan interval 3 jam
- 📱 **Responsif & User-Friendly** - Desain yang mudah diakses di semua perangkat
- 🔍 **Pencarian Cerdas** - Cari destinasi berdasarkan lokasi, kategori, atau nama
- 📍 **Integrasi Peta** - Lokasi destinasi yang akurat dan mudah diakses
- 🎨 **Interface Modern** - Desain yang menarik dan intuitif

## 🏗️ Arsitektur Aplikasi

Proyek ini menggunakan **Service Pattern** sebagai arsitektur utama dengan struktur sebagai berikut:

```
app/
├── Http/
│   ├── Controllers/     # Handle HTTP requests, delegate ke Services
│   └── Requests/        # Form validation requests
├── Services/            # Business logic layer
│   ├── DestinationService.php
│   ├── WeatherService.php
│   └── TourismService.php
├── Models/              # Eloquent models untuk database interaction
└── Providers/           # Service providers untuk dependency injection
```

### 🔄 Alur Kerja Service Pattern:
1. **Controller** menerima HTTP request
2. **Controller** memanggil **Service** yang sesuai
3. **Service** mengeksekusi business logic
4. **Service** berinteraksi langsung dengan **Model** (Eloquent)
5. **Service** mengembalikan hasil ke **Controller**
6. **Controller** mengirim response ke user

### 💡 Keuntungan Service Pattern:
- **Separation of Concerns** - Business logic terpisah dari HTTP handling
- **Reusability** - Service dapat digunakan di berbagai controller
- **Testability** - Mudah untuk unit testing
- **Maintainability** - Kode lebih mudah dipelihara dan dikembangkan

## 🚀 Teknologi yang Digunakan

### Backend
- **Laravel** - Framework PHP untuk pengembangan web yang robust
- **Service Pattern** - Arsitektur untuk memisahkan business logic dari controller
- **MySQL** - Database management system untuk penyimpanan data

### Frontend  
- **HTML5** - Markup language untuk struktur web
- **Tailwind CSS** - Utility-first CSS framework untuk styling yang efisien

### API Integration
- **OpenWeatherMap API** - Layanan data cuaca global yang akurat

### Tools & Development
- **Composer** - Dependency manager untuk PHP
- **NPM** - Package manager untuk JavaScript
- **Git** - Version control system

## 📊 Dataset

Proyek ini menggunakan dataset destinasi wisata dari Kaggle:

**📋 Detail Dataset:**
- **Nama**: Destinasi Wisata Dataset
- **Sumber**: [Kaggle - Destinasi Wisata Dataset](https://www.kaggle.com/datasets/athreal/destinasi-wisata-dataset)
- **Kontributor**: Rahma Annisa & Aqila Darin Makkyah
- **Rating**: 2.35/10 (Usability Score)
- **Lisensi**: Unknown
- **Cakupan**: Destinasi wisata di seluruh Indonesia

## 🎯 Tujuan Proyek

1. **Pengembangan Platform Digital** - Merancang sistem informasi wisata yang modern dan mudah digunakan
2. **Integrasi Data Cuaca** - Menyediakan informasi cuaca yang akurat untuk mendukung perencanaan perjalanan
3. **Peningkatan Pengalaman Wisatawan** - Membantu wisatawan membuat keputusan yang lebih baik dalam merencanakan liburan

## 💡 Manfaat

### 👥 Untuk Wisatawan
- Perencanaan perjalanan yang lebih efektif dengan informasi cuaca
- Akses mudah ke informasi destinasi wisata yang lengkap
- Pengalaman browsing yang menyenangkan dan intuitif

### 🏝️ Untuk Pariwisata Indonesia
- Promosi destinasi wisata lokal yang lebih efektif
- Peningkatan kesadaran masyarakat terhadap potensi wisata nusantara
- Kontribusi pada pertumbuhan sektor pariwisata daerah

## 🛠️ Instalasi & Setup

### Prasyarat
- PHP >= 8.0
- Composer
- MySQL
- Node.js & NPM

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/nusatawan.git
   cd nusatawan
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   - Buat database MySQL baru
   - Update konfigurasi database di file `.env`
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Setup Services**
   - Services akan otomatis ter-register melalui Laravel's service container
   - Pastikan semua service classes ada di folder `app/Services/`

6. **API Configuration**
   - Daftar di [OpenWeatherMap](https://openweathermap.org/api)
   - Tambahkan API key ke file `.env`
   ```
   OPENWEATHER_API_KEY=your_api_key_here
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan ikuti langkah berikut:

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.

## 👨‍💻 Pengembang

**[Risky]**
- GitHub: [@Aerossky](https://github.com/Aerossky)
- LinkedIn: [Profile LinkedIn](https://linkedin.com/in/risky-aerossky)
- Email: risky4tech@gmail.com

## 🙏 Acknowledgments

- Dataset destinasi wisata dari [Kaggle](https://www.kaggle.com/datasets/athreal/destinasi-wisata-dataset)
- Data cuaca dari [OpenWeatherMap](https://openweathermap.org/)
- Inspiration dari komunitas pengembang Indonesia

---

<div align="center">
  <p>⭐ Jika proyek ini membantu Anda, jangan lupa berikan star!</p>
  <p>Made with ❤️ for Indonesian Tourism</p>
</div>
