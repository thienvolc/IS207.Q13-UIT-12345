<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','PinkCapy')</title>
  <!-- CSS chính -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])


  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <!-- Roboto - font chữ chính (giống CellphoneS, TGDĐ) -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <!-- Boostrap icons-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body class="pinkcapy">
  @include('partials.header')
  <main class="main">
    @yield('content')
  </main>
  @include('partials.footer')
  @include('partials.chatbot')
  
  <!-- Global Cart Script -->
  <script>
    // Add to cart from product card
    async function addToCartFromCard(button, productId) {
        const originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
        
        try {
            const response = await fetch('/api/web/cart/items', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1,
                }),
            });
            
            const data = await response.json();
            
            if (data.success) {
                button.innerHTML = '<i class="bi bi-check-lg"></i>';
                showGlobalToast('Đã thêm vào giỏ hàng!', 'success');
                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                }, 1500);
            } else {
                button.innerHTML = originalHtml;
                button.disabled = false;
                showGlobalToast(data.message || 'Không thể thêm vào giỏ.', 'error');
            }
        } catch (error) {
            console.error('Add to cart error:', error);
            button.innerHTML = originalHtml;
            button.disabled = false;
            showGlobalToast('Vui lòng đăng nhập để thêm vào giỏ hàng.', 'warning');
        }
    }
    
    // Global toast notification
    function showGlobalToast(message, type = 'success') {
        document.querySelectorAll('.global-toast').forEach(t => t.remove());
        
        const toast = document.createElement('div');
        toast.className = 'global-toast';
        
        const colors = {
            success: '#198754',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#0dcaf0'
        };
        
        const icons = {
            success: 'check-circle-fill',
            error: 'x-circle-fill',
            warning: 'exclamation-triangle-fill',
            info: 'info-circle-fill'
        };
        
        toast.innerHTML = `<i class="bi bi-${icons[type]}"></i> ${message}`;
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            border-left: 4px solid ${colors[type]};
            font-size: 14px;
            color: #333;
        `;
        
        const icon = toast.querySelector('i');
        if (icon) icon.style.color = colors[type];
        
        document.body.appendChild(toast);
        setTimeout(() => toast.style.transform = 'translateX(0)', 10);
        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
  </script>
  
  @stack('scripts')
</body>