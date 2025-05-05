import mysql.connector
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

# Fungsi untuk menghitung rekomendasi menggunakan cosine similarity
def get_recommendations(wisata_id, data_wisata):
    # Ambil deskripsi wisata yang dipilih
    wisata_pilihan = data_wisata[data_wisata['id_wisata'] == wisata_id]['deskripsi_processed'].values[0]

    # Vectorisasi deskripsi wisata
    tfidf = TfidfVectorizer()
    tfidf_matrix = tfidf.fit_transform(data_wisata['deskripsi_processed'])

    # Hitung cosine similarity antara wisata yang dipilih dengan semua wisata lain
    cosine_sim = cosine_similarity(tfidf.transform([wisata_pilihan]), tfidf_matrix)

    # Urutkan wisata berdasarkan skor similarity (descending)
    similarity_scores = list(enumerate(cosine_sim[0]))
    similarity_scores = sorted(similarity_scores, key=lambda x: x[1], reverse=True)

    # Ambil top 5 rekomendasi (tidak termasuk wisata yang dipilih)
    recommendations = []
    for i, score in similarity_scores[1:6]:
        recommendations.append((data_wisata['id_wisata'][i], score))
    
    return recommendations

# Fungsi untuk menyimpan hasil rekomendasi ke dalam tabel tbl_rekomendasi
def save_recommendations_to_db(wisata_id, recommendations):
    # Koneksi ke database
    conn = mysql.connector.connect(
        host="localhost",
        user="root",  # Ganti dengan username database kamu
        password="",  # Ganti dengan password database kamu
        database="dbs_wisata"  # Nama database kamu
    )
    cursor = conn.cursor()

    # Menyusun query untuk memasukkan data ke tbl_rekomendasi
    query = """
        INSERT INTO tbl_rekomendasi (id_wisata, id_rekomendasi_wisata, similarity_score)
        VALUES (%s, %s, %s)
    """
    
    # Menyusun data rekomendasi
    # Konversi id_wisata dan id_rekomendasi_wisata ke tipe int
    data = [(int(wisata_id), int(rec[0]), float(rec[1])) for rec in recommendations]

    try:
        # Menyimpan hasil rekomendasi ke dalam database
        cursor.executemany(query, data)
        conn.commit()
        print(f"Hasil rekomendasi untuk wisata ID {wisata_id} berhasil disimpan.")
    except mysql.connector.Error as err:
        print(f"Error: {err}")
    finally:
        cursor.close()
        conn.close()

# Mengambil data wisata dari database menggunakan mysql-connector
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="dbs_wisata"
)
query = "SELECT id_wisata, deskripsi_processed FROM tbl_wisata"
cursor = conn.cursor()
cursor.execute(query)

# Memasukkan hasil query ke dalam dataframe pandas
data_wisata = pd.DataFrame(cursor.fetchall(), columns=['id_wisata', 'deskripsi_processed'])

cursor.close()
conn.close()

# Loop untuk menghitung rekomendasi untuk setiap wisata
for wisata_id in data_wisata['id_wisata']:
    recommendations = get_recommendations(wisata_id, data_wisata)
    save_recommendations_to_db(wisata_id, recommendations)
