<?php
// Ambil data dari JSON
$jsonData = file_get_contents(__DIR__ . '/data.json');
$all_products = json_decode($jsonData, true);

// Filter kategori
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'ALL PRODUCTS';
$filtered_products = [];

if ($all_products) {
    foreach ($all_products as $product) {
        if ($category_filter === 'ALL PRODUCTS' || strtoupper($product['kategori']) === strtoupper($category_filter)) {
            $filtered_products[] = $product;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIGATE MART | Media Rich Catalog</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="logo">BIGATE<span>MART</span></div>
    </nav>

    <header class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <span class="badge-new">NEW COLLECTION 2026</span>
                <h1>Modern Solutions for Your <span>Digital Life.</span></h1>
                <p>Bigate Mart menyediakan kurasi produk terbaik mulai dari kebutuhan IT, Furniture hingga Sembako dengan jaminan kualitas dan pengiriman cepat.</p>
                
                <div class="hero-actions">
                    <a href="#katalog" class="btn-primary">Mulai Belanja</a>
                    <a href="#" class="btn-secondary">Lihat Video Review</a>
                </div>

                <div class="hero-features">
                    <div class="feature-item">
                        <div class="icon">🚀</div>
                        <div class="text">
                            <strong>Fast Delivery</strong>
                            <span>Sameday service</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="icon">🛡️</div>
                        <div class="text">
                            <strong>Quality Hub</strong>
                            <span>100% Original</span>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="icon">💬</div>
                        <div class="text">
                            <strong>24/7 Support</strong>
                            <span>Fast response</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="main-circle">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1000" alt="Tech & Lifestyle">
                </div>
                <div class="floating-card">
                    <p>⭐ 4.9 Rating</p>
                    <span>Trusted by 10k+ Users</span>
                </div>
            </div>
        </div>
    </header>

    <section class="catalog-header" id="katalog">
        <div class="filter-wrapper">
            <?php 
            $categories = ['ALL PRODUCTS', 'ELEKTRONIK', 'FURNITURE', 'SEMBAKO'];
            foreach ($categories as $cat): 
            ?>
                <a href="index.php?category=<?= $cat ?>#katalog" 
                   class="filter-link <?= $category_filter == $cat ? 'active' : '' ?>">
                   <?= $cat ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <main class="container">
        <div class="product-grid">
            <?php if (empty($filtered_products)): ?>
                <p style="grid-column: 1/-1; text-align: center; padding: 50px; color: var(--grey);">Belum ada produk untuk kategori ini.</p>
            <?php else: ?>
                <?php foreach ($filtered_products as $p): ?>
                <div class="product-card" onclick="openModal('<?= $p['id'] ?>')">
                    <div class="img-box">
                        <img src="<?= $p['gambar_utama'] ?>" alt="<?= $p['nama'] ?>" loading="lazy">
                    </div>
                    <div class="product-info">
                        <span class="cat-label"><?= $p['kategori'] ?></span>
                        <h3><?= $p['nama'] ?></h3>
                        <p class="price"><?= $p['harga'] ?></p>
                    </div>
                </div>

                <div id="modal-<?= $p['id'] ?>" class="modal">
                    <div class="modal-card">
                        <span class="close-icon" onclick="closeModal('<?= $p['id'] ?>')">&times;</span>
                        <div class="modal-layout">
                            <div class="media-container">
                                <div class="main-view">
                                    <img id="main-img-<?= $p['id'] ?>" src="<?= $p['gambar_utama'] ?>" class="active-media">
                                    <video id="main-video-<?= $p['id'] ?>" style="display:none;" controls>
                                        <source src="<?= $p['video'] ?>" type="video/mp4">
                                    </video>
                                </div>
                                <div class="thumbnail-list">
                                    <img src="<?= $p['gambar_utama'] ?>" onclick="changeMedia('<?= $p['id'] ?>', 'image', this.src)">
                                    
                                    <?php if(isset($p['galeri'])): ?>
                                        <?php foreach ($p['galeri'] as $g): ?>
                                            <img src="<?= $g ?>" onclick="changeMedia('<?= $p['id'] ?>', 'image', this.src)">
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <?php if(!empty($p['video'])): ?>
                                        <div class="video-thumb" onclick="changeMedia('<?= $p['id'] ?>', 'video')">
                                            <span>▶ VIDEO</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="modal-text">
                                <span class="cat-label"><?= $p['kategori'] ?></span>
                                <h2><?= $p['nama'] ?></h2>
                                <p class="modal-price"><?= $p['harga'] ?></p>
                                <div class="description">
                                    <h4>DESKRIPSI</h4>
                                    <p><?= $p['deskripsi'] ?></p>
                                </div>
                                <div class="specs">
                                    <h4>SPESIFIKASI</h4>
                                    <ul>
                                        <?php if(isset($p['spek'])): ?>
                                            <?php foreach ($p['spek'] as $s): ?>
                                                <li><?= $s ?></li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <a href="<?= $p['link'] ?>" target="_blank" class="btn-marketplace">BELI SEKARANG</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer style="text-align: center; padding: 40px; color: var(--grey); border-top: 1px solid #eee;">
        <p>&copy; 2026 BIGATE MART. All Rights Reserved.</p>
    </footer>

    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).style.display = "flex";
            document.body.style.overflow = "hidden";
        }

        function closeModal(id) {
            document.getElementById('modal-' + id).style.display = "none";
            document.body.style.overflow = "auto";
            const video = document.getElementById('main-video-' + id);
            if(video) {
                video.pause();
                video.currentTime = 0;
            }
        }

        function changeMedia(id, type, src = '') {
            const img = document.getElementById('main-img-' + id);
            const video = document.getElementById('main-video-' + id);

            if (type === 'image') {
                img.src = src;
                img.style.display = "block";
                video.style.display = "none";
                video.pause();
            } else {
                img.style.display = "none";
                video.style.display = "block";
                video.play();
            }
        }

        // Menutup modal dengan tombol ESC
        document.onkeydown = function(evt) {
            evt = evt || window.event;
            if (evt.keyCode == 27) {
                const modals = document.getElementsByClassName('modal');
                for (let i = 0; i < modals.length; i++) {
                    if (modals[i].style.display === "flex") {
                        const id = modals[i].id.split('-')[1];
                        closeModal(id);
                    }
                }
            }
        };
    </script>
</body>
</html>