<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "psychotest";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Koneksi gagal");
}
