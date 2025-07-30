<?php return [
    'render' => function($prop) {
        $title = $prop['title'] ?? 'Trusted by Leading Companies';
        $logos = $prop['logos'] ?? [
            ['name' => 'TechCorp', 'url' => 'img/cat01.jpg'],
            ['name' => 'StartupX', 'url' => 'img/cat01.jpg'],
            ['name' => 'DevStudio', 'url' => 'img/cat01.jpg'],
            ['name' => 'WebFlow', 'url' => 'img/cat01.jpg'],
            ['name' => 'CodeLab', 'url' => 'img/cat01.jpg'],
            ['name' => 'AppCraft', 'url' => 'img/cat01.jpg']
        ];
        
        ?>
        <div class="brands-section">
            <div class="brands-container">
                <h3 class="brands-title"><?= htmlspecialchars($title) ?></h3>
                <div class="brands-grid">
                    <?php foreach($logos as $index => $logo): ?>
                    <div class="brand-item" style="animation-delay: <?= $index * 0.1 ?>s">
                        <img src="<?= htmlspecialchars($logo['url']) ?>" alt="<?= htmlspecialchars($logo['name']) ?>" />
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <style>
        .brands-section {
            padding: 3rem 0;
            background: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        
        .brands-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            text-align: center;
        }
        
        .brands-title {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .brands-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            align-items: center;
        }
        
        .brand-item {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            animation: fadeInScale 0.6s ease-out forwards;
            opacity: 0;
            transform: scale(0.8);
        }
        
        .brand-item:hover {
            transform: scale(1.05);
            background: var(--surface-hover);
        }
        
        .brand-item img {
            max-width: 100%;
            height: auto;
            opacity: 0.6;
            transition: opacity 0.3s ease;
            filter: grayscale(100%);
        }
        
        .brand-item:hover img {
            opacity: 1;
            filter: grayscale(0%);
        }
        
        @keyframes fadeInScale {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @media (max-width: 768px) {
            .brands-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .brands-section {
                padding: 2rem 0;
            }
        }
        </style>
        <?php
        
    },
    
    'about' => 'A brand/logo showcase section with hover effects and animations'
]; ?>
