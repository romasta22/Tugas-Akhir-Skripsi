from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import mysql.connector

# Lokasi ChromeDriver
service = Service("C:/xampp/htdocs/wisataDanauToba/scraping/chromedriver-win64/chromedriver.exe")

# Konfigurasi WebDriver
options = webdriver.ChromeOptions()
options.add_argument("--start-maximized")  # Membuka browser dalam mode fullscreen

# Inisialisasi WebDriver
driver = webdriver.Chrome(service=service, options=options)

# URL Google Search untuk scraping
url = "https://www.google.com/search?q=wisata+wisata+danau+toba"
# url = "https://lh5.googleusercontent.com/p/AF1QipOVV6mgBfhdFaf1fNDCnNEeziovAoOfz_iItYzu=w148-h148-n-k-no"

driver.get(url)

# Tunggu hingga halaman selesai dimuat
WebDriverWait(driver, 10).until(EC.presence_of_all_elements_located((By.CSS_SELECTOR, ".tF2Cxc")))

# Temukan elemen hasil pencarian Google
results = driver.find_elements(By.CSS_SELECTOR, ".tF2Cxc")

# Ambil data untuk dua hasil pertama
data_wisata = []
for i in range(2):  # Ambil hanya dua wisata pertama
    if i >= len(results):
        break

    try:
        nama = results[i].find_element(By.CSS_SELECTOR, "h3").text
    except Exception as e:
        nama = "Nama tidak ditemukan"
        print(f"Error saat mengambil nama: {e}")

    try:
        link = results[i].find_element(By.CSS_SELECTOR, "a").get_attribute("href")
    except Exception as e:
        link = "Link tidak ditemukan"
        print(f"Error saat mengambil link: {e}")

    try:
        deskripsi = results[i].find_element(By.CSS_SELECTOR, ".VwiC3b").text
    except Exception as e:
        deskripsi = "Deskripsi tidak ditemukan"
        print(f"Error saat mengambil deskripsi: {e}")

    data_wisata.append({
        "nama": nama,
        "link": link,
        "deskripsi": deskripsi
    })

# Cetak data yang berhasil diambil
print("Data yang diambil:", data_wisata)

# Tutup browser setelah selesai
driver.quit()

# Koneksi ke database MySQL
try:
    dbs = mysql.connector.connect(
        host="localhost",
        user="root",  # Ganti dengan username MySQL Anda
        password="",  # Ganti dengan password MySQL Anda
        database="dbs_wisata"  # Nama database Anda
    )
    print("Koneksi ke database berhasil.")
except mysql.connector.Error as e:
    print(f"Gagal terhubung ke database: {e}")
    exit()

cursor = dbs.cursor()

# Simpan data hasil scraping ke tabel tbl_wisata
for wisata in data_wisata:
    nama_wisata = wisata['nama']
    deskripsi = wisata['deskripsi']
    lokasi_wisata = "Lokasi tidak tersedia"  # Placeholder jika lokasi tidak ditemukan
    link_peta = wisata['link']
    id_kategori = 1  # Set nilai default untuk id_kategori
    rating = None  # Kosongkan jika rating tidak tersedia
    ulasan = None  # Kosongkan jika ulasan tidak tersedia

    sql = """
        INSERT INTO tbl_wisata (nama_wisata, id_kategori, lokasi_wisata, link_peta, deskripsi, rating, ulasan)
        VALUES (%s, %s, %s, %s, %s, %s, %s)
    """
    
    try:
        cursor.execute(sql, (nama_wisata, id_kategori, lokasi_wisata, link_peta, deskripsi, rating, ulasan))
        print(f"Data yang dimasukkan: {nama_wisata}, {link_peta}, {deskripsi}")
    except mysql.connector.Error as e:
        print(f"Error saat memasukkan data ke database: {e}")
        print(f"Query: {cursor.statement}")

# Commit perubahan
try:
    dbs.commit()
    print("Perubahan disimpan ke database.")
except mysql.connector.Error as e:
    print(f"Error saat commit: {e}")

# Tutup koneksi database
dbs.close()