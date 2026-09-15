(function () {
    var CONFIG = window.CheckoutConfig || {};

    var state = {
        subtotal: CONFIG.subtotal || 0,
        grandTotal: CONFIG.grandTotal || 0,
        selectedMethod: 'cash',
        isNonChargeable: false,
    };

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    function formatMoney(n) {
        return n.toFixed(2);
    }

    function selectMethod(btn, method) {
        document.querySelectorAll('.pm-btn').forEach(function (b) {
            b.classList.remove('active');
        });
        btn.classList.add('active');
        state.selectedMethod = method;
    }

    function toggleNonChargeable() {
        var cb = document.getElementById('nonChargeableToggle');
        var tender = document.getElementById('tenderAmount');
        var change = document.getElementById('changeDisplay');
        var btnText = document.getElementById('checkoutBtnText');
        var grandEl = document.getElementById('grandTotalDisplay');

        state.isNonChargeable = cb.checked;

        if (state.isNonChargeable) {
            tender.value = '0.00';
            tender.disabled = true;
            change.style.display = 'none';
            btnText.textContent = 'Complete as Non-Chargeable';
            grandEl.style.textDecoration = 'line-through';
            grandEl.style.color = '#94a3b8';
            document.getElementById('completeCheckoutBtn').disabled = false;
        } else {
            tender.disabled = false;
            change.style.display = '';
            btnText.textContent = 'Complete Checkout';
            grandEl.style.textDecoration = '';
            grandEl.style.color = '';
            tender.value = state.grandTotal.toFixed(2);
            updateChange();
        }
    }

    function updateCalculations() {
        var sc = parseFloat(document.getElementById('serviceChargeInput').value) || 0;
        var vp = parseFloat(document.getElementById('vatPercentInput').value) || 0;

        var taxable = state.subtotal + sc;
        var vat = Math.round(taxable * (vp / 100) * 100) / 100;
        var newTotal = taxable + vat;
        state.grandTotal = newTotal;

        document.getElementById('vatAmountDisplay').textContent = 'Rs ' + formatMoney(vat);
        document.getElementById('grandTotalDisplay').textContent = 'Rs ' + formatMoney(newTotal);

        var tender = document.getElementById('tenderAmount');
        if (!tender.disabled) {
            var cur = parseFloat(tender.value) || 0;
            if (cur > 0) {
                tender.value = formatMoney(newTotal);
            }
            updateChange();
        }

        updatePrintReceipt(sc, vp, vat, newTotal);
    }

    function updatePrintReceipt(sc, vp, vat, total) {
        var scRow = document.getElementById('prServiceChargeRow');
        if (sc > 0) {
            scRow.style.display = '';
            document.getElementById('prServiceCharge').textContent = 'Rs ' + formatMoney(sc);
        } else {
            scRow.style.display = 'none';
        }

        var vatRow = document.getElementById('prVatRow');
        if (vp > 0 && vat > 0) {
            vatRow.style.display = '';
            document.getElementById('prVatPercent').textContent = formatMoney(vp);
            document.getElementById('prVatAmount').textContent = 'Rs ' + formatMoney(vat);
        } else {
            vatRow.style.display = 'none';
        }

        document.getElementById('prGrandTotal').textContent = 'Rs ' + formatMoney(total);
    }

    function updateChange() {
        var tender = parseFloat(document.getElementById('tenderAmount').value) || 0;
        var change = tender - state.grandTotal;
        var el = document.getElementById('changeDisplay');
        var label = document.getElementById('changeLabel');
        var amount = document.getElementById('changeAmount');
        var btn = document.getElementById('completeCheckoutBtn');

        if (state.isNonChargeable) {
            el.className = 'ck-change due';
            label.textContent = 'Non-Chargeable';
            amount.textContent = 'Rs 0.00';
            btn.disabled = false;
            return;
        }

        if (change >= 0) {
            el.className = 'ck-change due';
            label.textContent = 'Change Due';
            amount.textContent = 'Rs ' + formatMoney(change);
            btn.disabled = false;
        } else {
            el.className = 'ck-change owed';
            label.textContent = 'Amount Owed';
            amount.textContent = 'Rs ' + formatMoney(Math.abs(change));
            btn.disabled = true;
        }
    }

    function showToast(type, message) {
        var colors = { success: '#059669', error: '#dc2626', info: '#2563eb' };
        var container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem;';
            document.body.appendChild(container);
        }
        var t = document.createElement('div');
        t.style.cssText = 'background:' + (colors[type] || '#333') + ';color:#fff;padding:0.75rem 1.25rem;border-radius:8px;font-size:0.85rem;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.15);animation:ckFadeIn 0.25s ease;max-width:360px;';
        t.textContent = message;
        container.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; t.style.transition = 'opacity 0.3s'; setTimeout(function () { t.remove(); }, 300); }, 3000);
    }

    function isFormValid() {
        if (state.isNonChargeable) return true;
        var tender = parseFloat(document.getElementById('tenderAmount').value) || 0;
        return tender >= state.grandTotal;
    }

    function handleCheckout() {
        var btn = document.getElementById('completeCheckoutBtn');
        if (btn.disabled) return;

        if (!isFormValid()) {
            showToast('error', 'Amount received cannot be less than total amount');
            return;
        }

        var tender = parseFloat(document.getElementById('tenderAmount').value) || 0;
        var sc = parseFloat(document.getElementById('serviceChargeInput').value) || 0;
        var vp = parseFloat(document.getElementById('vatPercentInput').value) || 0;
        var taxable = state.subtotal + sc;
        var vat = Math.round(taxable * (vp / 100) * 100) / 100;

        var originalText = document.getElementById('checkoutBtnText').textContent;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Processing...';

        var url = CONFIG.isTableCheckout
            ? '/admin/orders/table/' + CONFIG.tableId + '/checkout'
            : '/admin/orders/' + CONFIG.orderId + '/checkout';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify({
                payment_method: state.selectedMethod,
                tender_amount: tender,
                total_amount: state.isNonChargeable ? 0 : state.grandTotal,
                subtotal: state.subtotal,
                service_charge_amount: sc,
                vat_percent: vp,
                vat_amount: vat,
                is_non_chargeable: state.isNonChargeable,
            }),
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    showToast('success', data.message || 'Checkout completed successfully');
                    window.print();
                    setTimeout(function () {
                        window.location.href = '/checkout-dashboard';
                    }, 2000);
                } else {
                    showToast('error', data.message || 'Checkout failed');
                    btn.disabled = false;
                    btn.innerHTML = '<span id="checkoutBtnText">' + originalText + '</span>';
                }
            })
            .catch(function () {
                showToast('error', 'Network error occurred');
                btn.disabled = false;
                btn.innerHTML = '<span id="checkoutBtnText">' + originalText + '</span>';
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('nonChargeableToggle').addEventListener('change', toggleNonChargeable);
        document.getElementById('serviceChargeInput').addEventListener('input', updateCalculations);
        document.getElementById('vatPercentInput').addEventListener('input', updateCalculations);
        document.getElementById('tenderAmount').addEventListener('input', updateChange);
        document.getElementById('completeCheckoutBtn').addEventListener('click', handleCheckout);

        updateCalculations();
        updateChange();
    });

    window.Checkout = { selectMethod: selectMethod };
})();
