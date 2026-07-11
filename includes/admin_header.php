<?php
if (!isset($pageTitle)) {
  $pageTitle = "Admin Dashboard";
}
?>

<?php
// Ambil data admin dari database
$admin_id = (int)$_SESSION['id'];
$query_nav = mysqli_query($conn, "SELECT profile_pic, fullname FROM users WHERE id = '$admin_id' LIMIT 1");
$user_nav = mysqli_fetch_assoc($query_nav);

$foto_nav = !empty($user_nav['profile_pic'])
  ? "../uploads/profiles/" . $user_nav['profile_pic']
  : "../assets/img/default.png";
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?= htmlspecialchars($pageTitle); ?> | PsychoTest System</title>

  <!-- Tailwind CSS - Ganti ke versi lokal / CDN alternatif yang lebih stabil -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Konfigurasi Warna & Font -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#2563eb',
            secondary: '#4f46e5',
            success: '#10b981',
            warning: '#f59e0b',
            danger: '#ef4444',
            dark: '#1e293b'
          },
          fontFamily: {
            poppins: ['Poppins', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <!-- Font Poppins - Ganti ke sumber lain atau gunakan fallback aman -->
  <style>
    /* Gunakan font sistem jika Google Font diblokir */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
    }
  </style>

  <!-- Font Awesome - Ganti ke CDN alternatif yang lebih jarang diblokir -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.7.2/css/all.css" crossorigin="anonymous">

  <!-- Chart.js - Tetap gunakan, jarang diblokir -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

  <!-- Gaya Kustom -->
  <style>
    body {
      background-color: #f8fafc;
      font-size: 14px;
      line-height: 1.6;
    }

    /* Scrollbar Khusus Sidebar */
    .sidebar-scroll::-webkit-scrollbar {
      width: 5px;
    }

    .sidebar-scroll::-webkit-scrollbar-track {
      background: #1e293b;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
      background: #475569;
      border-radius: 10px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb:hover {
      background: #64748b;
    }

    /* Kelas Kartu Standar */
    .card {
      @apply bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100;
    }

    /* Efek Transisi Halus */
    .transition-all {
      transition: all 0.25s ease;
    }

    /* Gaya Tabel */
    table {
      border-collapse: separate;
      border-spacing: 0;
    }

    canvas {
      max-width: 100%;
    }
  </style>
</head>

<body>