<?php return [
    'render' => function($prop) {
        // Generate unique ID for this table instance
        $tableId = $prop['id'] ?? 'data-grid-' . uniqid();
        $data = $prop['items'] ?? [];
        $columns = $prop['columns'] ?? null;
        $options = $prop['options'] ?? [];
        
        // Auto-generate columns if not provided
        if (!$columns && !empty($data)) {
            $columns = [];
            $firstItem = reset($data);
            if (is_array($firstItem)) {
                foreach (array_keys($firstItem) as $key) {
                    $columns[] = [
                        'field' => $key,
                        'headerName' => ucfirst(str_replace('_', ' ', $key)),
                        'sortable' => true,
                        'filter' => true,
                        'resizable' => true
                    ];
                }
            }
        }
        
        // Default grid options (Community edition compatible)
        $defaultOptions = [
            'pagination' => true,
            'paginationPageSize' => 20,
            'suppressMenuHide' => true,
            'animateRows' => true,
            'defaultColDef' => [
                'sortable' => true,
                'filter' => true,
                'resizable' => true,
                'minWidth' => 100
            ]
        ];
        
        $gridOptions = array_merge($defaultOptions, $options);
        $gridOptions['columnDefs'] = $columns;
        $gridOptions['rowData'] = $data;
        
        ?>
        <!-- ag-Grid Data Table Component -->
        <div class="ag-grid-container" style="margin: 1rem 0;">
            <div class="ag-grid-toolbar" style="
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
                padding: 0.75rem;
                background: var(--surface-elevated);
                border: 1px solid var(--border);
                border-radius: 0.5rem 0.5rem 0 0;
                border-bottom: none;
            ">
                <div style="display: flex; gap: 1rem; align-items: center; flex: 1;">
                    <span style="color: var(--text-secondary); font-size: 0.875rem; font-weight: 500;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 0.25rem;">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <line x1="9" y1="9" x2="15" y2="9"/>
                            <line x1="9" y1="15" x2="15" y2="15"/>
                        </svg>
                        <?= count($data) ?> rows
                    </span>
                    
                    <!-- Search Input -->
                    <div style="position: relative; display: flex; align-items: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" style="position: absolute; left: 0.75rem; z-index: 1;">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        <input 
                            type="text" 
                            id="<?= $tableId ?>-search"
                            placeholder="Search all columns..."
                            style="
                                padding: 0.5rem 0.75rem 0.5rem 2.5rem;
                                border: 1px solid var(--border);
                                border-radius: 0.375rem;
                                background: var(--surface);
                                color: var(--text-primary);
                                font-size: 0.875rem;
                                width: 250px;
                                transition: all 0.2s ease;
                            "
                            onFocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 2px var(--primary-light)';"
                            onBlur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';"
                        />
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button id="<?= $tableId ?>-export-csv" style="
                        padding: 0.5rem 0.75rem;
                        border: 1px solid var(--border);
                        background: var(--surface);
                        color: var(--text-secondary);
                        border-radius: 0.375rem;
                        cursor: pointer;
                        font-size: 0.75rem;
                        transition: all 0.2s ease;
                    "
                    onmouseover="this.style.background='var(--primary)'; this.style.color='white'; this.style.borderColor='var(--primary)';"
                    onmouseout="this.style.background='var(--surface)'; this.style.color='var(--text-secondary)'; this.style.borderColor='var(--border)';">
                        📊 Export CSV
                    </button>
                    <button id="<?= $tableId ?>-clear-filters" style="
                        padding: 0.5rem 0.75rem;
                        border: 1px solid var(--border);
                        background: var(--surface);
                        color: var(--text-secondary);
                        border-radius: 0.375rem;
                        cursor: pointer;
                        font-size: 0.75rem;
                        transition: all 0.2s ease;
                    "
                    onmouseover="this.style.background='var(--surface-elevated)'; this.style.borderColor='var(--primary)';"
                    onmouseout="this.style.background='var(--surface)'; this.style.borderColor='var(--border)';">
                        🔄 Clear Filters
                    </button>
                </div>
            </div>
            
            <div id="<?= $tableId ?>" class="ag-theme-alpine-dark" style="
                height: <?= $prop['height'] ?? '400px' ?>;
                width: 100%;
                border: 1px solid var(--border);
                border-radius: 0 0 0.5rem 0.5rem;
                overflow: hidden;
            "></div>
        </div>

        <!-- ag-Grid Theme Override Styles -->
        <style>
        .ag-theme-alpine-dark {
            --ag-background-color: var(--surface);
            --ag-foreground-color: var(--text-primary);
            --ag-border-color: var(--border);
            --ag-secondary-border-color: var(--border);
            --ag-header-background-color: var(--surface-elevated);
            --ag-header-foreground-color: var(--text-primary);
            --ag-odd-row-background-color: var(--surface);
            --ag-even-row-background-color: var(--surface-elevated);
            --ag-row-hover-color: var(--surface-hover);
            --ag-selected-row-background-color: var(--primary-light);
            --ag-range-selection-background-color: var(--primary-light);
            --ag-range-selection-border-color: var(--primary);
            --ag-input-focus-border-color: var(--primary);
            --ag-minier-foreground-color: var(--text-secondary);
            --ag-subtle-text-color: var(--text-secondary);
            --ag-disabled-foreground-color: var(--text-muted);
        }
        
        .ag-theme-alpine-dark .ag-header-cell-label {
            font-weight: 600;
        }
        
        .ag-theme-alpine-dark .ag-cell {
            border-right: 1px solid var(--border);
        }
        
        .ag-theme-alpine-dark .ag-header-cell {
            border-right: 1px solid var(--border);
        }
        
        .ag-theme-alpine-dark .ag-paging-panel {
            border-top: 1px solid var(--border);
            background: var(--surface-elevated);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .ag-grid-toolbar {
                flex-direction: column !important;
                gap: 0.75rem !important;
                align-items: stretch !important;
            }
            
            .ag-grid-toolbar > div {
                justify-content: center !important;
            }
        }
        </style>

        <script>
        if (typeof agGrid === 'undefined') {
            const agGridCSS = document.createElement('link');
            agGridCSS.rel = 'stylesheet';
            agGridCSS.href = 'js/ag-grid/ag-grid.css';
            document.head.appendChild(agGridCSS);
            
            const agGridThemeCSS = document.createElement('link');
            agGridThemeCSS.rel = 'stylesheet';
            agGridThemeCSS.href = 'js/ag-grid/ag-theme-alpine.css';
            document.head.appendChild(agGridThemeCSS);
            
            const agGridScript = document.createElement('script');
            agGridScript.src = 'js/ag-grid/ag-grid-community.min.js';
            agGridScript.onload = function() {
                initializeGrid_<?= $tableId ?>();
            };
            document.head.appendChild(agGridScript);
        } else {
            initializeGrid_<?= $tableId ?>();
        }
        
        function initializeGrid_<?= $tableId ?>() {
            const gridOptions = <?= json_encode($gridOptions, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            
            // Enhanced column definitions with better formatting
            gridOptions.columnDefs = gridOptions.columnDefs.map(col => {
                // Add value formatters based on data type
                if (col.field && gridOptions.rowData.length > 0) {
                    const sampleValue = gridOptions.rowData[0][col.field];
                    
                    if (typeof sampleValue === 'number') {
                        col.type = 'numericColumn';
                        col.cellClass = 'ag-right-aligned-cell';
                        col.valueFormatter = params => {
                            if (params.value != null) {
                                return new Intl.NumberFormat().format(params.value);
                            }
                            return '';
                        };
                    } else if (typeof sampleValue === 'string' && !isNaN(Date.parse(sampleValue)) && sampleValue.includes('-')) {
                        col.valueFormatter = params => {
                            if (params.value) {
                                const date = new Date(params.value);
                                return date.toLocaleDateString();
                            }
                            return '';
                        };
                    } else if (typeof sampleValue === 'boolean') {
                        col.cellRenderer = params => {
                            return params.value ? 
                                '<span style="color: #10b981;">✓ Yes</span>' : 
                                '<span style="color: #ef4444;">✗ No</span>';
                        };
                    }
                }
                
                return col;
            });
            
            // Initialize the grid
            const gridDiv = document.querySelector('#<?= $tableId ?>');
            if (gridDiv && window.agGrid) {
                const gridApi = agGrid.createGrid(gridDiv, gridOptions);
                
                // Toolbar button handlers
                document.getElementById('<?= $tableId ?>-export-csv')?.addEventListener('click', () => {
                    gridApi.exportDataAsCsv({
                        fileName: 'data-export-' + new Date().toISOString().split('T')[0] + '.csv'
                    });
                });
                
                document.getElementById('<?= $tableId ?>-clear-filters')?.addEventListener('click', () => {
                    gridApi.setFilterModel(null);
                    if (gridApi.setQuickFilter) {
                        gridApi.setQuickFilter('');
                    }
                    // Clear search input
                    const searchInput = document.getElementById('<?= $tableId ?>-search');
                    if (searchInput) {
                        searchInput.value = '';
                    }
                });
                
                // Auto-size columns on first data render
                gridApi.addEventListener('firstDataRendered', () => {
                    gridApi.autoSizeAllColumns();
                });
                
                // Connect search input to quick filter
                const searchInput = document.getElementById('<?= $tableId ?>-search');
                if (searchInput) {
                    searchInput.addEventListener('input', (e) => {
                        const filterText = e.target.value;
                        if (gridApi.setQuickFilter) {
                            gridApi.setQuickFilter(filterText);
                        }
                    });
                }
                
                // Store grid API for external access
                window['gridApi_<?= $tableId ?>'] = gridApi;
                
                console.log('ag-Grid initialized for <?= $tableId ?> with', gridOptions.rowData.length, 'rows');
            }
        }
        </script>
        <?php
    },
    
    'about' => 'A powerful data grid component powered by ag-Grid with sorting, filtering, pagination, and export capabilities'
];