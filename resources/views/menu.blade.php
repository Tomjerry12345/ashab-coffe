<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Self‑Order Demo (Menu + Order + Cart)</title>

    <!-- Bootstrap 5 & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --primary-red: #d50000;
        }

        /* ===== Common ===== */
        body.modal-open .dim-when-modal {
            filter: brightness(.25);
        }

        .icon-search {
            font-size: 1.4rem;
            color: var(--primary-red);
        }

        /* ===== Navbar ===== */
        .navbar .nav-link {
            font-weight: 500;
            color: #000;
        }

        /* ===== Category Pills ===== */
        .nav-category .nav-link {
            color: #000;
            font-weight: 600;
            border-radius: 999rem;
            padding: .6rem 2.4rem;
        }

        .nav-category .nav-link.active {
            background: var(--primary-red);
            color: #fff;
        }

        /* ===== Menu Card ===== */
        .card-menu {
            background: #f4dfe3;
            border: none;
        }

        .card-menu img {
            width: 100%;
            height: 140px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 10px;
        }

        .btn-tambah {
            border: 2px solid var(--primary-red);
            color: var(--primary-red);
            font-weight: 600;
        }

        .btn-tambah:hover {
            background: var(--primary-red);
            color: #fff;
        }

        /* ===== Location Pill ===== */
        .location-pill {
            background: #f1f1f1;
            border-radius: 999rem;
            padding: .4rem 1.4rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-weight: 600;
        }

        .location-pill .fa-map-pin {
            color: var(--primary-red);
        }

        /* ===== Order Modal ===== */
        .order-modal .modal-content {
            border-radius: 28px;
            border: none;
            overflow: hidden;
            max-width: 560px;
            margin: auto;
        }

        .order-modal .modal-header {
            border: 0;
            background: #f4dfe3;
            justify-content: center;
            position: relative;
        }

        .order-modal .close-btn {
            position: absolute;
            top: 18px;
            right: 18px;
            background: var(--primary-red);
            color: #fff;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .order-img-placeholder {
            width: 70%;
            max-width: 300px;
            margin: 30px auto 10px;

        }

        .order-title {
            font-size: 1.75rem;
            font-weight: 600;
        }

        .qty-btn {
            border: 2px solid #d3d3d3;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #9e9e9e;
            background: #fff;
        }

        .qty-btn.active {
            border-color: var(--primary-red);
            color: #fff;
            background: var(--primary-red);
        }

        .buy-btn {
            background: var(--primary-red);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 34px;
            padding: 14px 0;
            font-size: 1.1rem;
        }

        /* ===== Cart Modal ===== */
        .cart-modal .modal-content {
            border-radius: 28px;
            border: none;
            overflow: hidden;
            max-width: 640px;
            margin: auto;
        }

        .cart-modal .modal-header {
            border: 0;
            padding: 20px 28px;
        }

        .back-btn {
            background: var(--primary-red);
            color: #fff;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-title {
            flex: 1;
            text-align: center;
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--primary-red);
        }

        .cart-body {
            padding: 0 28px 24px;
            max-height: 420px;
            overflow-y: auto;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 22px 0;
            border-bottom: 1px solid #eee;
            gap: 18px;
        }

        .mini-img {
            width: 90px;
            height: 90px;
            background: #f4dfe3;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mini-img img {
            width: 90%;
            height: 90%;
            /* opacity: .5; */
        }

        .cart-item h6 {
            font-weight: 600;
            color: var(--primary-red);
            margin-bottom: 4px;
        }

        .price-each {
            color: var(--primary-red);
            font-weight: 600;
            font-size: .9rem;
        }

        .remove-btn {
            color: var(--primary-red);
            cursor: pointer;
        }

        .cart-qty-btn {
            border: 2px solid #d3d3d3;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            color: #9e9e9e;
            background: #fff;
        }

        .cart-qty-btn.active {
            border-color: var(--primary-red);
            color: #fff;
            background: var(--primary-red);
        }

        .card-3d {
            perspective: 1000px;
        }

        .card-inner {
            position: relative;
            width: 100%;
            height: 140px;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            cursor: pointer;
        }

        .card-inner.is-flipped {
            transform: rotateY(180deg);
        }

        .card-front,
        .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            top: 0;
            left: 0;
            border-radius: 10px;
            overflow: hidden;
        }

        .card-front {
            z-index: 2;
            transform: rotateY(0deg);
        }

        .card-back {
            transform: rotateY(180deg);
            background-color: #fff;
            position: relative;
        }

        .card-back model-viewer {
            width: 100%;
            height: 100%;
            background-color: #e0e0e0;
            border-radius: 10px;
            z-index: 1;
            /* di bawah tombol */
            position: relative;
        }

        .btn-flip-back {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 5;
            /* pastikan lebih besar dari model-viewer */
            background: rgba(255, 255, 255, 0.85);
            border: none;
            padding: 6px 8px;
            border-radius: 6px;
            cursor: pointer;
        }

        .note-input {
            border: 0;
            border-bottom: 1px solid #ddd;
            width: 100%;
            outline: none;
            font-size: .9rem;
            padding: 6px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-weight: 600;
        }

        .checkout-btn {
            background: var(--primary-red);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 34px;
            padding: 16px 0;
            font-size: 1.1rem;
            margin-top: 18px;
            width: 100%;
        }

        .floating-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .floating-button {
            background-color: orange;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            position: relative;
        }

        .floating-button::after {
            content: var(--count, "0");
            position: absolute;
            bottom: 2px;
            right: 2px;
            background-color: red;
            width: 18px;
            height: 18px;
            font-size: 12px;
            border-radius: 50%;
            text-align: center;
        }

        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-content {
            background: white;
            padding: 20px 40px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            margin: 10px auto;
            border: 4px solid #ccc;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="dim-when-modal">
    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
        <div class="container-fluid px-4">
            <div class="d-flex gap-4 align-items-center flex-grow-1">
                <a class="nav-link" href="#">Daftar Menu</a>
            </div>
            <a class="navbar-brand mx-auto" href="#">Ashbab Coffe
                <!-- <img src="{{ asset('storage/logo/logo.png') }}" alt="Ashbab Coffee" height="40"> -->
            </a>
        </div>
    </nav>

    <!-- ===== LOCATION ===== -->
    <div class="container my-4 text-center" id="location" data-meja-id="{{ $meja->nomorMeja }}">
        <span class="location-pill">
            <i class="fa-solid fa-map-pin"></i>
            Ashbab Coffe • Meja {{ $meja->nomorMeja }}
        </span>
    </div>


    <!-- ===== CATEGORY PILLS ===== -->
    <div class="container">
        <ul class="nav nav-pills justify-content-center nav-category mb-4 gap-3 flex-nowrap overflow-auto">
            <li class="nav-item">
                <a class="nav-link {{ $kategori == 'minuman' ? 'active' : '' }}"
                    href="{{ route('order.meja', [$meja->nomorMeja, 'minuman']) }}">Minuman</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $kategori == 'makanan' ? 'active' : '' }}"
                    href="{{ route('order.meja', [$meja->nomorMeja, 'makanan']) }}">Makanan</a>
            </li>
        </ul>
    </div>

    <div class="container">
        <div class="row g-4">
            @foreach($items as $menu)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card card-menu text-center h-100 p-3 card-3d">
                    <div class="card-inner" onclick="flipCard(this)">
                        <!-- Sisi Depan -->
                        <div class="card-front">
                            <img src="{{ $menu->foto ?? 'https://dummyimage.com/200x200/f4dfe3/ffffff&text=🍹' }}"
                                class="card-img-top"
                                alt="{{ $menu->nama }}" />
                        </div>

                        <!-- Sisi Belakang -->
                        <div class="card-back">
                            <button class="btn-flip-back"
                                onclick="event.stopPropagation(); flipCard(this.closest('.card-inner'));"
                                aria-label="kembali">⟲</button>

                            <model-viewer src="{{ $menu->model3D }}"
                                alt="{{ $menu->nama }} 3D"
                                camera-controls
                                auto-rotate
                                rotation-per-second="20deg"
                                disable-zoom>
                            </model-viewer>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between">
                        <h6 class="card-title text-danger fw-bold mb-1">{{ $menu->nama }}</h6>
                        <p class="mb-3">{{ number_format($menu->harga, 0, ',', '.') }}</p>
                        <button class="btn btn-tambah w-100 open-order"
                            data-name="{{ $menu->nama }}"
                            data-price="{{ $menu->harga }}"
                            data-kategori="{{ $kategori }}"
                            data-foto="{{ $menu->foto }}">
                            <i class=""></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ===== ORDER MODAL ===== -->
    <div class="modal fade order-modal" id="orderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-0 flex-column">
                    <img src="https://dummyimage.com/400x250/f4dfe3/ffffff&text=%F0%9F%8D%B5" class="order-foto order-img-placeholder" alt="img" />
                    <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body text-center pb-4">
                    <h4 class="order-title mb-4">Item</h4>
                    <p class="text-muted mb-1" style="letter-spacing:1px;font-size:.8rem;">Jumlah</p>
                    <div class="d-flex justify-content-center align-items-center gap-4 mb-4">
                        <button class="qty-btn minus" disabled><i class="fa-solid fa-minus"></i></button>
                        <span id="qtyDisplay" style="font-size:1.25rem;font-weight:600;">1</span>
                        <button class="qty-btn plus active"><i class="fa-solid fa-plus"></i></button>
                    </div>
                    <button class="buy-btn w-100" id="buyBtn">Rp <span id="buyTotal">0</span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CART MODAL ===== -->
    <div class="modal fade cart-modal" id="cartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="back-btn" data-bs-dismiss="modal"><i class="fa-solid fa-arrow-left"></i></button>
                    <h5 class="cart-title">Keranjang</h5>
                    <span id="itemCount" class="fw-semibold text-muted" style="font-size:.9rem;">0 Barang</span>
                </div>
                <div class="cart-body" id="cartItems"><!-- items injected here --></div>
                <div class="px-4 py-3" style="border-top:1px solid #eee;">
                    <div class="summary-row"><span>Subtotal</span><span id="subtotalDisplay">Rp 0</span></div>
                    <div class="summary-row"><span>Total</span><span id="totalDisplay">Rp 0</span></div>

                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label fw-bold">Metode Pembayaran</label>
                        <select class="form-select" id="paymentMethod">
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div id="bankContainer" class="mb-3" style="display:none;">
                        <label for="bankSelect" class="form-label fw-bold">Pilih Bank / E-Wallet</label>
                        <select class="form-select" id="bankSelect">
                            <option value="">-- Pilih Bank --</option>
                            @foreach($banks as $bank)
                            <option value="{{ $bank->gambar_qris }}">{{ $bank->nama_bank }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tampilan QR Code -->
                    <div id="qrisContainer" class="text-center mt-3" style="display:none;">
                        <p class="fw-semibold text-danger">Scan QR Code berikut:</p>
                        <img id="qrisImage" src="" alt="QRIS" style="width:180px; height:180px; border-radius:10px;">
                    </div>

                    <input type="hidden" name="payment_method" id="paymentMethodInput">

                    <button class="checkout-btn" id="checkoutBtn">Pesan</button>

                    <!-- Loading overlay -->
                    <div id="loadingDialog" class="loading-overlay" style="display: none;">
                        <div class="loading-content">
                            <div class="spinner"></div>
                            <p>Memproses pesanan...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pesanan Saya -->
    <div class="modal fade" id="ordersModal" tabindex="-1" aria-labelledby="ordersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pesananSayaModalLabel">Pesanan Saya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- List pesanan -->
                    <ul class="list-group mb-3" id="listPesanan">
                        <!-- Data dari JS -->
                    </ul>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Buttons -->
    <div class="floating-buttons">
        <div class="floating-button" id="floatingCartBtn"> <i class="fa-solid fa-shopping-cart"></i>
        </div>

        <div class="floating-button" id="floatingOrdersBtn">
            <i class="fa-solid fa-list"></i>
        </div>
    </div>

    <!-- ===== BOOTSTRAP & CORE JS ===== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script>
        const orderModalEl = document.getElementById('orderModal');
        const cartModalEl = document.getElementById('cartModal');
        const orderModal = new bootstrap.Modal(orderModalEl);
        const cartModal = new bootstrap.Modal(cartModalEl);

        // Elements in order modal
        const qtyDisp = document.getElementById('qtyDisplay');
        const minusBtn = orderModalEl.querySelector('.minus');
        const plusBtn = orderModalEl.querySelector('.plus');
        const buyTotal = document.getElementById('buyTotal');
        const buyBtn = document.getElementById('buyBtn');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const loadingDialog = document.getElementById('loadingDialog');

        const locationEl = document.getElementById('location');
        const mejaId = locationEl.dataset.mejaId;

        let pesananSayaCache = []; // simpan hasil request terakhir


        let currentItem = {
            name: '',
            price: 0,
            qty: 1,
            kategori: '',
            foto: ''
        };
        // let cart = [];
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        const paymentMethod = document.getElementById('paymentMethod');
        const bankContainer = document.getElementById('bankContainer');
        const bankSelect = document.getElementById('bankSelect');
        const qrisContainer = document.getElementById('qrisContainer');
        const qrisImage = document.getElementById('qrisImage');
        const paymentMethodInput = document.getElementById('paymentMethodInput');

        paymentMethod.addEventListener('change', function() {
            if (this.value === 'qris') {
                bankContainer.style.display = 'block';
                qrisContainer.style.display = 'none'; // sembunyikan dulu QR
                paymentMethodInput.value = 'qris';
            } else {
                bankContainer.style.display = 'none';
                qrisContainer.style.display = 'none';
                paymentMethodInput.value = this.value;
            }
        });

        // Saat pilih bank / e-wallet
        bankSelect.addEventListener('change', function() {
            const selectedQR = this.value;
            if (selectedQR) {
                qrisImage.src = selectedQR;
                qrisContainer.style.display = 'block';
                paymentMethodInput.value = 'qris';
            } else {
                qrisContainer.style.display = 'none';
                paymentMethodInput.value = 'cash';
            }
        });

        function updateFloatingCartCount() {
            const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
            const floatingBtn = document.querySelector('#floatingCartBtn');
            floatingBtn.style.setProperty('--count', `"${totalQty}"`);
        }

        function updateFloatingOrderCount(count) {
            const floatingBtn = document.querySelector('#floatingOrdersBtn');
            floatingBtn.style.setProperty('--count', `"${count}"`);
        }

        // open order modal
        document.querySelectorAll('.open-order').forEach(btn => {
            btn.addEventListener('click', () => {
                currentItem.name = btn.dataset.name;
                currentItem.price = parseInt(btn.dataset.price, 10);
                currentItem.kategori = btn.dataset.kategori;
                currentItem.qty = 1;
                currentItem.foto = btn.dataset.foto;
                orderModalEl.querySelector('.order-title').textContent = currentItem.name;
                orderModalEl.querySelector('.order-foto').src = currentItem.foto;
                qtyDisp.textContent = 1;
                minusBtn.disabled = true;
                buyTotal.textContent = currentItem.price.toLocaleString('id-ID');
                orderModal.show();
            });
        });

        // qty controls
        function updateQtyDisplay() {
            qtyDisp.textContent = currentItem.qty;
            buyTotal.textContent = (currentItem.price * currentItem.qty).toLocaleString('id-ID');
            minusBtn.disabled = currentItem.qty === 1;
        }

        minusBtn.addEventListener('click', () => {
            if (currentItem.qty > 1) {
                currentItem.qty--;
                updateQtyDisplay();
            }
        });

        plusBtn.addEventListener('click', () => {
            currentItem.qty++;
            updateQtyDisplay();
        });

        // add to cart + open cart modal
        buyBtn.addEventListener('click', () => {
            // check existing
            const existing = cart.find(i => i.name === currentItem.name);
            if (existing) {
                existing.qty += currentItem.qty;
            } else {
                cart.push({
                    ...currentItem
                });
            }
            orderModal.hide();
            localStorage.setItem('cart', JSON.stringify(cart));
            updateFloatingCartCount();
        });

        document.getElementById('floatingCartBtn').addEventListener('click', () => {
            cart = JSON.parse(localStorage.getItem('cart')) || [];
            renderCart(); // render semua item di cart
            cartModal.show();
        });

        checkoutBtn.addEventListener('click', () => {
            if (cart.length === 0) {
                alert("Keranjang masih kosong!");
                return;
            }

            // ambil notes per item
            const cartItemsEl = document.querySelectorAll('#cartItems .cart-item');
            cart.forEach((item, idx) => {
                const noteInput = cartItemsEl[idx].querySelector('.note-input');
                item.note = noteInput.value || null;
            });

            // tampilkan dialog loading
            loadingDialog.style.display = 'flex';
            checkoutBtn.disabled = true; // cegah klik berulang

            fetch(`/order/checkout/${mejaId}`, { // ganti 1 = ID meja (atau bisa dynamic dari dataset/meja login)
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        cart,
                        "payment": paymentMethodInput.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Pesanan berhasil dikirim! Kode order: " + data.order_key);
                        cart = []; // kosongkan keranjang
                        renderCart();
                        cartModal.hide();
                        loadPesananSaya();
                    } else {
                        alert("Gagal menyimpan pesanan!");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Terjadi error saat checkout!");
                })
                .finally(() => {
                    loadingDialog.style.display = 'none';
                    checkoutBtn.disabled = false;
                })

        })

        // render cart
        function renderCart() {
            const cartItemsEl = document.getElementById('cartItems');
            cartItemsEl.innerHTML = '';
            let subtotal = 0;
            cart.forEach((item, index) => {
                subtotal += item.price * item.qty;
                const row = document.createElement('div');
                row.className = 'cart-item';
                row.innerHTML = `
          <div class="mini-img"><img src=${item.foto}></div>
          <div class="flex-grow-1">
            <h6>${item.name}</h6>
            <div class="d-flex align-items-center gap-2 mb-1">
              <button class="cart-qty-btn minus${index}"><i class='fa-solid fa-minus'></i></button>
              <span id='cartQty${index}' style='min-width:18px;text-align:center;'>${item.qty}</span>
              <button class="cart-qty-btn active plus${index}"><i class='fa-solid fa-plus'></i></button>
              <span class="price-each ms-2">@Rp ${item.price.toLocaleString('id-ID')}</span>
              <span class="remove-btn ms-auto" id='remove${index}'><i class='fa-solid fa-trash'></i></span>
            </div>
            <input type='text' class='note-input' placeholder='Tambah catatan (optional)'>
          </div>
          <div class='fw-semibold text-end' style='min-width:90px;'>Rp ${(item.price*item.qty).toLocaleString('id-ID')}</div>`;
                cartItemsEl.appendChild(row);

                // qty btns
                row.querySelector('.minus' + index).addEventListener('click', () => {
                    if (item.qty > 1) {
                        item.qty--;
                        renderCart();
                    }
                });
                row.querySelector('.plus' + index).addEventListener('click', () => {
                    item.qty++;
                    renderCart();
                });
                row.querySelector('#remove' + index).addEventListener('click', () => {
                    cart.splice(index, 1);
                    renderCart();
                });
            });

            document.getElementById('itemCount').textContent = `${cart.length} BARANG`;
            document.getElementById('subtotalDisplay').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('totalDisplay').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;

            localStorage.setItem('cart', JSON.stringify(cart));
            updateFloatingCartCount(); // update badge
        }

        function loadPesananSaya() {
            fetch(`/order/me/${mejaId}`)
                .then(res => res.json())
                .then(data => {
                    let html = "";

                    if (data.length === 0) {
                        html = `
                    <div class="p-3 border rounded bg-light text-center">
                        <p><strong>Belum ada pesanan</strong></p>
                    </div>
                `;
                    } else {
                        data.forEach(order => {

                            let itemsHtml = "";
                            let daftarPesanan = "";
                            let totalItem = 0;
                            let totalHarga = 0;

                            order.pesanan.forEach(item => {

                                // tampil list di atas (badge jumlah)
                                itemsHtml += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                ${item.nama_menu}
                                <span class="badge bg-primary rounded-pill">${item.jumlah}</span>
                            </li>
                        `;

                                let hargaSatuan = parseInt(item.harga);
                                let subtotal = hargaSatuan * parseInt(item.jumlah);

                                // list per item yang baru
                                daftarPesanan += `
                            <p class="mb-1">• ${item.nama_menu} — ${item.jumlah} × Rp ${hargaSatuan.toLocaleString('id-ID')} = <strong>Rp ${subtotal.toLocaleString('id-ID')}</strong></p>
                        `;

                                totalItem += parseInt(item.jumlah);
                                totalHarga += subtotal;
                            });

                            // status badge
                            let statusClass = "bg-secondary";
                            if (order.status === "pending") statusClass = "bg-warning text-dark";
                            else if (order.status === "cooking") statusClass = "bg-info text-dark";
                            else if (order.status === "done") statusClass = "bg-success";

                            html += `
                        <div class="mb-3 border rounded p-2">

                            <ul class="list-group mb-2">
                                ${itemsHtml}
                            </ul>

                            <div class="p-2 border-top bg-light">
                                <p><strong>No Antrian:</strong> ${order.antrian ?? "Belum ada"}</p>
                                <p><strong>Status:</strong> <span class="badge ${statusClass}">${order.status}</span></p>
                                <p><strong>Jumlah antrian sebelum Anda:</strong> ${order.sebelum_saya}</p>

                                <hr>

                                <p><strong>Pesanan Anda:</strong></p>
                                ${daftarPesanan}

                                <p class="mt-2"><strong>Total Pesanan:</strong> ${totalItem}</p>

                                <p class="mt-1"><strong>Total Harga:</strong> <span style="font-size: 1.1em;">Rp ${totalHarga.toLocaleString('id-ID')}</span></p>
                            </div>

                        </div>
                    `;
                        });
                    }

                    document.getElementById("listPesanan").innerHTML = html;
                    updateFloatingOrderCount(data.length)
                });
        }

        document.getElementById("floatingOrdersBtn").addEventListener("click", function() {
            var modal = new bootstrap.Modal(document.getElementById('ordersModal'));
            modal.show();

            loadPesananSaya();

        });

        function flipCard(element) {
            console.log("flipCard dipanggil untuk:", element);
            element.classList.toggle('is-flipped');
        }

        document.querySelectorAll('model-viewer').forEach(mv => {
            mv.addEventListener('mousedown', e => {
                // kalau klik bukan tombol flip
                if (!e.target.closest('.btn-flip-back')) e.stopPropagation();
            });
            mv.addEventListener('mouseup', e => {
                if (!e.target.closest('.btn-flip-back')) e.stopPropagation();
            });
            mv.addEventListener('click', e => {
                if (!e.target.closest('.btn-flip-back')) e.stopPropagation();
            });
            mv.addEventListener('touchstart', e => {
                if (!e.target.closest('.btn-flip-back')) e.stopPropagation();
            });
            mv.addEventListener('touchend', e => {
                if (!e.target.closest('.btn-flip-back')) e.stopPropagation();
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            loadPesananSaya();
            updateFloatingCartCount();
        });
    </script>
</body>

</html>