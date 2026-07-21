<?php

namespace App\Support;

class AdminNavigation
{
    /**
     * Resolve menu items for a role and optional enabled modules.
     *
     * @param  array<int, string>|null  $modules  Null = ignore module filter (show all)
     * @return array<int, array<string, mixed>>
     */
    public static function items(?string $role = null, ?array $modules = null): array
    {
        $items = config('admin_navigation', []);

        return array_values(array_filter($items, function (array $item) use ($role, $modules) {
            if ($role !== null && ! empty($item['roles']) && ! in_array($role, $item['roles'], true)) {
                return false;
            }

            if ($modules !== null && ! empty($item['modules'])) {
                $overlap = array_intersect($item['modules'], $modules);

                if ($overlap === []) {
                    return false;
                }
            }

            return true;
        }));
    }
}
