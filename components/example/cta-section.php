<?php return [
    'render' => function($prop) {
        $title = $prop['title'] ?? 'Ready to Get Started?';
        $subtitle = $prop['subtitle'] ?? 'Join thousands of developers building amazing applications with our framework.';
        $cta_text = $prop['cta_text'] ?? 'Start Building Now';
        $cta_link = $prop['cta_link'] ?? '#';
        $secondary_text = $prop['secondary_text'] ?? 'View Documentation';
        $secondary_link = $prop['secondary_link'] ?? '#';
        
        ?>
        <div class="cta-section">
            <div class="cta-container">
                <div class="cta-content">
                    <h2 class="cta-title"><?= safe($title) ?></h2>
                    <p class="cta-subtitle"><?= safe($subtitle) ?></p>
                    <div class="cta-actions">
                        <a href="<?= safe($cta_link) ?>" class="btn btn-large cta-primary"><?= safe($cta_text) ?></a>
                        <a href="<?= safe($secondary_link) ?>" class="btn btn-outline btn-large cta-secondary"><?= safe($secondary_text) ?></a>
                    </div>
                </div>
                <div class="cta-visual">
                    <div class="floating-shapes">
                        <div class="shape shape-1"></div>
                        <div class="shape shape-2"></div>
                        <div class="shape shape-3"></div>
                        <div class="shape shape-4"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="hexagons" width="28" height="24" patternUnits="userSpaceOnUse"><polygon points="14,2 26,8 26,20 14,26 2,20 2,8" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23hexagons)"/></svg>');
        }
        
        .cta-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .cta-content {
            text-align: left;
        }
        
        .cta-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }
        
        .cta-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .cta-actions {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        
        .cta-primary {
            background: white;
            color: var(--primary);
            border: 2px solid white;
        }
        
        .cta-primary:hover {
            background: transparent;
            color: white;
            border-color: white;
        }
        
        .cta-secondary {
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }
        
        .cta-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }
        
        .cta-visual {
            position: relative;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .floating-shapes {
            position: relative;
            width: 200px;
            height: 200px;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .shape-1 {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            top: 0;
            left: 0;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape-2 {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            top: 20px;
            right: 0;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        .shape-3 {
            width: 100px;
            height: 40px;
            border-radius: 20px;
            bottom: 40px;
            left: 20px;
            animation: float 7s ease-in-out infinite;
        }
        
        .shape-4 {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            bottom: 0;
            right: 30px;
            animation: float 5s ease-in-out infinite reverse;
            transform: rotate(45deg);
        }
        
        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
            }
            50% { 
                transform: translateY(-15px) rotate(180deg); 
            }
        }
        
        @media (max-width: 968px) {
            .cta-container {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 2rem;
            }
            
            .cta-content {
                text-align: center;
            }
            
            .cta-title {
                font-size: 2.5rem;
            }
            
            .cta-visual {
                height: 200px;
            }
            
            .floating-shapes {
                width: 150px;
                height: 150px;
            }
        }
        
        @media (max-width: 768px) {
            .cta-section {
                padding: 3rem 0;
            }
            
            .cta-title {
                font-size: 2rem;
            }
            
            .cta-subtitle {
                font-size: 1.1rem;
            }
            
            .cta-actions {
                justify-content: center;
            }
        }
        </style>
        <?php
        
    },
    
    'about' => 'A compelling call-to-action section with animated floating shapes and gradient background'
]; ?>
