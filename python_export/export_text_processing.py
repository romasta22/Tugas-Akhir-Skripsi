import mysql.connector
import pandas as pd
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords
import string
from tqdm import tqdm
from openpyxl import load_workbook
from openpyxl.utils.dataframe import dataframe_to_rows
from openpyxl.worksheet.filters import AutoFilter

# Koneksi Database
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="dbs_wisata"
)

cursor = db.cursor()

# Ambil data deskripsi dan kategori
cursor.execute("SELECT w.id_wisata, w.nama_wisata, w.deskripsi, w.id_kategori FROM tbl_wisata w")
data = cursor.fetchall()

# Mapping ID Kategori ke Nama Kategori
kategori_mapping = {
    1: "Wisata Alam",
    2: "Wisata Budaya",
    3: "Wisata Kuliner",
    4: "Akomodasi Penginapan"
}

# Inisialisasi Stemming
factory = StemmerFactory()
stemmer = factory.create_stemmer()

stop_words = set(stopwords.words('indonesian'))

rows = []

print("Memproses data...")

for row in tqdm(data, desc="Progress", unit="data"):
    id_wisata, nama, deskripsi, id_kategori = row
    kategori = kategori_mapping.get(id_kategori, "Tidak Diketahui")
    lower = deskripsi.lower()
    tokens = word_tokenize(lower)
    filtered = [word for word in tokens if word not in stop_words and word not in string.punctuation]
    stemming = [stemmer.stem(word) for word in filtered]
    hasil_akhir = ' '.join(stemming)
    rows.append([id_wisata, nama, kategori, deskripsi, lower, str(tokens), str(filtered), str(stemming), hasil_akhir])

# Buat DataFrame Pandas
df = pd.DataFrame(rows, columns=["ID Wisata", "Nama Wisata", "Kategori", "Deskripsi Asli", "Lowercassing", "Tokenizing", "Filtering", "Stemming", "Hasil Akhir"])

# Ekspor ke Excel
filename = "Coding_Sheet_Text_Processing.xlsx"
df.to_excel(filename, index=False)

# Tambahkan Auto Filter
wb = load_workbook(filename)
sheet = wb.active
sheet.auto_filter.ref = sheet.dimensions
wb.save(filename)

print("Export Berhasil dengan Auto Filter!")
