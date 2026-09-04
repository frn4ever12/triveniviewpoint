
const POS = {
    cart: [],
    currentOrderMode: 'dine_in',
    isProcessingOrder: false,
    lastCreatedOrder: null,
    currentOrderDetails: null,
    audioCtx: null,
    isAudioInit: false,
    dom: {} // Cached DOM references
};

document.addEventListener('DOMContentLoaded', function() {
    cacheDOM();
    initEventListeners();
    updateCartDisplay();
    initMobileCart();

    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Init audio on first click
    document.addEventListener('click', function initAudio() {
        if (!POS.isAudioInit) {
            initAudioSystem();
            document.removeEventListener('click', initAudio);
        }
    }, { once: true });

    // Modal event listeners for loading content
    const tablesModal = document.getElementById('tablesModal');
    if (tablesModal) {
        tablesModal.addEventListener('show.bs.modal', loadTablesContent);
    }

    const kotModal = document.getElementById('kotModal');
    if (kotModal) {
        kotModal.addEventListener('show.bs.modal', loadKotContent);
    }

    const checkoutModal = document.getElementById('checkoutModal');
    if (checkoutModal) {
        checkoutModal.addEventListener('show.bs.modal', loadCheckoutContent);
    }

    const todaysOrdersModal = document.getElementById('todaysOrdersModal');
    if (todaysOrdersModal) {
        todaysOrdersModal.addEventListener('show.bs.modal', loadTodaysOrders);
    }
});

function cacheDOM() {
    POS.dom = {
        cartItems: document.getElementById('cartItems'),
        clearCartBtn: document.getElementById('clearCartBtn'),
        confirmBtn: document.getElementById('confirmOrderBtn'),
        totalQty: document.getElementById('totalQty'),
        totalAmount: document.getElementById('totalAmount'),
        tableSelect: document.getElementById('tableSelect'),
        waiterSelect: document.getElementById('waiterSelect'),
        tableSelection: document.getElementById('tableSelection'),
        customerInfo: document.getElementById('customerInfo'),
        customerName: document.getElementById('customerName'),
        customerPhone: document.getElementById('customerPhone'),
        deliveryAddress: document.getElementById('deliveryAddress'),
        deliveryAddressField: document.getElementById('deliveryAddressField'),
        orderNotes: document.getElementById('orderNotes'),
        dishSearch: document.getElementById('dishSearch'),
        orderDetails: document.getElementById('orderDetails'),
        orderDetailsContent: document.getElementById('orderDetailsContent'),
        deliveryOrdersContent: document.getElementById('deliveryOrdersContent'),
        ongoingOrdersContent: document.getElementById('ongoingOrdersContent'),
        ordersContent: document.getElementById('ordersContent'),
        tablesContent: document.getElementById('tablesContent'),
        kotContent: document.getElementById('kotContent'),
        checkoutContent: document.getElementById('checkoutContent'),
        quickCheckoutBtn: document.getElementById('quickCheckoutBtn'),
        checkoutBtn: document.getElementById('checkoutBtn'),
        categorySlider: document.getElementById('categorySlider'),
        menuCategorySlider: document.getElementById('menuCategorySlider'),
        menuSlider: document.getElementById('menuSlider'),
        menuContainer: document.getElementById('menuContainer'),
        menuItemsGrid: document.getElementById('menuItemsGrid'),
        clearAllBtn: document.getElementById('clearCartBtn'),
        cartPanel: document.getElementById('cartPanel'),
        kotContent: document.getElementById('kotContent'),
    };
}

function initEventListeners() {
    // Order mode buttons
    document.querySelectorAll('.pos-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            POS.currentOrderMode = this.dataset.mode;
            document.querySelectorAll('.pos-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            toggleOrderModeFields();
        });
    });

    // Category buttons
    document.querySelectorAll('.pos-cat-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.pos-cat-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterItems(this.dataset.category);
        });
    });

    // Search input
    const searchEl = POS.dom.dishSearch;
    if (searchEl) {
        searchEl.addEventListener('input', function() {
            searchDishes(this.value);
        });
    }

    // Confirm order
    if (POS.dom.confirmBtn) {
        POS.dom.confirmBtn.addEventListener('click', confirmOrder);
    }

    // Clear cart
    if (POS.dom.clearAllBtn) {
        POS.dom.clearAllBtn.addEventListener('click', function() {
            if (POS.cart.length > 0 && confirm('Clear all items?')) {
                POS.cart = [];
                updateCartDisplay();
            }
        });
    }

    // Category slider scroll
    const catSlider = document.getElementById('menuCategorySlider');
    if (catSlider) {
        const prevBtn = document.getElementById('prevCategory');
        const nextBtn = document.getElementById('nextCategory');
        if (prevBtn) prevBtn.addEventListener('click', () => catSlider.scrollBy({ left: -200, behavior: 'smooth' }));
        if (nextBtn) nextBtn.addEventListener('click', () => catSlider.scrollBy({ left: 200, behavior: 'smooth' }));
    }

    // Modal events
    const deliveryModal = document.getElementById('deliveryOrdersModal');
    if (deliveryModal) {
        deliveryModal.addEventListener('show.bs.modal', loadDeliveryOrders);
    }
    const ongoingModal = document.getElementById('ongoingOrdersModal');
    if (ongoingModal) {
        ongoingModal.addEventListener('show.bs.modal', loadOngoingOrders);
    }
    if (typeof $ !== 'undefined') {
        $('#todaysOrdersModal').on('show.bs.modal', function() {
            $.ajax({
                url: '/admin/orders/today',
                type: 'GET',
                data: { ajax: 1 },
                success: function(resp) {
                    $('#ordersContent').html(resp);
                    if (typeof feather !== 'undefined') feather.replace();
                    initTodaysTable();
                },
                error: function() {
                    $('#ordersContent').html('<div class="alert alert-danger">Failed to load orders</div>');
                }
            });
        });
    }
}

// ══════════════════════════════════════════════════════════════
// MOBILE CART
// ══════════════════════════════════════════════════════════════

function initMobileCart() {
    const cartPanel = POS.dom.cartPanel;
    if (!cartPanel) return;

    const handle = cartPanel.querySelector('.pos-cart-drag-handle');
    if (!handle) return;

    let startY = 0, currentY = 0, isDragging = false;
    let isExpanded = false;

    function onStart(e) {
        startY = e.type === 'touchstart' ? e.touches[0].clientY : e.clientY;
        isDragging = true;
        handle.style.cursor = 'grabbing';
    }

    function onMove(e) {
        if (!isDragging) return;
        currentY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
        const diff = startY - currentY;
        if (diff > 10 && !isExpanded) {
            isExpanded = true;
            cartPanel.classList.add('expanded');
            isDragging = false;
            handle.style.cursor = 'grab';
        } else if (diff < -30 && isExpanded) {
            isExpanded = false;
            cartPanel.classList.remove('expanded');
            isDragging = false;
            handle.style.cursor = 'grab';
        }
    }

    function onEnd() {
        isDragging = false;
        handle.style.cursor = 'grab';
    }

    handle.addEventListener('touchstart', onStart, { passive: true });
    document.addEventListener('touchmove', onMove, { passive: true });
    document.addEventListener('touchend', onEnd);

    handle.addEventListener('mousedown', onStart);
    document.addEventListener('mousemove', onMove);
    document.addEventListener('mouseup', onEnd);

    // Toggle on click
    handle.addEventListener('click', function() {
        isExpanded = !isExpanded;
        cartPanel.classList.toggle('expanded', isExpanded);
    });
}

// ══════════════════════════════════════════════════════════════
// AUDIO SYSTEM
// ══════════════════════════════════════════════════════════════

function initAudioSystem() {
    if (POS.isAudioInit) return;
    try {
        POS.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    } catch(e) {}
    POS.isAudioInit = true;
}

function playBeep(freq, dur, vol) {
    if (!POS.audioCtx) return;
    try {
        if (POS.audioCtx.state === 'suspended') POS.audioCtx.resume();
        const osc = POS.audioCtx.createOscillator();
        const gain = POS.audioCtx.createGain();
        osc.connect(gain);
        gain.connect(POS.audioCtx.destination);
        osc.frequency.setValueAtTime(freq, POS.audioCtx.currentTime);
        osc.type = 'sine';
        gain.gain.setValueAtTime(0, POS.audioCtx.currentTime);
        gain.gain.linearRampToValueAtTime(vol || 0.1, POS.audioCtx.currentTime + 0.01);
        gain.gain.exponentialRampToValueAtTime(0.001, POS.audioCtx.currentTime + (dur || 100) / 1000);
        osc.start(POS.audioCtx.currentTime);
        osc.stop(POS.audioCtx.currentTime + (dur || 100) / 1000);
    } catch(e) {}
}

function playAddSound() { playBeep(3500, 60, 0.08); }
function playSuccessSound() { playBeep(1000, 100, 0.08); }
function playErrorSound() { playBeep(400, 250, 0.1); }
function playConfirmSound() {
    playBeep(800, 100, 0.08);
    setTimeout(() => playBeep(1000, 100, 0.08), 150);
}

// ══════════════════════════════════════════════════════════════
// ORDER MODE TOGGLING
// ══════════════════════════════════════════════════════════════

function toggleOrderModeFields() {
    const mode = POS.currentOrderMode;
    const d = POS.dom;

    if (mode === 'dine_in') {
        d.tableSelection?.classList.remove('d-none');
        d.customerInfo?.classList.add('d-none');
        d.deliveryAddressField?.classList.add('d-none');
    } else {
        d.tableSelection?.classList.add('d-none');
        d.customerInfo?.classList.remove('d-none');
        d.deliveryAddressField?.classList.toggle('d-none', mode !== 'delivery');
    }
}

// ══════════════════════════════════════════════════════════════
// MENU FILTERING
// ══════════════════════════════════════════════════════════════

function filterItems(categoryId) {
    document.querySelectorAll('.pos-item-card').forEach(card => {
        const cat = card.dataset.categoryId;
        card.style.display = (categoryId === 'all' || cat === categoryId) ? '' : 'none';
    });
    document.querySelectorAll('.pos-menu-section').forEach(section => {
        const cat = section.dataset.categoryId;
        section.style.display = (categoryId === 'all' || cat === categoryId) ? '' : 'none';
    });
}

function searchDishes(term) {
    const t = term.toLowerCase();
    document.querySelectorAll('.pos-item-card').forEach(card => {
        const name = card.querySelector('.pos-item-name')?.textContent?.toLowerCase() || '';
        card.style.display = name.includes(t) ? '' : 'none';
    });
    document.querySelectorAll('.pos-menu-section').forEach(section => {
        const visibleItems = section.querySelectorAll('.pos-item-card[style*="display: block"], .pos-item-card:not([style])');
        section.style.display = visibleItems.length > 0 ? '' : 'none';
    });
}

// ══════════════════════════════════════════════════════════════
// CART MANAGEMENT
// ══════════════════════════════════════════════════════════════

function addToCart(dishId, dishName, price, image) {
    // Check if this exact item (same dish + same size) already exists in cart
    // When clicking from menu, size is always 1 (Full)
    const defaultSize = 1;
    const idx = POS.cart.findIndex(item => item.id === dishId && Math.abs(item.size - defaultSize) < 0.01);
    if (idx > -1) {
        POS.cart[idx].quantity += 1;
    } else {
        POS.cart.push({
            id: dishId, name: dishName, basePrice: parseFloat(price),
            quantity: 1, size: defaultSize,
            image: image || 'https://via.placeholder.com/48?text=Item'
        });
    }
    playAddSound();
    updateCartDisplay();
}

function updateItemSize(index, change) {
    if (!POS.cart[index]) return;
    const ns = Math.round((POS.cart[index].size + change) * 10) / 10;
    if (ns >= 0.5 && ns <= 2) {
        POS.cart[index].size = ns;
        playBeep(700, 60, 0.06);
        updateCartDisplay();
    } else {
        playErrorSound();
    }
}

function updateQuantity(index, change) {
    if (!POS.cart[index]) return;
    POS.cart[index].quantity += change;
    if (POS.cart[index].quantity <= 0) {
        POS.cart.splice(index, 1);
        playErrorSound();
    } else {
        playBeep(600, 60, 0.06);
    }
    updateCartDisplay();
}

function removeItem(index) {
    POS.cart.splice(index, 1);
    playErrorSound();
    updateCartDisplay();
}

function clearCart() {
    if (POS.cart.length === 0) return;
    if (!confirm('Clear all items?')) return;
    POS.cart = [];
    updateCartDisplay();
}

function getSizeLabel(size) {
    if (size === 0.5) return 'Half';
    if (size === 1) return 'Full';
    return size + 'x';
}

function updateCartDisplay() {
    const d = POS.dom;
    if (!d.cartItems) return;

    if (POS.cart.length === 0) {
        d.cartItems.innerHTML = `
            <div class="pos-cart-empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <p>No items added</p>
                <small>Tap items from the menu</small>
            </div>`;
        if (d.clearAllBtn) d.clearAllBtn.style.display = 'none';
        if (d.confirmBtn) d.confirmBtn.disabled = true;
        if (d.totalQty) d.totalQty.textContent = '0';
        if (d.totalAmount) d.totalAmount.textContent = '0';
        return;
    }

    if (d.clearAllBtn) d.clearAllBtn.style.display = 'inline';
    if (d.confirmBtn) d.confirmBtn.disabled = POS.isProcessingOrder;

    let html = '';
    let totalQty = 0, totalAmt = 0;

    POS.cart.forEach((item, i) => {
        const unitPrice = item.basePrice * item.size;
        const itemTotal = unitPrice * item.quantity;
        totalQty += item.quantity;
        totalAmt += itemTotal;

        html += `
            <div class="pos-cart-item">
                <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}" class="pos-cart-item-img">
                <div class="pos-cart-item-body">
                    <div class="pos-cart-item-name">${escapeHtml(item.name)}</div>
                    <div class="pos-cart-item-price">Rs ${itemTotal.toFixed(2)}</div>
                </div>
                <div class="pos-cart-item-controls">
                    <div class="pos-size-group">
                        <button class="pos-size-btn" onclick="updateItemSize(${i}, -0.5)" ${item.size <= 0.5 ? 'disabled' : ''}>
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                        <span class="pos-size-label">${getSizeLabel(item.size)}</span>
                        <button class="pos-size-btn" onclick="updateItemSize(${i}, 0.5)" ${item.size >= 2 ? 'disabled' : ''}>
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                    </div>
                    <div class="pos-qty-group">
                        <button class="pos-qty-btn" onclick="updateQuantity(${i}, -1)">−</button>
                        <span class="pos-qty-display">${item.quantity}</span>
                        <button class="pos-qty-btn" onclick="updateQuantity(${i}, 1)">+</button>
                    </div>
                    <button class="pos-cart-remove" onclick="removeItem(${i})">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            </div>`;
    });

    d.cartItems.innerHTML = html;
    if (d.totalQty) d.totalQty.textContent = totalQty;
    if (d.totalAmount) d.totalAmount.textContent = totalAmt.toFixed(2);

    if (typeof feather !== 'undefined') feather.replace();
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

// ══════════════════════════════════════════════════════════════
// ORDER FLOW
// ══════════════════════════════════════════════════════════════

function toggleNotes() {
    const ta = POS.dom.orderNotes;
    if (!ta) return;
    ta.classList.toggle('d-none');
    if (!ta.classList.contains('d-none')) ta.focus();
}

function buildOrderData() {
    const d = POS.dom;
    const data = {
        order_type: POS.currentOrderMode,
        notes: d.orderNotes?.value || null,
        items: POS.cart.map(item => ({
            menu_item_id: item.id,
            quantity: item.quantity,
            unit_price: item.basePrice * item.size,
            size: item.size,
            notes: item.size !== 1 ? 'Size: ' + item.size + 'x' : null
        }))
    };

    if (POS.currentOrderMode === 'dine_in') {
        data.table_id = d.tableSelect?.value;
        data.waiter_id = d.waiterSelect?.value || null;
    } else {
        data.customer_name = d.customerName?.value?.trim();
        data.customer_phone = d.customerPhone?.value?.trim();
        if (POS.currentOrderMode === 'delivery') {
            data.delivery_address = d.deliveryAddress?.value?.trim();
        }
    }
    return data;
}

async function confirmOrder() {
    if (POS.cart.length === 0 || POS.isProcessingOrder) return;

    // Validate fields
    if (POS.currentOrderMode === 'dine_in') {
        if (!POS.dom.tableSelect?.value) {
            playErrorSound();
            showToast('warning', 'Please select a table');
            return;
        }
    } else {
        if (!POS.dom.customerName?.value?.trim() || !POS.dom.customerPhone?.value?.trim()) {
            playErrorSound();
            showToast('warning', 'Please enter customer name and phone');
            return;
        }
        if (POS.currentOrderMode === 'delivery' && !POS.dom.deliveryAddress?.value?.trim()) {
            playErrorSound();
            showToast('warning', 'Please enter delivery address');
            return;
        }
    }

    // Save cart for printing
    const cartForPrint = JSON.parse(JSON.stringify(POS.cart));

    POS.isProcessingOrder = true;
    const btn = POS.dom.confirmBtn;
    if (btn) {
        btn.disabled = true;
        btn.classList.add('loading');
    }

    try {
        const resp = await fetch('/admin/pos/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getMeta('csrf-token')
            },
            body: JSON.stringify(buildOrderData())
        });
        const data = await resp.json();

        if (data.success) {
            POS.lastCreatedOrder = data.order;
            POS.lastCreatedOrder.cartData = cartForPrint;
            playConfirmSound();
            showOrderConfirmation(data.order);
            POS.cart = [];
            updateCartDisplay();
        } else {
            playErrorSound();
            showToast('error', data.message || 'Failed to create order');
        }
    } catch (err) {
        console.error('Order failed:', err);
        playErrorSound();
        showToast('error', 'Network error. Please try again.');
    } finally {
        POS.isProcessingOrder = false;
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('loading');
        }
        updateCartDisplay();
    }
}

function showOrderConfirmation(order) {
    const d = POS.dom;
    if (d.orderDetails) {
        d.orderDetails.textContent = `Order #${order.order_no} confirmed successfully!`;
    }

    if (POS.currentOrderMode === 'dine_in' && d.checkoutBtn && order.table_id) {
        d.checkoutBtn.href = '/admin/orders/table/' + order.table_id + '/checkout';
        d.checkoutBtn.style.display = 'inline-block';
        if (d.quickCheckoutBtn) d.quickCheckoutBtn.style.display = 'none';
    } else {
        const qBtn = d.quickCheckoutBtn;
        if (qBtn && order.id) {
            qBtn.onclick = () => window.location.href = '/admin/orders/' + order.id + '/checkout';
            qBtn.style.display = 'inline-block';
        }
        if (d.checkoutBtn) d.checkoutBtn.style.display = 'none';
    }

    const modal = new bootstrap.Modal(document.getElementById('orderConfirmModal'));
    modal.show();
}

function startNewOrder() {
    POS.cart = [];
    if (POS.dom.orderNotes) {
        POS.dom.orderNotes.value = '';
        POS.dom.orderNotes.classList.add('d-none');
    }
    if (POS.dom.tableSelect) POS.dom.tableSelect.value = '';
    if (POS.dom.customerName) POS.dom.customerName.value = '';
    if (POS.dom.customerPhone) POS.dom.customerPhone.value = '';
    if (POS.dom.deliveryAddress) POS.dom.deliveryAddress.value = '';
    updateCartDisplay();
}

function quickCheckout(id) {
    if (id) window.location.href = '/admin/orders/' + id + '/checkout';
}

// ══════════════════════════════════════════════════════════════
// PRINT BILL & KOT
// ══════════════════════════════════════════════════════════════

function printOrderBill() {
    if (!POS.lastCreatedOrder || !POS.lastCreatedOrder.cartData) {
        showToast('error', 'No order data available');
        return;
    }
    const saved = POS.cart;
    POS.cart = POS.lastCreatedOrder.cartData;
    printBill();
    POS.cart = saved;
}

function printKot() {
    if (!POS.lastCreatedOrder || !POS.lastCreatedOrder.cartData) {
        showToast('error', 'No order data available');
        return;
    }
    const saved = POS.cart;
    POS.cart = POS.lastCreatedOrder.cartData;
    doPrintKot();
    POS.cart = saved;
}

function doPrintKot() {
    if (!POS.lastCreatedOrder || POS.cart.length === 0) {
        showToast('error', 'No items to print');
        return;
    }

    const restName = getMeta('restaurant-name') || 'Restaurant';
    const restAddr = getMeta('restaurant-address') || '';
    const restPhone = getMeta('restaurant-phone') || '';
    const userName = getMeta('user-name') || 'Staff';
    const d = POS.dom;
    const tableInfo = POS.currentOrderMode === 'dine_in'
        ? d.tableSelect?.selectedOptions[0]?.text || 'N/A'
        : 'N/A';

    const itemsHtml = POS.cart.map(function(item) {
        var sl = '';
        if (item.size === 0.5) sl = ' (Half)';
        else if (item.size === 1) sl = ' (Full)';
        else if (item.size !== 1) sl = ' (' + item.size + 'x)';
        return '<div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px;">'
            + '<span>' + item.quantity + 'x ' + item.name + sl + '</span>'
            + '<span>Rs ' + (item.basePrice * item.size * item.quantity).toFixed(2) + '</span></div>';
    }).join('');

    const printContent = '<!DOCTYPE html><html><head><title>KOT - ' + POS.lastCreatedOrder.order_no + '</title>'
        + '<style>body{font-family:\'Courier New\',monospace;font-size:11px;max-width:300px;margin:0 auto;padding:10px}'
        + '.hdr{text-align:center;border-bottom:2px dashed #000;padding-bottom:8px;margin-bottom:10px}'
        + '.hdr h3{margin:0;font-size:14px}.hdr p{margin:2px 0;font-size:10px}'
        + '.items{border-bottom:1px dashed #333;padding-bottom:10px;margin-bottom:8px}'
        + '.ftr{text-align:center;font-size:10px;padding-top:8px;border-top:1px dashed #333}'
        + '.info{font-size:10px;margin-bottom:8px}</style></head><body>'
        + '<div class="hdr"><h3>' + restName + '</h3>'
        + '<p>KITCHEN ORDER TICKET</p>'
        + '<p>' + restAddr + ' | ' + restPhone + '</p></div>'
        + '<div class="info">'
        + '<div>Order: <strong>' + POS.lastCreatedOrder.order_no + '</strong></div>'
        + '<div>Table: ' + tableInfo + ' | Staff: ' + userName + '</div>'
        + '<div>Time: ' + new Date().toLocaleString() + '</div>'
        + (POS.dom.orderNotes?.value ? '<div>Notes: ' + POS.dom.orderNotes.value + '</div>' : '')
        + '</div>'
        + '<div class="items">' + itemsHtml + '</div>'
        + '<div class="ftr"><p>Thank you! Please serve promptly.</p></div>'
        + '<\/body><\/html>';

    var pw = window.open('', '_blank', 'width=350,height=500');
    pw.document.write(printContent);
    pw.document.close();
    pw.onload = function() { pw.print(); pw.onafterprint = function() { pw.close(); }; };
}

// ══════════════════════════════════════════════════════════════
// PRINT BILL
// ══════════════════════════════════════════════════════════════

function printBill() {
    if (!POS.lastCreatedOrder) {
        showToast('error', 'No order to print');
        return;
    }

    const subtotal = POS.cart.reduce((sum, item) => {
        return sum + (item.basePrice * item.size * item.quantity);
    }, 0);
    const grandTotal = subtotal;

    const mode = POS.currentOrderMode;
    const d = POS.dom;
    const tableInfo = mode === 'dine_in'
        ? d.tableSelect?.selectedOptions[0]?.text || 'N/A'
        : 'N/A';
    const custName = mode !== 'dine_in'
        ? d.customerName?.value || 'Walk-in'
        : 'Dine-in';
    const custPhone = mode !== 'dine_in'
        ? d.customerPhone?.value || ''
        : '';
    const addr = mode === 'delivery'
        ? d.deliveryAddress?.value || ''
        : '';

    const restName = getMeta('restaurant-name') || 'Restaurant';
    const restAddr = getMeta('restaurant-address') || '';
    const restPhone = getMeta('restaurant-phone') || '';
    const userName = getMeta('user-name') || 'Staff';

    const itemsHtml = POS.cart.map(item => {
        const up = item.basePrice * item.size;
        const total = up * item.quantity;
        let sl = '';
        if (item.size === 0.5) sl = ' (Half)';
        else if (item.size === 1) sl = ' (Full)';
        else if (item.size !== 1) sl = ` (${item.size}x)`;
        return `
            <div class="pos-print-item">
                <div>
                    <div class="pos-print-item-name">${item.name}${sl}</div>
                    <div class="pos-print-item-detail">${item.quantity} x Rs ${up.toFixed(2)}</div>
                </div>
                <div class="pos-print-item-total">Rs ${total.toFixed(2)}</div>
            </div>`;
    }).join('');

    const customerBlock = mode !== 'dine_in'
        ? `<div class="pos-print-row"><span>Customer: ${custName}</span><span>Phone: ${custPhone}</span></div>`
        : `<div class="pos-print-row"><span>Table: ${tableInfo}</span><span>Staff: ${userName}</span></div>`;

    const addrBlock = mode === 'delivery' && addr
        ? `<div class="pos-print-row" style="margin-top:4px;"><span style="font-size:10px;">Address: ${addr}</span></div>`
        : '';

    const printContent = `<!DOCTYPE html>
<html>
<head>
    <title>Bill - ${POS.lastCreatedOrder.order_no}</title>
    <style>
        body { margin:0; padding:0; font-family:'Courier New',monospace; font-size:11px; max-width:400px; margin:0 auto; }
        .pos-print-header { text-align:center; padding:16px 12px; background:#000; color:#fff; margin-bottom:10px; }
        .pos-print-header h4 { font-size:14px; margin:0 0 4px; }
        .pos-print-header p { font-size:10px; margin:1px 0; }
        .pos-print-section { padding:0 12px 10px; border-bottom:1px dashed #333; margin-bottom:10px; }
        .pos-print-row { display:flex; justify-content:space-between; font-size:11px; margin-bottom:3px; }
        .pos-print-items { padding:0 12px 10px; margin-bottom:10px; }
        .pos-print-item { display:flex; justify-content:space-between; margin-bottom:5px; font-size:11px; }
        .pos-print-item-name { font-weight:700; flex:1; }
        .pos-print-item-detail { font-size:9px; color:#666; }
        .pos-print-item-total { font-weight:700; min-width:65px; text-align:right; }
        .pos-print-calc { padding:0 12px; border-top:1px dashed #333; padding-top:8px; }
        .pos-print-calc-row { display:flex; justify-content:space-between; font-size:11px; margin-bottom:3px; }
        .pos-print-grand-total { font-size:14px; font-weight:700; border-top:2px solid #333; border-bottom:2px solid #333; padding:8px 0; margin:8px 0; }
        .pos-print-footer { text-align:center; padding:14px 12px; border-top:2px dashed #333; font-size:10px; margin-top:12px; }
    </style>
</head>
<body>
    <div class="pos-print-header">
        <h4>${restName}</h4>
        <p>${restAddr}</p>
        <p>${restPhone}</p>
    </div>
    <div class="pos-print-section">
        <div class="pos-print-row"><span>Order: ${POS.lastCreatedOrder.order_no}</span><span>${new Date().toLocaleDateString()}</span></div>
        <div class="pos-print-row"><span>Type: ${mode.replace('_',' ').toUpperCase()}</span><span>${new Date().toLocaleTimeString()}</span></div>
        ${customerBlock}
        ${addrBlock}
    </div>
    <div class="pos-print-items">${itemsHtml}</div>
    <div class="pos-print-calc">
        <div class="pos-print-calc-row"><span>Subtotal:</span><span>Rs ${subtotal.toFixed(2)}</span></div>
        <div class="pos-print-calc-row pos-print-grand-total"><span>TOTAL:</span><span>Rs ${grandTotal.toFixed(2)}</span></div>
        <div class="pos-print-calc-row"><span>Payment:</span><span>Pending</span></div>
    </div>
    <div class="pos-print-footer">
        <p>Thank you! Please visit again.</p>
        <p>${new Date().toLocaleString()}</p>
    </div>
<\/body><\/html>`;

    const pw = window.open('', '_blank', 'width=420,height=600');
    pw.document.write(printContent);
    pw.document.close();
    pw.onload = function() {
        pw.print();
        pw.onafterprint = function() { pw.close(); };
    };
}

// ══════════════════════════════════════════════════════════════
// DELIVERY ORDERS
// ══════════════════════════════════════════════════════════════

function loadDeliveryOrders() {
    const div = POS.dom.deliveryOrdersContent;
    if (!div) return;
    div.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-danger mb-3" role="status"></div><p class="text-muted">Loading...</p></div>';

    fetch('/admin/orders/delivery', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            displayDeliveryOrders(data.orders);
        } else {
            div.innerHTML = '<div class="alert alert-danger m-3">Failed to load orders</div>';
        }
    })
    .catch(() => {
        div.innerHTML = '<div class="alert alert-danger m-3">Error loading orders</div>';
    });
}

function displayDeliveryOrders(orders) {
    const div = POS.dom.deliveryOrdersContent;
    if (!div) return;

    if (orders.length === 0) {
        div.innerHTML = `
            <div class="text-center py-5">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="text-muted mb-3">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
                <h6 class="text-muted">No Delivery Orders</h6>
                <p class="text-muted small">No delivery orders at the moment.</p>
            </div>`;
        if (typeof feather !== 'undefined') feather.replace();
        return;
    }

    let html = `<div class="table-responsive"><table class="table pos-delivery-table table-hover align-middle">
        <thead>
            <tr>
                <th>Order</th><th>Customer</th><th>Address</th><th>Items</th><th>Total</th>
                <th>Payment</th><th>Status</th><th>Delivery</th><th>Time</th><th>Actions</th>
            </tr>
        </thead><tbody>`;

    orders.forEach(order => {
        html += `<tr>
            <td><strong>${order.order_no}</strong></td>
            <td><div><strong>${order.customer_name || 'N/A'}</strong></div><small class="text-muted">${order.customer_phone || ''}</small></td>
            <td><div class="text-truncate" style="max-width:180px;" title="${order.delivery_address || ''}">${order.delivery_address || 'N/A'}</div></td>
            <td><span class="pos-badge pos-badge-secondary">${order.order_items_count || 0} items</span></td>
            <td><strong class="text-danger">Rs ${parseFloat(order.total||0).toFixed(2)}</strong></td>
            <td>${paymentBadge(order.payment_status)}</td>
            <td>${statusBadge(order.status)}</td>
            <td>${deliveryBadge(order.delivery_status)}</td>
            <td class="small text-muted">${getTimeAgo(order.created_at)}</td>
            <td>
                <div class="d-flex gap-1">
                    <button class="pos-action-btn" onclick="openDeliveryStatusModal(${order.id},'${order.order_no}','${order.customer_name||''}','${order.customer_phone||''}','${(order.delivery_address||'').replace(/'/g,"\\'")}')" title="Update Status">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    </button>
                    <button class="pos-action-btn" onclick="viewOrderDetails(${order.id})" title="View">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                    ${order.payment_status !== 'paid' ? `<a href="/admin/orders/${order.id}/checkout" class="pos-action-btn" title="Checkout">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </a>` : ''}
                    <button class="pos-action-btn" onclick="cancelPOSOrder(${order.id})" title="Cancel">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    </button>
                    <button class="pos-action-btn" onclick="deletePOSOrder(${order.id})" title="Delete" style="color:#ef4444;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            </td>
        </tr>`;
    });

    html += '</tbody></table></div>';
    div.innerHTML = html;
    if (typeof feather !== 'undefined') feather.replace();
}

// ══════════════════════════════════════════════════════════════
// ONGOING ORDERS
// ══════════════════════════════════════════════════════════════

function loadOngoingOrders() {
    const div = POS.dom.ongoingOrdersContent;
    if (!div) return;
    div.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-warning mb-3" role="status"></div><p class="text-muted">Loading...</p></div>';

    fetch('/admin/orders/recent', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) displayOngoingOrders(data.tables);
        else div.innerHTML = '<div class="alert alert-danger m-3">Failed to load orders</div>';
    })
    .catch(() => div.innerHTML = '<div class="alert alert-danger m-3">Error loading orders</div>');
}

function displayOngoingOrders(tables) {
    const div = POS.dom.ongoingOrdersContent;
    if (!div) return;

    const activeTables = tables.filter(t => t.orders && t.orders.length > 0);
    if (activeTables.length === 0) {
        div.innerHTML = `
            <div class="text-center py-5">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="text-muted mb-3">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                </svg>
                <h6 class="text-muted">No Ongoing Orders</h6>
                <p class="text-muted small">All tables are free or orders are completed.</p>
            </div>`;
        if (typeof feather !== 'undefined') feather.replace();
        return;
    }

    let html = '';
    activeTables.forEach(table => {
        html += `<div class="pos-order-card">
            <div class="pos-order-card-header">
                <div>
                    <h6 class="mb-1"><strong>${table.name}</strong></h6>
                    <span class="pos-badge pos-badge-warning">${table.orders.length} order(s)</span>
                </div>
                <a href="/admin/orders/table/${table.id}/edit" class="pos-btn pos-btn-sm pos-btn-info" style="text-decoration:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Manage
                </a>
            </div>`;

        table.orders.forEach(order => {
            html += `<div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color:var(--pos-border-light)!important;">
                <div>
                    <strong>${order.order_no}</strong>
                    <span class="pos-badge pos-badge-info ms-2">${order.status}</span>
                    <div class="small text-muted mt-1">
                        ${order.items_count || 0} items | ${order.waiter ? 'Waiter: ' + order.waiter : ''}
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-bold">Rs ${parseFloat(order.total_amount||0).toFixed(2)}</div>
                    <small class="text-muted">${order.created_at || ''}</small>
                </div>
            </div>`;
        });

        html += `<div class="d-flex gap-2 mt-3">
            <a href="/admin/orders/table/${table.id}/edit" class="pos-btn pos-btn-sm pos-btn-primary" style="text-decoration:none;">Add Items</a>
            <a href="/admin/orders/table/${table.id}/checkout" class="pos-btn pos-btn-sm pos-btn-success" style="text-decoration:none;">Checkout</a>
            ${table.orders.map(o => `<button class="pos-btn pos-btn-sm pos-btn-warning" onclick="cancelPOSOrder(${o.id})">Cancel</button>`).join('')}
        </div></div>`;
    });

    div.innerHTML = html;
    if (typeof feather !== 'undefined') feather.replace();
}

// ══════════════════════════════════════════════════════════════
// ORDER ACTIONS
// ══════════════════════════════════════════════════════════════

function cancelPOSOrder(orderId) {
    if (!confirm('Cancel this order?')) return;
    fetch('/admin/orders/' + orderId + '/cancel', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': getMeta('csrf-token'), 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            loadDeliveryOrders();
            loadOngoingOrders();
            showToast('success', 'Order cancelled');
        } else {
            showToast('error', data.message || 'Failed to cancel');
        }
    })
    .catch(() => showToast('error', 'Failed to cancel order'));
}

function deletePOSOrder(orderId) {
    if (!confirm('Permanently delete this order? This cannot be undone.')) return;
    if (!confirm('Are you sure?')) return;
    fetch('/admin/orders/' + orderId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': getMeta('csrf-token'), 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            loadDeliveryOrders();
            loadOngoingOrders();
            showToast('success', 'Order deleted');
        } else {
            showToast('error', data.message || 'Failed to delete');
        }
    })
    .catch(() => showToast('error', 'Failed to delete order'));
}

// ══════════════════════════════════════════════════════════════
// ORDER DETAILS
// ══════════════════════════════════════════════════════════════

function viewOrderDetails(orderId) {
    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
    modal.show();

    const contentDiv = POS.dom.orderDetailsContent;
    if (!contentDiv) return;

    contentDiv.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary mb-3" role="status"></div><p class="text-muted">Loading...</p></div>';

    fetch('/admin/orders/' + orderId + '/details', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': getMeta('csrf-token') }
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(data => {
        if (data.success) displayOrderDetails(data.order);
        else throw new Error(data.message || 'Failed');
    })
    .catch(err => {
        contentDiv.innerHTML = `
            <div class="alert alert-danger m-3">
                <h6><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Error</h6>
                <p class="mb-0 small">${err.message}</p>
                <button class="btn btn-sm btn-danger mt-2" onclick="viewOrderDetails(${orderId})">Retry</button>
            </div>`;
        if (typeof feather !== 'undefined') feather.replace();
    });
}

function displayOrderDetails(order) {
    POS.currentOrderDetails = order;
    const contentDiv = POS.dom.orderDetailsContent;
    if (!contentDiv) return;

    const subtotal = order.items.reduce((sum, item) => sum + parseFloat(item.total || 0), 0);
    const grandTotal = subtotal;
    const typeBadge = order.order_type === 'dine_in' ? 'bg-primary'
        : order.order_type === 'delivery' ? 'bg-warning text-dark'
        : 'bg-info';

    const html = `
        <div class="p-3">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-2"><strong>${order.order_no}</strong> <span class="pos-badge pos-badge-${order.order_type === 'dine_in' ? 'info' : order.order_type === 'delivery' ? 'warning' : 'info'}">${order.order_type?.replace('_',' ').toUpperCase()}</span></h6>
                            <div class="mb-1"><strong>Status:</strong> ${statusBadge(order.status)}</div>
                            <div class="mb-1"><strong>Payment:</strong> ${paymentBadge(order.payment_status)}</div>
                            <div class="mb-1"><strong>Created:</strong> ${new Date(order.created_at).toLocaleString()}</div>
                        </div>
                        <div class="col-md-6">
                            ${order.order_type === 'dine_in' ? `
                                <div class="mb-1"><strong>Table:</strong> ${order.table?.name || 'N/A'}</div>
                                <div class="mb-1"><strong>Waiter:</strong> ${order.waiter?.name || 'N/A'}</div>
                            ` : `
                                <div class="mb-1"><strong>Customer:</strong> ${order.customer_name || 'N/A'}</div>
                                <div class="mb-1"><strong>Phone:</strong> ${order.customer_phone || 'N/A'}</div>
                                ${order.delivery_address ? `<div class="mb-1"><strong>Address:</strong><br><small>${order.delivery_address}</small></div>` : ''}
                            `}
                        </div>
                    </div>
                    ${order.notes ? `<div class="mt-2 pt-2 border-top"><strong>Notes:</strong><p class="mb-0 text-muted small">${order.notes}</p></div>` : ''}
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light py-2"><h6 class="mb-0">Items</h6></div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Item</th><th>Size</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>
                        <tbody>${order.items.map(item => {
                            const sl = item.size === 0.5 ? 'Half' : item.size === 1 || !item.size ? 'Full' : item.size + 'x';
                            return `<tr>
                                <td><div class="d-flex align-items-center gap-2">${item.dish?.image_url ? '<img src="'+item.dish.image_url+'" style="width:36px;height:36px;object-fit:cover;border-radius:4px;">' : ''}<div><strong>${item.dish?.name || 'Unknown'}</strong>${item.notes ? '<br><small class="text-muted">'+item.notes+'</small>' : ''}</div></div></td>
                                <td><span class="pos-badge pos-badge-secondary">${sl}</span></td>
                                <td class="text-center"><strong>${item.quantity}</strong></td>
                                <td class="text-end">Rs ${parseFloat(item.unit_price||0).toFixed(2)}</td>
                                <td class="text-end"><strong class="text-danger">Rs ${parseFloat(item.total||0).toFixed(2)}</strong></td>
                            </tr>`;
                        }).join('')}</tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row"><div class="col-md-6 offset-md-6">
                        <table class="table table-sm mb-0">
                            <tr><td>Subtotal:</td><td class="text-end"><strong>Rs ${subtotal.toFixed(2)}</strong></td></tr>
                            <tr class="border-top"><td><strong>Grand Total:</strong></td><td class="text-end"><h5 class="mb-0 text-danger">Rs ${grandTotal.toFixed(2)}</h5></td></tr>
                        </table>
                    </div></div>
                </div>
            </div>
        </div>`;

    contentDiv.innerHTML = html;
    if (typeof feather !== 'undefined') feather.replace();
}

function printOrderDetails() {
    if (!POS.currentOrderDetails) { showToast('error', 'No details to print'); return; }
    const order = POS.currentOrderDetails;
    const subtotal = order.items.reduce((s, i) => s + parseFloat(i.total||0), 0);
    const restName = getMeta('restaurant-name') || 'Restaurant';
    const restAddr = getMeta('restaurant-address') || '';
    const restPhone = getMeta('restaurant-phone') || '';

    const itemsRows = order.items.map(item => {
        const sl = item.size === 0.5 ? 'Half' : item.size === 1 || !item.size ? 'Full' : item.size + 'x';
        return `<tr><td>${item.dish?.name || 'Item'} (${sl})</td><td class="text-center">${item.quantity}</td><td class="text-right">Rs ${parseFloat(item.unit_price||0).toFixed(2)}</td><td class="text-right">Rs ${parseFloat(item.total||0).toFixed(2)}</td></tr>`;
    }).join('');

    const html = `<!DOCTYPE html>
<html><head><title>Order - ${order.order_no}</title>
<style>body{font-family:'Courier New',monospace;font-size:12px;padding:20px;max-width:400px;}.hdr{text-align:center;padding:15px 0;border-bottom:2px solid #000;margin-bottom:15px}.hdr h2{margin:0;font-size:16px}.info{margin-bottom:15px;padding-bottom:10px;border-bottom:1px dashed #333}.info-row{display:flex;justify-content:space-between;margin-bottom:3px}table{width:100%;border-collapse:collapse;margin-bottom:15px}th,td{padding:6px 4px;border-bottom:1px solid #ddd;text-align:left}.text-right{text-align:right}.text-center{text-align:center}.total{border-top:2px solid #000;padding-top:10px;margin-top:10px}.ftr{text-align:center;padding-top:15px;border-top:2px dashed #333;margin-top:20px;font-size:11px}
</style></head><body>
    <div class="hdr"><h2>${restName}</h2><p>${restAddr}<br>${restPhone}</p></div>
    <div class="info"><div class="info-row"><span><strong>Order:</strong> ${order.order_no}</span><span><strong>${new Date(order.created_at).toLocaleDateString()}</strong></span></div>
    <div class="info-row"><span><strong>Type:</strong> ${order.order_type?.replace('_',' ').toUpperCase()}</span><span>${new Date(order.created_at).toLocaleTimeString()}</span></div></div>
    <table><thead><tr><th>Item</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th></tr></thead><tbody>${itemsRows}</tbody></table>
    <div class="total"><div class="info-row"><span>Subtotal:</span><span class="text-right">Rs ${subtotal.toFixed(2)}</span></div><div class="info-row" style="font-size:14px;font-weight:700;"><span>GRAND TOTAL:</span><span class="text-right">Rs ${subtotal.toFixed(2)}</span></div></div>
    <div class="ftr"><p>Thank you!</p><p>${new Date().toLocaleString()}</p></div>
<\/body><\/html>`;

    const pw = window.open('', '_blank', 'width=400,height=600');
    pw.document.write(html);
    pw.document.close();
    pw.onload = () => { pw.print(); pw.onafterprint = () => pw.close(); };
}

// ══════════════════════════════════════════════════════════════
// DELIVERY STATUS
// ══════════════════════════════════════════════════════════════

let currentDeliveryOrderId = null;

function openDeliveryStatusModal(orderId, orderNo, customerName, customerPhone, address) {
    currentDeliveryOrderId = orderId;
    document.getElementById('modalOrderNo').textContent = orderNo;
    document.getElementById('modalCustomerInfo').textContent = (customerName || 'N/A') + ' | ' + (customerPhone || '');
    document.getElementById('modalDeliveryAddress').textContent = address || 'N/A';
    document.getElementById('deliveryStatus').value = 'pending';
    document.getElementById('statusNotes').value = '';
    new bootstrap.Modal(document.getElementById('deliveryStatusModal')).show();
}

function updateDeliveryStatus() {
    if (!currentDeliveryOrderId) return;
    const status = document.getElementById('deliveryStatus').value;
    const notes = document.getElementById('statusNotes').value;

    fetch('/admin/orders/' + currentDeliveryOrderId + '/update-delivery-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getMeta('csrf-token'), 'Accept': 'application/json' },
        body: JSON.stringify({ delivery_status: status, notes: notes })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('deliveryStatusModal'))?.hide();
            loadDeliveryOrders();
            showToast('success', 'Delivery status updated');
        } else {
            showToast('error', data.message || 'Failed');
        }
    })
    .catch(() => showToast('error', 'Failed to update status'));
}

function refreshOngoingOrders() {
    loadOngoingOrders();
}

// ══════════════════════════════════════════════════════════════
// UTILITY FUNCTIONS
// ══════════════════════════════════════════════════════════════

function getMeta(name) {
    const el = document.querySelector('meta[name="' + name + '"]');
    return el ? el.content : '';
}

function showToast(type, message) {
    // Use global toaster from toaster.js (loaded via admin.includes.bottom)
    if (typeof window.showToast === 'function' && window.showToast !== showToast) {
        window.showToast(type, message);
        return;
    }
    if (typeof $ !== 'undefined' && typeof bootstrap !== 'undefined') {
        var toastEl = $('<div class="toast align-items-center text-white bg-' + (type === 'success' ? 'success' : type === 'error' ? 'danger' : 'warning') + ' border-0" role="alert"><div class="d-flex"><div class="toast-body">' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>');
        if (!$('#toast-container').length) $('body').append('<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index:9999"></div>');
        $('#toast-container').append(toastEl);
        new bootstrap.Toast(toastEl[0]).show();
        toastEl.on('hidden.bs.toast', function() { $(this).remove(); });
        return;
    }
    if (type === 'error') alert(message);
}

function paymentBadge(status) {
    const map = {
        'pending': '<span class="pos-badge pos-badge-warning">Pending</span>',
        'paid': '<span class="pos-badge pos-badge-success">Paid</span>',
        'non_chargeable': '<span class="pos-badge pos-badge-info">N/C</span>',
        'cancelled': '<span class="pos-badge pos-badge-secondary">Cancelled</span>'
    };
    return map[status] || '<span class="pos-badge pos-badge-secondary">' + status + '</span>';
}

function statusBadge(status) {
    const map = {
        'pending': '<span class="pos-badge pos-badge-warning">Pending</span>',
        'confirmed': '<span class="pos-badge pos-badge-info">Confirmed</span>',
        'served': '<span class="pos-badge pos-badge-success">Served</span>',
        'completed': '<span class="pos-badge pos-badge-success">Completed</span>',
        'cancelled': '<span class="pos-badge pos-badge-danger">Cancelled</span>'
    };
    return map[status] || '<span class="pos-badge pos-badge-secondary">' + status + '</span>';
}

function deliveryBadge(status) {
    const map = {
        'pending': '<span class="pos-badge pos-badge-warning">Pending</span>',
        'on the way': '<span class="pos-badge pos-badge-info">On Way</span>',
        'delivered': '<span class="pos-badge pos-badge-success">Delivered</span>',
        'cancelled': '<span class="pos-badge pos-badge-danger">Cancelled</span>'
    };
    return map[status] || '<span class="pos-badge pos-badge-secondary">' + status + '</span>';
}

function getTimeAgo(dateStr) {
    if (!dateStr) return '';
    const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (diff < 60) return 'Just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return new Date(dateStr).toLocaleDateString();
}

// ══════════════════════════════════════════════════════════════
// TODAY'S ORDERS ACTIONS (used by todaysorder.blade.php in modal)
// ══════════════════════════════════════════════════════════════

function refreshTodaysOrders() {
    if (typeof $ === 'undefined') {
        location.reload();
        return;
    }
    $.ajax({
        url: '/admin/orders/today',
        type: 'GET',
        success: function(data) {
            if (POS.dom.ordersContent) {
                POS.dom.ordersContent.innerHTML = data;
            }
        }
    });
}

// ══════════════════════════════════════════════════════════════
// TABLES MODAL FUNCTIONS
// ══════════════════════════════════════════════════════════════

function loadTablesContent() {
    if (typeof $ === 'undefined') {
        POS.dom.tablesContent.innerHTML = '<div class="alert alert-warning m-3">jQuery not loaded. Please refresh the page.</div>';
        return;
    }
    
    POS.dom.tablesContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-info mb-3" role="status"><span class="visually-hidden">Loading...</span></div><p class="text-muted">Loading tables...</p></div>';
    
    $.ajax({
        url: '/admin/tables/json',
        type: 'GET',
        success: function(data) {
            let tablesHtml = '';
            
            if (data.length > 0) {
                tablesHtml = '<div class="row g-3 p-3">';
                data.forEach((table) => {
                    const status = table.status ? table.status.toLowerCase() : 'available';
                    const statusClass = status === 'occupied' ? 'bg-danger' : 'bg-success';
                    const statusText = status === 'occupied' ? 'Occupied' : 'Available';
                    
                    tablesHtml += `
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="table-card ${status === 'occupied' ? 'occupied' : 'available'}">
                                <div class="table-card-header">
                                    <h6 class="mb-0">${table.name}</h6>
                                    <span class="table-status ${statusClass}">${statusText}</span>
                                </div>
                                <div class="table-card-body">
                                    <i class="bi bi-grid-3x3 table-icon"></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
                tablesHtml += '</div>';
            } else {
                tablesHtml = '<div class="alert alert-info m-3">No tables found</div>';
            }
            
            POS.dom.tablesContent.innerHTML = tablesHtml;
        },
        error: function() {
            POS.dom.tablesContent.innerHTML = '<div class="alert alert-danger m-3">Failed to load tables. Please try again.</div>';
        }
    });
}

function refreshTables() {
    loadTablesContent();
}

// ══════════════════════════════════════════════════════════════
// KOT MODAL FUNCTIONS
// ══════════════════════════════════════════════════════════════

function loadKotContent() {
    if (typeof $ === 'undefined') {
        POS.dom.kotContent.innerHTML = '<div class="alert alert-warning m-3">jQuery not loaded. Please refresh the page.</div>';
        return;
    }
    
    POS.dom.kotContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-purple mb-3" role="status"><span class="visually-hidden">Loading...</span></div><p class="text-muted">Loading KOTs...</p></div>';
    
    $.ajax({
        url: '/admin/kitchen-display',
        type: 'GET',
        success: function(data) {
            // Extract the KOT content from the response
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data;
            const kotContent = tempDiv.querySelector('.kitchen-display') || tempDiv;
            POS.dom.kotContent.innerHTML = kotContent.innerHTML;
        },
        error: function() {
            POS.dom.kotContent.innerHTML = '<div class="alert alert-danger m-3">Failed to load KOTs. Please try again.</div>';
        }
    });
}

function refreshKots() {
    loadKotContent();
}

// ══════════════════════════════════════════════════════════════
// CHECKOUT MODAL FUNCTIONS
// ══════════════════════════════════════════════════════════════

function loadCheckoutContent() {
    if (typeof $ === 'undefined') {
        POS.dom.checkoutContent.innerHTML = '<div class="alert alert-warning m-3">jQuery not loaded. Please refresh the page.</div>';
        return;
    }
    
    POS.dom.checkoutContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-success mb-3" role="status"><span class="visually-hidden">Loading...</span></div><p class="text-muted">Loading checkout...</p></div>';
    
    $.ajax({
        url: '/admin/orders/checkout-dashboard',
        type: 'GET',
        success: function(data) {
            // Extract the checkout content from the response
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data;
            const checkoutContent = tempDiv.querySelector('.checkout-dashboard') || tempDiv.querySelector('.container-fluid') || tempDiv.querySelector('main') || tempDiv;
            POS.dom.checkoutContent.innerHTML = checkoutContent.innerHTML;
        },
        error: function(xhr, status, error) {
            console.error('Checkout load error:', xhr, status, error);
            POS.dom.checkoutContent.innerHTML = '<div class="alert alert-danger m-3">Failed to load checkout. Please try again. Error: ' + (xhr.responseText || error) + '</div>';
        }
    });
}

function refreshCheckout() {
    loadCheckoutContent();
}

function initTodaysTable() {
    var table = document.querySelector('#ordersContent .table');
    if (table && typeof $ !== 'undefined' && $.fn && $.fn.dataTable) {
        if ($.fn.dataTable.isDataTable(table)) {
            $(table).DataTable().destroy();
        }
        $(table).DataTable({
            order: [[0, 'asc']],
            pageLength: 10
        });
    }
}

window.printBillFromToday = function (orderId) {
    var printContent = document.getElementById('print-content-' + orderId);
    if (!printContent) {
        showToast('error', 'Print content not found');
        return;
    }
    var html = printContent.innerHTML;
    var pw = window.open('', '_blank', 'width=420,height=600');
    pw.document.write('<!DOCTYPE html><html><head><title>Receipt - Order #' + orderId + '</title><style>'
        + 'body{margin:0;padding:0;font-family:\'Courier New\',monospace;font-size:11px;max-width:400px;margin:0 auto}'
        + '.print-header{text-align:center;padding:15px 10px;border-bottom:2px dashed #333;background:#000;color:white;margin-bottom:10px}'
        + '.print-header h4{font-size:14px;margin:5px 0;font-weight:bold}'
        + '.print-header p{font-size:10px;margin:2px 0}'
        + '.print-order-info{padding:0 10px 10px;border-bottom:1px dashed #333;margin-bottom:10px}'
        + '.print-order-row{display:flex;justify-content:space-between;margin-bottom:3px;font-size:11px}'
        + '.print-items{padding:0 10px 10px;margin-bottom:10px}'
        + '.print-item{display:flex;justify-content:space-between;margin-bottom:6px;font-size:11px;padding-right:10px}'
        + '.print-item-name{flex:1;font-weight:bold}'
        + '.print-item-total{min-width:60px;text-align:right;font-weight:bold}'
        + '.print-order-total{border-top:1px dashed #333;padding-top:5px;margin-top:5px;font-weight:bold;font-size:11px;text-align:right}'
        + '.print-calc{padding:0 10px;border-top:1px dashed #333;margin-top:10px}'
        + '.print-calc-row{display:flex;justify-content:space-between;margin-bottom:4px;font-size:11px}'
        + '.print-total{font-weight:bold;font-size:13px;border-top:2px solid #333;border-bottom:2px solid #333;padding:6px 0;margin:8px 0}'
        + '.print-footer{text-align:center;padding:15px 10px;border-top:2px dashed #333;font-size:10px;margin-top:15px}'
        + '.no-print{display:none!important}'
        + '</style></head><body>' + html + '</body></html>');
    pw.document.close();
    pw.onload = function () { pw.print(); };
};

window.cancelOrderToday = function (orderId) {
    if (!confirm('Cancel entire order? All items will be cancelled.')) return;
    fetch('/admin/orders/' + orderId + '/cancel', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': getMeta('csrf-token'), 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (data.success) {
            showToast('success', 'Order cancelled');
            refreshTodaysOrders();
            loadOngoingOrders();
        } else {
            showToast('error', data.message || 'Failed to cancel');
        }
    })
    .catch(function () { showToast('error', 'Failed to cancel order'); });
};

window.deleteOrderToday = function (orderId) {
    if (!confirm('Permanently delete this order? This cannot be undone.')) return;
    if (!confirm('Are you sure?')) return;
    fetch('/admin/orders/' + orderId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': getMeta('csrf-token'), 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (data.success) {
            showToast('success', 'Order deleted');
            refreshTodaysOrders();
            loadOngoingOrders();
        } else {
            showToast('error', data.message || 'Failed to delete');
        }
    })
    .catch(function () { showToast('error', 'Failed to delete order'); });
};
