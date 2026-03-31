<?php
// 1. VERİTABANI BAĞLANTISI
$conn = new mysqli("127.0.0.1", "root", "", "odev_db", 3306);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// 2. YENİ RESİM EKLEME İŞLEMİ
if (isset($_POST['ekle'])) {
    $url = $_POST['resim_url'];
    $aciklama = $_POST['aciklama'];
    
    $sql = "INSERT INTO album (resim_url, aciklama) VALUES ('$url', '$aciklama')";
    $conn->query($sql);
    
    header("Location: index.php");
    exit;
}

// 3. SİLME İŞLEMİ
if (isset($_GET['sil'])) {
    $sil_id = $_GET['sil'];
    $conn->query("DELETE FROM album WHERE id = $sil_id");
    header("Location: index.php");
    exit;
}

// 4. GÜNCELLEME İŞLEMİ (Popup içindeki formdan gelir)
if (isset($_POST['guncelle'])) {
    $guncellenecek_id = $_POST['id'];
    $yeni_url = $_POST['resim_url'];
    $yeni_aciklama = $_POST['aciklama'];
    $conn->query("UPDATE album SET resim_url = '$yeni_url', aciklama = '$yeni_aciklama' WHERE id = $guncellenecek_id");
    header("Location: index.php");
    exit;
}
?>

<!doctype html>
<html lang="tr" data-bs-theme="auto">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PHP & MySQL Albüm Ödevi</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  </head>
  <body>
    
    <header data-bs-theme="dark">
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
          <a href="#" class="navbar-brand d-flex align-items-center">
            <strong>📸 Benim Albümüm</strong>
          </a>
        </div>
      </div>
    </header>
    
    <main>
      <!-- ÜST KISIM: RESİM EKLEME FORMU -->
      <section class="py-5 text-center container">
        <div class="row py-lg-5">
          <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">Yeni Fotoğraf Ekle</h1>
            <p class="lead text-body-secondary">
              İnternetten bulduğun bir resmin URL'sini ve açıklamasını aşağıya girerek albüme ekleyebilirsin.
            </p>
            
            <form method="POST" action="index.php" class="card p-4 shadow-sm mt-4 text-start">
                <div class="mb-3">
                    <label class="form-label fw-bold">Resim URL'si</label>
                    <input type="text" name="resim_url" class="form-control" placeholder="Örn: https://site.com/resim.jpg" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Resim Açıklaması</label>
                    <textarea name="aciklama" class="form-control" rows="3" placeholder="Bu resim ne hakkında?" required></textarea>
                </div>
                <button type="submit" name="ekle" class="btn btn-success w-100">Albüme Ekle</button>
            </form>

          </div>
        </div>
      </section>

      <!-- ALT KISIM: ALBÜM KARTLARI (VERİTABANINDAN ÇEKİLİYOR) -->
      <div class="album py-5 bg-body-tertiary">
        <div class="container">
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            
            <?php
            $sorgu = $conn->query("SELECT * FROM album ORDER BY id DESC");
            
            if ($sorgu->num_rows == 0) {
                echo "<div class='col-12 text-center'><p class='text-muted'>Albümde henüz hiç fotoğraf yok. Yukarıdan eklemeye başla!</p></div>";
            }

            while ($row = $sorgu->fetch_assoc()):
            ?>
            <div class="col">
              <div class="card shadow-sm h-100">
                <img src="<?php echo $row['resim_url']; ?>" class="card-img-top" style="height: 225px; object-fit: cover;" alt="Albüm Resmi">
                
                <div class="card-body d-flex flex-column">
                  <p class="card-text flex-grow-1">
                    <?php echo htmlspecialchars($row['aciklama']); ?>
                  </p>
                  
                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="btn-group">
                      
                      <!-- GÜNCELLE BUTONU (Artık sayfayı yenilemiyor, popup'ı açıyor) -->
                      <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#duzenleModal<?php echo $row['id']; ?>">
                        Düzenle
                      </button>

                      <a href="index.php?sil=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu resmi silmek istediğine emin misin?')">Sil</a>
                    </div>
                    <small class="text-body-secondary fw-bold">
                        <?php echo date('d.m.Y', strtotime($row['ekleme_tarihi'])); ?>
                    </small>
                  </div>
                </div>
              </div>
            </div>

            <!-- DÜZENLEME İÇİN POPUP (MODAL) BAŞLANGICI -->
            <!-- Her resim için arka planda gizli bir popup oluşturuluyor -->
            <div class="modal fade" id="duzenleModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="duzenleModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  
                  <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="duzenleModalLabel<?php echo $row['id']; ?>">Fotoğrafı Düzenle</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                  </div>
                  
                  <div class="modal-body">
                    <!-- POPUP İÇİ GÜNCELLEME FORMU -->
                    <form method="POST" action="index.php">
                        <!-- Hangi ID'yi güncellediğimizi tutan gizli input -->
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        
                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold">Resim URL'si</label>
                            <input type="text" name="resim_url" class="form-control" value="<?php echo htmlspecialchars($row['resim_url']); ?>" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold">Resim Açıklaması</label>
                            <textarea name="aciklama" class="form-control" rows="4" required><?php echo htmlspecialchars($row['aciklama']); ?></textarea>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                            <button type="submit" name="guncelle" class="btn btn-primary">Değişiklikleri Kaydet</button>
                        </div>
                    </form>
                  </div>

                </div>
              </div>
            </div>
            <!-- POPUP BİTİŞİ -->

            <?php endwhile; ?>

          </div>
        </div>
      </div>
    </main>

    <footer class="text-body-secondary py-5 border-top mt-5">
      <div class="container text-center">
        <p class="mb-1">&copy; 2026 PHP Ödevi</p>
      </div>
    </footer>
    
    <!-- Bootstrap JS (Popupların ve menülerin çalışması için şart) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>