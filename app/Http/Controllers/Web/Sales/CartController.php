<?php

namespace App\Http\Controllers\Web\Sales;

use App\Domains\Cart\DTOs\Commands\AddCartItemDTO;
use App\Domains\Cart\Services\CartService;
use App\Domains\Checkout\DTOs\Commands\CheckoutCartDTO;
use App\Domains\Checkout\Services\CheckoutService;
use App\Domains\Order\DTOs\Commands\PlaceOrderDTO;
use App\Domains\Order\Services\OrderService;
use App\Exceptions\BusinessException;
use App\Http\Controllers\AppController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends AppController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CheckoutService $checkoutService,
        private readonly OrderService $orderService,
    ) {
    }

    /**
     * GET /cart - Hiển thị trang giỏ hàng
     */
    public function index()
    {
        $cart = $this->cartService->getOrCreateActiveCart();
        return view('pages.cart', ['cart' => $cart]);
    }

    /**
     * GET /api/web/cart - Lấy giỏ hàng hiện tại (AJAX)
     */
    public function getCart(): JsonResponse
    {
        try {
            $cart = $this->cartService->getOrCreateActiveCart();
            return response()->json([
                'success' => true,
                'data' => $cart->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải giỏ hàng.',
            ], 500);
        }
    }

    /**
     * POST /api/web/cart/items - Thêm sản phẩm vào giỏ (AJAX)
     */
    public function addItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity' => 'required|integer|min:1|max:9999',
        ]);

        try {
            $dto = new AddCartItemDTO(
                productId: (int) $request->product_id,
                quantity: (int) $request->quantity,
            );

            $item = $this->cartService->addOrIncrementQuantityCartItem($dto);

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
                'data' => $item->toArray(),
            ]);
        } catch (BusinessException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể thêm sản phẩm vào giỏ hàng.',
            ], 500);
        }
    }

    /**
     * PUT /api/web/cart/items/{id} - Cập nhật số lượng sản phẩm (AJAX)
     */
    public function updateQuantity(int $cartItemId, Request $request): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:9999',
        ]);

        try {
            $item = $this->cartService->updateQuantity($cartItemId, (int) $request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng sản phẩm.',
                'data' => $item->toArray(),
            ]);
        } catch (BusinessException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật số lượng.',
            ], 500);
        }
    }

    /**
     * DELETE /api/web/cart/items/{id} - Xóa sản phẩm khỏi giỏ (AJAX)
     */
    public function removeItem(int $cartItemId): JsonResponse
    {
        try {
            $item = $this->cartService->removeItem($cartItemId);

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                'data' => $item->toArray(),
            ]);
        } catch (BusinessException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa sản phẩm.',
            ], 500);
        }
    }

    /**
     * DELETE /api/web/cart/clear - Xóa toàn bộ giỏ hàng (AJAX)
     */
    public function clearCart(): JsonResponse
    {
        try {
            $cart = $this->cartService->clear();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa toàn bộ giỏ hàng.',
                'data' => $cart->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa giỏ hàng.',
            ], 500);
        }
    }

    /**
     * GET /checkout - Hiển thị trang thanh toán
     */
    public function showCheckout()
    {
        $user = Auth::user();
        $profile = $user->profile;
        $cart = $this->cartService->getOrCreateActiveCart();

        // Redirect về giỏ hàng nếu trống
        if (count($cart->items) === 0) {
            return redirect()->route('cart.page')->with('error', 'Giỏ hàng trống. Vui lòng thêm sản phẩm.');
        }

        return view('pages.checkout', [
            'user' => $user,
            'profile' => $profile,
            'cart' => $cart,
        ]);
    }

    /**
     * POST /api/web/cart/checkout - Checkout giỏ hàng (AJAX)
     */
    public function checkout(Request $request): JsonResponse
    {
        \Illuminate\Support\Facades\Log::info('Checkout Request received', ['user_id' => \Illuminate\Support\Facades\Auth::id(), 'is_auth' => \Illuminate\Support\Facades\Auth::check()]);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'integer',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'payment_method' => 'required|string|in:cod,vnpay,banking',
        ], [
            'items.required' => 'Vui lòng chọn sản phẩm để thanh toán.',
            'first_name.required' => 'Vui lòng nhập họ.',
            'last_name.required' => 'Vui lòng nhập tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'email.required' => 'Vui lòng nhập email.',
            'line1.required' => 'Vui lòng nhập địa chỉ.',
            'city.required' => 'Vui lòng chọn quận/huyện.',
            'province.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
        ]);

        try {
            $dto = new CheckoutCartDTO(
                items: $request->items,
                firstName: $request->first_name,
                middleName: $request->middle_name,
                lastName: $request->last_name,
                phone: $request->phone,
                email: $request->email,
                line1: $request->line1,
                line2: $request->line2,
                city: $request->city,
                province: $request->province,
                country: $request->country ?? 'Vietnam',
                note: $request->note,
                paymentMethod: $request->payment_method,
            );

            // Bước 1: Tạo checkout cart
            $checkoutCart = $this->checkoutService->checkout($dto);

            // Bước 2: Tạo order từ checkout cart
            $placeOrderDTO = new PlaceOrderDTO(
                cartId: $checkoutCart->cartId,
                promo: null,
                paymentMethod: $request->payment_method,
            );

            $orderSummary = $this->orderService->placeOrder($placeOrderDTO, $request->ip());

            // Nếu có payment URL (VNPay), redirect đến cổng thanh toán
            if (!empty($orderSummary->paymentUrl)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đang chuyển đến cổng thanh toán...',
                    'redirect' => $orderSummary->paymentUrl,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo đơn hàng thành công.',
                'data' => $checkoutCart->toArray(),
                'redirect' => route('order.success', ['cart_id' => $checkoutCart->cartId]),
            ]);
        } catch (BusinessException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Checkout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Không thể thanh toán. Vui lòng thử lại.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /order/success - Trang thành công sau checkout
     */
    public function orderSuccess(Request $request)
    {
        return view('pages.orders.success', [
            'cartId' => $request->cart_id,
        ]);
    }
}
