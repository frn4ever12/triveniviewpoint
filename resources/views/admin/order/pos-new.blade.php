<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="restaurant-name" content="{{ $siteName ?? 'Shree Foodies' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS - Shree Foodies</title>

    @include('admin.includes.top')

    <style>
        .pos-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
        }
        
        .pos-header {
            background: #1e293b;
            color: white;
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .pos-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .pos-brand h5 {
            margin: 0;
            font-weight: 700;
        }
        
        .pos-brand small {
            opacity: 0.7;
            font-size: 0.85rem;
        }
        
        .pos-nav {
            display: flex;
            gap: 0.5rem;
        }
        
        .pos-nav-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .pos-nav-btn:hover, .pos-nav-btn.active {
            background: rgba(255,255,255,0.2);
        }
        
        .pos-body {
            flex: 1;
            display: flex;
            overflow: hidden;
        }
        
        .pos-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e2e8f0;
        }
        
        .pos-categories {
            padding: 1rem;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
        }
        
        .category-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background: white;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
            font-size: 0.85rem;
        }
        
        .category-btn:hover, .category-btn.active {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }
        
        .pos-items {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 1rem;
        }
        
        .pos-item-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        
        .pos-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            border-color: #dc2626;
        }
        
        .pos-item-image {
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .pos-item-details {
            padding: 0.75rem;
        }
        
        .pos-item-name {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .pos-item-price {
            font-size: 0.9rem;
            font-weight: 700;
            color: #dc2626;
        }
        
        .pos-right {
            width: 400px;
            background: white;
            display: flex;
            flex-direction: column;
        }
        
        .pos-order-info {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .pos-order-type {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .order-type-btn {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .order-type-btn:hover, .order-type-btn.active {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }
        
        .pos-customer-info {
            display: flex;
            gap: 0.5rem;
        }
        
        .pos-customer-info input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.85rem;
        }
        
        .pos-cart {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }
        
        .cart-item {
            display: flex;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        
        .cart-item-info {
            flex: 1;
        }
        
        .cart-item-name {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .cart-item-modifiers {
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .cart-item-qty {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .qty-btn {
            width: 28px;
            height: 28px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .qty-btn:hover {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }
        
        .cart-item-price {
            text-align: right;
            font-weight: 600;
            color: #dc2626;
        }
        
        .pos-totals {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            font-size: 0.85rem;
        }
        
        .total-row.grand-total {
            font-size: 1.1rem;
            font-weight: 700;
            color: #dc2626;
            padding-top: 0.75rem;
            border-top: 2px solid #e2e8f0;
            margin-top: 0.5rem;
        }
        
        .pos-actions {
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 0.5rem;
        }
        
        .pos-action-btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .pos-action-btn.primary {
            background: #dc2626;
            color: white;
        }
        
        .pos-action-btn.primary:hover {
            background: #b91c1c;
        }
        
        .pos-action-btn.secondary {
            background: #64748b;
            color: white;
        }
        
        .pos-action-btn.secondary:hover {
            background: #475569;
        }
        
        .pos-action-btn.success {
            background: #16a34a;
            color: white;
        }
        
        .pos-action-btn.success:hover {
            background: #15803d;
        }
        
        .modifier-modal .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .modifier-group {
            margin-bottom: 1rem;
        }
        
        .modifier-group-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .modifier-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            cursor: pointer;
        }
        
        .modifier-option:hover {
            background: #f8fafc;
        }
        
        .modifier-option input[type="checkbox"],
        .modifier-option input[type="radio"] {
            width: 18px;
            height: 18px;
        }
        
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .payment-method-btn {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            text-align: center;
            font-size: 0.8rem;
            transition: all 0.2s;
        }
        
        .payment-method-btn:hover, .payment-method-btn.active {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="pos-container">
        <!-- Header -->
        <header class="pos-header">
            <div class="pos-brand">
                <i data-feather="utensils"></i>
                <div>
                    <h5>Shree Foodies</h5>
                    <small>Restaurant POS</small>
                </div>
            </div>
            <div class="pos-nav">
                <button class="pos-nav-btn active" onclick="location.reload()">
                    <i data-feather="grid" class="icon-xs me-1"></i> POS
                </button>
                <button class="pos-nav-btn" onclick="window.open('{{ route('admin.kitchen-display.index') }}', '_blank')">
                    <i data-feather="users" class="icon-xs me-1"></i> Kitchen
                </button>
                <button class="pos-nav-btn" onclick="window.open('{{ route('admin.tables.index') }}', '_blank')">
                    <i data-feather="layout" class="icon-xs me-1"></i> Tables
                </button>
                <button class="pos-nav-btn" onclick="window.open('{{ route('admin.orders.index') }}', '_blank')">
                    <i data-feather="list" class="icon-xs me-1"></i> Orders
                </button>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-end d-none d-md-block">
                    <small class="d-block text-white-50">{{ \App\Helpers\NepaliDateHelper::todayBS() }} BS</small>
                    <small class="d-block">{{ \Carbon\Carbon::now()->format('Y-m-d') }} AD</small>
                </div>
                <button class="btn btn-outline-light btn-sm" onclick="location.reload()">
                    <i data-feather="refresh-cw" class="icon-xs"></i>
                </button>
            </div>
        </header>

        <!-- Body -->
        <div class="pos-body">
            <!-- Left Side - Menu -->
            <div class="pos-left">
                <!-- Categories -->
                <div class="pos-categories">
                    <button class="category-btn active">All</button>
                    <button class="category-btn">Momo</button>
                    <button class="category-btn">Chowmein</button>
                    <button class="category-btn">Thukpa</button>
                    <button class="category-btn">Fried Rice</button>
                    <button class="category-btn">Curry</button>
                    <button class="category-btn">Tandoori</button>
                    <button class="category-btn">Beverages</button>
                    <button class="category-btn">Desserts</button>
                </div>

                <!-- Items Grid -->
                <div class="pos-items">
                    <div class="pos-item-card" onclick="addToCart('Chicken Momo', 150)">
                        <div class="pos-item-image">🥟</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Chicken Momo</div>
                            <div class="pos-item-price">Rs. 150</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Veg Momo', 120)">
                        <div class="pos-item-image">🥟</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Veg Momo</div>
                            <div class="pos-item-price">Rs.  120</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Chicken Chowmein', 180)">
                        <div class="pos-item-image">🍜</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Chicken Chowmein</div>
                            <div class="pos-item-price">Rs. 180</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Veg Chowmein', 140)">
                        <div class="pos-item-image">🍜</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Veg Chowmein</div>
                            <div class="pos-item-price">Rs. 140</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Chicken Thukpa', 200)">
                        <div class="pos-item-image">🍲</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Chicken Thukpa</div>
                            <div class="pos-item-price">Rs. 200</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Veg Thukpa', 160)">
                        <div class="pos-item-image">🍲</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Veg Thukpa</div>
                            <div class="pos-item-price">Rs. 160</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Chicken Fried Rice', 190)">
                        <div class="pos-item-image">🍚</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Chicken Fried Rice</div>
                            <div class="pos-item-price">Rs. 190</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Veg Fried Rice', 150)">
                        <div class="pos-item-image">🍚</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Veg Fried Rice</div>
                            <div class="pos-item-price">Rs. 150</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Chicken Curry', 220)">
                        <div class="pos-item-image">🍛</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Chicken Curry</div>
                            <div class="pos-item-price">Rs. 220</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Cold Drink', 50)">
                        <div class="pos-item-image">🥤</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Cold Drink</div>
                            <div class="pos-item-price">Rs. 50</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Lassi', 60)">
                        <div class="pos-item-image">🥛</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Lassi</div>
                            <div class="pos-item-price">Rs. 60</div>
                        </div>
                    </div>
                    <div class="pos-item-card" onclick="addToCart('Gulab Jamun', 80)">
                        <div class="pos-item-image">🍩</div>
                        <div class="pos-item-details">
                            <div class="pos-item-name">Gulab Jamun</div>
                            <div class="pos-item-price">Rs. 80</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Cart -->
            <div class="pos-right">
                <!-- Order Info -->
                <div class="pos-order-info">
                    <div class="pos-order-type">
                        <button class="order-type-btn active" onclick="setOrderType('dine_in')">Dine In</button>
                        <button class="order-type-btn" onclick="setOrderType('takeaway')">Take Away</button>
                        <button class="order-type-btn" onclick="setOrderType('delivery')">Delivery</button>
                    </div>
                    <div class="pos-customer-info">
                        <input type="text" placeholder="Customer Name" id="customerName">
                        <input type="text" placeholder="Phone" id="customerPhone">
                        <input type="text" placeholder="Table" id="tableNumber">
                    </div>
                </div>

                <!-- Cart Items -->
                <div class="pos-cart" id="cartItems">
                    <div class="text-center text-muted py-5">
                        <i data-feather="shopping-cart" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                        <p class="mt-2">Cart is empty</p>
                    </div>
                </div>

                <!-- Totals -->
                <div class="pos-totals">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span id="subtotal">Rs. 0.00</span>
                    </div>
                    <div class="total-row">
                        <span>Discount</span>
                        <span id="discount">Rs. 0.00</span>
                    </div>
                    <div class="total-row">
                        <span>VAT (13%)</span>
                        <span id="vat">Rs. 0.00</span>
                    </div>
                    <div class="total-row">
                        <span>Service Charge (10%)</span>
                        <span id="serviceCharge">Rs. 0.00</span>
                    </div>
                    <div class="total-row">
                        <span>Delivery Charge</span>
                        <span id="deliveryCharge">Rs. 0.00</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Grand Total</span>
                        <span id="grandTotal">Rs. 0.00</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pos-actions">
                    <button class="pos-action-btn secondary" onclick="clearCart()">
                        <i data-feather="trash-2" class="icon-xs me-1"></i> Clear
                    </button>
                    <button class="pos-action-btn success" onclick="holdOrder()">
                        <i data-feather="pause" class="icon-xs me-1"></i> Hold
                    </button>
                    <button class="pos-action-btn primary" onclick="showPaymentModal()">
                        <i data-feather="credit-card" class="icon-xs me-1"></i> Pay
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-methods">
                        <button class="payment-method-btn active" onclick="selectPayment('cash')">
                            <i data-feather="dollar-sign" class="icon-xs d-block mx-auto mb-1"></i>
                            Cash
                        </button>
                        <button class="payment-method-btn" onclick="selectPayment('esewa')">
                            <i data-feather="smartphone" class="icon-xs d-block mx-auto mb-1"></i>
                            eSewa
                        </button>
                        <button class="payment-method-btn" onclick="selectPayment('khalti')">
                            <i data-feather="smartphone" class="icon-xs d-block mx-auto mb-1"></i>
                            Khalti
                        </button>
                        <button class="payment-method-btn" onclick="selectPayment('fonepay')">
                            <i data-feather="credit-card" class="icon-xs d-block mx-auto mb-1"></i>
                            Fonepay
                        </button>
                        <button class="payment-method-btn" onclick="selectPayment('card')">
                            <i data-feather="credit-card" class="icon-xs d-block mx-auto mb-1"></i>
                            Card
                        </button>
                        <button class="payment-method-btn" onclick="selectPayment('bank')">
                            <i data-feather="briefcase" class="icon-xs d-block mx-auto mb-1"></i>
                            Bank
                        </button>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount to Pay</label>
                        <input type="text" class="form-control form-control-lg" id="amountToPay" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount Received</label>
                        <input type="number" class="form-control form-control-lg" id="amountReceived" oninput="calculateChange()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Change</label>
                        <input type="text" class="form-control form-control-lg" id="changeAmount" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="processPayment()">
                        <i data-feather="check" class="icon-xs me-1"></i> Complete Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modifier Modal -->
    <div class="modal fade" id="modifierModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modifier-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Modifiers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="modifier-group">
                        <div class="modifier-group-title">Spice Level</div>
                        <label class="modifier-option">
                            <input type="radio" name="spice" value="mild"> Mild
                            <span class="ms-auto">Rs. 0</span>
                        </label>
                        <label class="modifier-option">
                            <input type="radio" name="spice" value="medium" checked> Medium
                            <span class="ms-auto">Rs. 0</span>
                        </label>
                        <label class="modifier-option">
                            <input type="radio" name="spice" value="spicy"> Spicy
                            <span class="ms-auto">Rs. 0</span>
                        </label>
                        <label class="modifier-option">
                            <input type="radio" name="spice" value="extra_spicy"> Extra Spicy
                            <span class="ms-auto">Rs. 10</span>
                        </label>
                    </div>
                    <div class="modifier-group">
                        <div class="modifier-group-title">Add-ons</div>
                        <label class="modifier-option">
                            <input type="checkbox" value="cheese"> Extra Cheese
                            <span class="ms-auto">Rs. 30</span>
                        </label>
                        <label class="modifier-option">
                            <input type="checkbox" value="egg"> Egg
                            <span class="ms-auto">Rs. 25</span>
                        </label>
                        <label class="modifier-option">
                            <input type="checkbox" value="chicken"> Extra Chicken
                            <span class="ms-auto">Rs. 50</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addModifiersToCart()">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
        
        let cart = [];
        let currentOrderType = 'dine_in';
        let currentItem = null;
        
        function setOrderType(type) {
            currentOrderType = type;
            document.querySelectorAll('.order-type-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            const tableInput = document.getElementById('tableNumber');
            if (type === 'delivery') {
                tableInput.placeholder = 'Delivery Address';
            } else if (type === 'takeaway') {
                tableInput.placeholder = 'Order #';
            } else {
                tableInput.placeholder = 'Table';
            }
        }
        
        function addToCart(name, price) {
            currentItem = { name, price };
            const modal = new bootstrap.Modal(document.getElementById('modifierModal'));
            modal.show();
        }
        
        function addModifiersToCart() {
            const spice = document.querySelector('input[name="spice"]:checked')?.value || 'medium';
            const addons = Array.from(document.querySelectorAll('.modifier-option input[type="checkbox"]:checked'))
                .map(cb => cb.value);
            
            const existingItem = cart.find(item => 
                item.name === currentItem.name && 
                item.spice === spice && 
                JSON.stringify(item.addons) === JSON.stringify(addons)
            );
            
            if (existingItem) {
                existingItem.quantity++;
            } else {
                let modifierPrice = 0;
                if (spice === 'extra_spicy') modifierPrice += 10;
                if (addons.includes('cheese')) modifierPrice += 30;
                if (addons.includes('egg')) modifierPrice += 25;
                if (addons.includes('chicken')) modifierPrice += 50;
                
                cart.push({
                    name: currentItem.name,
                    price: currentItem.price + modifierPrice,
                    basePrice: currentItem.price,
                    quantity: 1,
                    spice,
                    addons,
                    modifierPrice
                });
            }
            
            bootstrap.Modal.getInstance(document.getElementById('modifierModal')).hide();
            updateCart();
        }
        
        function updateCart() {
            const cartContainer = document.getElementById('cartItems');
            
            if (cart.length === 0) {
                cartContainer.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i data-feather="shopping-cart" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                        <p class="mt-2">Cart is empty</p>
                    </div>
                `;
                feather.replace();
            } else {
                cartContainer.innerHTML = cart.map((item, index) => `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-modifiers">
                                ${item.spice !== 'medium' ? item.spice + ', ' : ''}
                                ${item.addons.join(', ') || 'No modifiers'}
                            </div>
                        </div>
                        <div class="cart-item-qty">
                            <button class="qty-btn" onclick="updateQty(${index}, -1)">-</button>
                            <span>${item.quantity}</span>
                            <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                        <div class="cart-item-price">Rs. ${(item.price * item.quantity).toFixed(2)}</div>
                    </div>
                `).join('');
            }
            
            updateTotals();
        }
        
        function updateQty(index, change) {
            cart[index].quantity += change;
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }
            updateCart();
        }
        
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const discount = 0;
            const vat = subtotal * 0.13;
            const serviceCharge = subtotal * 0.10;
            const deliveryCharge = currentOrderType === 'delivery' ? 50 : 0;
            const grandTotal = subtotal - discount + vat + serviceCharge + deliveryCharge;
            
            document.getElementById('subtotal').textContent = 'Rs. ' + subtotal.toFixed(2);
            document.getElementById('discount').textContent = 'Rs. ' + discount.toFixed(2);
            document.getElementById('vat').textContent = 'Rs. ' + vat.toFixed(2);
            document.getElementById('serviceCharge').textContent = 'Rs. ' + serviceCharge.toFixed(2);
            document.getElementById('deliveryCharge').textContent = 'Rs. ' + deliveryCharge.toFixed(2);
            document.getElementById('grandTotal').textContent = 'Rs. ' + grandTotal.toFixed(2);
        }
        
        function clearCart() {
            if (confirm('Clear cart?')) {
                cart = [];
                updateCart();
            }
        }
        
        function holdOrder() {
            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }
            alert('Order held successfully!');
            cart = [];
            updateCart();
        }
        
        function showPaymentModal() {
            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }
            const grandTotal = document.getElementById('grandTotal').textContent;
            document.getElementById('amountToPay').value = grandTotal;
            document.getElementById('amountReceived').value = '';
            document.getElementById('changeAmount').value = '';
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }
        
        function selectPayment(method) {
            document.querySelectorAll('.payment-method-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }
        
        function calculateChange() {
            const toPay = parseFloat(document.getElementById('amountToPay').value.replace('Rs. ', ''));
            const received = parseFloat(document.getElementById('amountReceived').value) || 0;
            const change = received - toPay;
            document.getElementById('changeAmount').value = 'Rs. ' + (change >= 0 ? change.toFixed(2) : '0.00');
        }
        
        function processPayment() {
            alert('Payment processed successfully!');
            cart = [];
            updateCart();
            bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
        }
    </script>
</body>
</html>
