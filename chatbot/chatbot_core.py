import os
from dotenv import load_dotenv

# LLM = GPT-5-nano (Lưu ý: Đảm bảo bạn có quyền truy cập model này hoặc đổi tên nếu cần)
from langchain_openai import ChatOpenAI

# Embedding = OpenAI "text-embedding-3-small"
from langchain_openai import OpenAIEmbeddings

from langchain_chroma import Chroma
from langchain_community.document_loaders import DirectoryLoader, TextLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.runnables import RunnablePassthrough
from langchain_core.output_parsers import StrOutputParser


# ---------------------------------------------------
# 1. LOAD ENV
# ---------------------------------------------------
print("Đang tải API keys...")
load_dotenv()

OPENAI_API_KEY = os.getenv("OPENAI_API_KEY")
if not OPENAI_API_KEY:
    print("LỖI: OPENAI_API_KEY không tồn tại trong .env")


# ---------------------------------------------------
# 2. PATH
# ---------------------------------------------------
DATA_PATH = "./data"
CHROMA_PATH = "./chroma_db"


# ---------------------------------------------------
# 3. INITIALIZE RAG
# ---------------------------------------------------
def initialize_rag_system():

    print("=== KHỞI TẠO RAG HỆ THỐNG ===")

    # ---------------------------
    # Embedding: text-embedding-3-small
    # ---------------------------
    print("Khởi tạo Embedding model: text-embedding-3-small ...")
    try:
        embeddings = OpenAIEmbeddings(
            model="text-embedding-3-small",
            api_key=OPENAI_API_KEY
        )
        # Test kết nối
        test_vec = embeddings.embed_query("hello")
        print(f"Embedding OK (vec size = {len(test_vec)})")
    except Exception as e:
        print("LỖI Embedding:", e)
        return None
    

    # ---------------------------
    # Load hoặc báo lỗi nếu chưa build
    # ---------------------------
    if os.path.exists(CHROMA_PATH) and len(os.listdir(CHROMA_PATH)) > 0:
        print("Đang load Vector Store (ChromaDB)...")
        vector_store = Chroma(
            persist_directory=CHROMA_PATH,
            embedding_function=embeddings
        )
        print("Vector Store OK.")
    else:
        print("❌ Không tìm thấy ChromaDB. Hãy chạy build_db.py trước.")
        return None

    # ---------------------------
    # Retriever
    # ---------------------------
    retriever = vector_store.as_retriever(
        search_type="similarity",
        search_kwargs={"k": 5}
    )

    # ---------------------------
    # LLM = GPT-5-nano
    # ---------------------------
    print("Khởi tạo LLM GPT-5-nano ...")
    try:
        llm = ChatOpenAI(
            model="gpt-5-nano",
            temperature=0.1,
            api_key=OPENAI_API_KEY
        )
        print("LLM GPT-5-nano OK.")
    except Exception as e:
        print("LỖI LLM:", e)
        return None

    # ---------------------------
    # Prompt (ĐÃ CHỈNH SỬA CHO SHOP CÔNG NGHỆ)
    # ---------------------------
    prompt_template = """
    Bạn là chuyên viên tư vấn của một Cửa Hàng Đồ Công Nghệ (chuyên Camera, Đồng hồ thông minh và Phụ kiện).
    Nhiệm vụ của bạn là hỗ trợ khách hàng tìm kiếm sản phẩm, báo giá và giải thích tính năng kỹ thuật dựa trên thông tin trong NGỮ CẢNH.
    
    QUY TẮC TRẢ LỜI:
    1. Dựa 100% vào NGỮ CẢNH (CONTEXT) bên dưới. Không được tự bịa ra sản phẩm hoặc mức giá không có trong dữ liệu.
    2. Nếu NGỮ CẢNH không có thông tin, hãy trả lời: "Xin lỗi, hiện tại tôi chưa tìm thấy thông tin chính xác về sản phẩm này trong hệ thống."
    3. Phong cách: Chuyên nghiệp, ngắn gọn, tập trung vào thông số kỹ thuật và giá bán (nếu có).
    4. Khi nêu giá, hãy ghi rõ đơn vị VNĐ.

    ---
    NGỮ CẢNH (Dữ liệu cửa hàng):
    {context}

    ---
    CÂU HỎI KHÁCH HÀNG:
    {question}

    ---
    CÂU TRẢ LỜI:
    """

    prompt = ChatPromptTemplate.from_template(prompt_template)

    def format_docs(docs):
        return "\n\n".join(doc.page_content for doc in docs)

    # ---------------------------
    # RAG Chain
    # ---------------------------
    rag_chain = (
        {"context": retriever | format_docs, "question": RunnablePassthrough()}
        | prompt
        | llm
        | StrOutputParser()
    )

    print("=== HỆ THỐNG RAG SẴN SÀNG ===")
    return rag_chain


# ---------------------------------------------------
# 4. GLOBAL INIT
# ---------------------------------------------------
try:
    rag_chain = initialize_rag_system()
except Exception as e:
    print("Lỗi khởi tạo:", e)
    rag_chain = None


# ---------------------------------------------------
# 5. API FUNCTION
# ---------------------------------------------------
def get_rag_response(user_query: str) -> str:
    if not rag_chain:
        return "Hệ thống RAG chưa khởi tạo."

    if not user_query:
        return "Vui lòng nhập câu hỏi."

    print(f"[Query] {user_query}")

    try:
        return rag_chain.invoke(user_query)
    except Exception as e:
        print("Lỗi:", e)
        return f"Xin lỗi, tôi gặp lỗi: {e}"