import re
import os
import pickle
import nltk
import numpy as np
from fastapi import FastAPI
from pydantic import BaseModel
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing.sequence import pad_sequences
from nltk.corpus import stopwords
from nltk.stem import WordNetLemmatizer
from deep_translator import GoogleTranslator
import tensorflow as tf

# ───── Inisialisasi device GPU / CPU ─────
physical_gpus = tf.config.list_physical_devices('GPU')
if physical_gpus:
    try:
        for gpu in physical_gpus:
            tf.config.experimental.set_memory_growth(gpu, True)
        DEVICE = "/GPU:0"
    except:
        DEVICE = "/CPU:0"
else:
    DEVICE = "/CPU:0"
print(f"🚀 Using device: {DEVICE}")

# ───── NLP resource ─────
nltk.download("stopwords")
nltk.download("wordnet")

stop_words = set(stopwords.words("english"))
lemmatizer = WordNetLemmatizer()

slang_dict = {
    "u": "you", "r": "are", "btw": "by the way", "gr8": "great", "lol": "laugh out loud",
    "omg": "oh my god", "idk": "i don't know", "bff": "best friend forever", "ty": "thank you",
    "smh": "shaking my head", "stfu": "shut the f up", "fml": "f my life", "g2g": "got to go",
    "tbh": "to be honest", "crybaby": "emotional", "overthinking": "overanalyzing",
    "feels": "feelings", "stressed out": "stress", "shook": "emotionally affected",
    "bipolar": "bi-polar", "heartbroken": "feeling broken", "feeling some type of way": "confused or overwhelmed",
    "tired": "fatigued or exhausted"
}

# ───── Preprocessing function ─────
def full_preprocess(text: str) -> str:
    if not isinstance(text, str):
        return ""
    try:
        text = GoogleTranslator(source='auto', target='en').translate(text)
    except Exception:
        pass
    text = text.lower()
    text = re.sub(r"http\S+|www\S+|@\S+|#\S+", "", text)
    text = re.sub(r"[^a-z\s]", "", text)
    text = ' '.join(slang_dict.get(word, word) for word in text.split())
    cleaned_words = [
        lemmatizer.lemmatize(word) for word in text.split() if word not in stop_words
    ]
    return ' '.join(cleaned_words)

# ───── Load model dan artefak ─────
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
tokenizer_path = os.path.join(BASE_DIR, "model", "tokenizer.pkl")
label_encoder_path = os.path.join(BASE_DIR, "model", "label_encoder.pkl")
model_path = os.path.join(BASE_DIR, "model", "model_mental_health_finetuned.h5")

with open(tokenizer_path, "rb") as f:
    tokenizer = pickle.load(f)
with open(label_encoder_path, "rb") as f:
    encoder = pickle.load(f)

with tf.device(DEVICE):
    model = load_model(model_path)

# ───── Prediction function ─────
def predict_sentiment(text: str) -> str:
    cleaned = full_preprocess(text)
    seq = tokenizer.texts_to_sequences([cleaned])
    padded = pad_sequences(seq, maxlen=100, padding='post')
    with tf.device(DEVICE):
        pred = model.predict(padded, verbose=0)
    label = encoder.inverse_transform([np.argmax(pred)])[0]
    return label

# ───── FastAPI setup ─────
app = FastAPI(docs_url=None, redoc_url=None, openapi_url=None)

class InputText(BaseModel):
    text: str

@app.post("/")
async def classify_emotion(request: InputText):
    result = predict_sentiment(request.text)
    return {"label": result}
