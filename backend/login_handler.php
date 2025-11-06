<?php
require_once 'session_config.php'; 
require_once 'database.php';

function show_error_message($message) {
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Failed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #020617;
            color: #cbd5e1;
        }
        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        .content-wrapper {
            position: relative;
            z-index: 10;
        }
        .gradient-text { background: linear-gradient(135deg, #a7b2f5 0%, #cda3f7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .nav-blur { 
            backdrop-filter: blur(16px);
            background: rgba(15, 23, 42, 0.5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .message-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.3);
        }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(118, 75, 162, 0.4); }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <canvas id="particles-canvas"></canvas>
    <div class="content-wrapper">
        <nav class="sticky top-0 left-0 right-0 z-50 nav-blur">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="/CareerBridge/index.html" class="text-2xl font-black gradient-text">CareerBridge</a>
                </div>
            </div>
        </nav>
        <main class="flex-grow flex items-center justify-center py-12">
            <div class="max-w-lg w-full mx-auto px-4">
                <div class="message-card p-8 rounded-2xl text-center">
                    <div class="text-red-400 mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x"></i>
                    </div>
                    <h1 class="text-3xl font-black text-slate-100 mb-2">
                        Login Failed
                    </h1>
                    <p class="text-slate-300 mb-6">
                        {$message}
                    </p>
                    <a href="/CareerBridge/frontend/components/signUp.html" class="inline-block btn-primary text-white font-bold py-3 px-6 rounded-xl">
                        <i class="fas fa-redo-alt mr-2"></i> Try Again
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('particles-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let particles = [];
            
            const setup = () => {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                particles = [];
                const particleCount = Math.floor(canvas.width * canvas.height / 20000);
                for (let i = 0; i < particleCount; i++) {
                    particles.push(new Particle());
                }
            };

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.vx = Math.random() - 0.5;
                    this.vy = Math.random() - 0.5;
                    this.radius = Math.random() * 1.5 + 1;
                    this.color = 'rgba(167, 178, 245, 0.8)';
                }
                update() {
                    this.x += this.vx;
                    this.y += this.vy;
                    if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                    if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
                }
                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                    ctx.fillStyle = this.color;
                    ctx.fill();
                }
            }
            
            function connectParticles() {
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i; j < particles.length; j++) {
                        const dist = Math.sqrt(Math.pow(particles[i].x - particles[j].x, 2) + Math.pow(particles[i].y - particles[j].y, 2));
                        if (dist < 100) {
                            const opacity = 1 - (dist / 100);
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = 'rgba(167, 178, 245, ' + opacity + ')';
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    }
                }
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach(p => { p.update(); p.draw(); });
                connectParticles();
                requestAnimationFrame(animate);
            }

            window.addEventListener('resize', setup);
            setup();
            animate();
        }
    });
    </script>
</body>
</html>
HTML;
}

try {
    if (!isset($servername) || !isset($username) || !isset($password) || !isset($dbname)) {
        throw new Exception("Database configuration not loaded properly. Check database.php");
    }
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            throw new Exception("Email and password are required.");
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Fetch user roles
                $stmt_roles = $conn->prepare(
                    "SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?"
                );
                $stmt_roles->bind_param("i", $user['id']);
                $stmt_roles->execute();
                $roles_result = $stmt_roles->get_result();
                
                $roles = [];
                while ($row = $roles_result->fetch_assoc()) {
                    $roles[] = $row['name'];
                }
                $stmt_roles->close();
                
                // Clear any existing session data
                $_SESSION = array();
                
                // Set new session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['roles'] = $roles;
                $_SESSION['loggedin'] = true;
                $_SESSION['login_time'] = time();
                
                // Force session save
                session_write_close();
                
                // Restart session to ensure data is available
                require_once 'session_config.php';
                
                // Redirect to the home page after successful login
                header("Location: ../index.html");
                exit();

            } else {
                // Incorrect password
                show_error_message("Invalid email or password.");
            }
        } else {
            // User not found
            show_error_message("Invalid email or password.");
        }
        $stmt->close();
    }
    $conn->close();

} catch (Exception $e) {
    // Show error message
    show_error_message("An error occurred during login. Please try again or contact support if the problem persists.");
}
?>
