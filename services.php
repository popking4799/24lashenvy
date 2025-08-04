<?php
include 'header.php';
include 'dbconnect.php';

// Function to sanitize the category name for filtering
function sanitize_category($category) {
    return strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $category));
}

// Query to fetch unique categories from the services table
$stmt = $pdo->prepare("SELECT DISTINCT category FROM services");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query to fetch all services from the database
$stmt_services = $pdo->prepare("SELECT * FROM services");
$stmt_services->execute();
$services = $stmt_services->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="breadcrumbs-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Services</h2>
                    <ul>
                        <li>
                            <a class="active" href="index.php">Home</a>
                        </li>
                        <li>Services</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-filter-area ptb-50">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <ul id="service-filters" class="port-filter-nav">
                    <li data-filter="*" class="is-checked">All</li>
                    <?php foreach ($categories as $category): ?>
                        <li data-filter=".<?= sanitize_category($category['category']) ?>"><?= ucfirst($category['category']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="hs-service-area" class="hs-service area ptb-90 bg-gray">
    <div class="container">
        <div class="row mb-n6 grid">
            <?php foreach ($services as $service): ?>
                <div class="col-lg-4 col-md-6 mb-6 pro-item <?= sanitize_category($service['category']) ?>">
                    <div class="single-service-area" style="border-radius: 18px; box-shadow: 0 4px 24px rgba(124,58,237,0.08); background: #fff; padding: 32px 28px 28px 28px; margin-bottom: 24px; transition: box-shadow 0.2s, transform 0.2s; position: relative;">
                        <div style="display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 18px;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; background: #f3e8ff; color: #7c3aed; border-radius: 50%; width: 48px; height: 48px; font-size: 2rem; margin-bottom: 10px;">
                                <i class="zmdi <?php
                                    if (strtolower($service['category']) === 'hair') echo 'zmdi-cut';
                                    else if (strtolower($service['category']) === 'spa') echo 'zmdi-spa';
                                    else if (strtolower($service['category']) === 'face') echo 'zmdi-face';
                                    else echo 'zmdi-star';
                                ?>"></i>
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
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>