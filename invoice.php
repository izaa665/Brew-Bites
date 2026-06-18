<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Receipt - Sraddha Coffee</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #6F4E37;
            --primary-light: #4B3621;
            --bg-gradient: #fafafa;
            --glass: #ffffff;
            --shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: #FAFAFA; /* Soft white background to give contrast to the white card */
            min-height: 100vh;
            color: #2d3436;
            display: flex;
            flex-direction: column;
            padding-bottom: 50px;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            padding: 0 20px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Success Animation */
        .success-animation {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .checkmark-circle {
            width: 80px;
            height: 80px;
            background: #27ae60;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(39, 174, 96, 0.2);
            animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
        }

        .checkmark {
            color: white;
            font-size: 40px;
        }

        /* Main Card */
        .card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(111, 78, 55, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            position: relative;
            animation: fadeInUp 1s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .inv-header {
            padding: 40px 40px 30px;
            background: rgba(255, 255, 255, 0.4);
            border-bottom: 2px dashed rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .brand-name {
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-id {
            font-family: 'JetBrains Mono', monospace;
            background: #eee;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #636e72;
            margin-top: 8px;
            display: inline-block;
        }

        .status-badge {
            background: #FAF3E0;
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .inv-body {
            padding: 40px;
        }

        /* Order Items */
        .item-list {
            margin-bottom: 30px;
        }

        .item-entry {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            animation: fadeInRight 0.5s ease both;
        }

        .item-entry:nth-child(1) {
            animation-delay: 0.3s;
        }

        .item-entry:nth-child(2) {
            animation-delay: 0.4s;
        }

        .item-entry:nth-child(3) {
            animation-delay: 0.5s;
        }

        .item-entry:nth-child(4) {
            animation-delay: 0.6s;
        }

        .item-info {
            flex: 1;
        }

        .item-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 4px;
        }

        .item-sub {
            font-size: 0.8rem;
            color: #6F4E37;
            font-weight: 600;
        }

        .item-qty {
            font-size: 0.85rem;
            color: #95a5a6;
            margin-top: 2px;
        }

        .item-total {
            font-weight: 800;
            font-size: 1rem;
        }

        .divider {
            height: 1px;
            background: rgba(0, 0, 0, 0.05);
            margin: 25px 0;
        }

        /* Notes Box */
        .notes-box {
            background: rgba(111, 78, 55, 0.05);
            border-left: 4px solid var(--primary);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            position: relative;
        }

        .notes-box::before {
            content: "💡";
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 1.2rem;
            opacity: 0.2;
        }

        /* Summary */
        .summary {
            background: rgba(0, 0, 0, 0.02);
            padding: 25px;
            border-radius: 20px;
        }

        .sum-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-weight: 500;
            color: #636e72;
            font-size: 0.95rem;
        }

        .sum-total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px dashed rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary);
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }

        .btn {
            flex: 1;
            padding: 18px;
            border-radius: 18px;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-align: center;
            border: none;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 20px rgba(111, 78, 55, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(111, 78, 55, 0.3);
            background: var(--primary-light);
        }

        .btn-secondary {
            background: white;
            color: #2d3436;
            border: 1px solid #eee;
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.5);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 600px) {

            .inv-header,
            .inv-body {
                padding: 30px;
            }

            .inv-title {
                font-size: 1.5rem;
            }
        }

        @media print {
            @page {
                margin: 0; /* Menghilangkan header/footer browser (tanggal, url, dsb) */
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                background: white;
                padding: 20px !important;
            }

            .container {
                margin: 0 auto;
                max-width: 600px; /* Menjaga ukuran tetap proporsional di tengah kertas */
            }

            .success-animation {
                display: none !important; /* Disembunyikan karena tidak perlu diprint di kertas struk nyata */
            }

            .card {
                box-shadow: none;
                border: 1px solid #e5e7eb;
                backdrop-filter: none;
                border-radius: 12px;
            }

            .actions,
            nav {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container" id="invoice-app">
        <!-- Content Rendered by JS -->
    </div>

    <script>
        const invoiceData = JSON.parse(localStorage.getItem('cafe_invoice'));

        if (!invoiceData) {
            document.getElementById('invoice-app').innerHTML = `
                <div style="text-align:center; padding:100px;">
                    <div style="font-size: 4rem; margin-bottom: 20px;">🏜️</div>
                    <h2 style="color: #2d3436; font-weight: 800;">Data Tidak Ditemukan</h2>
                    <p style="color: #636e72;">Sepertinya sesi pesanan Anda telah berakhir.</p>
                    <a href="index.php" class="btn btn-primary" style="display:inline-flex; width:auto; margin-top:20px;">Kembali ke Beranda</a>
                </div>
            `;
        } else {
            renderPremiumInvoice(invoiceData);
        }

        function renderPremiumInvoice(data) {
            let itemsHtml = '';
            data.items.forEach(item => {
                itemsHtml += `
                    <div class="item-entry">
                        <div class="item-info">
                            <div class="item-title">${item.nama}</div>
                            ${item.level ? `<div class="item-sub">${item.levelLabel}: ${item.level}</div>` : ''}
                            <div class="item-qty">Qty: ${item.qty} × Rp ${item.harga.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="item-total">Rp ${(item.harga * item.qty).toLocaleString('id-ID')}</div>
                    </div>
                `;
            });

            let payInfo = {
                name: 'Tunai', icon: '💵', desc: 'Silakan bayar di kasir.'
            };
            if (data.paymentMethod === 'card') payInfo = { name: 'Credit/Debit Card', icon: '💳', desc: 'Pembayaran digital terverifikasi.' };
            else if (data.paymentMethod === 'ewallet') payInfo = { name: 'QRIS / E-Wallet', icon: '📱', desc: 'Transaksi via QRIS berhasil.' };

            const dateStr = new Date().toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });

            document.getElementById('invoice-app').innerHTML = `
                <div class="success-animation">
                    <div class="checkmark-circle">
                        <span class="checkmark">✓</span>
                    </div>
                    <h1 style="font-weight: 800; margin: 0; font-size: 2.2rem; letter-spacing: -1px;">Yeay! Pesanan Terkirim</h1>
                    <p style="color: #636e72; margin-top: 10px;">Terima kasih telah makan bersama <b>Sraddha Coffee</b>.</p>
                </div>

                <div class="card">
                    <div class="inv-header">
                        <div>
                            <div class="brand-name"><span>☕</span> Sraddha Coffee</div>
                            <div class="order-id">ID: ${data.id}</div>
                            <div style="font-size: 0.8rem; color: #95a5a6; margin-top: 10px; font-weight: 500;">
                                ${dateStr}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div class="status-badge">SUKSES</div>
                            <div style="margin-top:15px; font-weight:700; font-size:0.9rem;">${data.customerName}</div>
                            <div style="font-size:0.8rem; color:#636e72;">Meja: ${data.tableNo}</div>
                        </div>
                    </div>

                    <div class="inv-body">
                        <div class="item-list">
                            ${itemsHtml}
                        </div>

                        ${data.catatan ? `
                        <div class="notes-box">
                            <h4 style="margin: 0 0 8px 0; font-size: 0.75rem; color: var(--primary); font-weight: 800; text-transform: uppercase;">Catatan Anda:</h4>
                            <p style="margin: 0; font-style: italic; color: #4b6584; font-size: 0.95rem;">"${data.catatan}"</p>
                        </div>
                        ` : ''}

                        <div class="summary">
                            <div class="sum-row">
                                <span>Subtotal</span>
                                <span>Rp ${data.subtotal.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="sum-row">
                                <span>Pajak & Layanan (10%)</span>
                                <span>Rp ${data.tax.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="sum-total">
                                <span>TOTAL</span>
                                <span>Rp ${data.total.toLocaleString('id-ID')}</span>
                            </div>
                        </div>

                        <div style="margin-top: 30px; display: flex; align-items: center; gap: 12px; background: #fafafa; padding: 15px; border-radius: 15px; border: 1px solid #f0f0f0;">
                            <span style="font-size: 1.5rem;">${payInfo.icon}</span>
                            <div>
                                <div style="font-weight: 700; font-size: 0.9rem;">${payInfo.name}</div>
                                <div style="font-size: 0.75rem; color: #95a5a6;">${payInfo.desc}</div>
                            </div>
                        </div>
                        
                        <div style="text-align: center; margin-top: 30px; opacity: 0.3; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">
                            --- Akhir dari Struk ---
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button class="btn btn-secondary" onclick="window.print()">
                        <span>📄</span> Cetak Struk
         </button>
                    <button class="btn btn-primary" onclick="finishOrder()">
                        Beranda <span>→</span>
                    </button>
                </div>
            `;
        }

        function finishOrder() {
            if (confirm('Selesaikan transaksi dan kembali ke Menu?')) {
                localStorage.removeItem('cafe_cart');
                localStorage.removeItem('current_order_id');
                localStorage.removeItem('cafe_invoice');
                window.location.href = 'index.php';
            }
        }
    </script>
</body>

</html>