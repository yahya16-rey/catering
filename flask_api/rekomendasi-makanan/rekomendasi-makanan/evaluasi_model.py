import os
import pandas as pd
import joblib

# Definisi Path
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
dataset_path = os.path.join(BASE_DIR, 'dataset', 'menu.csv')
tfidf_path = os.path.join(BASE_DIR, 'model', 'tfidf_vectorizer.pkl')
cosine_sim_path = os.path.join(BASE_DIR, 'model', 'cosine_similarity.pkl')
indices_path = os.path.join(BASE_DIR, 'model', 'menu_indices.pkl')

print("Loading data dan model...")
df = pd.read_csv(dataset_path)
cosine_sim = joblib.load(cosine_sim_path)
indices = joblib.load(indices_path)

def recommend_menu(nama_menu, top_n=5):
    if nama_menu not in indices:
        return pd.DataFrame()
        
    idx = indices[nama_menu]
    sim_scores = list(enumerate(cosine_sim[idx]))
    sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
    sim_scores = sim_scores[1:top_n+1]
    menu_indices = [i[0] for i in sim_scores]
    return df[['nama_menu', 'kategori']].iloc[menu_indices]

print("\n" + "="*40)
print("     EVALUASI MODEL MACHINE LEARNING     ")
print("="*40)

total_data = len(df)
print(f"Total dataset digunakan : {total_data} data")
print("Metrik Evaluasi         : Mean Precision@K (Relevansi Kategori)")

K = 5
total_precision = 0

print("\nMenjalankan simulasi rekomendasi...")
for index, row in df.iterrows():
    nama_menu_target = row['nama_menu']
    kategori_target = row['kategori']
    
    hasil_rekomendasi = recommend_menu(nama_menu_target, top_n=K)
    
    hits = 0
    for _, rek_row in hasil_rekomendasi.iterrows():
        if rek_row['kategori'] == kategori_target:
            hits += 1
            
    precision = hits / K
    total_precision += precision

akurasi_model = (total_precision / total_data) * 100

print("\n" + "="*40)
print("          HASIL EVALUASI AKHIR           ")
print("="*40)
print(f"Tingkat Akurasi Model : {akurasi_model:.2f}%")
print(f"Kesimpulan:")
print(f"Model mampu mengenali data preferensi dan")
print(f"menghasilkan rekomendasi yang {akurasi_model:.2f}% akurat")
print("sesuai dengan riwayat dan kategori.")
print("="*40)
