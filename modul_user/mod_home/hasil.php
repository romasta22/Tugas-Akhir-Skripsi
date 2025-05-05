<?php
function logDebug($message) {
    error_log(date("Y-m-d H:i:s") . " " . $message . "\n", 3, "debug.log");
}

$conn = new mysqli("localhost", "root", "", "dbs_wisata");
if ($conn->connect_error) {
    logDebug("Koneksi gagal: " . $conn->connect_error);
    die("Koneksi gagal: " . $conn->connect_error);
}
logDebug("Koneksi database berhasil.");

// Ambil total jumlah fasilitas yang tersedia dari tabel fasilitas
$q_total_fasilitas = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_fasilitas");
$data_total = mysqli_fetch_assoc($q_total_fasilitas);
$totalFasilitasTersedia = $data_total['total'];


$sql_bobot = "SELECT nama_kriteria, bobot FROM tbl_kriteria";
$result_bobot = $conn->query($sql_bobot);
$bobot_kriteria = [];
while ($row = $result_bobot->fetch_assoc()) {
    $bobot_kriteria[$row['nama_kriteria']] = $row['bobot'];
}
logDebug("Bobot kriteria: " . json_encode($bobot_kriteria));

$lokasi_input = $_GET['lokasi'];          
$harga_input   = $_GET['harga'];           
$min_rating    = $_GET['rating'];           
$id_kategori   = $_GET['kategori'];
$fasilitas_input = isset($_GET['fasilitas']) ? $_GET['fasilitas'] : [];

logDebug("Input GET: lokasi=$lokasi_input, harga=$harga_input, rating=$min_rating, kategori=$id_kategori");
logDebug("Fasilitas dipilih: " . (is_array($fasilitas_input) ? implode(", ", $fasilitas_input) : $fasilitas_input));


$user_latitude  = $_GET['latitude'] ?? 2.66;
$user_longitude = $_GET['longitude'] ?? 98.94;
logDebug("Koordinat user: latitude=$user_latitude, longitude=$user_longitude");

$prov_url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($lokasi_input) . "&format=json&limit=1";
logDebug("Mengambil koordinat dari URL: $prov_url");

$options = [
    'http' => [
        'header' => "User-Agent: MyApp/1.0\r\n",
        'timeout' => 5
    ]
];
$context = stream_context_create($options);

$response = @file_get_contents($prov_url, false, $context);
if ($response === false) {
    logDebug("Gagal mendapatkan respons dari Nominatim. Menggunakan koordinat default.");
    $prov_lat = $user_latitude;
    $prov_lon = $user_longitude;
} else {
    $data_osm = json_decode($response, true);
    if (!empty($data_osm)) {
        $prov_lat = $data_osm[0]['lat'];
        $prov_lon = $data_osm[0]['lon'];
        logDebug("Koordinat provinsi ($lokasi_input): latitude=$prov_lat, longitude=$prov_lon");
    } else {
        $prov_lat = $user_latitude;
        $prov_lon = $user_longitude;
        logDebug("Data OSM kosong, menggunakan koordinat user: latitude=$prov_lat, longitude=$prov_lon");
    }
}

function hitungJarak($lat1, $lon1, $lat2, $lon2) {
    $radius_earth = 6371; 
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $radius_earth * $c;
}

$jarak_openstreetmap = hitungJarak($user_latitude, $user_longitude, $prov_lat, $prov_lon);
logDebug("Jarak antara user dan provinsi ($lokasi_input): $jarak_openstreetmap km");

if ($jarak_openstreetmap > 100) {
    $range_label = "100+ km";
} else if ($jarak_openstreetmap >= 50) {
    $range_label = "50 - 100 km";
} else if ($jarak_openstreetmap >= 20) {
    $range_label = "20 - 50 km";
} else {
    $range_label = "0 - 20 km";
}
logDebug("Range jarak: $range_label");

if (strpos($harga_input, '+') !== false) {
    $harga_min = (int) rtrim($harga_input, '+');
    $harga_max = 1000000;
} else {
    list($harga_min, $harga_max) = explode('-', $harga_input);
    $harga_min = (int)$harga_min;
    $harga_max = (int)$harga_max;
}
logDebug("Range harga input: $harga_min - $harga_max");

$sql = "SELECT * FROM tbl_wisata WHERE id_kategori = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    logDebug("Prepare statement gagal: " . $conn->error);
    die("Terjadi kesalahan pada query SQL: " . $conn->error);
}
$stmt->bind_param("i", $id_kategori);
$stmt->execute();
$result = $stmt->get_result();
logDebug("Query wisata dijalankan untuk id_kategori = $id_kategori");

$wisata_terpilih = [];
$id_terpilih = [];
while ($row = $result->fetch_assoc()) {

    /////// fasilitas ///////
    $fasilitas_level = is_array($fasilitas_input) ? '' : $fasilitas_input;

    if (!empty($fasilitas_level)) {
        $id_wisata = $row['id_wisata'];
    
        // Hitung jumlah fasilitas untuk wisata ini
        $query_fasilitas = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM tbl_wisata_fasilitas WHERE id_wisata = '$id_wisata'");
        $data_fasilitas = mysqli_fetch_assoc($query_fasilitas);
        $jumlah_fasilitas = $data_fasilitas['jumlah'];  
        
       // Filter berdasarkan level fasilitas   
       if ($fasilitas_level === 'kurang' || $fasilitas_level === 'lengkap') {
        echo "<script>
            alert('Tidak ada wisata dengan jumlah fasilitas $fasilitas_level. Disarankan memilih CUKUP LENGKAP!!');
            window.location.href = 'dashboard_user.php?hal=home';
        </script>";
        return;
    }      

    }
     /////// fasilitas ///////
    $jarak = hitungJarak($user_latitude, $user_longitude, $row['latitude'], $row['longitude']);
    
    $kategori_jarak = match(true) {
        $jarak >= 100 => "100+ km",
        $jarak >= 50  => "50 - 100 km",
        $jarak >= 20  => "20 - 50 km",
        default       => "0 - 20 km"
    };

    if ($kategori_jarak !== $range_label) continue;

    $row_harga = $row['harga'];
    if (strpos($row_harga, '-') !== false) {
        $parts = explode('-', $row_harga);
        $row_price_min = (int) str_replace(['.', ' '], '', trim($parts[0]));
        $row_price_max = (int) str_replace(['.', ' '], '', trim($parts[1]));
    } elseif (strpos($row_harga, '+') !== false) {
        $row_price_min = (int) str_replace(['.', ' '], '', rtrim($row_harga, '+'));
        $row_price_max = 1000000;
    } else {
        $row_price_min = (int) str_replace(['.', ' '], '', $row_harga);
        $row_price_max = $row_price_min;
    }

    $rating = floatval($row['rating']);
    logDebug("Wisata ID {$row['id_wisata']}: jarak = $jarak, rating = $rating");

    if (($harga_min <= $row_price_max && $harga_max >= $row_price_min) && $rating >= $min_rating) {
        if (!in_array($row['id_wisata'], $id_terpilih)) {
            $row['harga_min_numeric'] = $row_price_min;
            $row['jarak'] = $jarak;
            $row['harga_range'] = "$row_price_min - $row_price_max";
            $row['rating'] = $rating;
            $wisata_terpilih[] = $row;
            $id_terpilih[] = $row['id_wisata'];
        }
    }
}
logDebug("Total wisata terpilih: " . count($wisata_terpilih));

$nilai_fasilitas = [
    'kurang' => 1,
    'cukup'  => 2,
    'lengkap'=> 3
];

if (count($wisata_terpilih) > 0) {
    // Ambil nilai maksimum dari setiap kriteria untuk normalisasi
    $max_jarak       = max(array_column($wisata_terpilih, 'jarak')) ?: 1;
    $max_harga       = max(array_column($wisata_terpilih, 'harga_min_numeric')) ?: 1;
    $max_rating      = max(array_column($wisata_terpilih, 'rating')) ?: 1;
    $max_fasilitas   = 3; // nilai maksimal = 'lengkap'

    foreach ($wisata_terpilih as &$wisata) {
        // Normalisasi jarak dan harga (semakin kecil semakin baik)
        $norm_jarak     = ($max_jarak - $wisata['jarak']) / $max_jarak;
        $norm_harga     = ($max_harga - $wisata['harga_min_numeric']) / $max_harga;
        $norm_rating    = $wisata['rating'] / $max_rating;
        $norm_kategori  = ($wisata['id_kategori'] == $id_kategori) ? 1 : 0;
        $level_fasilitas = strtolower($wisata['fasilitas'] ?? 'kurang');
        $nilai_fas       = $nilai_fasilitas[$level_fasilitas] ?? 1;
        $norm_fasilitas  = $nilai_fas / $max_fasilitas;

        // Hitung skor total SAW
        $wisata['saw_score'] = (
            $norm_jarak     * $bobot_kriteria['jarak'] +
            $norm_harga     * $bobot_kriteria['harga'] +
            $norm_rating    * $bobot_kriteria['rating'] +
            $norm_kategori  * $bobot_kriteria['kategori'] +
            $norm_fasilitas * $bobot_kriteria['fasilitas']
        );
    }

    // Urutkan hasil berdasarkan skor tertinggi
    usort($wisata_terpilih, fn($a, $b) => $b['saw_score'] <=> $a['saw_score']);
    logDebug("Data wisata diurutkan berdasarkan SAW Score.");
}

?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil Pencarian Wisata</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
    .container { width: 80%; margin: 40px auto; text-align: center; }
    .wisata-list { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; }
    .wisata-item { width: 23%; padding: 15px; background: white; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s ease; text-align: left; }
    .wisata-item:hover { transform: scale(1.05); }
    img { width: 100%; height: 150px; object-fit: cover; border-radius: 10px; }
    h3 { font-size: 18px; margin: 10px 0; }
    p { font-size: 14px; margin: 5px 0; }
    a { font-size: 14px; color: #007BFF; text-decoration: none; }
    a:hover { text-decoration: underline; }
    .btn-back { margin-top: 20px; display: inline-block; padding: 10px 20px; background: #007BFF; color: white; text-decoration: none; border-radius: 8px; transition: background 0.3s ease; }
    .btn-back:hover { background: #0056b3; }
  </style>
</head>
<body>
<div class="container">
  <h2>Hasil Preferensi Pencarian Wisata Anda!</h2>
  <div class="wisata-list">
    <?php if (!empty($wisata_terpilih)): ?>
      <?php foreach ($wisata_terpilih as $wisata): ?>
        <div class="wisata-item">
          <img src="././image/<?= $wisata['gambar'] ?>" alt="<?= $wisata['nama_wisata'] ?>">
          <h3><?= $wisata['nama_wisata'] ?></h3>
          <p>Jarak: <?= round($wisata['jarak'], 2) ?> km</p>
          <p>Harga: <?= isset($wisata['harga_range']) ? $wisata['harga_range'] : $wisata['harga'] ?></p>
          <p>Rating: <?= $wisata['rating'] ?></p>
          <p>Skor SAW: <?= round($wisata['saw_score'], 2) ?></p>
          <a href="index_user.php?hal=detail_<?php 
            switch ($wisata['id_kategori']) {
              case 1: echo "wisata_alam"; break;
              case 2: echo "wisata_budaya"; break;
              case 3: echo "wisata_kuliner"; break;
              case 4: echo "akomodasi"; break;
              default: echo "alam";
            }
          ?>&id=<?= $wisata['id_wisata'] ?>">Lihat Detail</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Tidak ada wisata yang sesuai dengan kriteria Anda.</p>
    <?php endif; ?>
  </div>
</div>
<a href="dashboard_user.php?hal=home" class="btn-back">Kembali</a>
</body>
</html>
