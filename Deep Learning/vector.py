import pandas as pd
from langchain_core.documents import Document
from langchain_chroma import Chroma
from langchain_community.embeddings import FastEmbedEmbeddings
import os

# Load dataset
base_path = os.path.dirname(__file__)
csv_path = os.path.join(base_path, 'dataset', 'Combined Data.csv')

df = pd.read_csv(csv_path)

# Hapus baris dengan nilai NaN pada kolom 'statement' atau 'status'
df = df.dropna(subset=["statement", "status"])
df = df.sample(n=500, random_state=42)
# Buat embedding model
embedding_model = FastEmbedEmbeddings(
    model_name="sentence-transformers/paraphrase-multilingual-MiniLM-L12-v2"
)

# Ubah tiap baris menjadi dokumen LangChain
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

# Buat & simpan vectorstore
vectorstore = Chroma.from_documents(
    documents=docs,
    embedding=embedding_model,
    persist_directory="vectorstore"
)

# Inisialisasi retriever
retreiver = vectorstore.as_retriever(search_kwargs={"k": 5})
