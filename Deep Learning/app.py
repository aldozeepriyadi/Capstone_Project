from fastapi import FastAPI
from pydantic import BaseModel
import pickle
import numpy as np
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing.sequence import pad_sequences
from sklearn.preprocessing import LabelEncoder
from deep_translator import GoogleTranslator
import re

# Load all the necessary files
with open("model/tokenizer.pkl", "rb") as f:
    tokenizer = pickle.load(f)
with open("model/label_encoder.pkl", "rb") as f:
    encoder = pickle.load(f)
model = load_model("model/model_mental_health.h5")

# FastAPI app instance
app = FastAPI()

# Preprocessing function with slang dictionary and translation
slang_dict = {
    "u": "you", "r": "are", "btw": "by the way", "gr8": "great", "lol": "laugh out loud",
    "omg": "oh my god", "idk": "i don't know", "bff": "best friend forever", "ty": "thank you",
    "smh": "shaking my head", "stfu": "shut the fuck up", "fml": "fuck my life", "g2g": "got to go",
    "tbh": "to be honest", "crybaby": "emotional", "overthinking": "overanalyzing",
    "feels": "feelings", "stressed out": "stress", "shook": "emotionally affected",
    "bipolar": "bi-polar", "heartbroken": "feeling broken", "feeling some type of way": "confused or overwhelmed",
    "tired": "fatigued or exhausted"
}

def preprocess_text(text):
    text = text.lower()
    text = re.sub(r"http\S+|www\S+|@\S+|#\S+", "", text)
    text = re.sub(r"[^a-z\s]", "", text)
    text = ' '.join([slang_dict.get(word, word) for word in text.split()])
    try:
        translated = GoogleTranslator(source='auto', target='en').translate(text)
        roundtrip = GoogleTranslator(source='en', target='id').translate(translated)
        return roundtrip
    except:
        return text  # fallback if translation fails

class ChatRequest(BaseModel):
    user_input: str

@app.post("/chatbot/")
async def get_chat_response(request: ChatRequest):
    user_input = request.user_input

    # Preprocess the input text
    input_clean = preprocess_text(user_input)

    # Predict label (emotion) using the model
    seq = tokenizer.texts_to_sequences([input_clean])
    padded = pad_sequences(seq, maxlen=100, padding='post')
    pred = model.predict(padded, verbose=0)
    label = encoder.inverse_transform([np.argmax(pred)])[0]

    # Generate response based on the label
    response = f"Bot ({label}): I'm here to help you. How can I assist you further?"
    return {"response": label}

