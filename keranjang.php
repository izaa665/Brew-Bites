<style>
    @keyframes slideInBottom {
        from {
            transform: translate(-50%, 150%);
            opacity: 0;
        }

        to {
            transform: translate(-50%, 0);
            opacity: 1;
        }
    }

    @keyframes slideOutBottom {
        from {
            transform: translate(-50%, 0);
            opacity: 1;
        }

        to {
            transform: translate(-50%, 150%);
            opacity: 0;
        }
    }

    .cart-animate {
        animation: slideInBottom 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .cart-exit {
        animation: slideOutBottom 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
</style>
<div id="cart-bar" onclick="location.href='checkout.php'"
    style="display: none; position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); width: 85%; max-width: 420px; background: rgba(211, 84, 0, 0.9); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); color: white; padding: 16px 24px; border-radius: 24px; justify-content: space-between; align-items: center; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2), 0 10px 20px rgba(211, 84, 0, 0.3); z-index: 9999; cursor: pointer; border: 1px solid rgba(255,255,255,0.2); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
    <div style="display: flex; align-items: center; gap: 12px;">
        <div
            style="background: white; color: #d35400; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <span id="t-qty">0</span>
        </div>
        <div style="display: flex; flex-direction: column;">
            <span style="font-weight: 700; font-size: 0.95rem; line-height: 1.2;">Cek Keranjang</span>
            <span id="item-text" style="font-size: 0.75rem; opacity: 0.9; font-weight: 500;">Sudah siap dipesan?</span>
        </div>
    </div>
    <div id="t-price"
        style="font-weight: 800; font-size: 1.1rem; padding-left: 15px; border-left: 1px solid rgba(255,255,255,0.2);">
        Rp 0</div>
</div>

<script>
    let cart = JSON.parse(localStorage.getItem('cafe_cart')) || {};
    let isFirstLoad = true;

    function updateUI() {
        let tQ = 0, tP = 0;
        document.querySelectorAll('.card').forEach(card => {
            const id = card.dataset.id;
            const bA = card.querySelector('.btn-add');
            const bC = card.querySelector('.btn-counter');
            const qN = card.querySelector('.qty-num');

            // Sum all variations of this product
            let totalQtyForProduct = 0;
            for (let k in cart) {
                if (k === id || (cart[k].pid && cart[k].pid === id)) {
                    totalQtyForProduct += cart[k].qty;
                }
            }

            if (totalQtyForProduct > 0) {
                if (bA) bA.style.display = 'none';
                if (bC) bC.style.display = 'flex';
                if (qN) qN.innerText = totalQtyForProduct;
            } else {
                if (bA) bA.style.display = 'block';
                if (bC) bC.style.display = 'none';
            }
        });
        for (let k in cart) { tQ += cart[k].qty; tP += (cart[k].qty * cart[k].harga); }
        const bar = document.getElementById('cart-bar');

        if (bar) {
            if (tQ > 0) {
                bar.classList.remove('cart-exit');
                if (bar.style.display === 'none') {
                    bar.style.display = 'flex';
                    // Hanya gunakan animasi jika bukan saat pertama kali load halaman
                    if (!isFirstLoad) {
                        bar.classList.add('cart-animate');
                    }
                }
            } else {
                if (bar.style.display === 'flex' && !bar.classList.contains('cart-exit')) {
                    bar.classList.remove('cart-animate');
                    bar.classList.add('cart-exit');
                    setTimeout(() => {
                        if (bar.classList.contains('cart-exit')) {
                            bar.style.display = 'none';
                            bar.classList.remove('cart-exit');
                        }
                    }, 550);
                }
            }
            document.getElementById('t-qty').innerText = tQ;
            document.getElementById('t-price').innerText = 'Rp ' + tP.toLocaleString('id-ID');
            const itemText = document.getElementById('item-text');
            if (itemText) itemText.innerText = tQ + ' Item terpilih';
        }
        localStorage.setItem('cafe_cart', JSON.stringify(cart));
        isFirstLoad = false; // Matikan flag setelah eksekusi pertama
    }

    function upd(el, d) {
        const card = el.closest('.card');
        const id = card.dataset.id;

        // Define default levels for quick-add
        let defLvl = '';
        if (id.startsWith('heavy_meals')) defLvl = '0';
        else if (id.startsWith('hot_drinks') || id.startsWith('cold_drinks')) defLvl = 'N';

        const cartKey = defLvl ? `${id}|${defLvl}` : id;

        if (!cart[cartKey]) {
            const isFood = id.startsWith('rek_') || id.startsWith('heavy_');
            const levelLabel = isFood ? 'Kepedasan' : 'Kemanisan';
            const tooltips = { '0': 'Level 0', 'N': 'Normal' };

            cart[cartKey] = {
                pid: id,
                nama: card.dataset.nama,
                harga: parseInt(card.dataset.harga),
                gambar: card.dataset.gambar,
                qty: 0
            };

            if (defLvl) {
                cart[cartKey].level = tooltips[defLvl] || defLvl;
                cart[cartKey].levelLabel = levelLabel;
            }
        }

        cart[cartKey].qty += d;
        if (cart[cartKey].qty <= 0) delete cart[cartKey];
        updateUI();
    }
    updateUI();
    // Juga panggil saat DOMContentLoaded untuk memastikan
    document.addEventListener('DOMContentLoaded', updateUI);
</script>