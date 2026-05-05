<?php 
include('includes/header.php'); 

if(isset($_SESSION['loggedIn'])){
    ?>
    <script>window.location.href = 'index.php';</script>
    <?php
}
?>

<style>
    body {
        background-color: #f3f4f6;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    .login-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
        min-height: calc(100vh - 120px);
    }
    
    .login-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        overflow: hidden;
        max-width: 420px;
        width: 100%;
        margin: 0 1rem;
        border: 1px solid #e5e7eb;
    }
    
    .login-header {
        background: white;
        padding: 2rem 2rem 1rem;
        text-align: center;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .login-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.5rem;
        color: #111827;
    }
    
    .login-header p {
        margin: 0.5rem 0 0 0;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .login-body {
        padding: 2rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #374151;
        font-size: 0.9375rem;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border: 1.5px solid #e5e7eb;
        border-radius: 0.375rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .btn-login {
        background-color: #2563eb;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.9375rem;
        border-radius: 0.375rem;
        transition: background-color 0.2s ease;
    }
    
    .btn-login:hover {
        background-color: #1d4ed8;
    }
    
    .logo-text {
        font-size: 2rem;
        font-weight: 700;
        color: #2563eb;
        margin-bottom: 0.5rem;
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-text">TSL</div>
            <h4>Sign In</h4>
            <p>TipidSulit Laundromat POS System</p>
        </div>
        
        <div class="login-body">
            <?php alertMessage(); ?>
            
            <form action="login-code.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com" required />
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required />
                </div>
                
                <button type="submit" name="loginBtn" class="btn btn-login btn-primary w-100">
                    Sign In
                </button>
            </form>
            
            <div class="text-center mt-4">
                <small class="text-muted">TipidSulit Laundromat &copy; <?= date('Y'); ?></small>
            </div>
        </div>
    </div>
</div>
 
<?php include('includes/footer.php'); ?>
