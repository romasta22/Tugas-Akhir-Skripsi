import mysql.connector
from tabulate import tabulate

# Koneksi ke database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="dbs_wisata"
)
cursor = conn.cursor()

# Ambil hanya 6 ID wisata pertama yang unik
cursor.execute("""
    SELECT DISTINCT id_wisata FROM tbl_tfidf ORDER BY id_wisata LIMIT 6
""")
id_wisata_list = [row[0] for row in cursor.fetchall()]

# Ambil data berdasarkan ID wisata yang sudah dipilih, tiap ID hanya tampilkan 2 baris (kata unik)
cursor.execute(f"""
    SELECT id_wisata, term, tf, idf, tfidf_score
    FROM tbl_tfidf
    WHERE id_wisata IN ({','.join(map(str, id_wisata_list))})
    ORDER BY id_wisata, tfidf_score DESC
""")

data = cursor.fetchall()

# Filter agar setiap ID wisata hanya menampilkan 2 baris
filtered_data = []
count_per_wisata = {id_wisata: 0 for id_wisata in id_wisata_list}

for row in data:
    id_wisata = row[0]
    if count_per_wisata[id_wisata] < 2:
        filtered_data.append(row)
        count_per_wisata[id_wisata] += 1

# Menampilkan hasil dalam bentuk tabel
headers = ["ID Wisata", "Term", "TF", "IDF", "TF-IDF"]
print(tabulate(filtered_data, headers=headers, tablefmt="grid"))

# Tutup koneksi database
cursor.close()
conn.close()
