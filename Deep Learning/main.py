from fastapi import FastAPI
from app import app as predict_app
from chatbot import app as chatbot_app

main_app = FastAPI(
    title="Mental Health API",
    description="Kombinasi endpoint prediksi dan chatbot",
    version="1.0.0"
)

# Mount endpoint dari file app.py
main_app.mount("/predict", predict_app)

# Mount endpoint dari file chatbot.py
main_app.mount("/chat", chatbot_app)

# Jalankan dengan: uvicorn main:main_app --reload --port 8080
if __name__ == "__main__":
    import uvicorn
    uvicorn.run("main:main_app", host="127.0.0.1", port=8080, reload=False)