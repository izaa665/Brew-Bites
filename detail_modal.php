<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-container {
        background: #fff;
        width: 100%;
        max-width: 1000px;
        border-radius: 30px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transform: scale(0.9);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-height: 90vh;
        position: relative;
        margin: 20px;
    }

    .modal-close-btn {
        position: absolute;
        top: 25px;
        right: 25px;
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        color: #2d3436;
        font-size: 1.2rem;
        transition: all 0.2s;
    }

    .modal-close-btn:hover {
        background: #fff;
        transform: rotate(90deg) scale(1.1);
        color: #d35400;
    }

    .modal-close-btn:active {
        transform: rotate(90deg) scale(0.9);
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
        visibility: visible;
        backdrop-filter: blur(10px);
    }

    .modal-overlay.active .modal-container {
        transform: scale(1);
    }

    .modal-body {
        display: flex;
        flex-direction: row;
        flex: 1;
        overflow-y: auto;
        padding-bottom: 30px;
    }

    .modal-img-sec {
        flex: 1;
        background: #f8f9fa;
        padding: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        position: sticky;
        top: 0;
    }

    .modal-main-img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .modal-thumbs {
        display: flex;
        gap: 15px;
    }

    .thumb-item {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
    }

    .thumb-item.active {
        border-color: #d35400;
        transform: scale(1.05);
    }

    .modal-info-sec {
        flex: 1.2;
        padding: 40px 40px 80px 40px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .badge-recommend {
        background: #fff5ec;
        color: #d35400;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.65rem;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 0px;
    }

    .modal-spec-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 5px;
    }

    .spec-card {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 4px;
        border: 1px solid #eee;
        min-height: 70px;
        justify-content: center;
    }

    .spec-label {
        font-size: 0.6rem;
        color: #95a5a6;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

    .spec-value {
        font-weight: 800;
        color: #2d3436;
        font-size: 0.85rem;
    }

    .tag-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 5px;
    }

    .tag-item {
        background: #f1f2f6;
        color: #2f3542;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .lvl-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1.5px solid #eee;
        background: white;
        font-size: 0.75rem;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        color: #2d3436;
    }

    .lvl-btn.active {
        background: #d35400;
        color: white;
        border-color: #d35400;
        box-shadow: 0 4px 10px rgba(211, 84, 0, 0.3);
        transform: scale(1.1);
    }

    .modal-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .modal-price-tag {
        background: #d35400;
        color: white;
        padding: 12px 20px;
        border-radius: 15px;
        font-weight: 800;
        font-size: 1.2rem;
        box-shadow: 0 8px 15px rgba(211, 84, 0, 0.3);
    }

    .modal-spec-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .spec-card {
        background: #fdfdfd;
        border: 1px solid #f0f0f0;
        padding: 12px;
        border-radius: 15px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 4px;
        min-height: 80px;
    }

    .spec-label {
        font-size: 0.7rem;
        color: #95a5a6;
        font-weight: 600;
        text-transform: uppercase;
    }

    .spec-value {
        font-weight: 700;
        font-size: 0.9rem;
        color: #2d3436;
    }

    .tag-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .tag-item {
        background: #f1f2f6;
        padding: 8px 15px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #4b6584;
    }

    /* 3D Button Style */
    .btn-3d-group {
        display: flex;
        gap: 20px;
        margin-top: 20px;
        align-items: center;
    }

    .qty-control-3d {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 6px;
        border-radius: 16px;
        border: 1px solid #eee;
        height: 52px;
        box-sizing: border-box;
    }

    .qty-btn-3d {
        width: 40px;
        height: 40px;
        border: none;
        background: white;
        border-radius: 10px;
        font-weight: 800;
        color: #2d3436;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .qty-btn-3d:active {
        transform: translateY(2px);
        box-shadow: none;
    }

    .add-to-cart-3d {
        flex: 1;
        background: #d35400;
        color: white;
        border: none;
        height: 52px;
        border-radius: 16px;
        font-weight: 800;
        font-size: 1rem;
        cursor: pointer;
        position: relative;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 4px 12px rgba(211, 84, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .add-to-cart-3d:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(211, 84, 0, 0.4);
        background: #e67e22;
    }

    .add-to-cart-3d:active {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(211, 84, 0, 0.3);
    }

    @media (max-width: 768px) {
        .modal-body {
            flex-direction: column;
        }

        .modal-img-sec {
            position: relative;
            padding: 20px;
        }

        .modal-main-img {
            height: 250px;
        }
    }
</style>

<div id="product-modal" class="modal-overlay" onclick="closeModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <button class="modal-close-btn" onclick="closeModal(event)">✕</button>
        <div class="modal-body">
            <div class="modal-img-sec">
                <img id="m-img" src="" class="modal-main-img" alt="">
                <div class="modal-thumbs">
                    <img id="m-thumb-1" src="" class="thumb-item active" onclick="changeImg(this)">
                    <img id="m-thumb-2" src="https://images.unsplash.com/photo-1512058560366-cd2427ffaa96?w=200"
                        class="thumb-item" onclick="changeImg(this)">
                    <img id="m-thumb-3" src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200"
                        class="thumb-item" onclick="changeImg(this)">
                </div>
            </div>
            <div class="modal-info-sec">
                <div class="badge-recommend">🔥 Chef's Recommendation</div>
                <div class="modal-title-row">
                    <div>
                        <h1 id="m-nama" style="margin:0; font-weight:800; font-size:2.2rem; color:#2d3436;"></h1>
                        <p id="m-kat" style="color:#d35400; font-weight:700; margin:5px 0 0 0;"></p>
                    </div>
                    <div id="m-price" class="modal-price-tag"></div>
                </div>

                <p id="m-desc" style="color:#636e72; line-height:1.4; font-size:0.85rem; margin:10px 0;">
                    Hidangan istimewa kami yang diolah dengan bumbu rahasia dapur. Perpaduan rasa otentik dan bahan
                    berkualitas tinggi untuk pengalaman kuliner tak terlupakan.
                </p>

                <div class="modal-spec-grid">
                    <div class="spec-card">
                        <span class="spec-label">⏱️ Estimasi</span>
                        <span class="spec-value">10-15 Min</span>
                    </div>
                    <div class="spec-card">
                        <span class="spec-label">🍴 Porsi</span>
                        <span class="spec-value">1 Orang</span>
                    </div>
                    <div class="spec-card" id="m-lvl-card">
                        <span class="spec-label" id="m-lvl-label">🌶️ Level</span>
                        <div id="m-lvl-options" style="display:flex; gap:6px; justify-content:center; margin-top:4px;">
                            <!-- Dynamic Levels -->
                        </div>
                    </div>
                </div>

                <div style="margin-top: 10px;">
                    <h3 style="font-size:0.8rem; margin-bottom:8px; color:#2d3436;"><i class="fas fa-leaf"></i>
                        Komposisi</h3>
                    <div class="tag-container">
                        <span class="tag-item">Fresh</span>
                        <span class="tag-item">No MSG</span>
                        <span class="tag-item">Organic</span>
                    </div>
                </div>

                <div class="btn-3d-group">
                    <div class="qty-control-3d">
                        <button class="qty-btn-3d" onclick="modalUpd(-1)">−</button>
                        <span id="m-qty"
                            style="width:40px; text-align:center; font-weight:800; font-size:1.1rem;">1</span>
                        <button class="qty-btn-3d" onclick="modalUpd(1)">+</button>
                    </div>
                    <button class="add-to-cart-3d" onclick="modalAddToCart()">
                        🛒 Tambah ke Keranjang
                    </button>
                </div>


            </div>
        </div>
    </div>
</div>

<script>
    let activeProductId = '';
    let currentLevel = '';

    function openProductModal(id, nama, harga, gambar, kat) {
        activeProductId = id;
        document.getElementById('m-nama').innerText = nama;
        document.getElementById('m-price').innerText = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
        document.getElementById('m-img').src = 'img/' + gambar;
        document.getElementById('m-thumb-1').src = 'img/' + gambar;
        document.getElementById('m-kat').innerText = kat || 'Brew & Bites Special';

        // Handle Dynamic Levels (Spiciness vs Sweetness vs None)
        const lvlCard = document.getElementById('m-lvl-card');
        const lvlLabel = document.getElementById('m-lvl-label');
        const lvlOptions = document.getElementById('m-lvl-options');
        lvlOptions.innerHTML = ''; // Clear previous
        lvlCard.style.display = 'flex'; // Reset to visible

        if (id.startsWith('heavy_meals')) {
            lvlLabel.innerText = '🌶️ Kepedasan';
            const levels = ['0', '1', '2', '3'];
            levels.forEach(l => {
                const btn = document.createElement('button');
                btn.className = 'lvl-btn' + (l === '0' ? ' active' : '');
                btn.innerText = l;
                btn.onclick = () => setLevel(l, btn);
                lvlOptions.appendChild(btn);
            });
            currentLevel = '0';
        } else if (id.startsWith('hot_drinks') || id.startsWith('cold_drinks')) {
            lvlLabel.innerText = '🍯 Kemanisan';
            const levels = ['L', 'N', 'X']; // Less, Normal, Extra
            const tooltips = { 'L': 'Less Sugar', 'N': 'Normal', 'X': 'Extra Sugar' };
            levels.forEach(l => {
                const btn = document.createElement('button');
                btn.className = 'lvl-btn' + (l === 'N' ? ' active' : '');
                btn.innerText = l;
                btn.title = tooltips[l];
                btn.onclick = () => setLevel(l, btn);
                lvlOptions.appendChild(btn);
            });
            currentLevel = 'N';
        } else {
            // Desserts or others: Hide level selection
            lvlCard.style.display = 'none';
            currentLevel = '';
        }

        // Handle Dynamic Description
        const descEl = document.getElementById('m-desc');
        if (id.startsWith('heavy_meals')) {
            descEl.innerText = 'Hidangan utama yang mengenyangkan, dibuat dengan bahan segar dan bumbu pilihan untuk memuaskan rasa lapar Anda dengan cita rasa otentik.';
        } else if (id.startsWith('hot_drinks') || id.startsWith('cold_drinks')) {
            descEl.innerText = 'Minuman segar pendamping setia santai Anda. Diracik dari bahan berkualitas untuk memberikan kesegaran maksimal di setiap tegukan.';
        } else if (id.startsWith('desserts')) {
            descEl.innerText = 'Sajian penutup manis yang sempurna untuk mengakhiri sesi makan Anda. Tekstur lembut dan rasa yang pas akan memanjakan pelengkap rasa.';
        } else {
            descEl.innerText = 'Hidangan istimewa kami yang diolah dengan bumbu rahasia dapur. Perpaduan rasa otentik dan bahan berkualitas tinggi untuk pengalaman kuliner tak terlupakan.';
        }

        // Reset qty in modal
        let currentQty = (cart[id]) ? cart[id].qty : 1;
        document.getElementById('m-qty').innerText = currentQty;

        const modal = document.getElementById('product-modal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function setLevel(lvl, btn) {
        currentLevel = lvl;
        document.querySelectorAll('.lvl-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function closeModal(e) {
        const modal = document.getElementById('product-modal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function changeImg(el) {
        document.getElementById('m-img').src = el.src;
        document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    function modalUpd(d) {
        let q = parseInt(document.getElementById('m-qty').innerText);
        q += d;
        if (q < 1) q = 1;
        document.getElementById('m-qty').innerText = q;
    }

    function modalAddToCart() {
        let q = parseInt(document.getElementById('m-qty').innerText);
        const card = document.querySelector(`.card[data-id="${activeProductId}"]`);

        // Get Level Detail
        const isFood = activeProductId.startsWith('rek_') || activeProductId.startsWith('heavy_');
        const levelLabel = isFood ? 'Kepedasan' : 'Kemanisan';

        let levelText = currentLevel;
        if (!isFood && currentLevel) {
            const tooltips = { 'L': 'Less Sugar', 'N': 'Normal', 'X': 'Extra Sugar' };
            levelText = tooltips[currentLevel] || currentLevel;
        }

        // Composite Key: ID + Level
        const cartKey = currentLevel ? `${activeProductId}|${currentLevel}` : activeProductId;

        if (!cart[cartKey]) {
            cart[cartKey] = {
                pid: activeProductId, // Store original product ID for grouping
                nama: card.dataset.nama,
                harga: parseInt(card.dataset.harga),
                gambar: card.dataset.gambar,
                qty: 0
            };
        }

        cart[cartKey].qty += q; // Add quantity instead of overwrite
        if (currentLevel) {
            cart[cartKey].level = levelText;
            cart[cartKey].levelLabel = levelLabel;
        }

        updateUI();
        closeModal();
    }
</script>