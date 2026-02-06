<style>
    .nav-container {
        padding: 12px 0;
        background: linear-gradient(135deg, #d35400, #e67e22);
        box-shadow: 0 4px 20px rgba(211, 84, 0, 0.2);
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: all 0.3s;
    }

    .nav-content {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: white;
        font-weight: 800;
        font-size: 1.5rem;
        transition: transform 0.3s;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .nav-brand:hover {
        transform: scale(1.05);
    }

    .nav-links {
        display: flex;
        list-style: none;
        gap: 35px;
        margin: 0;
        padding: 0;
    }

    .nav-link {
        text-decoration: none;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        position: relative;
        transition: color 0.3s;
    }

    .nav-link:hover {
        color: white;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 3px;
        bottom: -5px;
        left: 0;
        background-color: white;
        border-radius: 2px;
        transition: width 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .nav-link:hover::after {
        width: 100%;
    }

    /* Page Transition Styles */
    body {
        opacity: 0;
        animation: fadeIn 0.5s ease-in-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    .fade-out {
        animation: fadeOut 0.4s ease-in-out forwards;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Intercept links for fade-out effect
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function (e) {
                const target = this.getAttribute('href');
                if (target && target !== '#' && !target.startsWith('javascript')) {
                    e.preventDefault();
                    document.body.classList.add('fade-out');
                    setTimeout(() => {
                        window.location.href = target;
                    }, 400); // Wait for animation
                }
            });
        });
    });
</script>

<nav class="nav-container">
    <div class="nav-content">
        <a href="index.php" class="nav-brand">
            <img src="img/logo.jpg" alt="Brew & Bites Logo"
                style="height: 60px; width: 60px; object-fit: cover; border-radius: 50%; border: 2px solid rgba(255,255,255,0.8); background: white; padding: 2px; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
            <span style="font-size: 1.6rem; letter-spacing: 0.5px; margin-left: 10px;">Brew & Bites</span>
        </a>
        <ul class="nav-links">
            <li><a href="index.php" class="nav-link">Beranda</a></li>
            <li><a href="menu.php" class="nav-link">Menu</a></li>
        </ul>
    </div>
</nav>