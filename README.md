<img width="2533" height="1271" alt="Ekran görüntüsü 2026-03-31 200337" src="https://github.com/user-attachments/assets/f87c8a9f-59d1-488d-9fd7-3beb3b842571" />
<img width="2557" height="1272" alt="image" src="https://github.com/user-attachments/assets/bf7a8f70-287c-474c-9110-efc78c55bfc8" />


# 📸 PHP & MySQL Albüm Projesi

Bu proje, kullanıcıların internetten buldukları görselleri URL ile ekleyebildiği basit bir **fotoğraf albümü uygulamasıdır**.
PHP ve MySQL kullanılarak geliştirilmiştir.

## 🚀 Özellikler

* 📥 Resim URL'si ile fotoğraf ekleme
* 📝 Fotoğraflara açıklama ekleme
* ✏️ Popup (modal) ile fotoğraf düzenleme
* ❌ Fotoğraf silme
* 📅 Eklenme tarihini görüntüleme
* 🎨 Bootstrap ile modern arayüz

## 🛠️ Kullanılan Teknolojiler

* PHP
* MySQL
* HTML5 / CSS3
* Bootstrap 5

## ⚙️ Kurulum

1. Bu repoyu indir veya klonla:

   ```bash
   git clone https://github.com/24020091021EyupHalitInci/AlbumProjesi
   ```

2. Projeyi bir localhost ortamına koy:

   * XAMPP / WAMP / Laragon kullanabilirsin

3. MySQL'de `odev_db` adında bir veritabanı oluştur

4. Aşağıdaki SQL kodunu çalıştır:

   ```sql
   CREATE TABLE album (
       id INT AUTO_INCREMENT PRIMARY KEY,
       resim_url TEXT NOT NULL,
       aciklama TEXT NOT NULL,
       ekleme_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

5. Tarayıcıdan projeyi aç:

   ```
   http://localhost/proje-klasoru
   ```
