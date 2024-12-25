import nltk
import pandas as pd
import re
import random
from flask import Flask, request, jsonify
from flask_cors import CORS
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.feature_extraction.text import TfidfVectorizer
from nltk.corpus import stopwords
import warnings
import json
import pickle
import numpy as np
from tensorflow.keras.models import Sequential, load_model
from tensorflow.keras.layers import Dense, Dropout
from tensorflow.keras.optimizers import SGD
import os

# Flask setup
app = Flask(__name__)
CORS(app)  # Enable CORS for cross-origin requests
warnings.filterwarnings("ignore")

# Download stopwords
nltk.download('stopwords')
nltk.download('punkt')
nltk.download('wordnet')

# ===================== Bagian Rekomendasi =====================

# Load dataset Deskriptif UKM
df = pd.read_csv("public/storage/python_data/Formulir Data Deskriptif ORMAWA, UKM, dan Study Club.csv", header=0)

# Pembobotan pada fitur
feature_weights = {
    'Deskripsi': 1.5,
    'Tujuan': 1.2,
    'Kategori': 1.0,
    'Kegiatan utama': 1.0,
}

clean_spcl = re.compile('[/(){}\[\]\|@,;]')
clean_symbol = re.compile('[^0-9a-z #+_]')
stopworda = set(stopwords.words('indonesian'))

def clean_text(text):
    text = text.lower()
    text = clean_spcl.sub(' ', text)
    text = clean_symbol.sub('', text)
    text = ' '.join(word for word in text.split() if word not in stopworda)
    return text

df['combined_info'] = df['Deskripsi'] + " " + df['Kategori'] + " " + df['Tujuan'] + " " + df['Kegiatan utama']
df['combined_clean'] = df['combined_info'].apply(clean_text)
df.reset_index(inplace=True)
df.set_index('Nama', inplace=True)

tfidf_vectorizer = TfidfVectorizer(analyzer='word', ngram_range=(1, 3), min_df=1, max_df=0.85, max_features=1000)
tfidf_matrix = tfidf_vectorizer.fit_transform(df['combined_clean'])

for col, weight in feature_weights.items():
    col_clean = df[col].apply(clean_text)
    col_tfidf = tfidf_vectorizer.transform(col_clean)
    tfidf_matrix += col_tfidf * weight

cos_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)

def process_user_input(user_input):
    return clean_text(user_input)

def recommendations_based_on_input_setelah_optimasi(user_input, df=df, cos_sim=cos_sim):
    user_input_cleaned = process_user_input(user_input)
    all_texts = df['combined_clean'].tolist() + [user_input_cleaned]
    tfidf_matrix = tfidf_vectorizer.transform(all_texts)
    cos_sim_user = cosine_similarity(tfidf_matrix[-1], tfidf_matrix[:-1]).flatten()
    ranked_indices = cos_sim_user.argsort()[::-1]

    recommended_ukms = []
    threshold = 0.05
    for idx in ranked_indices:
        if cos_sim_user[idx] > threshold:
            recommended_ukms.append(df.iloc[idx].name)
        if len(recommended_ukms) >= 3:
            break

    if len(recommended_ukms) < 3:
        existing_recommendations = set(recommended_ukms)
        additional_recommendations = random.sample(
            [ukm for ukm in df.index if ukm not in existing_recommendations],
            3 - len(recommended_ukms)
        )
        recommended_ukms.extend(additional_recommendations)

    recommended_ukms = list(dict.fromkeys(recommended_ukms))
    return recommended_ukms

@app.route('/recommend', methods=['POST'])
def recommend():
    user_input = request.json.get('query', '')
    if not user_input:
        return jsonify({'error': 'Query is required'}), 400
    recommended_ukms = recommendations_based_on_input_setelah_optimasi(user_input)
    return jsonify({'recommendations': recommended_ukms})

# ===================== Bagian Chatbot =====================

lemmatizer = nltk.WordNetLemmatizer()
data_file = open("D:/Nexus/nexus/storage/app/public/python_data/data.json").read()
intents = json.loads(data_file)

words, classes, documents = [], [], []
ignore_words = ['?', '!']
for intent in intents['intents']:
    for pattern in intent['patterns']:
        w = nltk.word_tokenize(pattern)
        words.extend(w)
        documents.append((w, intent['tag']))
        if intent['tag'] not in classes:
            classes.append(intent['tag'])

words = sorted(list(set([lemmatizer.lemmatize(w.lower()) for w in words if w not in ignore_words])))
classes = sorted(list(set(classes)))

if not os.path.exists("texts.pkl"):
    pickle.dump(words, open('texts.pkl', 'wb'))
if not os.path.exists("label.pkl"):
    pickle.dump(classes, open('label.pkl', 'wb'))

train_x, train_y = [], []
output_empty = [0] * len(classes)
for doc in documents:
    bag = [1 if w in [lemmatizer.lemmatize(word.lower()) for word in doc[0]] else 0 for w in words]
    output_row = list(output_empty)
    output_row[classes.index(doc[1])] = 1
    train_x.append(bag)
    train_y.append(output_row)

train_x, train_y = np.array(train_x, dtype="float32"), np.array(train_y, dtype="float32")

if not os.path.exists("model.h5"):
    model = Sequential()
    model.add(Dense(128, input_shape=(len(train_x[0]),), activation='relu'))
    model.add(Dropout(0.5))
    model.add(Dense(64, activation='relu'))
    model.add(Dropout(0.5))
    model.add(Dense(len(train_y[0]), activation='softmax'))
    sgd = SGD(learning_rate=0.01, decay=1e-6, momentum=0.9, nesterov=True)
    model.compile(loss='categorical_crossentropy', optimizer=sgd, metrics=['accuracy'])
    model.fit(train_x, train_y, epochs=200, batch_size=5, verbose=1)
    model.save('model.h5')

model = load_model("model.h5")
words = pickle.load(open("texts.pkl", "rb"))
classes = pickle.load(open("label.pkl", "rb"))

def enhanced_predict_class(sentence, threshold=0.25):
    bow = np.array([1 if w in nltk.word_tokenize(sentence) else 0 for w in words])
    res = model.predict(np.array([bow]))[0]
    results = [[i, r] for i, r in enumerate(res) if r > threshold]
    return [{"intent": classes[r[0]], "probability": str(r[1])} for r in results]

def enhanced_get_response(sentence, threshold=0.6):
    intents_list = enhanced_predict_class(sentence, threshold)
    if intents_list:
        tag = intents_list[0]['intent']
        for intent in intents["intents"]:
            if intent["tag"] == tag:
                return random.choice(intent["responses"])
    return "Maaf, saya tidak mengerti. Bisa Anda jelaskan lebih rinci?"

@app.route('/chatbot', methods=['POST'])
def chatbot():
    user_message = request.json.get('message')
    if not user_message:
        return jsonify({"response": "Pesan tidak ditemukan."}), 400
    response = enhanced_get_response(user_message)
    return jsonify({"response": response})

if __name__ == '__main__':
    app.run(debug=True)
