# 🏥 Prescription App

A modern web application for digitizing and managing medical prescriptions using AI-powered OCR technology. Built with Laravel 12, Livewire, and powered by NVIDIA NIM AI services.

## ✨ Features

### 🔍 **AI-Powered Prescription OCR**
- Upload prescription images (JPG, PNG format)
- Automatic medicine name and dosage extraction using NVIDIA NIM AI
- Real-time processing with visual feedback
- Support for multiple prescription images

### 📋 **Prescription Management**
- Create and manage prescription records
- Add delivery address and preferred delivery time
- Attach multiple prescription images
- Add custom notes for special instructions

### 💰 **Quotation System**
- Automatic quotation generation based on extracted medicines
- Medicine-wise pricing breakdown
- Multiple quotation statuses: Pending, Approved, Rejected, Completed
- Total price calculation

### 📧 **Email Notifications**
- Automated email notifications when quotations are ready
- Quotation status change notifications
- Professional email templates

### 👤 **User Management**
- User authentication and registration
- Personal prescription history
- Secure user data management

### 🎨 **Modern UI/UX**
- Responsive design with Tailwind CSS
- Interactive file upload with drag-and-drop support
- Real-time updates using Livewire
- Clean and intuitive interface

## 🛠️ Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Livewire, Volt, TailwindCSS 4
- **Database**: MySQL/SQLite
- **AI Service**: NVIDIA NIM API (Mistral-Medium-3-Instruct)
- **Email**: Resend API
- **File Processing**: Base64 image encoding
- **Testing**: PestPHP
- **Build Tool**: Vite

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- PHP 8.2 or higher
- Composer
- Node.js (v18 or higher)
- NPM
- MySQL/SQLite database
- Git

### 1. Installation & Setup
```bash
composer install
```

### 2. Install Node.js Dependencies
```bash
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Create database tables
php artisan migrate

# (Optional) Seed sample data
php artisan db:seed
```

### 5. Storage Setup
```bash
# Create storage link for file uploads
php artisan storage:link
```

### 6. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Running the Application
```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server
npm run dev
```

### 8. 📁 Project Structure

```
app/
├── Http/Controllers/     # HTTP controllers
├── Livewire/            # Livewire components
│   ├── DropZone.php     # File upload component
│   ├── OCRPrescriptons.php # OCR processing component
│   └── Actions/         # Livewire actions
├── Mail/                # Email templates
├── Models/              # Eloquent models
│   ├── Prescription.php
│   ├── Quotation.php
│   ├── MedicineQuotation.php
│   └── Image.php
└── Services/
    └── NvidiaNIMService.php # AI OCR service

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders

resources/
├── views/              # Blade templates
├── css/               # Stylesheets
└── js/                # JavaScript assets
```

### 9. 🔑 API Keys Setup

### NVIDIA NIM API Key
1. Visit [NVIDIA NIM Platform](https://build.nvidia.com/)
2. Create an account and generate an API key
3. Add the key to your `.env` file as `NVIDIA_API_KEY`

### Resend API Key
1. Visit [Resend](https://resend.com/)
2. Create an account and generate an API key
3. Add the key to your `.env` file as `RESEND_KEY`

## 📊 Development Time

**Total Development Time: ~23 hours** (September 2-3, 2025)

### Day 1 (September 2, 2025)
- **19:52** - Initialize Laravel application *(Start)*
- **22:51** - Setup app structure and user model
- **Total Day 1**: ~3 hours

### Day 2 (September 3, 2025)
- **07:01** - Create prescription MCR and view table
- **07:17** - Build prescription create UI
- **08:38** - Add prescription creation functionality
- **09:46** - Complete prescription view UI
- **10:29** - Create quotation system and medicine database
- **13:56** - Implement CRUD operations for quotations
- **14:19** - Add email notifications
- **15:08** - Implement quotation approval/rejection
- **15:22** - Complete quotation status management
- **15:30** - Add prescription deletion
- **15:52** - Implement result filtering
- **16:01** - Clean up unused routes
- **16:20** - Create NVIDIA NIM service class
- **17:38** - Switch LLM model
- **19:04** - Finalize project with OCR scanner *(End)*
- **Total Day 2**: ~20 hours

### Development Breakdown:
- **Project setup and architecture**: 3 hours
- **Database design and migrations**: 2 hours
- **Prescription management system**: 4 hours
- **Quotation system development**: 5 hours
- **AI OCR integration (NVIDIA NIM)**: 4 hours
- **Email notification system**: 2 hours
- **UI/UX with Livewire & Tailwind**: 2 hours
- **Testing, debugging & optimization**: 1 hour

## 👨‍💻 Author

**Dilanka Yasuru**
- GitHub: [@dilankayasuru](https://github.com/dilankayasuru)