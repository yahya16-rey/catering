import os
from flask import Flask, request, jsonify
import pandas as pd
import numpy as np
import joblib

app = Flask(__name__)

# Construct paths relative to this file
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_DIR = os.path.join(BASE_DIR, 'rekomendasi-makanan', 'rekomendasi-makanan')

dataset_path = os.path.join(MODEL_DIR, 'dataset', 'menu.csv')
tfidf_path = os.path.join(MODEL_DIR, 'model', 'tfidf_vectorizer.pkl')
cosine_sim_path = os.path.join(MODEL_DIR, 'model', 'cosine_similarity.pkl')
indices_path = os.path.join(MODEL_DIR, 'model', 'menu_indices.pkl')

print(f"Loading dataset from: {dataset_path}")
print(f"Loading TF-IDF vectorizer from: {tfidf_path}")
print(f"Loading Cosine Similarity Matrix from: {cosine_sim_path}")
print(f"Loading Menu Indices from: {indices_path}")

try:
    # Load dataset
    df = pd.read_csv(dataset_path)
    
    # Load pickles using joblib
    tfidf = joblib.load(tfidf_path)
    cosine_sim = joblib.load(cosine_sim_path)
    indices = joblib.load(indices_path)
    
    model_loaded = True
    print("SUCCESS: User trained models loaded successfully!")
except Exception as e:
    print(f"WARNING: Failed to load user pickles due to: {e}")
    # Fallback to local dataframe and dynamic TF-IDF calculation
    model_loaded = False
    
    # Default corpus fallback
    MENUS_CORPUS = [
        {'nama_menu': 'Kue Lapis', 'kategori': 'Dessert', 'deskripsi': 'kue lapis tradisional manis lembut', 'harga': 12000},
        {'nama_menu': 'Kelepon', 'kategori': 'Dessert', 'deskripsi': 'kelepon isi gula merah kelapa', 'harga': 10000},
        {'nama_menu': 'Onde Onde', 'kategori': 'Dessert', 'deskripsi': 'onde onde wijen isi kacang hijau', 'harga': 12000},
        {'nama_menu': 'Kue Putu', 'kategori': 'Dessert', 'deskripsi': 'kue putu pandan isi gula merah', 'harga': 10000},
        {'nama_menu': 'Bika Ambon', 'kategori': 'Dessert', 'deskripsi': 'bika ambon khas medan lembut', 'harga': 15000},
        {'nama_menu': 'Serabi', 'kategori': 'Dessert', 'deskripsi': 'serabi tradisional gurih manis', 'harga': 10000},
        {'nama_menu': 'Donat', 'kategori': 'Dessert', 'deskripsi': 'donat topping coklat warna warni', 'harga': 12000},
        {'nama_menu': 'Risol', 'kategori': 'Snack', 'deskripsi': 'risol goreng isi sayur dan mayo', 'harga': 10000},
        {'nama_menu': 'Lemper', 'kategori': 'Snack', 'deskripsi': 'lemper ayam dibungkus daun pisang', 'harga': 10000},
        {'nama_menu': 'Bronis', 'kategori': 'Dessert', 'deskripsi': 'brownies coklat lembut manis', 'harga': 15000},
        {'nama_menu': 'Paket Specials', 'kategori': 'Corporate', 'deskripsi': 'paket catering menu spesial lengkap', 'harga': 30000},
        {'nama_menu': 'Paket Hemat', 'kategori': 'Personal', 'deskripsi': 'paket catering hemat ekonomis', 'harga': 25000},
        {'nama_menu': 'Paket Sultan', 'kategori': 'Event', 'deskripsi': 'paket catering premium lengkap', 'harga': 50000},
        {'nama_menu': 'Birthday Cake', 'kategori': 'Event', 'deskripsi': 'custom birthday cake berbagai rasa', 'harga': 75000},
        {'nama_menu': 'Snackbox Hemat', 'kategori': 'Personal', 'deskripsi': 'paket snackbox hemat isi lengkap', 'harga': 20000},
        {'nama_menu': 'Snackbox Hajat', 'kategori': 'Event', 'deskripsi': 'snackbox acara hajatan lengkap', 'harga': 25000},
        {'nama_menu': 'Snackbox Sultan', 'kategori': 'Corporate', 'deskripsi': 'snackbox premium isi lengkap', 'harga': 35000},
        {'nama_menu': 'Nasi Kuning', 'kategori': 'Personal', 'deskripsi': 'nasi kuning lauk lengkap', 'harga': 25000},
        {'nama_menu': 'Nasi Ayam Geprek', 'kategori': 'Personal', 'deskripsi': 'nasi ayam geprek sambal pedas', 'harga': 22000},
        {'nama_menu': 'Nasi Ayam Bakar', 'kategori': 'Corporate', 'deskripsi': 'nasi ayam bakar bumbu khas', 'harga': 25000},
        {'nama_menu': 'Nasi Katsu', 'kategori': 'Personal', 'deskripsi': 'nasi ayam katsu saus spesial', 'harga': 25000},
        {'nama_menu': 'Nasi Ayam Uduk', 'kategori': 'Personal', 'deskripsi': 'nasi uduk ayam lengkap', 'harga': 23000},
        {'nama_menu': 'Nasi Bakar', 'kategori': 'Personal', 'deskripsi': 'nasi bakar isi ayam dan sambal', 'harga': 22000},
        {'nama_menu': 'Nasi Goreng', 'kategori': 'Corporate', 'deskripsi': 'nasi goreng seafood spesial', 'harga': 20000},
        {'nama_menu': 'Seblak', 'kategori': 'Personal', 'deskripsi': 'seblak pedas topping lengkap', 'harga': 18000}
    ]
    df = pd.DataFrame(MENUS_CORPUS)
    from sklearn.feature_extraction.text import TfidfVectorizer
    from sklearn.metrics.pairwise import cosine_similarity
    tfidf = TfidfVectorizer(stop_words='english')
    tfidf_matrix = tfidf.fit_transform(df['deskripsi'])
    cosine_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)
    indices = pd.Series(df.index, index=df['nama_menu']).to_dict()

def get_content_based_recommendations(menu_name, top_n=4):
    """
    Get top recommendations based on TF-IDF description similarity using loaded pickles
    """
    # Verify key exists
    if menu_name not in indices:
        # Case insensitive backup check
        matched = [k for k in indices.keys() if k.lower() == menu_name.lower()]
        if matched:
            idx = indices[matched[0]]
        else:
            # Fallback if menu name not found
            return list(df['nama_menu'].head(top_n))
    else:
        idx = indices[menu_name]
        
    # Get similarity scores from the loaded cosine matrix
    sim_scores = list(enumerate(cosine_sim[idx]))
    
    # Sort descending
    sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
    
    # Get top_n recommendations (excluding the current menu index)
    recommendation_indices = [i[0] for i in sim_scores if i[0] != idx][:top_n]
    
    # Fill in case not enough similarity
    if len(recommendation_indices) < top_n:
        for i in range(len(df)):
            if i != idx and i not in recommendation_indices:
                recommendation_indices.append(i)
                if len(recommendation_indices) == top_n:
                    break
                    
    return list(df.iloc[recommendation_indices]['nama_menu'])

@app.route('/recommend', methods=['GET'])
def recommend():
    menu_name = request.args.get('menu', '')
    diet = request.args.get('diet', '')
    budget = request.args.get('budget', '')
    allergies = request.args.get('allergies', '')

    if not menu_name:
        return jsonify([])

    # Get recommendations based on content similarity
    recommended_menus = get_content_based_recommendations(menu_name, top_n=4)

    # Filter recommended menus based on optional criteria
    filtered_recommendations = []
    for menu in recommended_menus:
        menu_row = df[df['nama_menu'] == menu].iloc[0]
        
        # Apply budget filter
        if budget:
            try:
                if menu_row['harga'] > float(budget):
                    continue
            except ValueError:
                pass
                
        # Apply simple allergen keyword check
        if allergies:
            allergen_matched = False
            for allergen in allergies.lower().split(','):
                allergen = allergen.strip()
                if allergen and (allergen in str(menu_row['deskripsi']).lower() or allergen in str(menu_row['nama_menu']).lower()):
                    allergen_matched = True
                    break
            if allergen_matched:
                continue

        filtered_recommendations.append(menu)

    # Ensure we return at least 3 items by falling back to base recommendations if filters are too strict
    if len(filtered_recommendations) < 3:
        filtered_recommendations = recommended_menus[:3]

    return jsonify(filtered_recommendations)

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=True)
