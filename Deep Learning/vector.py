import pandas as pd
from langchain_core.documents import Document
from langchain_chroma import Chroma
from langchain_community.embeddings import FastEmbedEmbeddings

# Load dataset
df = pd.read_csv("dataset/unpacked.csv")

# Buat embedding model (pakai multilingual, ringan & akurat)
embedding_model = FastEmbedEmbeddings(
    model_name="sentence-transformers/paraphrase-multilingual-MiniLM-L12-v2"
)

# Hapus baris kosong di kolom 'pattern'
df = df[df['pattern'].notna()]

# Ubah tiap baris menjadi dokumen LangChain
docs = [
    Document(
        page_content=row["pattern"],
        metadata={
            "tag": row["tag"],
            "response": row["response"]
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
