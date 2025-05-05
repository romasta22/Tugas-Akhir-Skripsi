import mysql.connector
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

# Koneksi ke database
conn = mysql.connector.connect(
    host="localhost",
    user="root",  # Sesuaikan dengan username database kamu
    password="",  # Sesuaikan dengan password database kamu
    database="dbs_wisata"  # Sesuaikan dengan nama database kamu
)

# Ambil data wisata dari database
query = "SELECT id_wisata, deskripsi_processed FROM tbl_wisata"
cursor = conn.cursor()
cursor.execute(query)

# Memasukkan hasil query ke dalam dataframe pandas
data_wisata = pd.DataFrame(cursor.fetchall(), columns=['id_wisata', 'deskripsi_processed'])

cursor.close()
conn.close()

# Vectorisasi deskripsi wisata menggunakan TF-IDF
tfidf = TfidfVectorizer(stop_words='english')
tfidf_matrix = tfidf.fit_transform(data_wisata['deskripsi_processed'])

# Hitung cosine similarity antara semua wisata
cosine_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)

# Pilih 6 ID wisata secara acak untuk ditampilkan
sample_wisata = data_wisata.sample(n=6, random_state=42)['id_wisata'].tolist()

print("\n=== Hasil Perhitungan Cosine Similarity ===\n")

# Loop untuk menampilkan hasil cosine similarity
for wisata_id in sample_wisata:
    # Ambil indeks wisata dari dataframe
    index = data_wisata[data_wisata['id_wisata'] == wisata_id].index[0]

    # Urutkan wisata berdasarkan skor similarity (descending), kecuali dirinya sendiri
    similarity_scores = list(enumerate(cosine_sim[index]))
    similarity_scores = sorted(similarity_scores, key=lambda x: x[1], reverse=True)

    # Ambil 2 rekomendasi teratas selain dirinya sendiri
    recommendations = similarity_scores[1:3]

    # Tampilkan hasil di terminal
    print(f"ID Wisata: {wisata_id}")
    for i, score in recommendations:
        print(f"  -> Rekomendasi: ID {data_wisata['id_wisata'][i]}, Skor: {score:.4f}")
    print("-" * 40)

