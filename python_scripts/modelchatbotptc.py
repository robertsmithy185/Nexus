
# import pustaka yang diperlukan
from flask import Flask, request, jsonify
from flask_cors import CORS
import warnings
warnings.filterwarnings("ignore")
import nltk
from nltk.stem import WordNetLemmatizer
import json
import pickle
from sklearn.model_selection import train_test_split
import numpy as np
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Activation, Dropout
from tensorflow.keras.optimizers import SGD
import random
from keras.models import load_model

# Flask setup
app = Flask(__name__)
CORS(app)  # Enable CORS for cross-origin requests

# Suppress warnings
warnings.filterwarnings("ignore")

#buat objek dari WordNetLemmatizer
lemmatizer = WordNetLemmatizer()

# mengimpor file korpus GL Bot untuk pemrosesan awal

words=[]
classes = []
documents = []
ignore_words = ['?', '!']
data_file = open("D:/Nexus/nexus/storage/app/public/python_data/data.json").read()
intents = json.loads(data_file)

"""# 2. Data pre-processing"""

# pemrosesan data json
# tokenization
nltk.download('punkt')
nltk.download('wordnet')
# Download the 'punkt_tab' resource
nltk.download('punkt_tab')  # This line is added to download the missing resource
for intent in intents['intents']:
    for pattern in intent['patterns']:
        #tokenisasi setiap kata
        w = nltk.word_tokenize(pattern)
        words.extend(w)
        #tambahkan dokumen ke dalam korpus
        documents.append((w, intent['tag']))

        # tambahkan ke dalam daftar kelas kita
        if intent['tag'] not in classes:
            classes.append(intent['tag'])

"""### Tokenisasi

- Pada proses tokenisasi pada dasarnya adalah pemisahan kalimat, paragraf,
atau seluruh dokumen teks menjadi unit yang lebih kecil, proses itu yang disebut token

- Pada proses ini juga akan save documen tersebut menjadi file label.pkl dan texts.pkl (proses labeling)
"""

words = [lemmatizer.lemmatize(w.lower()) for w in words if w not in ignore_words]
words = sorted(list(set(words)))

# urutkan kelas
classes = sorted(list(set(classes)))

# dokumen = kombinasi antara pola dan intent
print (len(documents), "documents")

# classes = intents
print (len(classes), "classes", classes)

# words = semua kata, kosakata
print (len(words), "unique lemmatized words", words)

# membuat file pickle untuk menyimpan objek Python yang akan kita gunakan saat prediksi
pickle.dump(words,open('texts.pkl','wb'))
pickle.dump(classes,open('label.pkl','wb'))

"""# 3. Creating Training Data

- Pada dasarnya, bag of words adalah representasi sederhana dari setiap teks dalam sebuah kalimat sebagai bag of words-nya.
"""

# buat data pelatihan kita
training = []

# buat array kosong untuk output kita
output_empty = [0] * len(classes)

# set pelatihan, tas kata untuk setiap kalimat
for doc in documents:
    # inisialisasi bag tiap kata
    bag = []
    # daftar kata yang telah ditokenisasi untuk pola
    pattern_words = doc[0]

    # lemmatize setiap kata - buat kata dasar, untuk mencoba mewakili kata-kata yang terkait
    pattern_words = [lemmatizer.lemmatize(word.lower()) for word in pattern_words]

    # buat array dari bag kata dengan 1, jika kata ditemukan dalam pola saat ini
    for w in words:
        bag.append(1) if w in pattern_words else bag.append(0)
    # outputnya menjadi 0 jika setiap tag dan 1 untuk tag saat ini (untuk setiap pola yang ada)
    output_row = list(output_empty)
    output_row[classes.index(doc[1])] = 1
    training.append([bag, output_row])

# acak fitur dan konversi menjadi array numpy
random.shuffle(training)
training = np.array(training, dtype=object)

# buat daftar pelatihan dan pengujian
train_x = np.array(list(training[:, 0]), dtype="float32")
train_y = np.array(list(training[:, 1]), dtype="float32")

print("Data pelatihan dibuat")
print("Training X shape:", train_x.shape)
print("Training Y shape:", train_y.shape)

"""# 5. Creating Modeling

- Pada proses ini kami akan membuat model jaringan saraf dan menyimpan model tersebut
"""

# Membuat model ANN untuk memprediksi respons
model = Sequential()
model.add(Dense(128, input_shape=(len(train_x[0]),), activation='relu'))
model.add(Dropout(0.5))
model.add(Dense(64, activation='relu'))
model.add(Dropout(0.5))
model.add(Dense(len(train_y[0]), activation='softmax'))

# Kompilasi model. Stochastic gradient descent dengan Nesterov accelerated gradient memberikan hasil yang baik untuk model ini
sgd = SGD(learning_rate=0.01, decay=1e-6, momentum=0.9, nesterov=True)
model.compile(loss='categorical_crossentropy', optimizer=sgd, metrics=['accuracy'])

# melatih dan menyimpan model
hist = model.fit(np.array(train_x), np.array(train_y), epochs=200, batch_size=5, verbose=1)
model.save('model.h5', hist) # Memilih model yang akan digunakan dimasa mendatang
print("\n")
print("*"*50)
print("\nModel Created Successfully!")

"""# 6. Menyiapkan Fungsi Untuk prediksi dan Respon"""

# Muat model dan file pembantu
model = load_model("model.h5")
words = pickle.load(open("texts.pkl", "rb"))
classes = pickle.load(open("label.pkl", "rb"))

def clean_up_sentence(sentence):
    sentence_words = nltk.word_tokenize(sentence)
    sentence_words = [lemmatizer.lemmatize(word.lower()) for word in sentence_words]
    return sentence_words

def bag_of_words(sentence, words):
    sentence_words = clean_up_sentence(sentence)
    bag = [0] * len(words)
    for s in sentence_words:
        for i, w in enumerate(words):
            if w == s:
                bag[i] = 1
    return np.array(bag)

# Fungsi yang ditingkatkan untuk memprediksi kelas
def enhanced_predict_class(sentence, model, words, classes, threshold=0.25):
    bow = bag_of_words(sentence, words)
    res = model.predict(np.array([bow]))[0]
    results = [[i, r] for i, r in enumerate(res) if r > threshold]
    results.sort(key=lambda x: x[1], reverse=True)
    return [{"intent": classes[r[0]], "probability": str(r[1])} for r in results]

# Fungsi yang ditingkatkan untuk mendapatkan respons
def enhanced_get_response(sentence, intents_json, threshold=0.6):
    intents_list = enhanced_predict_class(sentence, model, words, classes, threshold)
    if intents_list:
        tag = intents_list[0]['intent']
        for intent in intents_json["intents"]:
            if intent["tag"] == tag:
                return random.choice(intent["responses"])
    return "Maaf, saya tidak mengerti. Bisa Anda jelaskan lebih rinci?"

"""# 7.menjalankan chatbot"""

# Fungsi utama chatbot
def chatbot_response(message):
    return enhanced_get_response(message, intents)

# # Main loop untuk chatbot
# while True:
#     message = input("You: ")
#     if message.lower() == "quit":
#         print("Chatbot: Goodbye!")
#         break
#     response = chatbot_response(message)
#     print("Chatbot:", response)

# API endpoint for chatbot
@app.route('/chatbot', methods=['POST'])
def chatbot():
    user_message = request.json.get('message')
    if not user_message:
        return jsonify({"response": "Pesan tidak ditemukan."}), 400

    # Generate chatbot response
    bot_response = enhanced_get_response(user_message, intents)
    
    return jsonify({"response": bot_response})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)