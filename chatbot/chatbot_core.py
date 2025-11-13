import os
from dotenv import load_dotenv

# THÀNH PHẦN CHAT (API)
from langchain_google_genai import ChatGoogleGenerativeAI
# THÀNH PHẦN EMBEDDING (LOCAL - OLLAMA)
from langchain_ollama import OllamaEmbeddings
from langchain_chroma import Chroma
from langchain_community.document_loaders import DirectoryLoader, TextLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.runnables import RunnablePassthrough
from langchain_core.output_parsers import StrOutputParser

# --- 1. TẢI API KEY & CẤU HÌNH ---
print("Đang tải biến môi trường (API Key)...")
load_dotenv()

# LẤY KEY GEMINI
GOOGLE_API_KEY = os.getenv("GOOGLE_API_KEY")
if not GOOGLE_API_KEY:
    # Không cần dùng key HuggingFace cũ nữa
    print("LỖI: GOOGLE_API_KEY không được tìm thấy trong file .env")

# Đường dẫn
DATA_PATH = "./data" # Thư mục chứa file .sql và .md
CHROMA_PATH = "./chroma_db" # Thư mục để lưu trữ Vector Store

# --- CẤU HÌNH OLLAMA ---
# Đảm bảo Ollama đang chạy tại http://localhost:11434
OLLAMA_BASE_URL = "http://localhost:11434"
# Mô hình embedding của Ollama (nomic-embed-text nhẹ hơn mxbai-embed-large)
OLLAMA_EMBED_MODEL = "nomic-embed-text" 

# --- 2. HÀM KHỞI TẠO HỆ THỐNG RAG ---
def initialize_rag_system():
    """
    Hàm này thực hiện toàn bộ quy trình RAG:
    1. Khởi tạo OllamaEmbeddings (chạy local/Render)
    2. Tải/Tạo ChromaDB.
    3. Khởi tạo ChatGoogleGenerativeAI (gọi API Gemini)
    4. Tạo RAG Chain.
    """
    print("--- Bắt đầu khởi tạo hệ thống RAG ---")

    # Khởi tạo mô hình Embedding OLLAMA (chạy local/Render)
    # Lần đầu tiên chạy, Ollama sẽ tải mô hình embedding về.
    print(f"Đang khởi tạo Ollama Embeddings với model: {OLLAMA_EMBED_MODEL}...")
    try:
        embeddings = OllamaEmbeddings(
            model=OLLAMA_EMBED_MODEL,
            base_url=OLLAMA_BASE_URL
        )
        print("Đã khởi tạo Ollama Embeddings thành công.")
    except Exception as e:
        print(f"LỖI: Không thể kết nối tới Ollama Embeddings. Đảm bảo Ollama đang chạy: {e}")
        return None


    # --- Bước 1, 2, 3, 4: Tải, Phân mảnh, Nhúng và Lưu trữ ---
    if os.path.exists(CHROMA_PATH) and len(os.listdir(CHROMA_PATH)) > 0:
        print(f"Phát hiện Vector Store đã tồn tại tại: {CHROMA_PATH}")
        # Tải DB đã có từ ổ đĩa
        vector_store = Chroma(
            persist_directory=CHROMA_PATH,
            embedding_function=embeddings
        )
        print("Đã tải Vector Store (ChromaDB) từ ổ đĩa.")
    else:
        # Lỗi nghiêm trọng nếu bạn chưa chạy build_db.py
        print(f"LỖI: Không tìm thấy Vector Store tại {CHROMA_PATH}.")
        print("Vui lòng chạy file 'build_db.py' (sửa đổi để dùng OllamaEmbeddings) TRƯỚC KHI khởi động server này.")
        return None
    
    # --- Bước 5: Tạo Retriever và Định nghĩa LLM ---
    # Retriever: Đối tượng chịu trách nhiệm tìm kiếm các chunks liên quan
    retriever = vector_store.as_retriever(
        search_type="similarity",
        search_kwargs={"k": 5}
    )
    
    # LLM: Sử dụng ChatGoogleGenerativeAI (Miễn phí qua API Key)
    print("Đang kết nối tới API Chat (Gemini 2.0 Flash)...")
    llm = ChatGoogleGenerativeAI(
        model="gemini-2.0-flash",
        temperature=0.1,
        # Đảm bảo bạn đã thêm GOOGLE_API_KEY vào file .env
        google_api_key=GOOGLE_API_KEY 
    )

    # --- Bước 6: Tạo Prompt Template ---
    prompt_template = """
    Bạn là trợ lý ảo chuyên gia tư vấn bán hàng cho một cửa hàng đồ công nghệ.
    Nhiệm vụ của bạn là trả lời câu hỏi của khách hàng DỰA TRÊN NGỮ CẢNH (CONTEXT) được cung cấp.
    Ngữ cảnh chứa thông tin về sản phẩm, giá cả, và danh mục được trích xuất từ cơ sở dữ liệu (file .sql).

    QUY TẮC:
    1. Chỉ trả lời dựa vào NGỮ CẢNH.
    2. Nếu NGỮ CẢNH không chứa thông tin để trả lời, hãy nói: "Xin lỗi, tôi không tìm thấy thông tin chính xác về sản phẩm này trong dữ liệu của cửa hàng."
    3. Trả lời bằng Tiếng Việt, giọng điệu thân thiện, chuyên nghiệp.
    4. Khi tư vấn sản phẩm, hãy cố gắng nêu bật TÊN SẢN PHẨM và GIÁ TIỀN (nếu có trong ngữ cảnh).

    ---
    NGỮ CẢNH (CONTEXT):
    {context}
    ---
    CÂU HỎI CỦA KHÁCH HÀNG:
    {question}
    ---
    CÂU TRẢ LỜI CỦA BẠN:
    """
    
    prompt = ChatPromptTemplate.from_template(prompt_template)

    # --- Bước 7: Xây dựng RAG Chain (LCEL) ---
    def format_docs(docs):
        return "\n\n".join(doc.page_content for doc in docs)

    rag_chain = (
        {"context": retriever | format_docs, "question": RunnablePassthrough()}
        | prompt
        | llm
        | StrOutputParser()
    )
    
    print("--- Khởi tạo RAG Chain hoàn tất. Server sẵn sàng! ---")
    return rag_chain

# --- KHỞI TẠO TOÀN CỤC ---
try:
    rag_chain = initialize_rag_system()
except Exception as e:
    print(f"LỖI NGHIÊM TRỌNG KHI KHỞI TẠO RAG: {e}")
    rag_chain = None

# --- HÀM XỬ LÝ CHÍNH (ĐƯỢC GỌI BỞI API) ---
def get_rag_response(user_query: str) -> str:
    if not rag_chain:
        return "Xin lỗi, hệ thống chatbot đang gặp lỗi khởi tạo."

    if not user_query:
        return "Vui lòng cung cấp câu hỏi."

    print(f"Đang xử lý query (với Gemini Chat): {user_query}")
    
    try:
        response_text = rag_chain.invoke(user_query)
        print(f"Phản hồi từ RAG: {response_text}")
        return response_text
    
    except Exception as e:
        print(f"LỖI khi đang invoke chain: {e}")
        # Bắt lỗi Quota của Google nếu có
        if "quota" in str(e).lower() or "429" in str(e):
            return "Xin lỗi, hiện tại API Chat (Gemini) đang bị quá tải quota miễn phí. Vui lòng thử lại sau 1 phút."
        if "google_api_key" in str(e).lower():
            return "Lỗi: GOOGLE_API_KEY bị thiếu hoặc sai. Vui lòng kiểm tra lại."
        return f"Xin lỗi, tôi gặp lỗi khi đang xử lý câu hỏi của bạn. Lỗi: {e}"