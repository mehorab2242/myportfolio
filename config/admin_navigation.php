<?php

/**
 * Admin sidebar navigation.
 *
 * Scalable for:
 * - role-based visibility (`roles`)
 * - module/profession filters (`modules`) — leave empty = always available
 * - future API-driven menus (merge/override via AdminNavigation)
 */
return [
    [
        'key' => 'dashboard',
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'icon' => 'layout-dashboard',
        'roles' => ['admin', 'user'],
        'modules' => [],
    ],
    [
        'key' => 'profile',
        'label' => 'Profile',
        'route' => 'admin.profile',
        'icon' => 'user',
        'roles' => ['admin', 'user'],
        'modules' => [],
    ],
    [
        'key' => 'sections',
        'label' => 'Sections',
        'route' => 'admin.sections',
        'icon' => 'layout-template',
        'roles' => ['admin', 'user'],
        'modules' => ['portfolio'],
    ],
    [
        'key' => 'skills',
        'label' => 'Skills',
        'route' => 'admin.skills',
        'icon' => 'sparkles',
        'roles' => ['admin', 'user'],
        'modules' => ['portfolio'],
    ],
    [
        'key' => 'projects',
        'label' => 'Projects',
        'route' => 'admin.projects',
        'icon' => 'folder-kanban',
        'roles' => ['admin', 'user'],
        'modules' => ['portfolio'],
    ],
    [
        'key' => 'experience',
        'label' => 'Experience',
        'route' => 'admin.experience',
        'icon' => 'briefcase',
        'roles' => ['admin', 'user'],
        'modules' => ['portfolio'],
    ],
    [
        'key' => 'education',
        'label' => 'Education',
        'route' => 'admin.education',
        'icon' => 'graduation-cap',
        'roles' => ['admin', 'user'],
        'modules' => ['portfolio'],
    ],
    [
        'key' => 'theme',
        'label' => 'Theme',
        'route' => 'admin.theme',
        'icon' => 'palette',
        'roles' => ['admin', 'user'],
        'modules' => ['portfolio'],
    ],
    [
        'key' => 'settings',
        'label' => 'Settings',
        'route' => 'admin.settings',
        'icon' => 'settings',
        'roles' => ['admin', 'user'],
        'modules' => [],
    ],
];
