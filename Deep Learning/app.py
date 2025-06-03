import re
import pickle
import numpy as np
from fastapi import FastAPI
from pydantic import BaseModel
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing.sequence import pad_sequences
from nltk.corpus import stopwords
from deep_translator import GoogleTranslator
from nltk.stem import WordNetLemmatizer
import nltk

nltk.download('stopwords')
nltk.download('wordnet')

app = FastAPI(docs_url=None, redoc_url=None, openapi_url=None)

with open("Deep Learning/model/tokenizer.pkl", "rb") as f:
    tokenizer = pickle.load(f)
with open("Deep Learning/model/label_encoder.pkl", "rb") as f:
    encoder = pickle.load(f)
model = load_model("Deep Learning/model/model_mental_health_finetuned.h5")

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

def full_preprocess(text):
    if not isinstance(text, str):
        return ""
    try:
        text = GoogleTranslator(source='auto', target='en').translate(text)
    except:
        pass
    text = text.lower()
    text = re.sub(r"http\S+|www\S+|@\S+|#\S+", "", text)
    text = re.sub(r"[^a-z\s]", "", text)
    text = ' '.join([slang_dict.get(word, word) for word in text.split()])
    words = text.split()
    cleaned_words = [lemmatizer.lemmatize(word) for word in words if word not in stop_words]
    return ' '.join(cleaned_words)

def predict_sentiment(text):
    cleaned = full_preprocess(text)
    seq = tokenizer.texts_to_sequences([cleaned])
    padded = pad_sequences(seq, maxlen=100, padding='post')
    pred = model.predict(padded, verbose=0)
    label = encoder.inverse_transform([np.argmax(pred)])[0]
    return label

class InputText(BaseModel):
    text: str

@app.post("/")
async def predict(request: InputText):
    result = predict_sentiment(request.text)
    return {"label": result}
