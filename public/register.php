<?php
/**
 * Registration Page
 * Classroom Resource Booking System
 */

require_once 'config/config.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect(isAdmin() ? SITE_URL . '/admin/dashboard.php' : SITE_URL . '/user/dashboard.php');
}

$errors = [];
$success = false;

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDBConnection();
    
    // Get and sanitize input
    $full_name = sanitizeInput($conn, $_POST['full_name']);
    $email = sanitizeInput($conn, $_POST['email']);
    $phone = sanitizeInput($conn, $_POST['phone']);
    $department = sanitizeInput($conn, $_POST['department']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Server-side validation
    if (empty($full_name)) {
        $errors['full_name'] = 'Full name is required';
    } elseif (strlen($full_name) < 3) {
        $errors['full_name'] = 'Full name must be at least 3 characters';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!isValidEmail($email)) {
        $errors['email'] = 'Invalid email format';
    } else {
        // Check if email already exists
        $check_sql = "SELECT user_id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $errors['email'] = 'Email already registered';
        }
        $check_stmt->close();
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    } elseif (!isValidPhone($phone)) {
        $errors['phone'] = 'Invalid phone number format';
    }
    
    if (empty($department)) {
        $errors['department'] = 'Department is required';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }
    
    if (empty($confirm_password)) {
        $errors['confirm_password'] = 'Please confirm your password';
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    
    // If no validation errors, proceed with registration
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $sql = "INSERT INTO users (full_name, email, password, phone, department, role, status) 
                VALUES (?, ?, ?, ?, ?, 'user', 'active')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $full_name, $email, $hashed_password, $phone, $department);
        
        if ($stmt->execute()) {
            $success = true;
            setFlashMessage('Registration successful! You can now login with your credentials.', 'success');
            
            // Redirect to login page after 2 seconds
            header("refresh:2;url=" . SITE_URL . "/login.php");
        } else {
            $errors['general'] = 'Registration failed. Please try again.';
        }
        
        $stmt->close();
    }
    
    closeDBConnection($conn);
}

$page_title = 'Register';
include 'includes/header.php';
?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card auth-card">
                    <div class="card-body p-5">
                        <div class="auth-header">
                            <div>
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h3>Create Your Account</h3>
                            <p class="text-muted">Fill in the details to register</p>
                        </div>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                Registration successful! Redirecting to login page...
                            </div>
                        <?php endif; ?>

                        <?php if (isset($errors['general'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo htmlspecialchars($errors['general']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!$success): ?>
                        <form method="POST" action="" data-validate="true">
                            <div class="form-group">
                                <label for="full_name" class="required">Full Name</label>
                                <input type="text" class="form-control <?php echo isset($errors['full_name']) ? 'is-invalid' : ''; ?>" 
                                       id="full_name" name="full_name" placeholder="Enter your full name" 
                                       value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" 
                                       required>
                                <?php if (isset($errors['full_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['full_name']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="email" class="required">Email Address</label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" placeholder="Enter your email" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                       required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="required">Phone Number</label>
                                <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" 
                                       id="phone" name="phone" placeholder="+92-300-1234567" 
                                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                                       required>
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="department" class="required">Department</label>
                                <input type="text" class="form-control <?php echo isset($errors['department']) ? 'is-invalid' : ''; ?>" 
                                       id="department" name="department" placeholder="e.g., Computer Science" 
                                       value="<?php echo isset($_POST['department']) ? htmlspecialchars($_POST['department']) : ''; ?>" 
                                       required>
                                <?php if (isset($errors['department'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['department']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="password" class="required">Password</label>
                                <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                       id="password" name="password" placeholder="Minimum 6 characters" required>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Password must be at least 6 characters long</small>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password" class="required">Confirm Password</label>
                                <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                       id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-user-plus"></i> Register
                            </button>
                        </form>
                        <?php endif; ?>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-0">Already have an account? 
                                <a href="<?php echo SITE_URL; ?>/login.php">Login here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
