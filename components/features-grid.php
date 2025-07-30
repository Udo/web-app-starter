<?php return [
    'render' => function($prop) {
        $features = $prop['features'] ?? [
            [
                'icon' => '⚡',
                'title' => 'Lightning Fast',
                'description' => 'Optimized for speed and performance with modern web technologies.'
            ],
            [
                'icon' => '🎨',
                'title' => 'Beautiful Design',
                'description' => 'Carefully crafted components with attention to detail and user experience.'
            ],
            [
                'icon' => '📱',
                'title' => 'Mobile First',
                'description' => 'Fully responsive design that works perfectly on all devices.'
            ],
            [
                'icon' => '🔧',
                'title' => 'Easy to Use',
                'description' => 'Simple and intuitive component system for rapid development.'
            ],
            [
                'icon' => '🛡️',
                'title' => 'Secure',
                'description' => 'Built with security best practices and modern PHP standards.'
            ],
            [
                'icon' => '🚀',
                'title' => 'Scalable',
                'description' => 'Architecture designed to grow with your application needs.'
            ]
        ];
        
        ?>
        <div class="features-section" id="features">
            <div class="features-header">
                <h2>Why Choose Our Framework?</h2>
                <p>Discover the powerful features that make development a breeze</p>
            </div>
            <div class="features-grid">
                <?php foreach($features as $index => $feature): ?>
                <div class="feature-card" style="animation-delay: <?= $index * 0.1 ?>s">
                    <div class="feature-icon">
                        <?= $feature['icon'] ?>
                    </div>
                    <h3 class="feature-title"><?= htmlspecialchars($feature['title']) ?></h3>
                    <p class="feature-description"><?= htmlspecialchars($feature['description']) ?></p>
                    <div class="feature-overlay"></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <style>
        .features-section {
            padding: 4rem 0;
            background: var(--bg-color);
        }
        
        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .features-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .features-header p {
            font-size: 1.25rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .feature-card {
            background: var(--surface);
            padding: 2.5rem 2rem;
            border-radius: var(--radius-xl);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            display: inline-block;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            color: white;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .feature-description {
            color: var(--text-secondary);
            line-height: 1.6;
        }
        
        .feature-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .feature-card:hover .feature-overlay {
            opacity: 0.05;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .features-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 0 0.5rem;
            }
            
            .features-header h2 {
                font-size: 2rem;
            }
            
            .feature-card {
                padding: 2rem 1.5rem;
            }
            
            .feature-icon {
                font-size: 2.5rem;
                padding: 0.75rem;
            }
        }
        </style>
        <?php
    },
    
    'about' => 'A modern features grid with hover animations and gradient effects'
]; ?>
