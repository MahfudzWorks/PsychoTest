<?php
if (!isset($pageTitle)) {
  $pageTitle = "Admin Dashboard";
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?= htmlspecialchars($pageTitle); ?> | PsychoTest System</title>

  <!-- Tailwind CSS v3 -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Konfigurasi Tambahan Tailwind -->
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

  <!-- Google Font - Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Chart.js v4 Terbaru -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

  <!-- Gaya Kustom -->
  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }

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