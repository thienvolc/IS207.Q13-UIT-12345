README - API Quick Start (PinkCapy Demo)

Mục đích
- Tài liệu này cung cấp các lệnh mẫu để bạn gọi nhanh API demo đã triển khai trong project (DB-backed).
- Các route demo nằm dưới /api/* (ví dụ: /api/me/carts, /api/admin/orders/{id}/confirm).

Yêu cầu trước khi test
1. PHP và Composer đã cài trên máy.
2. Trong project root (nơi chứa artisan), chạy:

```cmd
composer dump-autoload -o
php artisan migrate --seed --force
```

3. Khởi động dev server (cmd.exe):

```cmd
php artisan serve --host=127.0.0.1 --port=8000
```

Tokens demo (được tạo sẵn bởi seeder)
- User token: user-token-1  (thuộc user demo)
- Admin token: admin-token-2 (thuộc admin demo)

Endpoints chính (mẫu)
Base URL: http://127.0.0.1:8000/api

1) Thêm item vào giỏ (user)
- URL: POST /api/me/carts/items
- Body: { "product_id": 1, "quantity": 2 }
- Header: Authorization: Bearer user-token-1

cmd.exe (curl.exe):
```cmd
curl.exe -v -X POST -H "Content-Type: application/json" -H "Authorization: Bearer user-token-1" -d "{\"product_id\":1,\"quantity\":2}" http://127.0.0.1:8000/api/me/carts/items
```

PowerShell (Invoke-RestMethod):
```powershell
Invoke-RestMethod -Uri 'http://127.0.0.1:8000/api/me/carts/items' -Method Post -Headers @{ Authorization = 'Bearer user-token-1' } -ContentType 'application/json' -Body '{"product_id":1,"quantity":2}' | ConvertTo-Json -Depth 5
```

2) Lấy giỏ hàng (user)
- URL: GET /api/me/carts
- Header: Authorization: Bearer user-token-1

cmd.exe:
```cmd
curl.exe -v -H "Authorization: Bearer user-token-1" http://127.0.0.1:8000/api/me/carts
```

PowerShell:
```powershell
Invoke-RestMethod -Uri 'http://127.0.0.1:8000/api/me/carts' -Method Get -Headers @{ Authorization = 'Bearer user-token-1' } | ConvertTo-Json -Depth 5
```

3) Đặt hàng (user) — tạo order từ cart
- URL: POST /api/me/orders
- Body: { "promo": "SUMMER2025", "note": "Giao buoi sang" }
- Header: Authorization: Bearer user-token-1

cmd.exe:
```cmd
curl.exe -v -X POST -H "Content-Type: application/json" -H "Authorization: Bearer user-token-1" -d "{\"promo\":\"SUMMER2025\",\"note\":\"Giao buoi sang\"}" http://127.0.0.1:8000/api/me/orders
```

4) Kiểm tra trạng thái đơn hàng (user)
- URL: GET /api/me/orders/{id}/status
- Header: Authorization: Bearer user-token-1

cmd.exe:
```cmd
curl.exe -v -H "Authorization: Bearer user-token-1" http://127.0.0.1:8000/api/me/orders/1/status
```
(Thay `1` bằng order id bạn nhận được từ bước đặt hàng.)

5) Admin: xem và xác nhận đơn hàng
- Admin xem status: GET /api/admin/orders/{id}/status
- Admin confirm: PUT /api/admin/orders/{id}/confirm
- Header: Authorization: Bearer admin-token-2

cmd.exe:
```cmd
curl.exe -v -H "Authorization: Bearer admin-token-2" http://127.0.0.1:8000/api/admin/orders/1/status
curl.exe -v -X PUT -H "Authorization: Bearer admin-token-2" http://127.0.0.1:8000/api/admin/orders/1/confirm
```

Mẫu phản hồi (thành công)
```json
{
  "meta": {
    "code": "200000",
    "type": "SUCCESS",
    "message": "Success",
    "extra_meta": {}
  },
  "data": { ... }
}
```

Một số lưu ý & troubleshooting
- Nếu bạn dùng PowerShell, `curl` là alias đến Invoke-WebRequest; dùng `curl.exe` hoặc `Invoke-RestMethod` để có đầu ra JSON dễ đọc.
- Nếu nhận được lỗi 401/403: kiểm tra header Authorization, token đúng (user-token-1 / admin-token-2), và đã chạy `php artisan migrate --seed`.
- Kiểm tra log nếu cần: `type storage\logs\laravel.log` (cmd) hoặc `Get-Content storage\logs\laravel.log -Tail 200` (PowerShell).
- Kiểm tra routes: `php artisan route:list --path=api` để xem các route đã đăng ký.

Muốn thêm gì nữa?
- Tôi có thể tạo một Postman collection (.json) hoặc file curl script `.bat` sẵn để bạn chạy nhanh trên Windows.
- Muốn format `Authorization: user 1` thay vì Bearer token? Tôi có thể điều chỉnh middleware.

Nếu bạn muốn, tôi sẽ tạo thêm file `tests/ManualApiTest.bat` chứa các lệnh curl.exe để chạy thứ tự (add -> get -> order -> admin confirm).
