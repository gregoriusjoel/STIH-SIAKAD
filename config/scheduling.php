<?php

/**
 * Scheduling Configuration
 * 
 * Defines rules for automatic jadwal (schedule) generation,
 * including room category matching and constraint priorities.
 */

return [
    /**
     * Room Category Matching Rules
     * 
     * Maps course types (tipe mata kuliah) to allowed room categories (kategori ruangan)
     * This is the PRIMARY constraint when selecting rooms for course scheduling
     */
    'room_matching' => [
        // Teori (Theory courses) → Only classroom category
        'teori' => [
            'categories' => ['kelas'],
            'priority' => 100,
            'description' => 'Mata kuliah teori hanya menggunakan ruangan kelas',
        ],
        
        // Praktikum (Practice/Lab courses) → Lab or Practice rooms
        'praktikum' => [
            'categories' => ['praktikum', 'laboratorium'],
            'priority' => 100,
            'description' => 'Mata kuliah praktikum menggunakan ruangan praktikum atau laboratorium',
        ],
        
        // Sidang (Thesis presentation) → Presentation halls
        'sidang' => [
            'categories' => ['sidang', 'kelas'],
            'priority' => 100,
            'description' => 'Sidang menggunakan ruangan sidang atau kelas',
        ],
        
        // Lab (Laboratory) → Lab rooms only
        'lab' => [
            'categories' => ['laboratorium'],
            'priority' => 100,
            'description' => 'Laboratorium hanya menggunakan ruangan laboratorium',
        ],
    ],

    /**
     * Constraint Priority (in order of importance)
     * 
     * When scheduling, apply constraints in this order:
     * 1. Room category (HIGHEST PRIORITY)
     * 2. Dosen availability
     * 3. Schedule conflicts
     * 4. Room capacity
     */
    'constraint_priority' => [
        'room_category' => 1,
        'dosen_availability' => 2,
        'schedule_conflict' => 3,
        'room_capacity' => 4,
    ],

    /**
     * Fallback Strategy
     * 
     * When no room with correct category is available
     */
    'fallback' => [
        // Allow fallback when no matching category available
        'enabled' => true,
        
        // Log all fallback cases for monitoring
        'log_fallback' => true,
        
        // Fallback strategy: 'any' = any active room, 'preferred' = prefer large rooms
        'strategy' => 'any',
        
        // List of room categories to try as fallback (in order)
        'fallback_order' => ['kelas', 'praktikum', 'laboratorium', 'sidang'],
    ],

    /**
     * Capacity Validation
     * 
     * Minimum capacity ratio: room_capacity / class_capacity >= ratio
     */
    'capacity' => [
        'validate' => true,
        'min_ratio' => 1.1,  // Room must be at least 10% larger than class
        'allow_exact_fit' => true,  // Allow room size == class size
    ],

    /**
     * Preview Mode
     * 
     * Simulate scheduling without saving to database
     */
    'preview_mode' => [
        'enabled' => true,
        'return_conflicts' => true,
        'return_fallbacks' => true,
    ],

    /**
     * Batch Processing
     * 
     * For large schedules, use queue jobs
     */
    'batch_processing' => [
        'enabled' => true,
        'threshold' => 50,  // Use queue if > 50 classes to schedule
        'queue' => 'default',
        'timeout' => 300,
    ],

    /**
     * Structured Logging
     * 
     * Log scheduling events in JSON format for monitoring/debugging
     */
    'logging' => [
        'channel' => 'scheduling',
        'structured' => true,  // Use JSON format
        'level' => 'info',
        'track_stats' => true,
        'stats_interval' => 10,  // Log stats every N operations
    ],

    /**
     * Room Selection Algorithm
     * 
     * Strategy for choosing among multiple eligible rooms:
     * - 'random': Random selection (for distribution)
     * - 'first_available': Pick first available room
     * - 'least_used': Pick room with least scheduled classes
     * - 'best_fit': Pick room closest to required capacity
     */
    'room_selection_algorithm' => 'least_used',

    /**
     * Transaction Safety
     * 
     * Wrap schedule generation in database transactions
     */
    'transactions' => [
        'enabled' => true,
        'isolation_level' => 'READ_COMMITTED',
    ],
];
