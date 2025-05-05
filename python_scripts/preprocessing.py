import mysql.connector
import re
import nltk
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
from tqdm import tqdm

# Mengunduh stopwords dari NLTK jika belum diunduh
nltk.download('stopwords')
nltk.download('punkt')

# Koneksi ke database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",  # Ganti dengan password MySQL kamu
    database="dbs_wisata"
)
cursor = conn.cursor()

# Fungsi untuk melakukan preprocessing pada teks
def preprocess(text):
    text = text.lower()  # Lowercasing
    text = re.sub(r'[^a-z\s]', '', text)  # Cleaning (Menghapus karakter selain huruf dan spasi)
    words = word_tokenize(text)  # Tokenizing
    stop_words = set(stopwords.words('indonesian'))  # Filtering Stopwords
    words = [word for word in words if word not in stop_words]  # Filtering
    
    # Stemming pakai Sastrawi
    factory = StemmerFactory()
    stemmer = factory.create_stemmer()
    words = list(map(stemmer.stem, words))  # Stemming lebih cepat pakai map()

    return ' '.join(words)  # Menggabungkan kembali ke kalimat

# Mengambil data deskripsi wisata dari tabel
cursor.execute("SELECT id_wisata, deskripsi FROM tbl_wisata")
wisata_data = cursor.fetchall()

# Melakukan preprocessing dengan progress bar
print("Mulai Text Processing...")
for id_wisata, deskripsi in tqdm(wisata_data, desc="Processing", unit="data"):
    deskripsi_processed = preprocess(deskripsi)  # Preprocessing
    update_query = """
    UPDATE tbl_wisata
    SET deskripsi_processed = %s
    WHERE id_wisata = %s
    """
    cursor.execute(update_query, (deskripsi_processed, id_wisata))
    conn.commit()

# Menutup koneksi database
cursor.close()
conn.close()

print("Preprocessing selesai dan hasilnya telah disimpan di kolom deskripsi_processed.")
