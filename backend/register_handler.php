<?php
require_once 'database.php';

function show_message($title, $message, $is_success = true) {
    $icon = $is_success ? 'fa-check-circle text-green-400' : 'fa-exclamation-triangle text-red-400';
    $button_text = $is_success ? 'Login Now' : 'Try Again';
    // Use absolute path from root directory
    $button_link = $is_success ? '/CareerBridge/frontend/components/signUp.html' : 'javascript:history.back()';

    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title - CareerBridge</title>
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
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(118, 75, 162, 0.4); }
        .message-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.3);
        }
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
                    <i class="fas $icon text-6xl mb-6"></i>
                    <h1 class="text-3xl font-black text-slate-100">$title</h1>
                    <p class="text-slate-300 mt-4 text-lg">$message</p>
                    <div class="mt-8">
                        <a href="$button_link" class="btn-primary text-white font-bold px-8 py-3 rounded-xl inline-block">$button_text</a>
                    </div>
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
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['full_name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['user_type'])) {
            throw new Exception("All fields are required.");
        }
        
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user_type = $_POST['user_type'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        if ($result->num_rows > 0) {
            throw new Exception("This email address is already registered.");
        }
        $stmt_check->close();

        $conn->begin_transaction();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt_user = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt_user->bind_param("sss", $full_name, $email, $hashed_password);
        $stmt_user->execute();
        $user_id = $conn->insert_id; 
        $stmt_user->close();

        $role_id = ($user_type === 'employer') ? 2 : 1; 

        $stmt_role = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
        $stmt_role->bind_param("ii", $user_id, $role_id);
        $stmt_role->execute();
        $stmt_role->close();

        $conn->commit();

        show_message(
            "Registration Successful!",
            "Welcome, " . htmlspecialchars($full_name) . "! Your account has been created successfully."
        );
    }
    $conn->close();

} catch (Exception $e) {
    if (isset($conn) && $conn->ping()) {
        $conn->rollback();
    }
    show_message(
        "Registration Failed",
        "<strong>Error:</strong> " . $e->getMessage(),
        false
    );
}
?>
