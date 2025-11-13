#!/usr/bin/env python3
"""
Script để build Vector Store (ChromaDB) từ dữ liệu trong thư mục ./data
Sử dụng Ollama Embeddings (local)
"""

import os
import sys
from dotenv import load_dotenv
from langchain_community.document_loaders import DirectoryLoader, TextLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_chroma import Chroma
from langchain_ollama import OllamaEmbeddings

load_dotenv()

# Định nghĩa các đường dẫn
DATA_PATH = "./data"
CHROMA_PATH = "./chroma_db"
OLLAMA_BASE_URL = "http://localhost:11434"
OLLAMA_EMBED_MODEL = "nomic-embed-text"  # Model nhẹ hơn (300MB vs 669MB)

def build_vector_store():
    """
    Hàm này tải data, phân mảnh, và dùng embedding CỤC BỘ (miễn phí)
    để xây dựng ChromaDB.
    """
    if os.path.exists(CHROMA_PATH):
        print(f"Thư mục {CHROMA_PATH} đã tồn tại. Xóa đi nếu bạn muốn xây dựng lại.")
        return

    print(f"Đang tạo Vector Store mới từ: {DATA_PATH}")
    
    # 1. Tải Dữ liệu
    loader = DirectoryLoader(
        DATA_PATH,
        glob="**/*",  # Tải tất cả file
        loader_cls=TextLoader,
        loader_kwargs={'autodetect_encoding': True},
        show_progress=True,
        use_multithreading=True
    )
    documents = loader.load()
    if not documents:
        print("Lỗi: Không tìm thấy tài liệu.")
        return

    print(f"Đã tải {len(documents)} file. Bắt đầu phân mảnh...")

    # 2. Phân Mảnh
    text_splitter = RecursiveCharacterTextSplitter(
        chunk_size=1500,
        chunk_overlap=200
    )
    docs = text_splitter.split_documents(documents)
    print(f"Đã phân mảnh thành {len(docs)} chunks.")

    # 3. Embedding Ollama (Miễn phí 100%, chạy local)
    print(f"Đang khởi tạo Ollama Embeddings với model {OLLAMA_EMBED_MODEL}...")
    print(f"(Đảm bảo: ollama serve đang chạy)")
    
    try:
        embeddings = OllamaEmbeddings(
            model=OLLAMA_EMBED_MODEL,
            base_url=OLLAMA_BASE_URL
        )
        # Test embed
        test_vec = embeddings.embed_query("test")
        print(f"✅ Ollama Embeddings khởi tạo thành công (vector size: {len(test_vec)})")
    except Exception as e:
        print(f"❌ LỖI: Không thể kết nối tới Ollama!")
        print(f"   Lỗi: {e}")
        print(f"   Vui lòng kiểm tra:")
        print(f"   1. Ollama server đang chạy (ollama serve)")
        print(f"   2. Model đã tải (ollama pull {OLLAMA_EMBED_MODEL})")
        return

    # 4. Xây dựng ChromaDB
    print("Đang xây dựng Vector Store (ChromaDB)...")
    Chroma.from_documents(
        documents=docs,
        embedding=embeddings,
        persist_directory=CHROMA_PATH
    )
    print("--- XÂY DỰNG VECTOR STORE THÀNH CÔNG! ---")
    print(f"Dữ liệu đã được lưu tại: {CHROMA_PATH}")

if __name__ == "__main__":
    build_vector_store()