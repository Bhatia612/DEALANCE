<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Freelance Job Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: 'Outfit', sans-serif;
            color: white;
            overflow: hidden;
        }

        body {
            background: rgb(1, 1, 70);
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        #background-canvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .content {
            z-index: 1;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            letter-spacing: 2px;
        }

        p {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .btn-group {
            margin-top: 1rem;
        }

        .btn {
            padding: 12px 24px;
            margin: 0 10px;
            background-color: white;
            color: black;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #ddd;
        }

    </style>
</head>

<body>
    <canvas id="background-canvas"></canvas>

    <div class="content">
        <h1>Welcome to DEALANCE !</h1>
        <p>"Empowering freelancers, connecting businesses. At DEALANCE, we make collaboration seamless and rewarding. Start exploring projects or post your job today!"</p>
        <div class="btn-group">
            <a href="./pages/register.php" class="btn">Register</a>
            <a href="./pages/login.php" class="btn">Login</a>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('background-canvas');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        const stars = [];

        for (let i = 0; i < 100; i++) {
            stars.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                radius: Math.random() * 1.5,
                velocity: Math.random() * 0.5 + 0.2
            });
        }

        function animateStars() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = 'white';

            for (let star of stars) {
                ctx.beginPath();
                ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
                ctx.fill();

                star.y += star.velocity;
                if (star.y > canvas.height) {
                    star.y = 0;
                    star.x = Math.random() * canvas.width;
                }
            }

            requestAnimationFrame(animateStars);
        }

        animateStars();
    </script>
</body>

</html>