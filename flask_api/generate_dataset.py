import os
import pandas as pd
import numpy as np
import random

# Tentukan path file
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_DIR = os.path.join(BASE_DIR, 'rekomendasi-makanan', 'rekomendasi-makanan')
menu_dataset_path = os.path.join(MODEL_DIR, 'dataset', 'menu.csv')
output_path = os.path.join(MODEL_DIR, 'dataset', 'user_history_dataset.csv')

def generate_synthetic_data(num_samples=1200):
    print(f"Membaca data menu dari: {menu_dataset_path}")
    
    try:
        df_menu = pd.read_csv(menu_dataset_path)
    except FileNotFoundError:
        print("Dataset menu.csv tidak ditemukan. Pastikan path sudah benar.")
        return

    # Kategori unik yang ada di menu
    kategori_list = df_menu['kategori'].unique().tolist()
    
    data = []
    
    for i in range(num_samples):
        user_id = i + 1
        
        # Simulasi profil user
        user_budget = random.choice([10000, 15000, 20000, 25000, 35000, 50000, 75000, 100000])
        user_preferensi = random.choice(kategori_list)
        
        # Pilih menu secara acak (bisa beberapa kali untuk satu user jika mau, tapi kita buat 1 baris per interaksi)
        menu_row = df_menu.sample(1).iloc[0]
        menu_id = menu_row['id']
        menu_harga = menu_row['harga']
        menu_kategori = menu_row['kategori']
        
        # Logika Cocok (Target = 1) atau Tidak Cocok (Target = 0)
        # 1. Budget cukup
        # 2. Kategori sesuai preferensi (atau kasih peluang kecil untuk nyoba kategori lain)
        
        budget_cukup = user_budget >= menu_harga
        kategori_pas = user_preferensi == menu_kategori
        
        if budget_cukup and kategori_pas:
            # Sangat cocok, 95% kemungkinan dibeli/disukai
            cocok = np.random.choice([1, 0], p=[0.95, 0.05])
        elif budget_cukup and not kategori_pas:
            # Budget masuk tapi beda preferensi, peluang kecil disukai
            cocok = np.random.choice([1, 0], p=[0.2, 0.8])
        else:
            # Budget tidak cukup, otomatis tidak bisa beli (0)
            cocok = 0
            
        data.append({
            'user_id': user_id,
            'user_budget': user_budget,
            'user_preferensi': user_preferensi,
            'menu_id': menu_id,
            'cocok': cocok
        })
        
    df_synthetic = pd.DataFrame(data)
    
    # Simpan ke CSV
    df_synthetic.to_csv(output_path, index=False)
    print(f"Berhasil membuat dataset simulasi sebanyak {num_samples} baris!")
    print(f"Dataset disimpan di: {output_path}")
    print("\nSample Data:")
    print(df_synthetic.head(10))
    
    # Cek keseimbangan kelas (target 1 vs 0)
    print("\nDistribusi Target (Cocok 1 / 0):")
    print(df_synthetic['cocok'].value_counts())

if __name__ == '__main__':
    generate_synthetic_data(1500)  # Kita buat 1500 baris agar model bisa belajar lebih baik
