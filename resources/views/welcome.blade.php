<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StepUp Supply | Sneaker & Streetwear</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --black: #000000;
            --white: #ffffff;
            --gray-light: #f5f5f5;
            --gray-medium: #e0e0e0;
            --gray-dark: #666666;
            --accent: #e94560;
            --green: #28a745;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--white); color: var(--black); overflow-x: hidden; }
        .hidden { display: none !important; }

        /* TOAST NOTIFICATION MODERN */
        .toast-container {
            position: fixed; top: 25px; right: 25px; z-index: 9999;
            display: flex; flex-direction: column; gap: 12px; pointer-events: none;
        }
        .toast {
            pointer-events: auto; min-width: 320px; max-width: 420px; background: var(--white);
            color: var(--black); padding: 16px 20px; border-radius: 12px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border: 1px solid var(--gray-medium); display: flex; align-items: center; gap: 14px;
            transform: translateX(120%); opacity: 0; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative; overflow: hidden;
        }
        .toast.show { transform: translateX(0); opacity: 1; }
        .toast-icon { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: bold; flex-shrink: 0; }
        .toast-content { flex: 1; }
        .toast-title { font-weight: 700; font-size: 0.95rem; color: var(--black); margin-bottom: 2px; }
        .toast-message { font-size: 0.85rem; color: var(--gray-dark); line-height: 1.3; }
        .toast-close { background: none; border: none; font-size: 1.1rem; color: var(--gray-dark); cursor: pointer; padding: 2px; line-height: 1; }
        .toast-progress { position: absolute; bottom: 0; left: 0; height: 3px; width: 100%; animation: toastProgress 4s linear forwards; }
        @keyframes toastProgress { from { width: 100%; } to { width: 0%; } }

        /* NAVBAR HEADER */
        .navbar {
            display: flex; justify-content: space-between; align-items: center; padding: 15px 40px;
            background-color: var(--white); position: sticky; top: 0; z-index: 100; border-bottom: 1px solid var(--gray-medium);
        }
        .nav-left, .nav-right { display: flex; align-items: center; gap: 20px; flex: 1; }
        .nav-right { justify-content: flex-end; }
        .nav-center { flex: 1; text-align: center; font-weight: 800; font-size: 1.8rem; letter-spacing: -1px; text-transform: uppercase; }
        .nav-icon { cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.9rem; color: var(--black); transition: color 0.2s; text-decoration: none; }
        .nav-icon:hover { color: var(--accent); }
        .search-bar { background: var(--gray-light); border: none; padding: 10px 15px; border-radius: 20px; width: 250px; font-family: 'Inter'; outline: none; }
        .cart-badge { background: var(--accent); color: var(--white); font-size: 0.75rem; font-weight: 700; padding: 2px 7px; border-radius: 50%; margin-left: 2px; }
        .btn-logout { background: none; border: none; font-size: 0.8rem; color: var(--gray-dark); cursor: pointer; text-decoration: underline; padding: 0; }
        .btn-logout:hover { color: var(--accent); }

        /* OVERLAY LAYAR */
        .ui-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px); z-index: 999; opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .ui-overlay.active { opacity: 1; visibility: visible; }

        /* 1. SIDE MENU DRAWER (KIRI) */
        .side-menu {
            position: fixed; top: 0; left: -350px; width: 350px; height: 100vh; background-color: var(--white);
            z-index: 1000; display: flex; flex-direction: column; transition: left 0.3s ease; box-shadow: 5px 0 15px rgba(0,0,0,0.1); overflow-y: auto;
        }
        .side-menu.open { left: 0; }
        .side-menu-header { padding: 30px; display: flex; align-items: center; cursor: pointer; font-weight: 600; font-size: 1.1rem; }
        .side-menu-header span { font-size: 1.4rem; margin-right: 15px; font-weight: 300; }
        .side-menu-items { display: flex; flex-direction: column; }
        .side-menu-parent {
            padding: 15px 30px; display: flex; justify-content: space-between; align-items: center;
            cursor: pointer; font-size: 0.95rem; font-weight: 600; transition: background 0.2s; user-select: none;
        }
        .side-menu-parent:hover { background-color: var(--gray-light); }
        .side-menu-parent .arrow { color: var(--gray-dark); font-size: 0.8rem; transition: transform 0.3s ease; }
        .side-menu-parent.active { background-color: var(--gray-light); }
        .side-menu-parent.active .arrow { transform: rotate(90deg); color: var(--black); }
        .sub-menu { max-height: 0; overflow: hidden; transition: max-height 0.3s cubic-bezier(0, 1, 0, 1); background-color: #fafafa; }
        .sub-menu.open { max-height: 350px; transition: max-height 0.3s ease-in-out; border-bottom: 1px solid var(--gray-medium); }
        .sub-menu-item { padding: 12px 30px 12px 45px; font-size: 0.85rem; color: var(--gray-dark); cursor: pointer; transition: color 0.2s, background 0.2s; display: block; }
        .sub-menu-item:hover { color: var(--black); background-color: var(--gray-medium); font-weight: 600; }
        .side-menu-divider { height: 1px; background-color: var(--gray-medium); margin: 15px 30px; }
        .side-menu-footer { margin-top: auto; padding: 30px; display: flex; justify-content: space-between; align-items: center; }
        .side-menu-login { font-weight: 600; cursor: pointer; font-size: 0.95rem; }
        .side-menu-brand { font-weight: 800; font-style: italic; letter-spacing: -0.5px; font-size: 1.1rem; }

        /* 2. CART DRAWER (KANAN) */
        .cart-drawer {
            position: fixed; top: 0; right: -400px; width: 400px; height: 100vh; background-color: var(--white);
            z-index: 1000; display: flex; flex-direction: column; transition: right 0.3s ease; box-shadow: -5px 0 15px rgba(0,0,0,0.1);
        }
        .cart-drawer.open { right: 0; }
        .cart-header { padding: 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--gray-medium); }
        .cart-header h3 { font-size: 1.2rem; font-weight: 700; }
        .cart-header .close-btn { cursor: pointer; font-size: 1.5rem; font-weight: 300; }
        .cart-body { flex: 1; display: flex; flex-direction: column; padding: 20px 30px; overflow-y: auto; color: var(--gray-dark); }
        .cart-item { padding: 15px 0; border-bottom: 1px solid var(--gray-medium); display: flex; justify-content: space-between; align-items: center; }
        .cart-item-title { font-weight: 700; color: var(--black); font-size: 0.95rem; margin-bottom: 3px; }
        .cart-item-detail { font-size: 0.85rem; color: var(--gray-dark); }
        .cart-empty { text-align: center; margin: auto; }
        .cart-empty h4 { color: var(--black); font-size: 1.2rem; margin-bottom: 10px; }
        .cart-footer { padding: 25px 30px; border-top: 1px solid var(--gray-medium); background: var(--white); display: flex; flex-direction: column; gap: 10px; }
        .cart-total-box { display: flex; justify-content: space-between; align-items: center; font-weight: 700; font-size: 1.1rem; margin-bottom: 10px; }

        /* MODALS */
        .modal-box {
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.9); width: 90%; max-width: 450px;
            background: var(--white); z-index: 1001; padding: 35px; border-radius: 12px; box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            opacity: 0; visibility: hidden; transition: all 0.3s ease; max-height: 90vh; overflow-y: auto;
        }
        .modal-box.open { transform: translate(-50%, -50%) scale(1); opacity: 1; visibility: visible; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--gray-light); padding-bottom: 10px; }
        .modal-header h2 { font-weight: 800; font-size: 1.4rem; text-transform: uppercase; }
        .modal-header .close-btn { cursor: pointer; font-size: 1.5rem; color: var(--gray-dark); }
        .auth-switch-text { text-align: center; margin-top: 20px; font-size: 0.85rem; color: var(--gray-dark); }
        .auth-switch-text a { color: var(--black); font-weight: 700; text-decoration: underline; cursor: pointer; }

        /* BUTTONS & FORM */
        .btn-full { background: var(--black); color: var(--white); width: 100%; padding: 12px; border: none; border-radius: 30px; font-weight: 600; cursor: pointer; transition: 0.3s; text-transform: uppercase; font-size: 0.9rem; text-align: center; }
        .btn-full:hover { background: var(--accent); }
        .btn-secondary { background: var(--white); color: var(--black); border: 1px solid var(--black); width: 100%; padding: 12px; border-radius: 30px; font-weight: 600; cursor: pointer; transition: 0.3s; text-transform: uppercase; font-size: 0.9rem; text-align: center; }
        .btn-secondary:hover { background: var(--gray-light); }
        .btn-clear { background: #dc3545; color: var(--white); width: 100%; padding: 10px; border: none; border-radius: 30px; font-weight: 600; cursor: pointer; text-transform: uppercase; font-size: 0.8rem; }
        .btn-clear:hover { background: #bb2d3b; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 0.85rem; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid var(--gray-medium); border-radius: 6px; font-size: 0.9rem; font-family: 'Inter'; }

        /* KODE PEMBAYARAN & QRIS CARDS */
        .pay-card { background: #f8f9fa; border: 2px dashed var(--gray-medium); padding: 20px; border-radius: 12px; text-align: center; margin: 15px 0; }
        .pay-number { font-size: 1.6rem; font-weight: 800; letter-spacing: 2px; color: var(--black); margin: 8px 0; }
        .btn-copy { background: var(--gray-light); border: 1px solid var(--gray-medium); padding: 6px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-copy:hover { background: var(--black); color: var(--white); }
        .qris-container { text-align: center; background: #fff; border: 1px solid var(--gray-medium); padding: 20px; border-radius: 12px; margin: 15px 0; }
        .qris-img { width: 180px; height: 180px; margin: 10px auto; border-radius: 8px; border: 1px solid var(--gray-medium); display: block; }

        /* HERO SECTION */
        .hero { height: 80vh; display: flex; align-items: center; padding: 0 5%; color: var(--white); }
        .hero-content { max-width: 600px; }
        .hero h1 { font-size: 4rem; font-weight: 800; text-transform: uppercase; line-height: 1.1; margin-bottom: 15px; letter-spacing: -2px; }
        .hero p { font-size: 1.2rem; margin-bottom: 30px; font-weight: 400; }
        .btn-shop { background: var(--white); color: var(--black); padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: 600; font-size: 1rem; transition: 0.3s; display: inline-block; cursor: pointer; }
        .btn-shop:hover { background: var(--gray-medium); }

        /* SECTIONS & PRODUCTS */
        .section-container { padding: 60px 40px; }
        .section-title-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        .section-title { font-size: 1.5rem; font-weight: 700; }
        .btn-reset-filter { background: var(--gray-light); border: 1px solid var(--gray-medium); padding: 6px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-reset-filter:hover { background: var(--black); color: var(--white); }

        .scroll-wrapper { display: flex; gap: 20px; overflow-x: auto; padding-bottom: 20px; scroll-snap-type: x mandatory; scrollbar-width: none; }
        .scroll-wrapper::-webkit-scrollbar { display: none; }
        
        .category-card { min-width: 280px; height: 350px; background: var(--gray-light); border-radius: 8px; position: relative; scroll-snap-align: start; cursor: pointer; overflow: hidden; }
        .category-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .category-card:hover img { transform: scale(1.05); }
        .category-title { position: absolute; top: 20px; left: 20px; color: var(--white); font-weight: 700; font-size: 1.2rem; text-shadow: 0 2px 5px rgba(0,0,0,0.5); z-index: 2; }

        .promo-split { display: flex; gap: 20px; padding: 0 40px 60px 40px; }
        .promo-card { flex: 1; height: 400px; display: flex; flex-direction: column; justify-content: flex-end; padding: 40px; color: var(--white); border-radius: 8px; cursor: pointer; overflow: hidden; }
        .promo-card h2 { font-size: 2rem; font-weight: 800; text-transform: uppercase; margin-bottom: 5px; }

        /* PRODUCT CARDS */
        .product-card { min-width: 300px; scroll-snap-align: start; cursor: pointer; transition: transform 0.3s; }
        .product-card:hover { transform: translateY(-5px); }
        .product-img-box { background: var(--gray-light); height: 300px; border-radius: 8px; position: relative; margin-bottom: 15px; overflow: hidden; }
        .product-img-box img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .product-card:hover .product-img-box img { transform: scale(1.08); }
        .product-img-box .heart { position: absolute; top: 15px; right: 15px; font-size: 1.2rem; color: var(--black); background: var(--white); width: 35px; height: 35px; border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 2; }
        .product-info h4 { font-size: 1rem; font-weight: 700; margin-bottom: 5px; }
        .product-info p { color: var(--gray-dark); font-size: 0.9rem; margin-bottom: 5px; }
        .product-price { font-weight: 600; }
        .no-product-msg { width: 100%; padding: 40px; text-align: center; background: var(--gray-light); border-radius: 8px; color: var(--gray-dark); }

        /* FOOTER */
        .top-categories { background: var(--white); padding: 60px 40px; border-top: 1px solid var(--gray-medium); display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; }
        .cat-col h4 { font-weight: 700; margin-bottom: 20px; }
        .cat-col ul { list-style: none; }
        .cat-col ul li { margin-bottom: 10px; color: var(--gray-dark); font-size: 0.9rem; cursor: pointer; }
        footer { background: var(--black); color: var(--white); padding: 40px 40px 100px 40px; text-align: center; }
        footer p { opacity: 0.7; margin-bottom: 10px; font-size: 0.9rem; }
        footer strong { color: var(--white); font-weight: 700; }

        /* FLOATING QUICK ORDER WIDGET */
        .floating-widget { position: fixed; bottom: 30px; left: 30px; z-index: 100; display: flex; flex-direction: column; align-items: flex-start; }
        .floating-btn { background: var(--black); color: var(--white); border: 1px solid var(--gray-medium); border-radius: 30px; padding: 12px 25px; font-size: 0.9rem; font-weight: 600; cursor: pointer; box-shadow: 0 10px 20px rgba(0,0,0,0.2); transition: all 0.3s; display: flex; align-items: center; gap: 10px; text-transform: uppercase; }
        .floating-btn:hover { background: var(--accent); border-color: var(--accent); transform: translateY(-3px); }
        .floating-form-container { background: var(--white); width: 320px; padding: 20px; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.15); position: absolute; bottom: 60px; left: 0; transform: scale(0); transform-origin: bottom left; opacity: 0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); visibility: hidden; color: var(--black); border: 1px solid var(--gray-medium); }
        .floating-form-container.active { transform: scale(1); opacity: 1; visibility: visible; }
        .floating-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid var(--gray-light); padding-bottom: 10px; }
        .floating-header h3 { font-size: 1rem; font-weight: 700; margin: 0; }
        .close-widget { cursor: pointer; font-size: 1.2rem; color: var(--gray-dark); }

        /* INVOICE CARD STYLING */
        .invoice-card { background: var(--gray-light); padding: 15px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 15px; }
        .invoice-card-row { display: flex; justify-content: space-between; margin-bottom: 6px; }
        .invoice-status { background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; text-align: center; display: inline-block; }
    </style>
</head>
<body>

    <div class="toast-container" id="toastContainer"></div>
    <div class="ui-overlay" id="uiOverlay" onclick="closeAllUI()"></div>

    <!-- SIDE MENU -->
    <div class="side-menu" id="sideMenu">
        <div class="side-menu-header" onclick="closeAllUI()"><span>✕</span> Close</div>
        <div class="side-menu-items">
            <div class="side-menu-parent" onclick="toggleSubMenu(this)">New <span class="arrow">&gt;</span></div>
            <div class="sub-menu">
                <div class="sub-menu-item" onclick="filterCategory('New Arrivals')">New Arrivals</div>
                <div class="sub-menu-item" onclick="filterCategory('Trending')">Trending Now</div>
                <div class="sub-menu-item" onclick="filterCategory('Limited Drops')">Limited Drops</div>
            </div>
            <div class="side-menu-parent" onclick="toggleSubMenu(this)">Men <span class="arrow">&gt;</span></div>
            <div class="sub-menu">
                <div class="sub-menu-item" onclick="filterCategory('Men Sneakers')">All Men's Shoes</div>
                <div class="sub-menu-item" onclick="filterCategory('Men Apparel')">Apparel & Streetwear</div>
                <div class="sub-menu-item" onclick="filterCategory('Running')">Running & Training</div>
            </div>
            <div class="side-menu-parent" onclick="toggleSubMenu(this)">Women <span class="arrow">&gt;</span></div>
            <div class="sub-menu">
                <div class="sub-menu-item" onclick="filterCategory('Women Sneakers')">All Women's Shoes</div>
                <div class="sub-menu-item" onclick="filterCategory('Women Lifestyle')">Lifestyle Sneakers</div>
            </div>
            <div class="side-menu-parent" onclick="toggleSubMenu(this)">Sneakers & Lifestyle <span class="arrow">&gt;</span></div>
            <div class="sub-menu">
                <div class="sub-menu-item" onclick="filterCategory('Air Force 1')">Air Force 1 OG</div>
                <div class="sub-menu-item" onclick="filterCategory('Adidas Samba')">Adidas Samba Range</div>
                <div class="sub-menu-item" onclick="filterCategory('New Balance')">New Balance 550</div>
                <div class="sub-menu-item" onclick="filterCategory('Skateboarding')">Skateboarding Shoes</div>
            </div>
            <div class="side-menu-parent" onclick="toggleSubMenu(this)">Running <span class="arrow">&gt;</span></div>
            <div class="sub-menu">
                <div class="sub-menu-item" onclick="filterCategory('Running')">Road Running</div>
            </div>
            <div class="side-menu-parent" onclick="toggleSubMenu(this)">Hiking & Outdoor <span class="arrow">&gt;</span></div>
            <div class="sub-menu">
                <div class="sub-menu-item" onclick="filterCategory('Outdoor')">Hiking & Trail</div>
            </div>
            <div class="side-menu-divider"></div>
            <div class="side-menu-parent" onclick="filterCategory('All')">🔥 View All Products</div>
        </div>
        <div class="side-menu-footer">
            @if(session('user_name'))
                <div class="side-menu-login">👤 Hallo, {{ session('user_name') }}</div>
            @else
                <div class="side-menu-login" onclick="openLogin('login')">👤 Log in</div>
            @endif
            <div class="side-menu-brand">S/PLUS</div>
        </div>
    </div>

    <!-- CART DRAWER -->
    @php
        $total_cart = 0;
        if(session('cart')) {
            foreach(session('cart') as $c) {
                $total_cart += $c['harga_int'] ?? 1500000;
            }
        }
    @endphp
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-header">
            <h3>Your Cart ({{ count(session('cart', [])) }})</h3>
            <span class="close-btn" onclick="closeAllUI()">✕</span>
        </div>
        <div class="cart-body">
            @if(session('cart') && count(session('cart')) > 0)
                @foreach(session('cart') as $item)
                    <div class="cart-item">
                        <div>
                            <div class="cart-item-title">{{ $item['produk'] }}</div>
                            <div class="cart-item-detail">Size: {{ $item['ukuran'] }} | {{ $item['harga'] }}</div>
                            <div class="cart-item-detail" style="font-size: 0.75rem; color: #888;">Pemesan: {{ $item['nama_pelanggan'] }}</div>
                        </div>
                        <span style="font-size: 1.5rem;">👟</span>
                    </div>
                @endforeach
            @else
                <div class="cart-empty">
                    <h4>Your cart is empty</h4>
                    <p>Looks like you haven't added anything to your cart yet.</p>
                </div>
            @endif
        </div>
        <div class="cart-footer">
            @if(session('cart') && count(session('cart')) > 0)
                <div class="cart-total-box">
                    <span>Total Pembayaran:</span>
                    <span>Rp {{ number_format($total_cart, 0, ',', '.') }}</span>
                </div>
                <button class="btn-full" onclick="openCheckout()">Proceed to Checkout</button>
                <form method="POST" action="/cart/clear">
                    @csrf
                    <button type="submit" class="btn-clear">Kosongkan Cart</button>
                </form>
            @endif
            <button class="btn-secondary" onclick="closeAllUI()">Continue Shopping</button>
        </div>
    </div>

    <!-- LOGIN & REGISTER MODAL -->
    <div class="modal-box" id="loginModal">
        <div class="modal-header">
            <h2 id="modalTitle">Sign In</h2>
            <span class="close-btn" onclick="closeAllUI()">✕</span>
        </div>
        <form id="modalLoginForm" method="POST" action="/login">
            @csrf
            <div class="form-group"><label>Nama / Username (Opsional)</label><input type="text" name="name" placeholder="Masukkan nama Anda"></div>
            <div class="form-group"><label>Email Address</label><input type="email" name="email" placeholder="example@mail.com" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="••••••••" required></div>
            <button type="submit" class="btn-full" style="margin-top: 10px;">Log In</button>
            <div class="auth-switch-text">Don't have an account? <a onclick="switchModalAuth('register')">Create an Account</a></div>
        </form>
        <form id="modalRegisterForm" class="hidden" method="POST" action="/register">
            @csrf
            <div class="form-group"><label>Full Name</label><input type="text" name="name" placeholder="John Doe" required></div>
            <div class="form-group"><label>Email Address</label><input type="email" name="email" placeholder="example@mail.com" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="••••••••" required></div>
            <button type="submit" class="btn-full" style="margin-top: 10px;">Create Account</button>
            <div class="auth-switch-text">Already have an account? <a onclick="switchModalAuth('login')">Sign In</a></div>
        </form>
    </div>

    <!-- CHECKOUT MODAL -->
    <div class="modal-box" id="checkoutModal">
        <div class="modal-header">
            <h2>Checkout & Pembayaran</h2>
            <span class="close-btn" onclick="closeAllUI()">✕</span>
        </div>
        <form method="POST" action="/checkout">
            @csrf
            <div class="invoice-card">
                <div class="invoice-card-row"><strong>Total Item:</strong><span>{{ count(session('cart', [])) }} Barang</span></div>
                <div class="invoice-card-row"><strong>Total Tagihan:</strong><span style="color: var(--accent); font-weight: 800;">Rp {{ number_format($total_cart, 0, ',', '.') }}</span></div>
            </div>
            <div class="form-group"><label>Nama Lengkap Penerima</label><input type="text" name="nama" value="{{ session('user_name') }}" placeholder="Contoh: Muhammad Ilham" required></div>
            <div class="form-group"><label>Nomor HP / WhatsApp</label><input type="tel" name="no_hp" placeholder="081234567890" required></div>
            <div class="form-group"><label>Alamat Lengkap Pengiriman</label><textarea name="alamat" rows="2" placeholder="Jl. Raya Pamulang No. 1, Tangerang Selatan" required></textarea></div>
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode_pembayaran" required>
                    <option value="QRIS / GoPay / OVO / ShopeePay">QRIS (Scan Barcode Instan)</option>
                    <option value="Transfer Bank BCA">Virtual Account BCA</option>
                    <option value="Transfer Bank Mandiri">Virtual Account Mandiri</option>
                    <option value="Virtual Account BRI/BNI">Virtual Account BRI / BNI</option>
                    <option value="COD (Cash on Delivery)">Cash on Delivery (COD)</option>
                </select>
            </div>
            <button type="submit" class="btn-full" style="margin-top: 10px;">Lanjut ke Pembayaran</button>
        </form>
    </div>

    <!-- PEMBAYARAN & QRIS MODAL -->
    @if(session('payment_pending'))
    <div class="modal-box" id="paymentModal">
        <div class="modal-header"><h2>Instruksi Pembayaran</h2><span class="close-btn" onclick="closeAllUI()">✕</span></div>
        <div style="text-align: center; margin-bottom: 10px;">
            <div style="font-size: 0.85rem; color: var(--gray-dark);">Total Tagihan</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: var(--accent);">{{ session('payment_pending.total') }}</div>
            <div style="font-size: 0.75rem; color: #dc3545; font-weight: 600; margin-top: 4px;">⏳ Selesaikan dalam <span>23:59:59</span></div>
        </div>
        @if(session('payment_pending.is_qris'))
            <div class="qris-container">
                <div style="font-weight: 800; font-size: 1.1rem; letter-spacing: 1px;">QRIS STEPUP SUPPLY</div>
                <div style="font-size: 0.75rem; color: var(--gray-dark); margin-top: 2px;">BCA, Mandiri, OVO, GoPay, ShopeePay, Dana, LinkAja</div>
                <img class="qris-img" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ session('payment_pending.kode_pembayaran') }}" alt="QRIS Code">
                <div style="font-size: 0.8rem; color: var(--gray-dark);">Pindai QRIS di atas untuk menyelesaikan pembayaran</div>
            </div>
        @else
            <div class="pay-card">
                <div style="font-size: 0.85rem; color: var(--gray-dark);">{{ session('payment_pending.metode_pembayaran') }}</div>
                <div class="pay-number">{{ session('payment_pending.kode_pembayaran') }}</div>
                <button class="btn-copy" onclick="copyPayCode('{{ session('payment_pending.kode_pembayaran') }}')">📋 Salin Kode Bayar</button>
            </div>
            <div class="invoice-card" style="font-size: 0.8rem;">
                <strong>Cara Pembayaran:</strong>
                <ol style="margin-left: 15px; margin-top: 5px; line-height: 1.4;">
                    <li>Buka aplikasi m-Banking atau e-Wallet Anda.</li>
                    <li>Pilih menu **Transfer / Pembayaran Virtual Account**.</li>
                    <li>Masukkan kode di atas dan konfirmasi nama & nominal.</li>
                </ol>
            </div>
        @endif
        <form method="POST" action="/pay-confirm">
            @csrf
            <button type="submit" class="btn-full" style="background: var(--green);">Saya Sudah Bayar / Konfirmasi</button>
        </form>
    </div>
    @endif

    <!-- INVOICE BERHASIL MODAL -->
    @if(session('invoice'))
    <div class="modal-box" id="invoiceModal">
        <div class="modal-header"><h2>Bukti Pembayaran</h2><span class="close-btn" onclick="closeAllUI()">✕</span></div>
        <div style="text-align: center; margin-bottom: 15px;">
            <div style="font-size: 3rem; margin-bottom: 5px;">🎉</div>
            <div class="invoice-status">BERHASIL / LUNAS</div>
        </div>
        <div class="invoice-card">
            <div class="invoice-card-row"><span>No. Transaksi:</span><strong>{{ session('invoice.kode_transaksi') }}</strong></div>
            <div class="invoice-card-row"><span>Tanggal:</span><span>{{ session('invoice.tanggal') }}</span></div>
            <div class="invoice-card-row"><span>Nama Pembeli:</span><span>{{ session('invoice.nama') }}</span></div>
            <div class="invoice-card-row"><span>Metode Bayar:</span><span>{{ session('invoice.metode_pembayaran') }}</span></div>
        </div>
        <h4 style="font-size: 0.9rem; margin-bottom: 8px;">Detail Barang:</h4>
        <div class="invoice-card" style="margin-bottom: 15px;">
            @foreach(session('invoice.items', []) as $i)
                <div class="invoice-card-row"><span>{{ $i['produk'] }} (Size {{ $i['ukuran'] }})</span><strong>{{ $i['harga'] }}</strong></div>
            @endforeach
            <hr style="margin: 8px 0; border: none; border-top: 1px dashed var(--gray-medium);">
            <div class="invoice-card-row"><strong>Total Lunas:</strong><strong style="color: var(--accent);">{{ session('invoice.total') }}</strong></div>
        </div>
        <button class="btn-full" onclick="closeAllUI()">Selesai & Kembali Berbelanja</button>
    </div>
    @endif

    <!-- NAVBAR HEADER -->
    <nav class="navbar">
        <div class="nav-left">
            <div class="nav-icon" onclick="openMenu()">☰ Menu</div>
            <input type="text" class="search-bar" id="searchInput" oninput="searchProducts()" placeholder="🔍 Search for a product">
        </div>
        <div class="nav-center">STEPUP SUPPLY</div>
        <div class="nav-right">
            @if(session('user_name'))
                <div class="nav-icon" style="cursor: default;">👤 Hallo, {{ session('user_name') }}</div>
                <form method="POST" action="/logout" style="display: inline;">@csrf<button type="submit" class="btn-logout">(Logout)</button></form>
            @else
                <div class="nav-icon" onclick="openLogin('login')">👤 Log In</div>
            @endif
            <div class="nav-icon" onclick="openCart()">
                🛒 Cart
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="cart-badge">{{ count(session('cart')) }}</span>
                @endif
            </div>
        </div>
    </nav>

    <!-- HERO SECTION (IN THE HYPE - INLINE ASSET) -->
    <!-- Ganti 'hero-utama.jpg' dengan nama file Anda di dalam folder public/images/ -->
    <section class="hero" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.7)), url('{{ asset('images/hero-utama.jpg') }}') center/cover;">
        <div class="hero-content">
            <h1>IN THE HYPE</h1>
            <p>Exclusive sneakers and streetwear drops. Wherever you're going. Step up your game.</p>
            <a onclick="document.getElementById('bestsellers').scrollIntoView({ behavior: 'smooth' });" class="btn-shop">Shop Now</a>
        </div>
    </section>

    <!-- SHOP BY CATEGORY -->
    <section class="section-container">
        <h2 class="section-title">Shop by category</h2>
        <div class="scroll-wrapper">
            <div class="category-card" onclick="filterCategory('Running')">
                <div class="category-title">Running</div>
                <img src="{{ asset('images/category-running.jpg') }}" alt="Running" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x500?text=Kategori+Running';">
            </div>
            <div class="category-card" onclick="filterCategory('Sneakers & Lifestyle')">
                <div class="category-title">Sneakers &<br>Lifestyle</div>
                <img src="{{ asset('images/category-lifestyle.jpg') }}" alt="Lifestyle" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x500?text=Sneakers+Lifestyle';">
            </div>
            <div class="category-card" onclick="filterCategory('Streetwear')">
                <div class="category-title">Streetwear</div>
                <img src="{{ asset('images/category-streetwear.jpg') }}" alt="Streetwear" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x500?text=Kategori+Streetwear';">
            </div>
            <div class="category-card" onclick="filterCategory('Skateboarding')">
                <div class="category-title">Skateboarding</div>
                <img src="{{ asset('images/category-skate.jpg') }}" alt="Skateboarding" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x500?text=Kategori+Skateboarding';">
            </div>
            <div class="category-card" onclick="filterCategory('Hiking')">
                <div class="category-title">Hiking</div>
                <img src="{{ asset('images/category-hiking.jpg') }}" alt="Hiking" onerror="this.onerror=null; this.src='https://via.placeholder.com/400x500?text=Kategori+Hiking';">
            </div>
        </div>
    </section>

    <!-- PROMO SPLIT (INLINE ASSET) -->
    <section class="promo-split">
        <div class="promo-card" 
             style="background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.8)), url('{{ asset('images/af1.jpg') }}') center/cover;" 
             onclick="filterCategory('Air Force 1')">
            <h2>AIR FORCE 1 OG</h2>
            <p>A cult classic icon reborn.</p>
            <div class="btn-shop">Shop Now</div>
        </div>
        <div class="promo-card" 
             style="background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.8)), url('{{ asset('images/samba.jpg') }}') center/cover;" 
             onclick="filterCategory('Adidas Samba')">
            <h2>SAMBA RANGE</h2>
            <p>Cushioning and style for the long run.</p>
            <div class="btn-shop">Men / Women</div>
        </div>
    </section>

    <!-- OUR BEST SELLERS -->
    <section class="section-container" id="bestsellers">
        <div class="section-title-box">
            <h2 class="section-title" id="catalogTitle">Our Best Sellers</h2>
            <button class="btn-reset-filter hidden" id="resetFilterBtn" onclick="filterCategory('All')">🔄 Tampilkan Semua Produk</button>
        </div>
        <div class="scroll-wrapper" id="productGrid">
            <!-- Produk di-render oleh JavaScript -->
        </div>
    </section>

    <!-- FOOTER KELOMPOK -->
    <section class="top-categories">
        <div class="cat-col">
            <h4>Top Categories</h4>
            <ul><li onclick="filterCategory('Men Sneakers')">Men's Sneakers</li><li onclick="filterCategory('Women Sneakers')">Women's Sneakers</li><li onclick="filterCategory('Skateboarding')">Skateboarding Shoes</li><li onclick="filterCategory('Limited Drops')">Limited Edition Drops</li></ul>
        </div>
        <div class="cat-col">
            <h4>Apparel & Gear</h4>
            <ul><li onclick="filterCategory('Streetwear')">Men's Streetwear</li><li onclick="filterCategory('Women Apparel')">Women's Streetwear</li></ul>
        </div>
        <div class="cat-col">
            <h4>Help Center</h4>
            <ul><li>Size Guide</li><li>Order Tracking</li><li>Returns & Refunds</li><li>Contact Us</li></ul>
        </div>
        <div class="cat-col">
            <h4>Legal</h4>
            <ul><li>Terms & Conditions</li><li>Privacy Policy</li><li>Cookie Preferences</li></ul>
        </div>
    </section>
    <footer>
        <p><strong>Kelompok 03 - Kelas 05SIFE001</strong></p>
        <p>Anggota: Muhammad Ilham Ardiansyah, [Nama Anggota 2], [Nama Anggota 3], [Nama Anggota 4]</p>
        <p style="margin-top: 20px; font-size: 0.8rem; opacity: 0.4;">© 2026 StepUp Supply. All rights reserved.</p>
    </footer>

    <!-- FLOATING QUICK ORDER WIDGET -->
    <div class="floating-widget">
        <div class="floating-form-container" id="quickOrderForm">
            <div class="floating-header">
                <h3>🛒 Quick Order (Ke DB)</h3>
                <span class="close-widget" onclick="toggleOrderWidget()">✖</span>
            </div>
            <form method="POST" action="/order">
                @csrf
                <div class="form-group"><label>Nama Anda</label><input type="text" name="nama_pelanggan" value="{{ session('user_name') }}" placeholder="Masukkan nama" required></div>
                <div class="form-group">
                    <label>Pilih Produk</label>
                    <select id="orderProduct" name="produk" required>
                        <option value="">-- Pilih item --</option>
                        <option value="Nike Air Force 1 '07 White">Nike Air Force 1 '07 White</option>
                        <option value="Adidas Samba OG Black White">Adidas Samba OG Black White</option>
                        <option value="New Balance 550 White Grey">New Balance 550 White Grey</option>
                        <option value="Vans Old Skool Black/White">Vans Old Skool Black/White</option>
                        <option value="Carhartt WIP Pocket Tee Black">Carhartt WIP Pocket Tee Black</option>
                    </select>
                </div>
                <div class="form-group"><label>Ukuran (EU / Pakaian)</label><input type="text" name="ukuran" placeholder="Misal: 42 atau L" required></div>
                <button type="submit" class="btn-full" style="border-radius: 6px;">Simpan Pesanan</button>
            </form>
        </div>
        <button class="floating-btn" onclick="toggleOrderWidget()">🛒 Quick Order</button>
    </div>

    <!-- SCRIPT DAFTAR PRODUK DINAMIS -->
    <script>
        const isLoggedIn = {{ session('user_name') ? 'true' : 'false' }};

        // Menggunakan blade asset helper pada JavaScript untuk memastikan URL gambar selalu benar
        const productsDatabase = [
            {
                name: "Nike Air Force 1 '07 White",
                subtitle: "Sneakers - Unisex",
                price: "Rp 1.549.000",
                image: "{{ asset('images/af1.jpg') }}",
                categories: ["All", "New Arrivals", "Trending", "Men Sneakers", "Women Sneakers", "Air Force 1", "Sneakers & Lifestyle"]
            },
            {
                name: "Adidas Samba OG Black White",
                subtitle: "Sneakers - Unisex",
                price: "Rp 2.200.000",
                image: "{{ asset('images/samba.jpg') }}",
                categories: ["All", "New Arrivals", "Trending", "Limited Drops", "Men Sneakers", "Women Sneakers", "Adidas Samba", "Sneakers & Lifestyle"]
            },
            {
                name: "New Balance 550 White Grey",
                subtitle: "Sneakers - Unisex",
                price: "Rp 1.999.000",
                image: "{{ asset('images/nb550.jpg') }}",
                categories: ["All", "Trending", "Men Sneakers", "Women Lifestyle", "New Balance", "Sneakers & Lifestyle"]
            },
            {
                name: "Vans Old Skool Black/White",
                subtitle: "Skate Shoes - Unisex",
                price: "Rp 999.000",
                image: "{{ asset('images/vans.jpg') }}",
                categories: ["All", "Men Sneakers", "Skateboarding", "Sneakers & Lifestyle"]
            },
            {
                name: "Carhartt WIP Pocket Tee Black",
                subtitle: "Apparel - Men",
                price: "Rp 650.000",
                image: "{{ asset('images/carhartt.jpg') }}",
                categories: ["All", "New Arrivals", "Men Apparel", "Streetwear"]
            }
        ];

        function renderProducts(productsList) {
            const container = document.getElementById('productGrid');
            container.innerHTML = '';
            if (productsList.length === 0) {
                container.innerHTML = `
                    <div class="no-product-msg">
                        <h3>Belum Ada Produk</h3>
                        <p style="margin-top: 5px;">Produk untuk kategori ini sedang dalam tahap ketersediaan stok.</p>
                        <button class="btn-reset-filter" style="margin-top: 15px;" onclick="filterCategory('All')">Lihat Semua Produk</button>
                    </div>`;
                return;
            }
            productsList.forEach(item => {
                const card = document.createElement('div');
                card.className = 'product-card';
                card.onclick = () => selectProduct(item.name); 
                card.innerHTML = `
                    <div class="product-img-box">
                        <img src="${item.image}" alt="${item.name}" onerror="this.onerror=null; this.src='https://via.placeholder.com/300?text=Gambar+Belum+Ada';">
                        <div class="heart">♡</div>
                    </div>
                    <div class="product-info">
                        <h4>${item.name}</h4>
                        <p>${item.subtitle}</p>
                        <div class="product-price">${item.price}</div>
                    </div>`;
                container.appendChild(card);
            });
        }

        function filterCategory(categoryKey) {
            closeAllUI();
            const titleElem = document.getElementById('catalogTitle');
            const resetBtn = document.getElementById('resetFilterBtn');

            if (categoryKey === 'All' || !categoryKey) {
                titleElem.textContent = "Our Best Sellers";
                resetBtn.classList.add('hidden');
                renderProducts(productsDatabase);
            } else {
                titleElem.textContent = `Kategori: ${categoryKey}`;
                resetBtn.classList.remove('hidden');
                const filtered = productsDatabase.filter(p => p.categories.some(cat => cat.toLowerCase().includes(categoryKey.toLowerCase())));
                renderProducts(filtered);
            }
            const section = document.getElementById('bestsellers');
            if (section) { section.scrollIntoView({ behavior: 'smooth' }); }
        }

        function searchProducts() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const titleElem = document.getElementById('catalogTitle');
            const resetBtn = document.getElementById('resetFilterBtn');

            if (query.trim() === '') {
                filterCategory('All');
                return;
            }
            titleElem.textContent = `Pencarian: "${query}"`;
            resetBtn.classList.remove('hidden');
            const matched = productsDatabase.filter(p => p.name.toLowerCase().includes(query) || p.subtitle.toLowerCase().includes(query));
            renderProducts(matched);
        }

        function toggleSubMenu(element) {
            const nextSubMenu = element.nextElementSibling;
            const isAlreadyOpen = element.classList.contains('active');
            document.querySelectorAll('.side-menu-parent').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.sub-menu').forEach(el => el.classList.remove('open'));
            if (!isAlreadyOpen && nextSubMenu && nextSubMenu.classList.contains('sub-menu')) {
                element.classList.add('active');
                nextSubMenu.classList.add('open');
            }
        }

        function showToast(message, title = 'Informasi', isError = false) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast';
            const iconColor = isError ? '#d32f2f' : '#2e7d32';
            const iconBg = isError ? '#ffebee' : '#e8f5e9';
            const iconChar = isError ? '!' : '✓';
            toast.innerHTML = `
                <div class="toast-icon" style="color: ${iconColor}; background: ${iconBg};">${iconChar}</div>
                <div class="toast-content"><div class="toast-title">${title}</div><div class="toast-message">${message}</div></div>
                <button class="toast-close" onclick="this.parentElement.remove()">✕</button>
                <div class="toast-progress" style="background: ${iconColor};"></div>`;
            container.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 400); }, 4000);
        }

        function copyPayCode(text) { navigator.clipboard.writeText(text).then(() => { showToast("Kode pembayaran berhasil disalin!", "Disalin!"); }); }

        const uiOverlay = document.getElementById('uiOverlay'), sideMenu = document.getElementById('sideMenu'), cartDrawer = document.getElementById('cartDrawer');
        const loginModal = document.getElementById('loginModal'), checkoutModal = document.getElementById('checkoutModal');
        const paymentModal = document.getElementById('paymentModal'), invoiceModal = document.getElementById('invoiceModal');
        const loginForm = document.getElementById('modalLoginForm'), registerForm = document.getElementById('modalRegisterForm');
        const modalTitle = document.getElementById('modalTitle'), orderWidget = document.getElementById('quickOrderForm');

        function closeAllUI() {
            uiOverlay.classList.remove('active'); sideMenu.classList.remove('open'); cartDrawer.classList.remove('open');
            if(loginModal) loginModal.classList.remove('open'); if(checkoutModal) checkoutModal.classList.remove('open');
            if(paymentModal) paymentModal.classList.remove('open'); if(invoiceModal) invoiceModal.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
        function openMenu() { closeAllUI(); uiOverlay.classList.add('active'); sideMenu.classList.add('open'); document.body.style.overflow = 'hidden'; }
        function openCart() { closeAllUI(); uiOverlay.classList.add('active'); cartDrawer.classList.add('open'); document.body.style.overflow = 'hidden'; }
        
        function openCheckout() {
            if (!isLoggedIn) { showToast("Silakan login atau buat akun terlebih dahulu.", "Login Diperlukan", true); openLogin('login'); return; }
            closeAllUI(); uiOverlay.classList.add('active'); if(checkoutModal) checkoutModal.classList.add('open'); document.body.style.overflow = 'hidden';
        }
        function openLogin(mode = 'login') {
            closeAllUI(); switchModalAuth(mode); uiOverlay.classList.add('active');
            if(loginModal) loginModal.classList.add('open'); document.body.style.overflow = 'hidden';
        }
        function switchModalAuth(type) {
            if (type === 'register') { loginForm.classList.add('hidden'); registerForm.classList.remove('hidden'); modalTitle.textContent = 'Create Account'; } 
            else { registerForm.classList.add('hidden'); loginForm.classList.remove('hidden'); modalTitle.textContent = 'Sign In'; }
        }
        function toggleOrderWidget() {
            if (!isLoggedIn) { showToast("Silakan login terlebih dahulu untuk memesan barang.", "Akses Ditolak", true); openLogin('login'); return; }
            orderWidget.classList.toggle('active');
        }
        function selectProduct(namaProduk) {
            if (!isLoggedIn) { showToast("Silakan login terlebih dahulu untuk memesan barang ini.", "Akses Ditolak", true); openLogin('login'); return; }
            document.getElementById('orderProduct').value = namaProduk;
            if (!orderWidget.classList.contains('active')) toggleOrderWidget();
        }

        window.addEventListener('DOMContentLoaded', () => {
            renderProducts(productsDatabase);
            const urlParams = new URLSearchParams(window.location.search);
            @if(session('sukses')) showToast("{{ session('sukses') }}", "Berhasil!"); @endif
            @if(session('error')) showToast("{{ session('error') }}", "Perhatian!", true); @endif

            if (urlParams.has('open_login')) openLogin('login');
            else if (urlParams.has('open_cart')) openCart();
            else if (urlParams.has('open_payment')) { closeAllUI(); uiOverlay.classList.add('active'); if(paymentModal) paymentModal.classList.add('open'); }
            else if (urlParams.has('open_invoice')) { closeAllUI(); uiOverlay.classList.add('active'); if(invoiceModal) invoiceModal.classList.add('open'); }
        });
    </script>
</body>
</html>