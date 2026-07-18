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
knn_model_path = os.path.join(MODEL_DIR, 'model', 'knn_model.pkl')
encoder_path = os.path.join(MODEL_DIR, 'model', 'label_encoder.pkl')
scaler_path = os.path.join(MODEL_DIR, 'model', 'scaler.pkl')

print("Loading Data and KNN Models...")
try:
    # Load dataset
    df = pd.read_csv(dataset_path)
    
    # Load KNN models
    knn = joblib.load(knn_model_path)
    label_encoder = joblib.load(encoder_path)
    scaler = joblib.load(scaler_path)
    
    model_loaded = True
    print("SUCCESS: KNN Models loaded successfully!")
except Exception as e:
    print(f"WARNING: Failed to load models due to: {e}")
    model_loaded = False
    df = pd.DataFrame()

# VIRTUAL MEMORY (Untuk menyimpan histori rekomendasi ala Shopee)
# Struktur: {'user_id_1': {'last_viewed_menu_id': 3, 'last_viewed_kategori': 'dessert'}, ...}
USER_HISTORY = {}

@app.route('/view_menu', methods=['POST'])
def view_menu():
    """
    Endpoint untuk mencatat histori saat user membuka sebuah menu.
    Body JSON: {"user_id": "123", "menu_id": 5}
    """
    data = request.json
    user_id = str(data.get('user_id'))
    menu_id = int(data.get('menu_id'))
    
    if not user_id or not menu_id:
        return jsonify({"error": "user_id dan menu_id wajib diisi"}), 400
        
    menu_row = df[df['id'] == menu_id]
    if menu_row.empty:
        return jsonify({"error": "menu tidak ditemukan"}), 404
        
    kategori = menu_row.iloc[0]['kategori']
    
    # Simpan ke Virtual Memory
    USER_HISTORY[user_id] = {
        'last_viewed_menu_id': menu_id,
        'last_viewed_kategori': kategori
    }
    
    return jsonify({"message": "Histori berhasil disimpan", "history": USER_HISTORY[user_id]})

@app.route('/recommend_history', methods=['GET'])
def recommend_history():
    """
    Endpoint untuk mendapatkan rekomendasi mingguan/beranda berdasarkan histori KNN.
    Query Params: ?user_id=123&budget=25000
    """
    if not model_loaded:
        return jsonify({"error": "Model KNN belum dilatih. Jalankan train_knn.py dulu."}), 500
        
    user_id = str(request.args.get('user_id', ''))
    budget = request.args.get('budget', 20000)
    
    try:
        budget = float(budget)
    except ValueError:
        budget = 20000
        
    # Cek histori user
    if user_id not in USER_HISTORY:
        # Jika belum punya histori, kembalikan rekomendasi acak atau top seller
        random_menus = df.sample(4)[['id', 'nama_menu', 'kategori', 'harga']].to_dict(orient='records')
        return jsonify({
            "message": "Belum ada histori. Menampilkan rekomendasi acak.",
            "recommendations": random_menus
        })
        
    user_history = USER_HISTORY[user_id]
    last_kategori = user_history['last_viewed_kategori']
    last_menu_id = user_history['last_viewed_menu_id']
    
    # Siapkan data untuk diprediksi oleh KNN
    # Kita akan mengecek SEMUA menu yang ada di database, apakah akan disukai (Target 1) oleh user ini
    
    # Encode Kategori Histori
    # Gunakan label_encoder yang sudah dilatih (handle unseen label dengan fallback jika perlu)
    try:
        encoded_kategori = label_encoder.transform([last_kategori])[0]
    except ValueError:
        encoded_kategori = 0 # Fallback aman
        
    # Scale Budget
    import warnings
    with warnings.catch_warnings():
        warnings.simplefilter("ignore")
        scaled_budget = scaler.transform([[budget]])[0][0]
    
    predictions = []
    
    for _, row in df.iterrows():
        menu_id_target = row['id']
        
        # Jangan rekomendasikan barang yang sama persis dengan yang baru dilihat
        if menu_id_target == last_menu_id:
            continue
            
        # Bentuk fitur: [user_budget, user_preferensi, menu_id]
        features = np.array([[scaled_budget, encoded_kategori, menu_id_target]])
        
        # Prediksi probabilitas (Berapa persen kemungkinan Cocok = 1)
        proba = knn.predict_proba(features)[0]
        
        # proba[1] adalah probabilitas kelas 1 (Cocok)
        prob_cocok = proba[1] if len(proba) > 1 else 0
        
        predictions.append({
            'menu_id': menu_id_target,
            'nama_menu': row['nama_menu'],
            'kategori': row['kategori'],
            'harga': row['harga'],
            'probabilitas_cocok': prob_cocok
        })
        
    # Urutkan berdasarkan probabilitas paling tinggi
    predictions = sorted(predictions, key=lambda x: x['probabilitas_cocok'], reverse=True)
    
    # Ambil Top 4 Rekomendasi
    top_4_recommendations = predictions[:4]
    
    return jsonify({
        "message": f"Rekomendasi berdasarkan histori Kategori: {last_kategori}",
        "user_history": user_history,
        "recommendations": top_4_recommendations
    })

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
