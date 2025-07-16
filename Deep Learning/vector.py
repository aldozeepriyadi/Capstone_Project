import pandas as pd
from langchain_core.documents import Document
from langchain_chroma import Chroma
from langchain_community.embeddings import HuggingFaceEmbeddings
import torch
import os

# Cek device
device = "cuda" if torch.cuda.is_available() else "cpu"
print(f"Using device: {device}")

# Load dataset
base_path = os.path.dirname(__file__)
csv_path = os.path.join(base_path, 'dataset', 'Combined Data.csv')

df = pd.read_csv(csv_path)
df = df.dropna(subset=["statement", "status"])
df = df.sample(n=500, random_state=42)

# Buat embedding model yang jalan di GPU
embedding_model = HuggingFaceEmbeddings(
    model_name="sentence-transformers/paraphrase-multilingual-MiniLM-L12-v2",
    model_kwargs={"device": device}
)

# Konversi ke dokumen LangChain
docs = [
    Document(
        page_content=row["statement"],
        metadata={
            "status": row["status"],
            "statement": row["statement"]
        }
    )
    for _, row in df.iterrows()
]

# Buat dan simpan vectorstore
vectorstore = Chroma.from_documents(
    documents=docs,
    embedding=embedding_model,
    persist_directory="vectorstore"
)

# Retriever
retreiver = vectorstore.as_retriever(search_kwargs={"k": 5})
