<?php

namespace App\Menu;


class AdminMenu
{
    private $is_root;

    public function __construct($is_root)
    {
        $this->is_root = $is_root;
    }
    public function menuCombine()
    {
        $verticalMenuData = [
            [
                "url" => route('home'),
                "name" => "Главная",
                "icon" => "tf-icons ti ti-settings",
                "slug" => "home",
                'canDo' => true,
            ],
            [
                "name" => "Настройки",
                "icon" => "menu-icon tf-icons ti ti-settings",
                "slug" => "settings",
                'canDo' => $this->is_root,
                'submenu' => [
//                    [
//                        'url' => route('settings-roles'),
//                        'name' => 'Роли',
//                        'icon' => 'far fa-circle',
//                        'slug' => 'settings-roles',
//                        'canDo' => $this->is_root,
//                    ],
//                    [
//                        'url' => route('settings-permissions'),
//                        'name' => 'Привилегии',
//                        'icon' => 'far fa-circle',
//                        'slug' => 'settings-permissions',
//                        'canDo' => $this->is_root,
//                    ],
                    [
                        'url' => route('settings-staff'),
                        'name' => 'Сотрудники',
                        'icon' => 'far fa-circle',
                        'slug' => 'settings-staff',
                        'canDo' => $this->is_root,
                    ],
//                    [
//                        'url' => route('settings-general'),
//                        'name' => 'Системные натройки',
//                        'icon' => 'far fa-circle',
//                        'slug' => 'settings-general',
//                        'canDo' => $this->is_root,
//                    ],
                ],
            ],
        ];
        return $verticalMenuData;
    }
}
