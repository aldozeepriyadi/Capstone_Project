from fastapi import FastAPI
from pydantic import BaseModel
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain.prompts import PromptTemplate
import dotenv 
import os
from vector import retreiver

# Load environment variable (API key, etc)
dotenv.load_dotenv()

# Inisialisasi model Gemini
model = ChatGoogleGenerativeAI(
   model="gemini-2.0-flash-exp",
   google_api_key="AIzaSyAUb4OuObtE_7c31zPX3XPUQv1Rc33Mb_c",
)

# Prompt template dengan konteks emosi dan respon empatik
template = """
Kamu adalah asisten virtual yang responsif terhadap kondisi emosional user.

Berikut emosi yang dirasakan user: {tag}
Berikan respons dengan nada bicara yang sesuai.

Gunakan konteks berikut untuk memahami dan menjawab:
{context}

Pertanyaan dari user:
{question}

Jangan jawab jika konteks tidak relevan yang di luar Mental Health. Jika user ingin jawabannya diluar konteks,
jawab saja \"Maaf ini chatbot khusus mental health\". Prioritaskan empati dan keterhubungan emosional.
"""

prompt = PromptTemplate.from_template(template)
chain = prompt | model

# Setup FastAPI
app = FastAPI()

class UserQuery(BaseModel):
    question: str

@app.post("/chat")
def chat_endpoint(input: UserQuery):
    question = input.question

    # Retrieve hasil dari vectorstore
    results = retreiver.invoke(question)

    # Gabungkan semua response sebagai konteks
    context = "\n".join([doc.metadata["response"] for doc in results])

    # Ambil tag yang paling dominan dari hasil retrieval
    tag_counts = {}
    for doc in results:
        t = doc.metadata["tag"]
        tag_counts[t] = tag_counts.get(t, 0) + 1
    tag = max(tag_counts, key=tag_counts.get)

    # Masukkan ke prompt
    result = chain.invoke({
        "context": context,
        "question": question,
        "tag": tag,
    })

    return {"reply": result.content}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run("chatbot:app", host="127.0.0.1", port=8000, reload=True)