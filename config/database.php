<?php

$conn = mysqli_connect(
  "localhost",
  "root",
  "",
  "psychotest"
);

if (!$conn) {
  die("Database gagal terhubung");
}
