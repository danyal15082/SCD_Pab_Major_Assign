/**
 * Main JavaScript File
 * Classroom Resource Booking System
 */

// Document Ready
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Confirm delete actions
    $('.confirm-delete').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Confirm action
    $('.confirm-action').on('click', function(e) {
        const message = $(this).data('message') || 'Are you sure you want to perform this action?';
        if (!confirm(message)) {
            e.preventDefault();
            return false;
        }
    });
    
    // Form validation on submit
    $('form[data-validate="true"]').on('submit', function(e) {
        let isValid = true;
        
        // Remove previous error messages
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
        
        // Validate required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">This field is required.</div>');
            }
        });
        
        // Validate email fields
        $(this).find('input[type="email"]').each(function() {
            if ($(this).val() && !isValidEmail($(this).val())) {
                isValid = false;
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">Please enter a valid email address.</div>');
            }
        });
        
        // Validate phone fields
        $(this).find('input[type="tel"]').each(function() {
            if ($(this).val() && !isValidPhone($(this).val())) {
                isValid = false;
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">Please enter a valid phone number.</div>');
            }
        });
        
        // Validate password confirmation
        const password = $(this).find('input[name="password"]').val();
        const confirmPassword = $(this).find('input[name="confirm_password"]').val();
        
        if (password && confirmPassword && password !== confirmPassword) {
            isValid = false;
            $(this).find('input[name="confirm_password"]').addClass('is-invalid');
            $(this).find('input[name="confirm_password"]').after('<div class="invalid-feedback">Passwords do not match.</div>');
        }
        
        if (!isValid) {
            e.preventDefault();
            scrollToFirstError();
        }
    });
    
    // Real-time validation
    $('input[required], textarea[required], select[required]').on('blur', function() {
        if (!$(this).val()) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">This field is required.</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    
    // Email validation on blur
    $('input[type="email"]').on('blur', function() {
        if ($(this).val() && !isValidEmail($(this).val())) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Please enter a valid email address.</div>');
            }
        }
    });
    
    // Phone validation on blur
    $('input[type="tel"]').on('blur', function() {
        if ($(this).val() && !isValidPhone($(this).val())) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Please enter a valid phone number.</div>');
            }
        }
    });
    
    // Clear validation on input
    $('input, textarea, select').on('input change', function() {
        if ($(this).val()) {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    
    // Date validation - prevent past dates for booking
    $('input[name="booking_date"]').on('change', function() {
        const selectedDate = new Date($(this).val());
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            $(this).after('<div class="invalid-feedback">Cannot book past dates.</div>');
        }
    });
    
    // Time validation - end time must be after start time
    $('input[name="end_time"]').on('change', function() {
        const startTime = $('input[name="start_time"]').val();
        const endTime = $(this).val();
        
        if (startTime && endTime && endTime <= startTime) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            $(this).after('<div class="invalid-feedback">End time must be after start time.</div>');
        }
    });
    
    // DataTable initialization (if available)
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']]
        });
    }
    
    // Search functionality
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('.searchable').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Filter by status
    $('#statusFilter').on('change', function() {
        const status = $(this).val().toLowerCase();
        if (status === '') {
            $('.filterable').show();
        } else {
            $('.filterable').hide();
            $('.filterable[data-status="' + status + '"]').show();
        }
    });
    
    // Filter by category
    $('#categoryFilter').on('change', function() {
        const category = $(this).val();
        if (category === '') {
            $('.filterable').show();
        } else {
            $('.filterable').hide();
            $('.filterable[data-category="' + category + '"]').show();
        }
    });
    
    // Character counter
    $('textarea[maxlength]').each(function() {
        const maxLength = $(this).attr('maxlength');
        $(this).after('<small class="form-text text-muted char-counter">0 / ' + maxLength + ' characters</small>');
    });
    
    $('textarea[maxlength]').on('input', function() {
        const currentLength = $(this).val().length;
        const maxLength = $(this).attr('maxlength');
        $(this).next('.char-counter').text(currentLength + ' / ' + maxLength + ' characters');
    });
    
    // Print functionality
    $('.btn-print').on('click', function() {
        window.print();
    });
    
    // Export to CSV (simple version)
    $('.btn-export-csv').on('click', function() {
        const table = $(this).data('table');
        exportTableToCSV(table);
    });
});

// Validation Helper Functions
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function isValidPhone(phone) {
    const regex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
    return regex.test(phone);
}

function scrollToFirstError() {
    const firstError = $('.is-invalid').first();
    if (firstError.length) {
        $('html, body').animate({
            scrollTop: firstError.offset().top - 100
        }, 500);
        firstError.focus();
    }
}

// Loading Overlay
function showLoading() {
    const overlay = '<div class="spinner-overlay"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';
    $('body').append(overlay);
}

function hideLoading() {
    $('.spinner-overlay').remove();
}

// Export Table to CSV
function exportTableToCSV(tableId) {
    const table = $('#' + tableId);
    if (!table.length) return;
    
    let csv = [];
    const rows = table.find('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = $(rows[i]).find('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            row.push('"' + $(cols[j]).text().trim() + '"');
        }
        
        csv.push(row.join(','));
    }
    
    downloadCSV(csv.join('\n'), 'export.csv');
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Check availability (for booking form)
function checkAvailability() {
    const resourceId = $('#resource_id').val();
    const bookingDate = $('#booking_date').val();
    const startTime = $('#start_time').val();
    const endTime = $('#end_time').val();
    
    if (!resourceId || !bookingDate || !startTime || !endTime) {
        return;
    }
    
    showLoading();
    
    $.ajax({
        url: 'check_availability.php',
        method: 'POST',
        data: {
            resource_id: resourceId,
            booking_date: bookingDate,
            start_time: startTime,
            end_time: endTime
        },
        success: function(response) {
            hideLoading();
            const data = JSON.parse(response);
            
            if (data.available) {
                $('#availability-message').html('<div class="alert alert-success"><i class="fas fa-check-circle"></i> Resource is available for booking!</div>');
            } else {
                $('#availability-message').html('<div class="alert alert-danger"><i class="fas fa-times-circle"></i> Resource is not available for the selected time slot.</div>');
            }
        },
        error: function() {
            hideLoading();
            $('#availability-message').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> Error checking availability. Please try again.</div>');
        }
    });
}

// Format date for display
function formatDate(date) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(date).toLocaleDateString('en-US', options);
}

// Format time for display
function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}
