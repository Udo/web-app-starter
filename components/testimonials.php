<?php return [
    'render' => function($prop) {
        $testimonials = $prop['testimonials'] ?? [
            [
                'name' => 'Sarah Johnson',
                'role' => 'Senior Developer at TechCorp',
                'avatar' => 'img/cat01.jpg',
                'content' => 'This framework has revolutionized our development process. The component system is intuitive and the performance is outstanding.',
                'rating' => 5
            ],
            [
                'name' => 'Michael Chen',
                'role' => 'CTO at StartupX',
                'avatar' => 'img/cat01.jpg',
                'content' => 'We switched from our legacy system to this framework and saw immediate improvements in both development speed and code quality.',
                'rating' => 5
            ],
            [
                'name' => 'Emily Rodriguez',
                'role' => 'Full Stack Developer',
                'avatar' => 'img/cat01.jpg',
                'content' => 'The documentation is excellent and the learning curve is gentle. Perfect for both beginners and experienced developers.',
                'rating' => 5
            ]
        ];
        
        ?>
        <div class="testimonials-section">
            <div class="testimonials-container">
                <div class="testimonials-header">
                    <h2>What Developers Say</h2>
                    <p>Don't just take our word for it - hear from the community</p>
                </div>
                <div class="testimonials-grid">
                    <?php foreach($testimonials as $index => $testimonial): ?>
                    <div class="testimonial-card" style="animation-delay: <?= $index * 0.2 ?>s">
                        <div class="testimonial-content">
                            <div class="quote-icon">"</div>
                            <p><?= htmlspecialchars($testimonial['content']) ?></p>
                            <div class="stars">
                                <?php for($i = 0; $i < $testimonial['rating']; $i++): ?>
                                    <span class="star">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="testimonial-author">
                            <img src="<?= htmlspecialchars($testimonial['avatar']) ?>" alt="<?= htmlspecialchars($testimonial['name']) ?>" class="avatar">
                            <div class="author-info">
                                <div class="author-name"><?= htmlspecialchars($testimonial['name']) ?></div>
                                <div class="author-role"><?= htmlspecialchars($testimonial['role']) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <style>
        .testimonials-section {
            padding: 4rem 0;
            background: var(--bg-color);
        }
        
        .testimonials-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .testimonials-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .testimonials-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .testimonials-header p {
            font-size: 1.25rem;
            color: var(--text-secondary);
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .testimonial-card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            padding: 2.5rem 2rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }
        
        .testimonial-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        
        .testimonial-content {
            margin-bottom: 2rem;
        }
        
        .quote-icon {
            font-size: 4rem;
            color: var(--primary);
            opacity: 0.3;
            line-height: 1;
            margin-bottom: 1rem;
            font-family: serif;
        }
        
        .testimonial-content p {
            font-size: 1.1rem;
            line-height: 1.7;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
        }
        
        .stars {
            display: flex;
            gap: 0.25rem;
        }
        
        .star {
            color: #fbbf24;
            font-size: 1.25rem;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--border);
            transition: all 0.3s ease;
        }
        
        .testimonial-card:hover .avatar {
            border-color: var(--primary);
            transform: scale(1.05);
        }
        
        .author-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .author-role {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .testimonials-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .testimonial-card {
                padding: 2rem 1.5rem;
            }
            
            .testimonials-header h2 {
                font-size: 2rem;
            }
        }
        </style>
        <?php

    },
    
    'about' => 'A testimonials section with user photos, ratings, and smooth animations'
]; ?>
