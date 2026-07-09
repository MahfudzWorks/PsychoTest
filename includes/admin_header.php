<?php
if (!isset($pageTitle)) {
  $pageTitle = "Admin Dashboard";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= $pageTitle; ?> | PsychoTest</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />

  <!-- Chart JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #f3f4f6;
    }

    .sidebar-scroll::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
      background: #374151;
      border-radius: 10px;
    }

    .card {
      @apply bg-white rounded-xl shadow;
    }
  </style>
</head>

<body>