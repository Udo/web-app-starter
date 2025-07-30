<?php return [
    'render' => function($prop) {
        $plans = $prop['plans'] ?? [
            [
                'name' => 'Starter',
                'price' => 'Free',
                'period' => '',
                'description' => 'Perfect for small projects and learning',
                'features' => [
                    'Fuckall',
                    'A warm handshake',
                    'A pat on the back',
                    'A cup of coffee'
                ],
                'cta' => 'Get Started',
                'popular' => false
            ],
            [
                'name' => 'Professional',
                'price' => '$29',
                'period' => '/month',
                'description' => 'Ideal for growing businesses and teams',
                'features' => [
                    'Unlimited projects',
                    'Advanced components',
                    'Priority support',
                    'Team collaboration',
                    'Custom themes',
                    'Analytics dashboard'
                ],
                'cta' => 'Start Not Quite Free Trial',
                'popular' => true
            ],
            [
                'name' => 'Enterprise',
                'price' => '$99',
                'period' => '/month',
                'description' => 'For large organizations with advanced needs',
                'features' => [
                    'Everything in Professional',
                    'Custom integrations',
                    'Dedicated support',
                    'SLA guarantee',
                    'Advanced security',
                    'White-label options'
                ],
                'cta' => 'Contact Desperate Sales Person',
                'popular' => false
            ]
        ];
        
        ?>
        <div class="pricing-section">
            <div class="pricing-container">
                <div class="pricing-header">
                    <h2>Choose Your Plan</h2>
                    <p>Start building fucking amazing applications today with our quite inflexible pricing options</p>
                </div>
                <div class="pricing-grid">
                    <?php foreach($plans as $index => $plan): ?>
                    <div class="pricing-card <?= $plan['popular'] ? 'popular' : '' ?>" style="animation-delay: <?= $index * 0.2 ?>s">
                        <?php if($plan['popular']): ?>
                        <div class="popular-badge">Most Popular</div>
                        <?php endif; ?>
                        
                        <div class="plan-header">
                            <h3 class="plan-name"><?= htmlspecialchars($plan['name']) ?></h3>
                            <div class="plan-price">
                                <span class="price"><?= htmlspecialchars($plan['price']) ?></span>
                                <?php if($plan['period']): ?>
                                <span class="period"><?= htmlspecialchars($plan['period']) ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="plan-description"><?= htmlspecialchars($plan['description']) ?></p>
                        </div>
                        
                        <div class="plan-features">
                            <ul>
                                <?php foreach($plan['features'] as $feature): ?>
                                <li>
                                    <span class="check-icon">✓</span>
                                    <?= htmlspecialchars($feature) ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="plan-footer">
                            <button class="btn <?= $plan['popular'] ? 'btn-primary' : 'btn-outline' ?> btn-large plan-cta">
                                <?= htmlspecialchars($plan['cta']) ?>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="pricing-footer">
                    <p>All plans include a 30-day money-back guarantee. No setup fees.</p>
                </div>
            </div>
        </div>
        
        <style>
        .pricing-section {
            padding: 5rem 0;
            background: var(--bg-color);
        }
        
        .pricing-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .pricing-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .pricing-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .pricing-header p {
            font-size: 1.25rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .pricing-card {
            background: var(--surface);
            border: 2px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 2.5rem 2rem;
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .pricing-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }
        
        .pricing-card.popular {
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
            transform: scale(1.05);
        }
        
        .pricing-card.popular:hover {
            transform: translateY(-8px) scale(1.07);
        }
        
        .popular-badge {
            position: absolute;
            top: -1rem;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: var(--shadow-md);
        }
        
        .plan-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border);
        }
        
        .plan-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .plan-price {
            margin-bottom: 1rem;
        }
        
        .price {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
        }
        
        .period {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-left: 0.25rem;
        }
        
        .plan-description {
            color: var(--text-secondary);
            line-height: 1.6;
        }
        
        .plan-features {
            margin-bottom: 2rem;
        }
        
        .plan-features ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .plan-features li {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            color: var(--text-primary);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .plan-features li:last-child {
            border-bottom: none;
        }
        
        .check-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: bold;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        
        .plan-footer {
            text-align: center;
        }
        
        .plan-cta {
            width: 100%;
            justify-content: center;
        }
        
        .pricing-footer {
            text-align: center;
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
            .pricing-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .pricing-card {
                padding: 2rem 1.5rem;
            }
            
            .pricing-card.popular {
                transform: none;
            }
            
            .pricing-card.popular:hover {
                transform: translateY(-4px);
            }
            
            .pricing-header h2 {
                font-size: 2rem;
            }
            
            .price {
                font-size: 2.5rem;
            }
        }
        </style>
        <?php
    },
    
    'about' => 'A modern pricing table with popular plan highlighting and smooth animations'
]; ?>
