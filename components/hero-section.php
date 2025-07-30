<?php return [
    'render' => function($prop) {
        $title = $prop['title'] ?? 'Welcome to the Present';
        $subtitle = $prop['subtitle'] ?? 'Experience no-quite-modern web development with our cutting-edge framework';
        $cta_text = $prop['cta_text'] ?? 'Get Started';
        $cta_link = $prop['cta_link'] ?? '#';
        
        ?>
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title"><?= htmlspecialchars($title) ?></h1>
                <p class="hero-subtitle"><?= htmlspecialchars($subtitle) ?></p>
                <div class="hero-actions">
                    <a href="<?= htmlspecialchars($cta_link) ?>" class="btn btn-large hero-cta"><?= htmlspecialchars($cta_text) ?></a>
                    <a href="#features" class="btn btn-outline btn-large">Learn More</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="floating-card">
                    <div class="card-header"></div>
                    <div class="card-content">
                        <div class="line"></div>
                        <div class="line short"></div>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="floating-elements">
                    <div class="element element-1"></div>
                    <div class="element element-2"></div>
                    <div class="element element-3"></div>
                </div>
            </div>
        </div>
        
        <style>
        .hero-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            min-height: 70vh;
            padding: 4rem 2rem;
            background: var(--bg-gradient);
            color: var(--text-primary);
            border-radius: var(--radius-xl);
            margin-bottom: 4rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="90" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grain)"/></svg>');
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            background: none;
            padding: 0;
            box-shadow: none;
            border: none;
            color: white;
        }
        
        .hero-title::before {
            display: none;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
            color: white;
        }
        
        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .hero-cta {
            background: var(--surface);
            color: var(--primary);
            border: none;
        }
        
        .hero-cta:hover {
            background: var(--surface-hover);
            transform: translateY(-2px);
        }
            background: #f8fafc;
            transform: translateY(-2px);
        }
        
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
        }
        
        .floating-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 2rem;
            width: 280px;
            animation: float 6s ease-in-out infinite;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            height: 60px;
            background: linear-gradient(90deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .card-content .line {
            height: 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            margin-bottom: 8px;
        }
        
        .card-content .line.short {
            width: 60%;
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
        }
        
        .element {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
        }
        
        .element-1 {
            width: 60px;
            height: 60px;
            top: 20%;
            left: 10%;
            animation: float 4s ease-in-out infinite;
        }
        
        .element-2 {
            width: 40px;
            height: 40px;
            top: 60%;
            right: 15%;
            animation: float 5s ease-in-out infinite reverse;
        }
        
        .element-3 {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 20%;
            animation: float 7s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        
        @media (max-width: 768px) {
            .hero-section {
                grid-template-columns: 1fr;
                text-align: center;
                padding: 3rem 1rem;
                gap: 2rem;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .hero-visual {
                height: 300px;
            }
            
            .floating-card {
                width: 240px;
                padding: 1.5rem;
            }
        }
        </style>
        <?php

    },
    
    'about' => 'A modern hero section with animated floating elements and gradient background'
]; ?>
