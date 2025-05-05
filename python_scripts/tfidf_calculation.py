import mysql.connector
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer

# Koneksi ke database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="dbs_wisata"
)
cursor = conn.cursor()

# Mengambil data deskripsi wisata yang telah diproses
cursor.execute("SELECT id_wisata, deskripsi_processed FROM tbl_wisata")
wisata_data = cursor.fetchall()

# Pastikan ada data untuk diproses
if not wisata_data:
    print("Tidak ada data wisata yang ditemukan.")
    exit()

# Memisahkan ID dan Deskripsi
id_wisata_list = [str(row[0]) for row in wisata_data]
descriptions = [row[1] for row in wisata_data]

# Inisialisasi TF-IDF Vectorizer
vectorizer = TfidfVectorizer()

# Menghitung TF-IDF
tfidf_matrix = vectorizer.fit_transform(descriptions)

# Mendapatkan daftar kata unik (term)
terms = vectorizer.get_feature_names_out()

# Menghitung IDF (Inverse Document Frequency)
idf_values = vectorizer.idf_

# Menghapus data lama di tabel `tbl_tfidf` agar tidak duplikasi
cursor.execute("DELETE FROM tbl_tfidf")
conn.commit()

# Menyimpan hasil perhitungan ke database
for i, id_wisata in enumerate(id_wisata_list):
    tfidf_scores = tfidf_matrix[i].toarray().flatten()  # Ambil TF-IDF untuk wisata ini
    
    for term_idx, term in enumerate(terms):
        tf = tfidf_scores[term_idx] / idf_values[term_idx]  # Hitung TF dari TF-IDF
        idf = idf_values[term_idx]  # Ambil nilai IDF
        tfidf_score = tfidf_scores[term_idx]  # Nilai TF-IDF
        
        if tfidf_score > 0:  # Simpan hanya jika ada nilai
            query = """
                INSERT INTO tbl_tfidf (id_wisata, term, tf, idf, tfidf_score) 
                VALUES (%s, %s, %s, %s, %s)
            """
            cursor.execute(query, (id_wisata, term, float(tf), float(idf), float(tfidf_score)))

# Simpan perubahan
conn.commit()

# Tutup koneksi database
cursor.close()
conn.close()

print("Perhitungan TF-IDF selesai dan data telah disimpan ke tbl_tfidf.")
