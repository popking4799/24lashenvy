<?php
include 'header.php';
include 'dbconnect.php';
?>

<div class="slider-area bg-color-1">
    <div class="slide-active slider-shape-1 slider-activation slider-nav-style-1">
        <div class="single-slider-wrap sbg-1">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="slider-content">
                            <div class="slider-inner text-left">
                                <h1>24LashEnvy</h1>
                                <p>Your beauty, our passion. Experience luxury hair care and wellness.</p>
                                <div class="slider-button">
                                    <a class="hs-btn btn-light btn-large" href="contact.php">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="single-slider-wrap sbg-1">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="slider-content">
                            <div class="slider-inner text-left">
                                <h1>Transform Your Look</h1>
                                <p>From classic cuts to modern styles, we create the look you desire.</p>
                                <div class="slider-button">
                                    <a class="hs-btn btn-light btn-large" href="contact.php">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="single-slider-wrap sbg-1">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="slider-content">
                            <div class="slider-inner text-left">
                                <h1>Indulge in Self-Care</h1>
                                <p>Relax and rejuvenate with our exclusive salon treatments.</p>
                                <div class="slider-button">
                                    <a class="hs-btn btn-light btn-large" href="contact.php">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="hs-story-area" class="hs-story-area ptb-90">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="hs-story-thumb">
                    <div class="hs-story-img">
                        <img src="images/story-img/1.jpg" alt="Our Story Image">
                    </div>
                    <div class="hs-story-text">
                        <div class="hs-story-text-rot">
                            <h3>We Love What We Do, Every Day</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hs-story-wrap" style="border-radius: 15px;">
                    <h2 class="section-title">Our Story</h2>
                    <p class="theme-color pra-1">Where Beauty Meets Passion</p>
                    <div class="story-details">
                        <p>At Glamour Salon, we believe beauty is an expression of your inner self. Founded with the vision to create a luxurious space where clients can relax and rejuvenate, our salon has grown into a sanctuary of creativity, passion, and care.</p>
                        <p>Our team of expert stylists and beauticians is dedicated to providing personalized services that enhance your natural beauty and boost your confidence. From the latest hair trends to signature spa treatments, we ensure that every visit is a pampering experience you'll never forget.</p>
                        <p>With years of expertise, a commitment to excellence, and a love for what we do, we continue to evolve, offering the best in beauty and self-care.</p>
                    </div>
                    <div class="story-writter">
                        <!-- <img src="images/story-img/2.png" alt="Founder Signature"> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="hs-service-area" class="hs-service area ptb-90 bg-gray">
    <div class="container">
        <div class="row mb-n6">
            <?php
            // Fetch 6 random services from the services table
            $service_query = "SELECT * FROM services ORDER BY RAND() LIMIT 6";
            $services = $pdo->query($service_query)->fetchAll(PDO::FETCH_ASSOC);

            // Loop through each service and display it
            foreach ($services as $service) {
            ?>
                <div class="col-md-6 col-lg-4 mb-6">
                    <div class="single-service-area" style="border-radius: 18px; box-shadow: 0 4px 24px rgba(124,58,237,0.08); background: #fff; padding: 32px 28px 28px 28px; margin-bottom: 24px; transition: box-shadow 0.2s, transform 0.2s; position: relative;">
                        <div style="display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 18px;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; background: #f3e8ff; color: #7c3aed; border-radius: 50%; width: 48px; height: 48px; font-size: 2rem; margin-bottom: 10px;">
                                <i class="zmdi zmdi-cut"></i>
                            </span>
                            <h4 class="ser-vice-tit" style="font-size: 1.25rem; font-weight: 700; margin-bottom: 6px; color: #222;"><?= $service['name'] ?> <span style="font-size: 0.95em; color: #B23372; font-weight: 500;">(<?= $service['category'] ?>)</span></h4>
                        </div>
                        <p class="ser-pra" style="color: #6b7280; min-height: 48px; margin-bottom: 18px;"><?= $service['description'] ?></p>
                        <div style="margin-bottom: 10px;">
                            <span style="font-weight: 600; color: #222;">Price:</span> <?= number_format($service['price'], 2) ?> NGN
                        </div>
                        <div style="margin-bottom: 10px;">
                            <span style="font-weight: 600; color: #222;">Member Price:</span>
                            <span style="background: #B23372; color: #fff; border-radius: 8px; padding: 2px 12px; font-size: 1em; font-weight: 600; margin-left: 6px;"><?= number_format($service['member_price'], 2) ?> NGN</span>
                        </div>
                        <div>
                            <span style="font-weight: 600; color: #222;">Duration:</span> <?= $service['duration'] ?> minutes
                        </div>
                        <style>
                        .single-service-area:hover {
                            box-shadow: 0 8px 32px rgba(124,58,237,0.16);
                            transform: translateY(-4px) scale(1.02);
                        }
                        </style>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section id="hs-membership-benefits" class="hs-membership-benefits ptb-90 bg-white" style="background: linear-gradient(135deg, #fff 70%, #f8e1ff 100%); position: relative; overflow: hidden;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="hs-priceing-boxv text-center enhanced-membership-card">
                    <div class="membership-header">
                        <div class="membership-icon">
                            <i class="zmdi zmdi-star" style="font-size: 3rem; color: #B23372;"></i>
                        </div>
                        <h2 class="section-title mb-2" style="font-weight: 700; color: #B23372; letter-spacing: 0.5px;">Membership Benefits</h2>
                        <p class="section-details mb-4" style="color: #6b7280;">As a registered member of Glamour Salon, you unlock access to exclusive member prices and enjoy a range of premium services. Sign up today to take advantage of these fantastic benefits!</p>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="benefits-list">
                                <h4 style="color: #B23372; margin-bottom: 20px; font-weight: 600;">Exclusive Perks</h4>
                                <ul class="list-unstyled text-start mb-4">
                                    <li class="mb-3" style="padding-left: 2em; position: relative;">
                                        <span style="position: absolute; left: 0; color: #B23372; font-size: 1.2em;">&#10003;</span>
                                        Access to Member Prices on All Services
                                    </li>
                                    <li class="mb-3" style="padding-left: 2em; position: relative;">
                                        <span style="position: absolute; left: 0; color: #B23372; font-size: 1.2em;">&#10003;</span>
                                        Discounted Rates on Haircuts
                                    </li>
                                    <li class="mb-3" style="padding-left: 2em; position: relative;">
                                        <span style="position: absolute; left: 0; color: #B23372; font-size: 1.2em;">&#10003;</span>
                                        Exclusive Discounts on Hair Treatments & Styling
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="benefits-list">
                                <h4 style="color: #B23372; margin-bottom: 20px; font-weight: 600;">Premium Features</h4>
                                <ul class="list-unstyled text-start mb-4">
                                    <li class="mb-3" style="padding-left: 2em; position: relative;">
                                        <span style="position: absolute; left: 0; color: #B23372; font-size: 1.2em;">&#10003;</span>
                                        Special Member Offers on Products
                                    </li>
                                    <li class="mb-3" style="padding-left: 2em; position: relative;">
                                        <span style="position: absolute; left: 0; color: #B23372; font-size: 1.2em;">&#10003;</span>
                                        Priority Booking & Scheduling
                                    </li>
                                    <li class="mb-3" style="padding-left: 2em; position: relative;">
                                        <span style="position: absolute; left: 0; color: #B23372; font-size: 1.2em;">&#10003;</span>
                                        Exclusive Invitations to Salon Events & Promotions
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="membership-stats mt-4 mb-4">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="stat-item">
                                    <h3 style="color: #B23372; font-weight: 700; margin-bottom: 5px;">500+</h3>
                                    <p style="color: #6b7280; font-size: 0.9rem;">Happy Members</p>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="stat-item">
                                    <h3 style="color: #B23372; font-weight: 700; margin-bottom: 5px;">20%</h3>
                                    <p style="color: #6b7280; font-size: 0.9rem;">Average Savings</p>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="stat-item">
                                    <h3 style="color: #B23372; font-weight: 700; margin-bottom: 5px;">24/7</h3>
                                    <p style="color: #6b7280; font-size: 0.9rem;">Priority Support</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-box mt-4 mb-4">
                        <div style="background: #f3e8ff; border-radius: 15px; padding: 20px; border-left: 4px solid #B23372;">
                            <i class="zmdi zmdi-quote" style="font-size: 2rem; color: #B23372; display: block; margin-bottom: 10px;"></i>
                            <p style="font-style: italic; color: #6b7280; margin-bottom: 10px;">"Becoming a member was the best decision! I love the exclusive discounts and the special treatment I get every visit."</p>
                            <span style="font-weight: 600; color: #B23372;">— Sarah Johnson, Member since 2022</span>
                        </div>
                    </div>
                    
                    <div class="membership-cta">
                        <a class="hs-btn enhanced-signup-btn" href="register.php">Join Now - It's FREE!</a>
                        <p style="color: #6b7280; font-size: 0.9rem; margin-top: 10px;">No hidden fees • Cancel anytime • Instant access</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="hs-team-area" class="hs-team-area ptb-90 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hs-pricing-title text-center">
                    <h2 class="section-title bg-color-bark">Our Special Team</h2>
                    <p class="section-details">Meet our talented team of stylists and beauty experts. Dedicated to making you look and feel your best.</p>
                </div>
            </div>
        </div>
        <div class="row mt-40 mb-n7">
            <div class="col-md-6 col-lg-4 mb-7">
                <div class="hs-single-team">
                    <div class="single-team-inner">
                        <div class="single-team-content">
                            <div class="team-thumb">
                                <img src="images/team/1.jpg" alt="team images">
                            </div>
                            <ul class="team-social-icon">
                                <li>
                                    <a href="mailto:Jenniferfelix@gmail.com" target="_blank" title="Jenniferfelix@gmail.com">
                                        <i class="zmdi zmdi-email"></i>
                                    </a>
                                </li>
                                <li><a href="https://twitter.com/amalstylist" target="_blank"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="https://skype.com/amalstylist" target="_blank"><i class="zmdi zmdi-skype"></i></a></li>
                                <li><a href="https://linkedin.com/in/amalstylist" target="_blank"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="https://pinterest.com/amalstylist" target="_blank"><i class="zmdi zmdi-pinterest"></i></a></li>
                            </ul>
                        </div>
                        <div class="team-info">
                            <h4><a href="#">Gift Adumike</a></h4>
                            <p>Staff</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-7">
                <div class="hs-single-team">
                    <div class="single-team-inner">
                        <div class="single-team-content">
                            <div class="team-thumb">
                                <img src="images/team/1.jpg" alt="team images">
                            </div>
                            <ul class="team-social-icon">
                                <li>
                                    <a href="mailto:Jenniferfelix@gmail.com" target="_blank" title="Jenniferfelix@gmail.com">
                                        <i class="zmdi zmdi-email"></i>
                                    </a>
                                </li>
                                <li><a href="https://twitter.com/nilukastylist" target="_blank"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="https://skype.com/nilukastylist" target="_blank"><i class="zmdi zmdi-skype"></i></a></li>
                                <li><a href="https://linkedin.com/in/nilukastylist" target="_blank"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="https://pinterest.com/niluka.stylist" target="_blank"><i class="zmdi zmdi-pinterest"></i></a></li>
                            </ul>
                        </div>
                        <div class="team-info">
                            <h4><a href="#">Jennifer Adumike</a></h4>
                            <p>CEO</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-7">
                <div class="hs-single-team">
                    <div class="single-team-inner">
                        <div class="single-team-content">
                            <div class="team-thumb">
                                <img src="images/team/1.jpg" alt="team images">
                            </div>
                            <ul class="team-social-icon">
                                <li>
                                    <a href="mailto:Jenniferfelix@gmail.com" target="_blank" title="Jenniferfelix@gmail.com">
                                        <i class="zmdi zmdi-email"></i>
                                    </a>
                                </li>
                                <li><a href="https://twitter.com/sachikamakeup" target="_blank"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="https://skype.com/sachikamakeup" target="_blank"><i class="zmdi zmdi-skype"></i></a></li>
                                <li><a href="https://linkedin.com/in/sachikamakeup" target="_blank"><i class="zmdi zmdi-linkedin"></i></a></li>
                                <li><a href="https://pinterest.com/sachikamakeup" target="_blank"><i class="zmdi zmdi-pinterest"></i></a></li>
                            </ul>
                        </div>
                        <div class="team-info">
                            <h4><a href="#">Kingsley Adumike</a></h4>
                            <p>Staff</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="hs-portfolio-area" class="hs-portfolio-area ptb-90 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hs-pricing-title text-center">
                    <h2 class="section-title">Our Works</h2>
                    <p class="section-details">At Glamour Salon, we pride ourselves on delivering stunning transformations. From elegant hairstyles to flawless makeup, our talented team brings your beauty vision to life with every service.</p>
                </div>
            </div>
        </div>

        <div class="row our-portfolio-page grid mt-30">
            <?php
            // Fetch 3 random gallery items from the database
            $stmt = $pdo->prepare("SELECT * FROM gallery ORDER BY RAND() LIMIT 3");
            $stmt->execute();
            $gallery_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($gallery_items as $item) {
            ?>
                <div class="pro-item col-lg-4 col-md-6">
                    <div class="our-portfolio" data-title-position="left, top">
                        <div class="our-port-thumb">
                            <img style="border-radius: 15px; width: 100%; height: 220px; object-fit: cover;" src="<?= $item['file_path'] ?>" alt="<?= $item['title'] ?>">
                        </div>
                        <div class="our-hover-information">
                            <div class="our-hover-action">
                                <a href="<?= $item['file_path'] ?>" data-lightbox="hsportimg" data-title="<?= $item['title'] ?>">
                                    <i class="zmdi zmdi-zoom-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section id="hs-shop-area" class="hs-shop-area pb-90 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hs-pricing-title text-center">
                    <h2 class="section-title">Our Shop Items</h2>
                </div>
            </div>
        </div>
        <div class="row mt-40 mb-n6">
            <?php
            // Fetch 4 random products from the database
            $stmt_products = $pdo->prepare("SELECT * FROM products ORDER BY RAND() LIMIT 4");
            $stmt_products->execute();
            $products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $product) {
            ?>
                <div class="col-md-6 col-lg-3 mb-6">
                    <div class="hs-single-shop-area">
                        <div class="single-shop-thumd">
                            <img style="border-radius: 15px;" src="<?= $product['image_url'] ?>" alt="<?= $product['product_name'] ?>">
                        </div>
                        <div class="hs-shop-details">
                            <h4 class="shop-title"><a href="products.php"><?= $product['product_name'] ?></a></h4>
                            <ul class="product-price">
                                <li class="new-price"><?= $product['price'] ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section id="our-blog-area" class="our-blog-area pt-90 mb-30 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hs-pricing-title text-center">
                    <h2 class="section-title">Our Latest Blogs</h2>
                    <p class="section-details">Discover the latest beauty trends, tips, and insights from our blog.</p>
                </div>
            </div>
        </div>
        <div class="row mt-40 mb-n6">
            <?php
            // Fetch 3 random blog posts from the database along with their comment counts
            $stmt_blogs = $pdo->prepare("
                        SELECT b.*, COUNT(c.id) as comments_count 
                        FROM blog b 
                        LEFT JOIN comments c ON b.id = c.blog_id 
                        GROUP BY b.id 
                        ORDER BY RAND() 
                        LIMIT 3
                    ");
            $stmt_blogs->execute();
            $blogs = $stmt_blogs->fetchAll(PDO::FETCH_ASSOC);

            foreach ($blogs as $blog) {
            ?>
                <div class="col-lg-4 col-md-6 mb-6">
                    <div class="single-blog-wrap">
                        <div class="blog-front">
                            <div class="blog-thumb">
                                <img style="border-top-left-radius: 15px; border-top-right-radius: 15px;" src="<?= htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>">
                            </div>
                            <div class="blog-hover-info">
                                <div class="blog-hover-action">
                                    <a href="blog-details.php?id=<?= $blog['id'] ?>"><i class="zmdi zmdi-link"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="blog-details" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                            <h4 class="blog-title"><a href="blog-details.php?id=<?= $blog['id'] ?>"><?= htmlspecialchars($blog['title']) ?></a></h4>
                            <ul class="blog-post-date">
                                <li class="post-time"><i class="zmdi zmdi-time"></i><span><?= date('M d, Y', strtotime($blog['post_date'])) ?></span></li>
                                <li class="post-cmt"><i class="zmdi zmdi-comment-alt-text"></i><span>(<?= $blog['comments_count'] ?>)</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php include 'footer.php' ?>