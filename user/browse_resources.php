<?php
/**
 * Browse Resources - User
 * Classroom Resource Booking System
 */

require_once '../config/config.php';
requireLogin();

$conn = getDBConnection();

// Get search and filter parameters
$search = isset($_GET['search']) ? sanitizeInput($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Build query
$where_clauses = ["r.status = 'available'"];
if (!empty($search)) {
    $where_clauses[] = "(r.resource_name LIKE '%$search%' OR r.resource_code LIKE '%$search%' OR r.location LIKE '%$search%')";
}
if ($category > 0) {
    $where_clauses[] = "r.category_id = $category";
}

$where_sql = implode(' AND ', $where_clauses);

$sql = "SELECT r.*, rc.category_name
        FROM resources r
        LEFT JOIN resource_categories rc ON r.category_id = rc.category_id
        WHERE $where_sql
        ORDER BY r.resource_name ASC";
$resources = executeQuery($conn, $sql);

// Get categories for filter
$categories_sql = "SELECT * FROM resource_categories ORDER BY category_name ASC";
$categories = executeQuery($conn, $categories_sql);

closeDBConnection($conn);

$page_title = 'Browse Resources';
include '../includes/header.php';
?>

<div class="container">
    <h2 class="mb-4"><i class="fas fa-search"></i> Browse Resources</h2>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" placeholder="Search by name, code, or location..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control" name="category">
                                <option value="0">All Categories</option>
                                <?php while ($cat = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo $cat['category_id']; ?>" <?php echo $category == $cat['category_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['category_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Resources Grid -->
    <?php if ($resources->num_rows > 0): ?>
    <div class="row">
        <?php while ($resource = $resources->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card resource-card h-100">
                <div class="card-body resource-info">
                    <h5 class="resource-title"><?php echo htmlspecialchars($resource['resource_name']); ?></h5>
                    <p class="text-muted mb-3">
                        <span class="badge badge-secondary"><?php echo htmlspecialchars($resource['category_name']); ?></span>
                        <span class="badge badge-info"><?php echo htmlspecialchars($resource['resource_code']); ?></span>
                    </p>
                    
                    <div class="resource-meta mb-3">
                        <div class="mb-2">
                            <i class="fas fa-users"></i>
                            Capacity: <?php echo $resource['capacity']; ?> persons
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($resource['location']); ?>
                        </div>
                        <?php if (!empty($resource['amenities'])): ?>
                        <div class="mb-2">
                            <i class="fas fa-check-circle"></i>
                            <?php echo htmlspecialchars($resource['amenities']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($resource['description'])): ?>
                    <p class="text-muted mb-3">
                        <small><?php echo htmlspecialchars(substr($resource['description'], 0, 100)) . '...'; ?></small>
                    </p>
                    <?php endif; ?>
                    
                    <a href="<?php echo SITE_URL; ?>/user/create_booking.php?resource_id=<?php echo $resource['resource_id']; ?>" 
                       class="btn btn-primary btn-block">
                        <i class="fas fa-calendar-plus"></i> Book Now
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h4>No Resources Found</h4>
                <p>No resources match your search criteria.</p>
                <a href="<?php echo SITE_URL; ?>/user/browse_resources.php" class="btn btn-primary">
                    Clear Filters
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
