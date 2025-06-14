from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain.prompts import PromptTemplate
import dotenv 
import os
from vector import retreiver

dotenv.load_dotenv()

model = ChatGoogleGenerativeAI(
   model="gemini-2.0-flash-exp",
   google_api_key="AIzaSyAUb4OuObtE_7c31zPX3XPUQv1Rc33Mb_c"
)

template = """
Kamu adalah asisten virtual yang responsif terhadap kondisi emosional user.

Berikut emosi yang dirasakan user: {tag}
Berikan respons dengan nada bicara yang sesuai.

Gunakan konteks berikut untuk memahami dan menjawab:
{context}

Pertanyaan dari user:
{question}

Jangan jawab jika konteks tidak relevan yang di luar Mental Health. Jika user ingin jawabannya diluar konteks,
jawab saja "Maaf ini chatbot khusus mental health". Prioritaskan empati dan keterhubungan emosional.
"""

prompt = PromptTemplate.from_template(template)
chain = prompt | model

app = FastAPI(docs_url=None, redoc_url=None, openapi_url=None)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Ganti "*" ke domain tertentu kalau ingin lebih aman
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class UserQuery(BaseModel):
    question: str

@app.post("/")
def chat_endpoint(input: UserQuery):
    question = input.question
    results = retreiver.invoke(question)
    for i, doc in enumerate(results):
        print(f"Document {i} metadata:", doc.metadata)
    # context = "\n".join([doc.metadata["statement"] for doc in results])
    context = "\n".join(doc.metadata.get("statement", "") for doc in results if doc.metadata.get("statement"))
    tag_counts = {}
    for doc in results:
        t = doc.metadata.get("status", "unknown")
        # t = doc.metadata["status"]
        tag_counts[t] = tag_counts.get(t, 0) + 1
    tag = max(tag_counts, key=tag_counts.get)
    result = chain.invoke({
        "context": context,
        "question": question,
        "tag": tag,
    })
    return {"reply": result.content}
