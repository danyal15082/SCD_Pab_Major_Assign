<?php
/**
 * Profile Page
 * Classroom Resource Booking System
 */

require_once 'config/config.php';
requireLogin();

$conn = getDBConnection();
$user_id = $_SESSION['user_id'];
$errors = [];
$success = false;

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitizeInput($conn, $_POST['full_name']);
    $phone = sanitizeInput($conn, $_POST['phone']);
    $department = sanitizeInput($conn, $_POST['department']);
    
    // Validation
    if (empty($full_name)) {
        $errors['full_name'] = 'Full name is required';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    } elseif (!isValidPhone($phone)) {
        $errors['phone'] = 'Invalid phone number format';
    }
    
    if (empty($department)) {
        $errors['department'] = 'Department is required';
    }
    
    if (empty($errors)) {
        $sql = "UPDATE users SET full_name = ?, phone = ?, department = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $full_name, $phone, $department, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['full_name'] = $full_name;
            $success = true;
            setFlashMessage('Profile updated successfully!', 'success');
        } else {
            $errors['general'] = 'Failed to update profile';
        }
        $stmt->close();
    }
}

// Get user profile data
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
closeDBConnection($conn);

$page_title = 'My Profile';
include 'includes/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user"></i> My Profile
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Profile updated successfully!
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $errors['general']; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" data-validate="true">
                        <div class="form-group">
                            <label for="full_name" class="required">Full Name</label>
                            <input type="text" class="form-control <?php echo isset($errors['full_name']) ? 'is-invalid' : ''; ?>" 
                                   id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            <?php if (isset($errors['full_name'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['full_name']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            <small class="form-text text-muted">Email cannot be changed</small>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="required">Phone Number</label>
                            <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" 
                                   id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            <?php if (isset($errors['phone'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="department" class="required">Department</label>
                            <input type="text" class="form-control <?php echo isset($errors['department']) ? 'is-invalid' : ''; ?>" 
                                   id="department" name="department" value="<?php echo htmlspecialchars($user['department']); ?>" required>
                            <?php if (isset($errors['department'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['department']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-control" value="<?php echo ucfirst($user['role']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Account Status</label>
                            <input type="text" class="form-control" value="<?php echo ucfirst($user['status']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Member Since</label>
                            <input type="text" class="form-control" value="<?php echo formatDate($user['created_at']); ?>" disabled>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo SITE_URL; ?>/change_password.php" class="btn btn-warning">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
