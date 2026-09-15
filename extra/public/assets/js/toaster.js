function showToast(type, message) {
    const icons = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill',
    };

    const accentColors = {
        success: '#22c55e',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6',
    };

    const icon = icons[type] || icons.info;
    const accent = accentColors[type] || accentColors.info;

    if (!document.getElementById('toast-container')) {
        const c = document.createElement('div');
        c.id = 'toast-container';
        c.innerHTML = '<style>'
            + '#toast-container{position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:12px;pointer-events:none;max-width:420px;width:calc(100% - 40px)}'
            + '.st-toast{display:flex;align-items:center;gap:12px;padding:14px 16px;background:rgba(255,255,255,0.95);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border-radius:14px;box-shadow:0 8px 32px rgba(0,0,0,0.12),0 2px 8px rgba(0,0,0,0.06);border:1px solid rgba(255,255,255,0.6);pointer-events:auto;transform:translateX(120%);opacity:0;transition:transform 0.4s cubic-bezier(0.16,1,0.3,1),opacity 0.3s ease;position:relative;overflow:hidden}'
            + '.st-toast::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:2px 0 0 2px}'
            + '.st-toast.st-toast-in{transform:translateX(0);opacity:1}'
            + '.st-toast.st-toast-out{transform:translateX(120%);opacity:0}'
            + '.st-toast-icon{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}'
            + '.st-toast-success .st-toast-icon{background:rgba(34,197,94,0.12);color:#22c55e}'
            + '.st-toast-error .st-toast-icon{background:rgba(239,68,68,0.12);color:#ef4444}'
            + '.st-toast-warning .st-toast-icon{background:rgba(245,158,11,0.12);color:#f59e0b}'
            + '.st-toast-info .st-toast-icon{background:rgba(59,130,246,0.12);color:#3b82f6}'
            + '.st-toast-success::before{background:#22c55e}'
            + '.st-toast-error::before{background:#ef4444}'
            + '.st-toast-warning::before{background:#f59e0b}'
            + '.st-toast-info::before{background:#3b82f6}'
            + '.st-toast-msg{flex:1;font-size:14px;font-weight:500;color:#1e293b;line-height:1.4;min-width:0}'
            + '.st-toast-close{border:none;background:none;color:#94a3b8;cursor:pointer;padding:4px;font-size:18px;line-height:1;transition:color 0.15s;flex-shrink:0;display:flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px}'
            + '.st-toast-close:hover{color:#475569;background:rgba(0,0,0,0.05)}'
            + '.st-toast-bar{position:absolute;bottom:0;left:0;height:3px;border-radius:0 0 0 14px}'
            + '.st-toast-success .st-toast-bar{background:#22c55e}'
            + '.st-toast-error .st-toast-bar{background:#ef4444}'
            + '.st-toast-warning .st-toast-bar{background:#f59e0b}'
            + '.st-toast-info .st-toast-bar{background:#3b82f6}'
            + '@media(max-width:576px){#toast-container{top:12px;right:12px;width:calc(100% - 24px);max-width:100%}.st-toast{padding:12px 14px;font-size:13px}}'
            + '</style>';
        document.body.appendChild(c);
    }

    const el = document.createElement('div');
    el.className = 'st-toast st-toast-' + type;
    el.innerHTML = '<div class="st-toast-icon"><i class="bi ' + icon + '"></i></div><div class="st-toast-msg">' + message + '</div><button class="st-toast-close" onclick="this.closest(\'.st-toast\').remove()">&times;</button><div class="st-toast-bar"></div>';

    document.getElementById('toast-container').appendChild(el);

    requestAnimationFrame(function () {
        el.classList.add('st-toast-in');
    });

    var duration = type === 'error' ? 5000 : 3500;
    var bar = el.querySelector('.st-toast-bar');
    if (bar) {
        bar.style.width = '100%';
        requestAnimationFrame(function () {
            bar.style.transition = 'width ' + duration + 'ms linear';
            bar.style.width = '0%';
        });
    }

    setTimeout(function () {
        el.classList.remove('st-toast-in');
        el.classList.add('st-toast-out');
        setTimeout(function () { el.remove(); }, 400);
    }, duration);
}
