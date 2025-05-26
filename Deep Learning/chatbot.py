from langchain_google_genai import ChatGoogleGenerativeAI
from langchain.prompts import PromptTemplate
import dotenv 
import os

from vector import retreiver


dotenv.load_dotenv()

model = ChatGoogleGenerativeAI(
   model="gemini-2.0-flash-exp",
   google_api_key="AIzaSyAUb4OuObtE_7c31zPX3XPUQv1Rc33Mb_c",
)

template = """
Kamu adalah asisten virtual yang responsif terhadap kondisi emosional user.

Berikut emosi yang dirasakan user: {tag}
Berikan respons dengan nada bicara yang sesuai.

Gunakan konteks berikut untuk memahami dan menjawab:
{context}

Pertanyaan dari user:
{question}

Jangan jawab jika konteks tidak relevan yang di luar Mental Health jika user ingin jawabannya diluar konteks
jawab Saja "Maaf ini chatbot khusus mental health". Prioritaskan empati dan keterhubungan emosional.
"""

prompt = PromptTemplate.from_template(template)
chain = prompt | model


while True:
    print("\n\n=========================")
    question = input("pertanyaan (q untuk keluar): ")
    print("\n\n")
    if question == "q":
        break

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

    print(result.content)
