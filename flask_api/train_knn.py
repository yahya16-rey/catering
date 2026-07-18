import os
import pandas as pd
import numpy as np
import joblib
from sklearn.model_selection import train_test_split
from sklearn.neighbors import KNeighborsClassifier
from sklearn.preprocessing import LabelEncoder, StandardScaler
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score, confusion_matrix, classification_report

# Tentukan path file
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_DIR = os.path.join(BASE_DIR, 'rekomendasi-makanan', 'rekomendasi-makanan')
dataset_path = os.path.join(MODEL_DIR, 'dataset', 'user_history_dataset.csv')
knn_model_path = os.path.join(MODEL_DIR, 'model', 'knn_model.pkl')
encoder_path = os.path.join(MODEL_DIR, 'model', 'label_encoder.pkl')
scaler_path = os.path.join(MODEL_DIR, 'model', 'scaler.pkl')

def train_and_evaluate_knn():
    print("1. Membaca Dataset...")
    df = pd.read_csv(dataset_path)
    print(f"Total baris data: {len(df)}")
    
    # Preprocessing
    print("2. Preprocessing Data...")
    X = df[['user_budget', 'user_preferensi', 'menu_id']].copy()
    y = df['cocok']
    
    # Encode categorical feature (user_preferensi)
    le = LabelEncoder()
    X['user_preferensi'] = le.fit_transform(X['user_preferensi'])
    
    # Scale numerical feature (user_budget)
    scaler = StandardScaler()
    X[['user_budget']] = scaler.fit_transform(X[['user_budget']])
    
    # Split data (80% training, 20% testing)
    print("3. Membagi Data (Train/Test Split)...")
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    print(f"Data Training: {len(X_train)} baris")
    print(f"Data Testing : {len(X_test)} baris")
    
    # Training Model
    print("4. Melatih Model K-Nearest Neighbors (KNN)...")
    knn = KNeighborsClassifier(n_neighbors=5) # K = 5 adalah default yang baik
    knn.fit(X_train, y_train)
    
    # Prediksi
    print("5. Melakukan Prediksi dan Evaluasi...")
    y_pred = knn.predict(X_test)
    
    # Metrics
    acc = accuracy_score(y_test, y_pred)
    prec = precision_score(y_test, y_pred)
    rec = recall_score(y_test, y_pred)
    f1 = f1_score(y_test, y_pred)
    cm = confusion_matrix(y_test, y_pred)
    
    print("\n" + "="*50)
    print("    HASIL EVALUASI K-NEAREST NEIGHBORS (KNN)    ")
    print("="*50)
    print(f"Accuracy  : {acc * 100:.2f}%")
    print(f"Precision : {prec * 100:.2f}%")
    print(f"Recall    : {rec * 100:.2f}%")
    print(f"F1-Score  : {f1 * 100:.2f}%")
    print("\nConfusion Matrix:")
    print("             Prediksi (0)   Prediksi (1)")
    print(f"Aktual (0) :      {cm[0][0]}              {cm[0][1]}")
    print(f"Aktual (1) :      {cm[1][0]}              {cm[1][1]}")
    print("="*50)
    
    # Save model and preprocessors
    print(f"\n6. Menyimpan model ke {MODEL_DIR}/model/ ...")
    joblib.dump(knn, knn_model_path)
    joblib.dump(le, encoder_path)
    joblib.dump(scaler, scaler_path)
    print("✅ Semua file model berhasil disimpan! Siap digunakan di app.py")

if __name__ == '__main__':
    train_and_evaluate_knn()
