# RUN SERVER

python build_db.py

1. Tạo venv + cài deps: `python -m venv .venv` → `.\\.venv\\Scripts\\Activate.ps1` → `pip install -r requirements.txt`
2. Chạy server: `uvicorn main:app --port 8001`
