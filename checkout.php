<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Selesaikan Pesanan - Sraddha Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: #f8f9fa;
            color: #1e272e;
            padding-bottom: 80px;
        }

        /* Layout */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }

        @media(max-width: 900px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Headers */
        .page-header {
            margin-bottom: 30px;
        }

        .breadcrumb {
            color: #6F4E37;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .breadcrumb a {
            text-decoration: none;
            color: inherit;
        }

        h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 800;
            color: #2d3436;
        }

        .subtitle {
            color: #636e72;
            margin-top: 5px;
        }

        /* Cards */
        .card-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid #eee;
            overflow: hidden;
            margin-bottom: 25px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            padding: 25px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 700;
            font-size: 1.2rem;
        }

        /* Order List */
        .order-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 25px;
            border-bottom: 1px solid #f9f9f9;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .item-img {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
            background: #eee;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f5f6fa;
            padding: 5px;
            border-radius: 10px;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border: none;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            color: #2d3436;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .qty-btn:hover {
            color: #6F4E37;
        }

        .item-price {
            font-weight: 700;
            color: #6F4E37;
        }

        /* Order Options */
        .opt-group {
            display: flex;
            gap: 15px;
            padding: 25px;
        }

        .opt-card {
            flex: 1;
            border: 2px solid #eee;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
            position: relative;
        }

        .opt-card.active {
            border-color: #6F4E37;
            background: #FAF3E0;
            color: #6F4E37;
        }

        .opt-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }

        .opt-check {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #6F4E37;
            display: none;
        }

        .opt-card.active .opt-check {
            display: block;
        }

        .table-input {
            margin: 0 25px 25px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #eee;
        }

        .table-input input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: inherit;
        }

        /* Payment Section */
        .pay-tabs {
            display: flex;
            padding: 5px;
            background: #f5f6fa;
            border-radius: 15px;
            margin: 25px;
            gap: 5px;
        }

        .pay-tab {
            flex: 1;
            text-align: center;
            padding: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            border-radius: 10px;
            color: #636e72;
            transition: 0.2s;
        }

        .pay-tab.active {
            background: white;
            color: #6F4E37;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .pay-form {
            padding: 0 25px 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: #636e72;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 10px;
            background: #fdfdfd;
            font-family: inherit;
            box-sizing: border-box;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #6F4E37;
            outline: none;
            background: white;
        }

        .row {
            display: flex;
            gap: 15px;
        }

        /* Summary & Footer */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.95rem;
            color: #636e72;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #ddd;
            font-weight: 800;
            font-size: 1.2rem;
            color: #2d3436;
        }

        .orange-text {
            color: #6F4E37;
        }

        .btn-confirm {
            width: 100%;
            background: #6F4E37;
            color: white;
            border: none;
            padding: 20px;
            border-radius: 15px;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 25px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            justify-content: center;
            gap: 10px;
            align-items: center;
            box-shadow: 0 5px 15px rgba(111, 78, 55, 0.2);
        }

        .btn-confirm:hover {
            background: #4B3621;
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 15px 30px rgba(111, 78, 55, 0.35);
        }

        .btn-confirm:active {
            transform: translateY(1px) scale(0.98);
            box-shadow: 0 5px 10px rgba(111, 78, 55, 0.2);
        }

        .secure-badge {
            text-align: center;
            margin-top: 15px;
            color: #b2bec3;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .empty-state {
            text-align: center;
            padding: 100px 20px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container" id="main-container">
        <!-- Content Rendered by JS -->
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('cafe_cart')) || {};
        let orderType = 'dine_in';
        let paymentMethod = 'card';
        // Generate Order ID once and persist (Updated with timestamp suffix to avoid checking)
        let orderId = localStorage.getItem('current_order_id') || 'ORD-' + Math.floor(100000 + Math.random() * 900000) + '-' + Date.now().toString().slice(-3);
        localStorage.setItem('current_order_id', orderId);

        function renderCheckout() {
            const container = document.getElementById('main-container');
            const totalItems = Object.keys(cart).length;

            if (totalItems === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div style="font-size: 5rem; margin-bottom: 20px; opacity: 0.2">🛒</div>
                        <h2 style="color: #2d3436;">Keranjang Anda Kosong</h2>
                        <p style="color: #636e72; margin-bottom: 30px;">Silakan pilih menu favoritmu terlebih dahulu.</p>
                        <a href="menu.php" style="background: #6F4E37; color: white; padding: 15px 35px; border-radius: 30px; text-decoration: none; font-weight: 700;">Kembali ke Menu</a>
                    </div>`;
                return;
            }

            let cartHtml = '';
            let subtotal = 0;

            for (let id in cart) {
                let item = cart[id];
                let itemTotal = item.harga * item.qty;
                subtotal += itemTotal;
                let imgSrc = item.gambar ? `img/${item.gambar}` : 'img/default.jpg';

                cartHtml += `
                    <div class="order-item">
                        <div class="item-info">
                            <img src="${imgSrc}" class="item-img" onerror="this.src='img/default.jpg'">
                            <div>
                                <div style="font-weight: 700; margin-bottom: 5px;">${item.nama}</div>
                                ${item.level ? `<div style="font-size: 0.8rem; color: #6F4E37; font-weight: 600; margin-bottom: 5px;">${item.levelLabel}: ${item.level}</div>` : ''}
                                <div style="font-size: 0.9rem; color: #636e72;">Rp ${item.harga.toLocaleString('id-ID')}</div>
                            </div>
                        </div>
                        <div class="item-actions" style="display:flex; align-items:center; gap:20px;">
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="updateAmount('${id}', -1)">−</button>
                                <span style="font-weight: 600; min-width: 20px; text-align: center;">${item.qty}</span>
                                <button class="qty-btn" onclick="updateAmount('${id}', 1)">+</button>
                            </div>
                            <div class="item-price">Rp ${itemTotal.toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                `;
            }

            let tax = subtotal * 0.1;
            let grandTotal = subtotal + tax;



            let html = `
                <div class="page-header">
                    <div class="breadcrumb"><a href="menu.php">Keranjang</a> / Checkout</div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <h1>Selesaikan Pesanan</h1>
                        <div style="background:#FAF3E0; color:#6F4E37; padding:8px 15px; border-radius:10px; font-weight:700; border:1px solid #D2B48C;">
                            #${orderId}
                        </div>
                    </div>
                    <div class="subtitle">Tinjau pesanan Anda dan pilih metode pembayaran.</div>
                </div>

                <div class="grid-layout">
                    <!-- LEFT COLUMN -->
                    <div class="left-col">
                        <!-- Order Summary -->
                        <div class="card-box">
                            <div class="card-header">Ringkasan Pesanan</div>
                            <div class="order-list">
                                ${cartHtml}
                            </div>
                            <div style="padding: 20px 25px; border-top: 1px solid #f0f0f0; background: #fafafa; display: flex; justify-content: space-between;">
                                <span style="color: #636e72;">Subtotal</span>
                                <span style="font-weight: 700;">Rp ${subtotal.toLocaleString('id-ID')}</span>
                            </div>
                        </div>

                        <!-- Order Options -->
                        <div class="card-box">
                            <div class="card-header">Opsi Pesanan</div>
                            <div style="padding: 25px 25px 0;">
                                <label style="display:block; margin-bottom:8px; font-weight:600; color:#2d3436;">Nama Pelanggan</label>
                                <input type="text" id="cust-name" class="form-control" placeholder="Nama Anda" style="margin-bottom: 20px;">
                                
                                <label style="display:block; margin-bottom:8px; font-weight:600; color:#2d3436;">Catatan Tambahan (Opsional)</label>
                                <textarea id="order-notes" class="form-control" placeholder="Contoh: Minta saus banyak ya, tidak pakai gula, dsb..." style="height: 100px; resize: none; margin-bottom: 20px;"></textarea>
                            </div>
                            <div class="opt-group">
                                <div class="opt-card ${orderType === 'dine_in' ? 'active' : ''}" onclick="setOrderType('dine_in')">
                                    <i class="opt-check">✔</i>
                                    <span class="opt-icon">🍽️</span>
                                    <div style="font-weight:700;">Makan di Tempat</div>
                                </div>
                                <div class="opt-card ${orderType === 'takeaway' ? 'active' : ''}" onclick="setOrderType('takeaway')">
                                    <i class="opt-check">✔</i>
                                    <span class="opt-icon">📦</span>
                                    <div style="font-weight:700;">Bungkus / Takeaway</div>
                                </div>
                            </div>
                            ${orderType === 'dine_in' ? `
                            <div class="table-input">
                                <label style="display:block; margin-bottom:8px; font-weight:600; color:#2d3436;">Nomor Meja</label>
                                <input type="text" id="table-input" placeholder="Contoh: A-12">
                            </div>` : ''}
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="right-col">
                        <div class="card-box">
                            <div class="card-header">Metode Pembayaran</div>
                            <div class="pay-tabs">
                                <div class="pay-tab ${paymentMethod === 'card' ? 'active' : ''}" onclick="setPayMethod('card')">KARTU</div>
                                <div class="pay-tab ${paymentMethod === 'ewallet' ? 'active' : ''}" onclick="setPayMethod('ewallet')">QRIS</div>
                                <div class="pay-tab ${paymentMethod === 'cash' ? 'active' : ''}" onclick="setPayMethod('cash')">TUNAI</div>
                            </div>
                            
                            <div class="pay-form">
                                ${getPaymentForm(paymentMethod)}
                            </div>

                            <div style="padding: 20px 25px; background: #fafafa; border-top: 1px solid #eee;">
                                <div class="summary-row"><span>Subtotal Produk</span><span>Rp ${subtotal.toLocaleString('id-ID')}</span></div>
                                <div class="summary-row"><span>Pajak & Layanan (10%)</span><span>Rp ${tax.toLocaleString('id-ID')}</span></div>
                                <div class="total-row"><span>Total</span><span class="orange-text">Rp ${grandTotal.toLocaleString('id-ID')}</span></div>
                                
                                <button class="btn-confirm" onclick="processOrder('${orderId}')">
                                    🔒 Konfirmasi & Bayar Rp ${grandTotal.toLocaleString('id-ID')}
                                </button>
                                <div class="secure-badge">🔒 Pembayaran aman dan terenkripsi</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML = html;
        }

        function getPaymentForm(method) {
            if (method === 'card') {
                return `
                    <div class="form-group">
                        <label class="form-label">Nama Pemegang Kartu</label>
                        <input type="text" class="form-control" placeholder="Nama Lengkap">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Kartu</label>
                        <input type="text" class="form-control" placeholder="0000 0000 0000 0000">
                    </div>
                    <div class="row">
                        <div class="form-group" style="flex:1">
                            <label class="form-label">Masa Berlaku</label>
                            <input type="text" class="form-control" placeholder="BB/TT">
                        </div>
                        <div class="form-group" style="flex:1">
                            <label class="form-label">CVV</label>
                            <input type="text" class="form-control" placeholder="123">
                        </div>
                    </div>
                `;
            } else if (method === 'ewallet') {
                return `
                    <div style="text-align:center; padding: 20px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" style="width:150px; opacity:0.8;">
                        <p style="margin-top:10px; color:#636e72;">Scan QRIS ini untuk membayar</p>
                    </div>
                `;
            } else {
                return `
                    <div style="text-align:center; padding: 20px;">
                        <p style="color:#636e72;">Silakan lakukan pembayaran di kasir dengan menyebutkan nomor pesanan Anda setelah konfirmasi.</p>
                    </div>
                `;
            }
        }

        function updateAmount(id, delta) {
            if (cart[id]) {
                cart[id].qty += delta;
                if (cart[id].qty <= 0) delete cart[id];
                localStorage.setItem('cafe_cart', JSON.stringify(cart));
                renderCheckout();
                // update logic in keranjang.php if necessary or just reload
            }
        }

        function setOrderType(type) {
            orderType = type;
            renderCheckout();
        }

        function setPayMethod(method) {
            paymentMethod = method;
            renderCheckout();
        }

        function processOrder(orderId) {
            if (confirm('Lanjutkan ke Invoice?')) {
                // Prepare Invoice Data
                let items = [];
                let subtotal = 0;
                for (let id in cart) {
                    items.push(cart[id]);
                    subtotal += (cart[id].harga * cart[id].qty);
                }

                let tableNo = document.getElementById('table-input') ? document.getElementById('table-input').value : '-';
                let custName = document.getElementById('cust-name').value.trim();
                let notes = document.getElementById('order-notes').value.trim();

                if (!custName) {
                    alert('Mohon isi Nama Pelanggan.');
                    return;
                }

                let invoiceData = {
                    id: orderId,
                    items: items,
                    subtotal: subtotal,
                    tax: subtotal * 0.1,
                    total: subtotal * 1.1,
                    paymentMethod: paymentMethod,
                    orderType: orderType,
                    tableNo: tableNo,
                    customerName: custName,
                    catatan: notes
                };

                // Send to Database
                fetch('save_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(invoiceData)
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            localStorage.setItem('cafe_invoice', JSON.stringify(invoiceData));
                            window.location.href = 'invoice.php';
                        } else if (result.message && result.message.includes('Duplicate entry')) {
                            // Auto-recover from duplicate ID
                            console.warn('Duplicate Order ID detected, regenerating...');
                            let newId = 'ORD-' + Math.floor(100000 + Math.random() * 900000) + '-' + Date.now().toString().slice(-4);
                            localStorage.setItem('current_order_id', newId);
                            // Update current ID variable
                            orderId = newId;
                            // Recursive retry (safe because ID changed)
                            processOrder(newId);
                        } else {
                            alert('Gagal menyimpan pesanan: ' + result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan koneksi.');
                    });
            }
        }

        document.addEventListener('DOMContentLoaded', renderCheckout);
    </script>
</body>

</html>