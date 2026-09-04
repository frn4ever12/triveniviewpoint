(function () {
    const CONFIG = window.OrderEditConfig || {};
    const CART = [];
    const TOAST_ICONS = { success: 'check-circle', error: 'x-circle', info: 'info' };

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    function formatMoney(amount) {
        return amount.toFixed(2);
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function initFeather() {
        if (typeof feather !== 'undefined') feather.replace();
    }

    function showToast(type, message) {
        const container = document.querySelector('.toast-container');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white border-0 bg-' + (type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info');
        toast.role = 'alert';
        toast.innerHTML = '<div class="d-flex"><div class="toast-body"><i data-feather="' + TOAST_ICONS[type] + '" width="16" height="16" class="me-2"></i>' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
        container.appendChild(toast);
        new bootstrap.Toast(toast).show();
        toast.addEventListener('hidden.bs.toast', function () { toast.remove(); });
        initFeather();
    }

    function updateCartDisplay() {
        const container = document.getElementById('cartItems');
        const qtyEl = document.getElementById('totalQty');
        const amtEl = document.getElementById('totalAmount');
        const clearBtn = document.getElementById('clearCartBtn');
        const confirmBtn = document.getElementById('confirmAddItemsBtn');

        if (CART.length === 0) {
            container.innerHTML = '<div style="text-align:center;padding:2rem 0;color:#94a3b8;font-size:0.85rem;">No items selected.</div>';
            if (clearBtn) clearBtn.style.display = 'none';
            if (confirmBtn) confirmBtn.disabled = true;
            if (qtyEl) qtyEl.textContent = '0';
            if (amtEl) amtEl.textContent = '0.00';
            return;
        }

        if (clearBtn) clearBtn.style.display = '';
        if (confirmBtn) confirmBtn.disabled = false;

        var html = '';
        var totalQty = 0;
        var totalAmt = 0;

        CART.forEach(function (item, idx) {
            var lineTotal = item.price * item.quantity;
            totalQty += item.quantity;
            totalAmt += lineTotal;

            html += '<div class="cart-item-modern" data-index="' + idx + '">'
                + '<div class="ci-name">' + escapeHtml(item.name) + '</div>'
                + '<div class="ci-price">Rs ' + formatMoney(item.price) + '</div>'
                + '<div class="qty-stepper">'
                + '<button type="button" class="qty-down">&minus;</button>'
                + '<span>' + item.quantity + '</span>'
                + '<button type="button" class="qty-up">+</button>'
                + '</div>'
                + '<button type="button" class="cart-remove-btn" title="Remove">&times;</button>'
                + '</div>';
        });

        container.innerHTML = html;
        if (qtyEl) qtyEl.textContent = totalQty;
        if (amtEl) amtEl.textContent = formatMoney(totalAmt);
    }

    function printKOT(kot) {
        var items = kot.items || [];
        var itemsHtml = items.map(function (i) {
            var name = i.dish ? i.dish.name : i.name || 'Item';
            return '<div style="margin-bottom:6px;font-size:13px;">' + i.quantity + 'x ' + escapeHtml(name) + '</div>';
        }).join('');

        var now = new Date().toLocaleString('en-IN');
        var kotNum = kot.kot_number || 'KOT-' + Date.now();

        var html = '<!DOCTYPE html><html><head><title>KOT - ' + CONFIG.tableName + '</title>'
            + '<style>body{margin:0;padding:20px;font-family:"Courier New",monospace;font-size:12px;line-height:1.4}'
            + '@media print{body{margin:0}@page{margin:0.3in;size:3in 4in}}</style>'
            + '</head><body onload="window.print();setTimeout(function(){window.close()},500)">'
            + '<div style="text-align:center;border-bottom:2px solid #000;padding-bottom:12px;margin-bottom:16px;">'
            + '<h2 style="margin:0;font-size:15px;font-weight:bold;">KITCHEN ORDER TICKET</h2>'
            + '<p style="margin:4px 0;font-size:11px;">' + kotNum + '</p>'
            + '<p style="margin:4px 0;font-size:11px;">' + now + '</p></div>'
            + '<div style="margin-bottom:12px;font-size:13px;"><strong>Table: ' + CONFIG.tableName + '</strong></div>'
            + '<div style="border-bottom:1px dashed #000;margin-bottom:12px;"></div>'
            + itemsHtml
            + '<div style="border-top:1px dashed #000;margin-top:12px;padding-top:12px;text-align:center;">'
            + '<p style="margin:0;font-size:13px;font-weight:bold;">PREPARE IMMEDIATELY</p></div>'
            + '</body></html>';

        var win = window.open('', 'kot_' + Date.now(), 'width=700,height=700');
        if (!win || win === window) {
            showToast('error', 'Popup blocked. Please allow popups to print KOT.');
            return;
        }
        win.document.open();
        win.document.write(html);
        win.document.close();
        win.focus();
    }

    document.addEventListener('DOMContentLoaded', function () {
        initFeather();

        var tabTriggers = document.querySelectorAll('#tableTabs [data-tab]');
        tabTriggers.forEach(function (btn) {
            btn.addEventListener('click', function () {
                tabTriggers.forEach(function (b) {
                    b.classList.remove('active');
                    b.style.color = '#64748b';
                    b.style.background = 'transparent';
                });
                btn.classList.add('active');
                btn.style.color = '#dc2626';
                btn.style.background = '#fff';
                document.querySelectorAll('#tabContent .tab-pane').forEach(function (p) {
                    p.classList.remove('show', 'active');
                });
                var pane = document.getElementById('pane-' + btn.dataset.tab);
                if (pane) pane.classList.add('show', 'active');
            });
        });

        if (tabTriggers.length > 0) {
            tabTriggers[0].style.color = '#dc2626';
            tabTriggers[0].style.background = '#fff';
        }

        var categorySidebar = document.getElementById('categorySidebar');
        if (categorySidebar) {
            categorySidebar.addEventListener('click', function (e) {
                var btn = e.target.closest('.category-item');
                if (!btn) return;
                categorySidebar.querySelectorAll('.category-item').forEach(function (b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');
                var menuId = btn.dataset.menuId;
                document.querySelectorAll('.dish-card-modern').forEach(function (d) {
                    d.style.display = menuId === 'all' || d.dataset.menuId === menuId ? '' : 'none';
                });
                var titleEl = document.getElementById('menuTitle');
                if (titleEl) {
                    titleEl.textContent = btn.textContent.trim().replace(/\d+$/, '').trim();
                }
            });
        }

        var dishSearch = document.getElementById('dishSearch');
        if (dishSearch) {
            dishSearch.addEventListener('input', function (e) {
                var term = e.target.value.toLowerCase();
                document.querySelectorAll('.dish-card-modern').forEach(function (d) {
                    var nameEl = d.querySelector('.dish-name');
                    var name = nameEl ? nameEl.textContent.toLowerCase() : '';
                    d.style.display = name.includes(term) ? '' : 'none';
                });
            });
        }

        var dishesContainer = document.getElementById('dishesContainer');
        if (dishesContainer) {
            dishesContainer.addEventListener('click', function (e) {
                var btn = e.target.closest('.add-dish-btn');
                if (!btn) return;
                var id = parseInt(btn.dataset.id);
                var name = btn.dataset.name;
                var price = parseFloat(btn.dataset.price);

                var existing = CART.findIndex(function (i) { return i.id === id; });
                if (existing > -1) {
                    CART[existing].quantity += 1;
                } else {
                    CART.push({ id: id, name: name, price: price, quantity: 1, size: 1 });
                }
                updateCartDisplay();
                showToast('success', escapeHtml(name) + ' added to cart');
            });
        }

        var cartItems = document.getElementById('cartItems');
        if (cartItems) {
            cartItems.addEventListener('click', function (e) {
                var itemEl = e.target.closest('.cart-item-modern');
                if (!itemEl) return;
                var idx = parseInt(itemEl.dataset.index);
                if (isNaN(idx) || !CART[idx]) return;

                if (e.target.closest('.qty-down')) {
                    CART[idx].quantity -= 1;
                    if (CART[idx].quantity <= 0) CART.splice(idx, 1);
                    updateCartDisplay();
                } else if (e.target.closest('.qty-up')) {
                    CART[idx].quantity += 1;
                    updateCartDisplay();
                } else if (e.target.closest('.cart-remove-btn')) {
                    CART.splice(idx, 1);
                    updateCartDisplay();
                }
            });
        }

        var clearCartBtn = document.getElementById('clearCartBtn');
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', function () {
                if (confirm('Clear all items from cart?')) {
                    CART.length = 0;
                    updateCartDisplay();
                    showToast('info', 'Cart cleared');
                }
            });
        }

        var confirmBtn = document.getElementById('confirmAddItemsBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', async function () {
                if (CART.length === 0) {
                    showToast('error', 'Please select items first.');
                    return;
                }

                var btn = this;
                var loadingIcon = document.getElementById('btnLoadingIcon');
                var btnText = document.getElementById('btnText');

                btn.disabled = true;
                if (loadingIcon) loadingIcon.classList.remove('d-none');
                if (btnText) btnText.textContent = 'Adding items...';

                try {
                    var response = await fetch('/admin/orders/table/' + CONFIG.tableId + '/add-items', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        body: JSON.stringify({
                            table_id: CONFIG.tableId,
                            waiter_id: document.getElementById('waiterSelect')?.value || null,
                            notes: document.getElementById('orderNotes')?.value || '',
                            items: CART.map(function (i) {
                                return { menu_item_id: i.id, quantity: i.quantity, unit_price: i.price, size: i.size || 1 };
                            })
                        })
                    });

                    var data = await response.json();
                    if (data.success) {
                        showToast('success', data.message);
                        CART.length = 0;
                        updateCartDisplay();
                        bootstrap.Modal.getInstance(document.getElementById('addItemsModal'))?.hide();
                        setTimeout(function () { window.location.reload(); }, 800);
                    } else {
                        showToast('error', data.message || 'Failed to add items');
                        btn.disabled = false;
                        if (loadingIcon) loadingIcon.classList.add('d-none');
                        if (btnText) btnText.textContent = CONFIG.hasActiveOrders ? 'Add to Existing Order' : 'Create Order';
                    }
                } catch (err) {
                    showToast('error', 'Failed to add items. Please try again.');
                    btn.disabled = false;
                    if (loadingIcon) loadingIcon.classList.add('d-none');
                    if (btnText) btnText.textContent = CONFIG.hasActiveOrders ? 'Add to Existing Order' : 'Create Order';
                }
            });
        }

        document.addEventListener('click', function (e) {
            var reprintBtn = e.target.closest('.reprint-kot-btn');
            if (!reprintBtn) return;

            var kotId = reprintBtn.dataset.kotId;
            var originalHtml = reprintBtn.innerHTML;
            reprintBtn.innerHTML = '<span style="display:inline-block;width:14px;height:14px;border:2px solid rgba(220,38,38,0.3);border-top-color:#dc2626;border-radius:50%;animation:spin 0.8s linear infinite;vertical-align:middle;margin-right:0.3rem;"></span> Reprinting...';
            reprintBtn.disabled = true;

            fetch('/admin/kots/' + kotId + '/reprint', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() }
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        showToast('success', data.message || 'KOT reprinted successfully');
                        if (data.kot) printKOT(data.kot);
                    } else {
                        showToast('error', data.message || 'Failed to reprint KOT');
                    }
                    reprintBtn.innerHTML = originalHtml;
                    reprintBtn.disabled = false;
                    initFeather();
                })
                .catch(function () {
                    showToast('error', 'Failed to reprint KOT.');
                    reprintBtn.innerHTML = originalHtml;
                    reprintBtn.disabled = false;
                });
        });
    });
})();
