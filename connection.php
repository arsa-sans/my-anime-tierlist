<?php 
  define('HOST_NAME', 'localhost');
  define('USER_NAME', 'root');
  define('PASSWORD', '');
  define('DB_NAME', 'anime_tierlist');

  $connection = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME) or die("Gagal Koneksi");
?>