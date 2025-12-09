<?php
/**
 * Create Booking - User
 * Classroom Resource Booking System
 */

require_once '../config/config.php';
requireLogin();

$conn = getDBConnection();
$user_id = $_SESSION['user_id'];
$errors = [];

// Get resource_id from query parameter if exists
$preselected_resource = isset($_GET['resource_id']) ? intval($_GET['resource_id']) : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resource_id = intval($_POST['resource_id']);
    $booking_date = sanitizeInput($conn, $_POST['booking_date']);
    $start_time = sanitizeInput($conn, $_POST['start_time']);
    $end_time = sanitizeInput($conn, $_POST['end_time']);
    $purpose = sanitizeInput($conn, $_POST['purpose']);
    $attendees = intval($_POST['attendees']);
    
    // Server-side validation
    if ($resource_id <= 0) {
        $errors['resource_id'] = 'Please select a resource';
    }
    
    if (empty($booking_date)) {
        $errors['booking_date'] = 'Booking date is required';
    } elseif (strtotime($booking_date) < strtotime(date('Y-m-d'))) {
        $errors['booking_date'] = 'Cannot book past dates';
    }
    
    if (empty($start_time)) {
        $errors['start_time'] = 'Start time is required';
    }
    
    if (empty($end_time)) {
        $errors['end_time'] = 'End time is required';
    } elseif ($end_time <= $start_time) {
        $errors['end_time'] = 'End time must be after start time';
    }
    
    if (empty($purpose)) {
        $errors['purpose'] = 'Purpose is required';
    }
    
    if ($attendees <= 0) {
        $errors['attendees'] = 'Number of attendees must be at least 1';
    }
    
    // Check for conflicts
    if (empty($errors)) {
        $conflict_sql = "SELECT booking_id FROM bookings 
                        WHERE resource_id = ? 
                        AND booking_date = ? 
                        AND status IN ('pending', 'approved')
                        AND (
                            (start_time <= ? AND end_time > ?) OR
                            (start_time < ? AND end_time >= ?) OR
                            (start_time >= ? AND end_time <= ?)
                        )";
        $stmt = $conn->prepare($conflict_sql);
        $stmt->bind_param("isssssss", $resource_id, $booking_date, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time);
        $stmt->execute();
        $conflict_result = $stmt->get_result();
        
        if ($conflict_result->num_rows > 0) {
            $errors['general'] = 'This resource is already booked for the selected time slot.';
        }
        $stmt->close();
    }
    
    // If no errors, create booking
    if (empty($errors)) {
        $sql = "INSERT INTO bookings (user_id, resource_id, booking_date, start_time, end_time, purpose, attendees, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissssi", $user_id, $resource_id, $booking_date, $start_time, $end_time, $purpose, $attendees);
        
        if ($stmt->execute()) {
            setFlashMessage('Booking request submitted successfully! Waiting for admin approval.', 'success');
            redirect(SITE_URL . '/user/my_bookings.php');
        } else {
            $errors['general'] = 'Failed to create booking. Please try again.';
        }
        $stmt->close();
    }
}

// Get available resources
$sql = "SELECT r.*, rc.category_name
        FROM resources r
        LEFT JOIN resource_categories rc ON r.category_id = rc.category_id
        WHERE r.status = 'available'
        ORDER BY r.resource_name ASC";
$resources = executeQuery($conn, $sql);

closeDBConnection($conn);

$page_title = 'Create Booking';
include '../includes/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-plus"></i> Create New Booking
                </div>
                <div class="card-body">
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $errors['general']; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" data-validate="true">
                        <div class="form-group">
                            <label for="resource_id" class="required">Select Resource</label>
                            <select class="form-control <?php echo isset($errors['resource_id']) ? 'is-invalid' : ''; ?>" 
                                    id="resource_id" name="resource_id" required>
                                <option value="">-- Select Resource --</option>
                                <?php while ($resource = $resources->fetch_assoc()): ?>
                                    <option value="<?php echo $resource['resource_id']; ?>" 
                                            <?php echo ($preselected_resource == $resource['resource_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($resource['resource_code']) . ' - ' . htmlspecialchars($resource['resource_name']) . ' (' . htmlspecialchars($resource['category_name']) . ')'; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <?php if (isset($errors['resource_id'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['resource_id']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="booking_date" class="required">Booking Date</label>
                                <input type="date" class="form-control <?php echo isset($errors['booking_date']) ? 'is-invalid' : ''; ?>" 
                                       id="booking_date" name="booking_date" 
                                       min="<?php echo date('Y-m-d'); ?>"
                                       value="<?php echo isset($_POST['booking_date']) ? $_POST['booking_date'] : ''; ?>" required>
                                <?php if (isset($errors['booking_date'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['booking_date']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="attendees" class="required">Number of Attendees</label>
                                <input type="number" class="form-control <?php echo isset($errors['attendees']) ? 'is-invalid' : ''; ?>" 
                                       id="attendees" name="attendees" min="1" 
                                       value="<?php echo isset($_POST['attendees']) ? $_POST['attendees'] : '1'; ?>" required>
                                <?php if (isset($errors['attendees'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['attendees']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_time" class="required">Start Time</label>
                                <input type="time" class="form-control <?php echo isset($errors['start_time']) ? 'is-invalid' : ''; ?>" 
                                       id="start_time" name="start_time" 
                                       value="<?php echo isset($_POST['start_time']) ? $_POST['start_time'] : ''; ?>" required>
                                <?php if (isset($errors['start_time'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['start_time']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="end_time" class="required">End Time</label>
                                <input type="time" class="form-control <?php echo isset($errors['end_time']) ? 'is-invalid' : ''; ?>" 
                                       id="end_time" name="end_time" 
                                       value="<?php echo isset($_POST['end_time']) ? $_POST['end_time'] : ''; ?>" required>
                                <?php if (isset($errors['end_time'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['end_time']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="purpose" class="required">Purpose of Booking</label>
                            <textarea class="form-control <?php echo isset($errors['purpose']) ? 'is-invalid' : ''; ?>" 
                                      id="purpose" name="purpose" rows="4" maxlength="500"
                                      placeholder="Describe the purpose of your booking..." required><?php echo isset($_POST['purpose']) ? htmlspecialchars($_POST['purpose']) : ''; ?></textarea>
                            <?php if (isset($errors['purpose'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['purpose']; ?></div>
                            <?php endif; ?>
                            <small class="form-text text-muted">Maximum 500 characters</small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo SITE_URL; ?>/user/browse_resources.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check"></i> Submit Booking Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
