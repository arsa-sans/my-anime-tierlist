<?php
include('connection.php');

$id_anime = "";
$judul = "";
$genre = "";
$episode = "";
$status = "";
$tier = "";
$rating = "";
$ulasan = "";
$status_nonton_anime = "";
$namaBtn = "input";
$valueBtn = "submit";

$error_message = "";
$success_message = "";

// Variabel untuk search
$search_query = "";
$filter_tier = "";
$filter_status = "";
$filter_status_nonton = "";

// Ambil parameter search dari GET
if(isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($connection, $_GET['search']);
}
if(isset($_GET['filter_tier'])) {
    $filter_tier = mysqli_real_escape_string($connection, $_GET['filter_tier']);
}
if(isset($_GET['filter_status'])) {
    $filter_status = mysqli_real_escape_string($connection, $_GET['filter_status']);
}
if(isset($_GET['filter_status_nonton'])) {
    $filter_status_nonton = mysqli_real_escape_string($connection, $_GET['filter_status_nonton']);
}

if(isset($_POST['submit']) && $_POST['submit'] == 'input'){
  // Escape input untuk mencegah SQL injection
  $id_anime_safe = mysqli_real_escape_string($connection, $_POST['id_anime']);
  $judul_safe = mysqli_real_escape_string($connection, $_POST['judul']);
  $genre_safe = mysqli_real_escape_string($connection, $_POST['genre']);
  $episode_safe = mysqli_real_escape_string($connection, $_POST['episode']);
  $status_safe = mysqli_real_escape_string($connection, $_POST['status']);
  $tier_safe = mysqli_real_escape_string($connection, $_POST['tier']);
  $rating_safe = mysqli_real_escape_string($connection, $_POST['rating']);
  $ulasan_safe = mysqli_real_escape_string($connection, $_POST['ulasan']);
  $keterangan_safe = mysqli_real_escape_string($connection, $_POST['keterangan_nonton']);

  // Cek apakah ID anime sudah ada
  $check_id = mysqli_query($connection, "SELECT id_anime FROM anime WHERE id_anime='$id_anime_safe'");
  $check_judul = mysqli_query($connection, "SELECT judul_anime FROM anime WHERE judul_anime='$judul_safe'");
  
  if(mysqli_num_rows($check_id) > 0) {
    $error_message = "ID Anime sudah ada! Silakan gunakan ID yang berbeda.";
  } elseif(mysqli_num_rows($check_judul) > 0) {
    $error_message = "Judul Anime sudah ada! Silakan gunakan judul yang berbeda.";
  } else {
    $anime_insert = mysqli_query($connection, "INSERT INTO anime(id_anime, judul_anime, genre, episode, status_anime)
                                        VALUES (
                                        '$id_anime_safe',
                                        '$judul_safe',
                                        '$genre_safe',
                                        '$episode_safe',
                                        '$status_safe'
                                        )
                                        ") or die (mysqli_error($connection));
    $rating_insert = mysqli_query($connection, "INSERT INTO rating(id_anime, tier, rating, ulasan)
                                        VALUES (
                                        '$id_anime_safe',
                                        '$tier_safe',
                                        '$rating_safe',
                                        '$ulasan_safe'
                                        )
                                        ") or die (mysqli_error($connection));
    $keterangan_insert = mysqli_query($connection, "INSERT INTO keterangan(id_anime, ketuntasan_nonton)
                                        VALUES (
                                        '$id_anime_safe',
                                        '$keterangan_safe'
                                        )
                                        ") or die (mysqli_error($connection));
    
    // Redirect setelah insert berhasil
    if($anime_insert && $rating_insert && $keterangan_insert) {
      $success_message = "Anime berhasil ditambahkan!";
      header('location:?success=1');
      exit();
    }
  }
}

if(isset($_POST['submit']) && $_POST['submit'] == 'update'){
  $id_anime_original = mysqli_real_escape_string($connection, $_GET['id_anime']);
  
  // Escape input untuk update
  $id_anime_safe = mysqli_real_escape_string($connection, $_POST['id_anime']);
  $judul_safe = mysqli_real_escape_string($connection, $_POST['judul']);
  $genre_safe = mysqli_real_escape_string($connection, $_POST['genre']);
  $episode_safe = mysqli_real_escape_string($connection, $_POST['episode']);
  $status_safe = mysqli_real_escape_string($connection, $_POST['status']);
  $tier_safe = mysqli_real_escape_string($connection, $_POST['tier']);
  $rating_safe = mysqli_real_escape_string($connection, $_POST['rating']);
  $ulasan_safe = mysqli_real_escape_string($connection, $_POST['ulasan']);
  $keterangan_safe = mysqli_real_escape_string($connection, $_POST['keterangan_nonton']);

  // Cek apakah ID anime sudah ada (kecuali ID yang sedang diedit)
  $check_id = mysqli_query($connection, "SELECT id_anime FROM anime WHERE id_anime='$id_anime_safe' AND id_anime != '$id_anime_original'");
  $check_judul = mysqli_query($connection, "SELECT judul_anime FROM anime WHERE judul_anime='$judul_safe' AND id_anime != '$id_anime_original'");
  
  if(mysqli_num_rows($check_id) > 0) {
    $error_message = "ID Anime sudah ada! Silakan gunakan ID yang berbeda.";
  } elseif(mysqli_num_rows($check_judul) > 0) {
    $error_message = "Judul Anime sudah ada! Silakan gunakan judul yang berbeda.";
  } else {
    $anime_update = mysqli_query($connection, "UPDATE anime SET
                                                id_anime = '$id_anime_safe',
                                                judul_anime = '$judul_safe',
                                                genre = '$genre_safe',
                                                episode = '$episode_safe',
                                                status_anime = '$status_safe'
                                                WHERE id_anime='$id_anime_original'
                                        ") or die (mysqli_error($connection));
    $rating_update = mysqli_query($connection, "UPDATE rating SET
                                                id_anime ='$id_anime_safe',
                                                tier ='$tier_safe',
                                                rating ='$rating_safe',
                                                ulasan ='$ulasan_safe'
                                                WHERE id_anime='$id_anime_original'
                                                ") or die (mysqli_error($connection));
    $keterangan_update = mysqli_query($connection, "UPDATE keterangan SET
                                                    id_anime = '$id_anime_safe',
                                                    ketuntasan_nonton = '$keterangan_safe'
                                                    WHERE id_anime='$id_anime_original'
                                        ") or die (mysqli_error($connection));
    
    // Redirect setelah update berhasil
    if($anime_update && $rating_update && $keterangan_update) {
      header('location:?success=2');
      exit();
    }
  }
}

// kondisi hapus data
if(isset($_GET['act']) && $_GET['act'] == 'hapus'){
  $id_anime = mysqli_real_escape_string($connection, $_GET['id_anime']);

  $anime_delete = mysqli_query($connection, "DELETE FROM anime WHERE id_anime='$id_anime'") or die ("gagal hapus");
  $rating_delete = mysqli_query($connection, "DELETE FROM rating WHERE id_anime='$id_anime'") or die ("gagal hapus");
  $keterangan_delete = mysqli_query($connection, "DELETE FROM keterangan WHERE id_anime='$id_anime'") or die ("gagal hapus");

  header('location:?success=3');
  exit();
}

// Pesan success berdasarkan parameter
if(isset($_GET['success'])){
  switch($_GET['success']){
    case '1':
      $success_message = "Anime berhasil ditambahkan!";
      break;
    case '2':
      $success_message = "Anime berhasil diupdate!";
      break;
    case '3':
      $success_message = "Anime berhasil dihapus!";
      break;
  }
}

// kondisi edit data
if(isset($_GET['act']) && $_GET['act'] == 'edit'){
  $id_anime = mysqli_real_escape_string($connection, $_GET['id_anime']);
  
  $anime_data = mysqli_query($connection, "SELECT * FROM anime WHERE id_anime='$id_anime'") or die ("gagal mengedit");
  $rating_data = mysqli_query($connection, "SELECT * FROM rating WHERE id_anime='$id_anime'") or die ("gagal mengedit");
  $keterangan_data = mysqli_query($connection, "SELECT * FROM keterangan WHERE id_anime='$id_anime'") or die ("gagal mengedit");
  
  $data1 = mysqli_fetch_array($anime_data);
  $data2 = mysqli_fetch_array($rating_data);
  $data3 = mysqli_fetch_array($keterangan_data);
  
  if($data1 && $data2 && $data3) {
    $id_anime = $data1['id_anime'];
    $judul = $data1['judul_anime'];
    $genre = $data1['genre'];
    $episode = $data1['episode'];
    $status = $data1['status_anime'];
    $tier = $data2['tier'];
    $rating = $data2['rating'];
    $ulasan = $data2['ulasan'];
    $status_nonton_anime = $data3['ketuntasan_nonton'];
    $namaBtn = "update";
    $valueBtn = "update";
  }
}

// Fungsi untuk membangun query pencarian
function buildSearchQuery($connection, $search_query, $filter_tier, $filter_status, $filter_status_nonton) {
    $base_query = "SELECT a.*, r.tier, r.rating, r.ulasan, k.ketuntasan_nonton, k.waktu 
                   FROM anime a 
                   LEFT JOIN rating r ON a.id_anime = r.id_anime 
                   LEFT JOIN keterangan k ON a.id_anime = k.id_anime";
    
    $conditions = [];
    
    // Search query (pencarian di judul, genre, dan ulasan)
    if (!empty($search_query)) {
        $conditions[] = "(a.judul_anime LIKE '%$search_query%' 
                        OR a.genre LIKE '%$search_query%' 
                        OR r.ulasan LIKE '%$search_query%')";
    }
    
    // Filter tier
    if (!empty($filter_tier)) {
        $conditions[] = "r.tier = '$filter_tier'";
    }
    
    // Filter status anime
    if (!empty($filter_status)) {
        $conditions[] = "a.status_anime = '$filter_status'";
    }
    
    // Filter status menonton
    if (!empty($filter_status_nonton)) {
        $conditions[] = "k.ketuntasan_nonton = '$filter_status_nonton'";
    }
    
    // Gabungkan kondisi
    if (!empty($conditions)) {
        $base_query .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $base_query .= " ORDER BY r.tier ASC, r.rating DESC";
    
    return $base_query;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Anime Tierlist</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .search-container {
      background: rgba(30, 30, 30, 0.95);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
      margin-bottom: 30px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .search-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #e0e0e0;
      margin-bottom: 15px;
    }
    
    .search-form {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr auto;
      gap: 15px;
      align-items: end;
    }
    
    .search-group {
      display: flex;
      flex-direction: column;
    }
    
    .search-group label {
      font-weight: 600;
      color: #e0e0e0;
      margin-bottom: 8px;
      font-size: 0.95em;
    }
    
    .search-input, .search-select {
      padding: 12px;
      border: 2px solid #404040;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: #2a2a2a;
      color: #e0e0e0;
    }
    
    .search-input:focus, .search-select:focus {
      outline: none;
      border-color: #666666;
      box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
    }
    
    .search-btn {
      background: linear-gradient(135deg, #333333 0%, #1a1a1a 100%);
      color: white;
      padding: 15px 30px;
      border: 2px solid #666666;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .search-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.5);
      border-color: #888888;
      background: linear-gradient(135deg, #444444 0%, #2a2a2a 100%);
    }
    
    .clear-btn {
      background: linear-gradient(135deg, #333333 0%, #1a1a1a 100%);
      color: white;
      padding: 15px 30px;
      border: 2px solid #666666;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .clear-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.5);
      border-color: #888888;
      background: linear-gradient(135deg, #444444 0%, #2a2a2a 100%);
    }
    
    .search-results-info {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.875rem;
      color: #666666  ;
    }
    
    .search-results-info strong {
      color: #e0e0e0;
    }
    
    @media (max-width: 768px) {
      .search-form {
        grid-template-columns: 1fr;
        gap: 10px;
      }
      
      .search-container {
        padding: 15px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Anime Tierlist</h1>
      <p>Kelola dan rating anime favorit kamu</p>
    </div>

    <div class="form-container">
      <?php if(!empty($error_message)): ?>
        <div class="alert alert-error">
          <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>
      
      <?php if(!empty($success_message)): ?>
        <div class="alert alert-success">
          <?php echo htmlspecialchars($success_message); ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST">
        <input type="hidden" name="id_anime" value="<?php echo htmlspecialchars($id_anime);?>">
        
        <div class="form-grid">
          <div class="form-group">
            <label for="id_anime">ID Anime</label>
            <input type="text" placeholder="Masukkan ID Anime" name="id_anime" value="<?php echo htmlspecialchars($id_anime);?>" required>
          </div>
          
          <div class="form-group">
            <label for="judul">Judul Anime</label>
            <input type="text" placeholder="Masukkan Judul Anime" name="judul" value="<?php echo htmlspecialchars($judul);?>" required>
          </div>
          
          <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" placeholder="Masukkan Genre Anime" name="genre" value="<?php echo htmlspecialchars($genre);?>" required>
          </div>
          
          <div class="form-group">
            <label for="episode">Jumlah Episode</label>
            <input type="number" placeholder="Masukkan Jumlah Episode" name="episode" value="<?php echo htmlspecialchars($episode);?>" required>
          </div>
          
          <div class="form-group">
            <label for="status">Status Anime</label>
            <select name="status" required>
              <option value="">Pilih Status</option>
              <option value="ongoing" <?php echo ($status == 'ongoing') ? 'selected' : ''; ?>>Ongoing</option>
              <option value="completed" <?php echo ($status == 'completed') ? 'selected' : ''; ?>>Completed</option>
              <option value="hiatus" <?php echo ($status == 'hiatus') ? 'selected' : ''; ?>>Hiatus</option>
              <option value="dropped" <?php echo ($status == 'dropped') ? 'selected' : ''; ?>>Dropped</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="tier">Tier</label>
            <select name="tier" required>
              <option value="">Pilih Tier</option>
              <option value="S" <?php echo ($tier == 'S') ? 'selected' : ''; ?>>S Tier</option>
              <option value="A" <?php echo ($tier == 'A') ? 'selected' : ''; ?>>A Tier</option>
              <option value="B" <?php echo ($tier == 'B') ? 'selected' : ''; ?>>B Tier</option>
              <option value="C" <?php echo ($tier == 'C') ? 'selected' : ''; ?>>C Tier</option>
              <option value="D" <?php echo ($tier == 'D') ? 'selected' : ''; ?>>D Tier</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="rating">Rating (1-10)</label>
            <input type="number" min="1" max="10" placeholder="Rating 1-10" name="rating" value="<?php echo htmlspecialchars($rating);?>" required>
          </div>
          
          <div class="form-group">
            <label for="keterangan_nonton">Status Menonton</label>
            <select name="keterangan_nonton" required>
              <option value="">Pilih Status</option>
              <option value="Sudah Selesai" <?php echo ($status_nonton_anime == 'Sudah Selesai') ? 'selected' : ''; ?>>Sudah Selesai</option>
              <option value="Sedang Menonton" <?php echo ($status_nonton_anime == 'Sedang Menonton') ? 'selected' : ''; ?>>Sedang Menonton</option>
              <option value="Akan Ditonton" <?php echo ($status_nonton_anime == 'Akan Ditonton') ? 'selected' : ''; ?>>Akan Ditonton</option>
              <option value="Dropped" <?php echo ($status_nonton_anime == 'Dropped') ? 'selected' : ''; ?>>Dropped</option>
            </select>
          </div>
        </div>
        
        <div class="form-group" style="grid-column: 1 / -1;">
          <label for="ulasan">Ulasan</label>
          <textarea name="ulasan" placeholder="Tulis ulasan anime..." required><?php echo htmlspecialchars($ulasan);?></textarea>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
          <button type="submit" name="submit" value="<?php echo htmlspecialchars($namaBtn); ?>" class="submit-btn">
            <?php echo ($valueBtn == 'update') ? 'Update Anime' : 'Tambah Anime'; ?>
          </button>
        </div>
      </form>
    </div>

    <!-- Search Container -->
    <div class="search-container">
      <h2 class="search-title">Cari Anime</h2>
      <form class="search-form" method="GET" action="">
        <div class="search-group">
          <input type="text" 
                 id="search" 
                 name="search" 
                 class="search-input" 
                 placeholder="Cari berdasarkan judul, genre, atau ulasan..." 
                 value="<?php echo htmlspecialchars($search_query); ?>">
        </div>
        <button type="submit" class="search-btn">Cari</button>
        <a href="?" class="clear-btn" style="text-decoration: none; text-align: center;">Reset</a>
      </form>
    </div>

    <div class="table-container">
      <h2 class="table-title">Daftar Anime Tierlist</h2>
      
      <?php
      // Bangun query pencarian
      $query = buildSearchQuery($connection, $search_query, $filter_tier, $filter_status, $filter_status_nonton);
      $data_join = mysqli_query($connection, $query);
      $total_results = mysqli_num_rows($data_join);
      
      // Tampilkan info hasil pencarian
      if (!empty($search_query) || !empty($filter_tier) || !empty($filter_status) || !empty($filter_status_nonton)): ?>
        <div class="search-results-info">
          <strong>Hasil Pencarian:</strong> Ditemukan <?php echo $total_results; ?> anime
          <?php if (!empty($search_query)): ?>
            untuk kata kunci "<strong><?php echo htmlspecialchars($search_query); ?></strong>"
          <?php endif; ?>
          <?php if (!empty($filter_tier)): ?>
            dengan tier <strong><?php echo htmlspecialchars($filter_tier); ?></strong>
          <?php endif; ?>
          <?php if (!empty($filter_status)): ?>
            dengan status <strong><?php echo htmlspecialchars($filter_status); ?></strong>
          <?php endif; ?>
          <?php if (!empty($filter_status_nonton)): ?>
            dengan status menonton <strong><?php echo htmlspecialchars($filter_status_nonton); ?></strong>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      
      <table>
        <thead>
          <tr>
            <th>Tier</th>
            <th>Judul</th>
            <th>Genre</th>
            <th>Episode</th>
            <th>Status</th>
            <th>Rating</th>
            <th>Ulasan</th>
            <th>Status Menonton</th>
            <th>Waktu Tambah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($total_results > 0) {
            while($tampil = mysqli_fetch_assoc($data_join)) :
          ?>
          <tr>
            <td>
              <span class="tier-badge tier-<?= htmlspecialchars($tampil['tier']) ?>">
                <?= htmlspecialchars($tampil['tier']) ?>
              </span>
            </td>
            <td style="font-weight: 600; text-align: left;"><?= htmlspecialchars($tampil['judul_anime']) ?></td>
            <td><?= htmlspecialchars($tampil['genre']) ?></td>
            <td><?= htmlspecialchars($tampil['episode']) ?></td>
            <td>
              <span class="status-badge status-<?= htmlspecialchars($tampil['status_anime']) ?>">
                <?= ucfirst(htmlspecialchars($tampil['status_anime'])) ?>
              </span>
            </td>
            <td>
              <?= htmlspecialchars($tampil['rating']) ?>/10
            </td>
            <td style="max-width: 200px; text-align: left;">
              <?= htmlspecialchars(substr($tampil['ulasan'], 0, 100)) ?>
              <?= strlen($tampil['ulasan']) > 100 ? '...' : '' ?>
            </td>
            <td><?= htmlspecialchars($tampil['ketuntasan_nonton']) ?></td>
            <td><?= htmlspecialchars($tampil['waktu']) ?></td>
            <td>
              <div class="action-links">
                <a href="?act=edit&id_anime=<?= urlencode($tampil['id_anime']) ?>" class="edit-link">Edit</a>
                <a href="?act=hapus&id_anime=<?= urlencode($tampil['id_anime']) ?>" 
                   class="delete-link" 
                   onclick='return confirm("Apakah Anda yakin ingin menghapus anime ini?")'>Hapus</a>
              </div>
            </td>
          </tr>
          <?php 
            endwhile;
          } else {
            echo "<tr><td colspan='10' style='text-align: center; padding: 40px; color: #6b7280;'>
                    <div style='font-size: 1.2rem; margin-bottom: 10px;'>📭</div>
                    <div>Tidak ada anime yang ditemukan dengan kriteria pencarian tersebut.</div>
                    <div style='margin-top: 10px;'><a href='?' style='color: #2563eb; text-decoration: none;'>Tampilkan semua anime</a></div>
                  </td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>