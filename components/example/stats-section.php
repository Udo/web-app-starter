<?php return [
    'render' => function($prop) {
        $stats = $prop['stats'] ?? [
            ['number' => '99.9%', 'label' => 'Uptime'],
            ['number' => '500ms', 'label' => 'Average Response'],
            ['number' => '50K+', 'label' => 'Active Users'],
            ['number' => '24/7', 'label' => 'Support']
        ];
        
        ?>
        <div class="stats-section">
            <div class="stats-container">
                <div class="stats-header">
                    <h2>Trusted by Developers Worldwide</h2>
                    <p>Join thousands of developers who have chosen our framework</p>
                </div>
                <div class="stats-grid">
                    <?php foreach($stats as $index => $stat): ?>
                    <div class="stat-item" style="animation-delay: <?= $index * 0.2 ?>s">
                        <div class="stat-number" data-target="<?= safe($stat['number']) ?>"><?= safe($stat['number']) ?></div>
                        <div class="stat-label"><?= safe($stat['label']) ?></div>
                        <div class="stat-bar">
                            <div class="stat-fill" style="animation-delay: <?= ($index * 0.2) + 0.5 ?>s"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <style>
        .stats-section {
            background: linear-gradient(135deg, var(--surface) 0%, var(--surface-elevated) 100%);
            color: var(--text-primary);
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        
        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="currentColor" opacity="0.1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23dots)"/></svg>');
            color: var(--text-muted);
        }
        
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }
        
        .stats-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .stats-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .stats-header p {
            font-size: 1.25rem;
            opacity: 0.8;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 2rem;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-xl);
            transition: all 0.3s ease;
            animation: fadeInScale 0.8s ease-out forwards;
            opacity: 0;
            transform: scale(0.8);
        }
        
        .stat-item:hover {
            transform: scale(1.05);
            background: var(--surface-hover);
            border-color: var(--border-hover);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .stat-bar {
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            overflow: hidden;
            position: relative;
        }
        
        .stat-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 2px;
            width: 0;
            animation: fillBar 1.5s ease-out forwards;
        }
        
        @keyframes fadeInScale {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes fillBar {
            to {
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
            
            .stat-item {
                padding: 1.5rem 1rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .stats-header h2 {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        </style>
        <?php
    },
    
    'about' => 'An animated statistics section with gradient backgrounds and progress bars'
]; ?>
