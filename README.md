# Seatify 🎬

Seatify is a modern, high-performance cinema ticket booking platform built with **Laravel 12**, **Livewire 4**, and **Flux UI**. It provides a seamless experience for users to browse movies, select showtimes, and book seats in real-time, while offering a powerful administrative suite for cinema managers.

---

## ✨ Key Features

### 🎞️ For Moviegoers
- **Dynamic Movie Catalog**: Browse currently showing movies with detailed descriptions.
- **Interactive Seat Selection**: Real-time seat availability and selection using Livewire's reactive components.
- **Secure Booking Flow**: Streamlined checkout process from movie selection to payment confirmation.
- **Booking History**: Access personal booking records and ticket details.
- **Responsive Design**: Optimized for both mobile and desktop using Tailwind CSS 4.

### 🛡️ For Administrators
- **Robust Dashboard**: High-level overview of total bookings, revenue, and active movies.
- **Cinema & Studio Management**: Easily manage multiple cinema locations and their respective studios.
- **Film Management**: CRUD operations for movies, including status tracking (showing/upcoming).
- **Showtime Scheduling**: Powerful scheduling tool to manage showtimes per studio.
- **Real-time Monitoring**: Track all bookings and seat occupancies as they happen.
- **Role-Based Access (RBAC)**: Secure isolation between Admin management and User booking areas.

---

## 🛠️ Technology Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Frontend Interactivity**: [Livewire 4](https://livewire.laravel.com) (Volt & Component-based)
- **UI Architecture**: [Flux UI Free](https://fluxui.dev/) 
- **Styling**: [Tailwind CSS 4](https://tailwindcss.com/)
- **Authentication**: [Laravel Fortify](https://laravel.com/docs/fortify)
- **Access Control**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- **Core Engine**: PHP 8.3+

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.3 or higher
- Composer
- Node.js & NPM
- SQLite (or your preferred database)

### Installation Guide

1. **Clone the repository**:
   ```bash
   git clone https://github.com/jodijonatan/seatify.git
   cd seatify
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Install Frontend dependencies**:
   ```bash
   npm install
   ```

4. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Initialize Database**:
   ```bash
   # Seatify uses SQLite by default for easy setup
   touch database/database.sqlite
   php artisan migrate --seed
   ```

6. **Build Assets**:
   ```bash
   npm run build
   ```

7. **Launch the Application**:
   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` to start booking!

---

## 🧪 Testing

Seatify is built with a test-driven mindset using **Pest**.

To run the test suite:
```bash
php artisan test
```

---

## 📜 License

The Seatify platform is open-sourced software licensed under the [Apache License 2.0](https://opensource.org/licenses/Apache-2.0).
