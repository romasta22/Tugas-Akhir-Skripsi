import mysql.connector
import re
import nltk
from nltk.corpus import stopwords
from nltk.stem import PorterStemmer
from nltk.tokenize import word_tokenize

# Mengunduh stopwords dari NLTK jika belum diunduh
nltk.download('stopwords')
nltk.download('punkt')

# Koneksi ke database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",  # Sesuaikan dengan password MySQL kamu
    database="dbs_wisata"
)
cursor = conn.cursor()

# Fungsi untuk preprocessing teks
def preprocess(text):
    text = text.lower()  # Mengubah teks menjadi huruf kecil
    text = re.sub(r'[^a-z\s]', '', text)  # Menghapus karakter selain huruf dan spasi
    words = word_tokenize(text)  # Tokenisasi kata
    stop_words = set(stopwords.words('indonesian'))  # Mengambil stopwords bahasa Indonesia
    words = [word for word in words if word not in stop_words]  # Menghapus stopwords
    stemmer = PorterStemmer()  # Inisialisasi stemming
    words = [stemmer.stem(word) for word in words]  # Stemming kata-kata
    return ' '.join(words)  # Menggabungkan kata-kata kembali menjadi teks

# Pilih satu data wisata berdasarkan ID
id_wisata = 1  # Ubah sesuai dengan ID wisata yang ingin diuji
cursor.execute("SELECT id_wisata, deskripsi FROM tbl_wisata WHERE id_wisata = %s", (id_wisata,))
wisata_data = cursor.fetchone()

if wisata_data:
    print(f"\n=== SEBELUM PREPROCESSING ===\n{wisata_data[1]}")  # Menampilkan deskripsi asli
    deskripsi_processed = preprocess(wisata_data[1])
    print(f"\n=== SESUDAH PREPROCESSING ===\n{deskripsi_processed}")  # Menampilkan hasil preprocessing
else:
    print("Data wisata tidak ditemukan!")

# Menutup koneksi database
cursor.close()
conn.close()
