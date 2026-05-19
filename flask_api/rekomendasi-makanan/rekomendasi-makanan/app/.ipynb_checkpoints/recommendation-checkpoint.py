import pandas as pd
import joblib

# ==========================================
# LOAD DATASET
# ==========================================

df = pd.read_csv('../dataset/menu.csv')

# ==========================================
# LOAD MODEL
# ==========================================

tfidf = joblib.load('../model/tfidf_vectorizer.pkl')

cosine_sim = joblib.load('../model/cosine_similarity.pkl')

indices = joblib.load('../model/menu_indices.pkl')

# ==========================================
# FUNCTION RECOMMENDATION
# ==========================================

def recommend_menu(nama_menu, top_n=5):

    idx = indices[nama_menu]

    sim_scores = list(enumerate(cosine_sim[idx]))

    sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)

    sim_scores = sim_scores[1:top_n+1]

    menu_indices = [i[0] for i in sim_scores]

    hasil = df[
        ['nama_menu', 'kategori', 'harga', 'gambar']
    ].iloc[menu_indices]

    return hasil

# ==========================================
# TEST
# ==========================================

hasil = recommend_menu('Nasi Ayam Bakar')

print(hasil)