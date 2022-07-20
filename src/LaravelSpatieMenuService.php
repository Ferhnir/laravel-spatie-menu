<?php

namespace Ferhnir\LaravelSpatieMenu;

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Link;

class LaravelSpatieMenuService extends Menu
{
    /**
     * @param string|null $class
     * @param string|null $parentClass
     * @param array $attrs
     * @param string|null $wrapperTag
     * @param bool $parentTag
     * @param bool $activeClassOnLink
     * @return Menu
     */
    public static function newMenu(
        string $class = null,
        string $parentClass = null,
        array $attrs = [],
        string $uniqueSubmenuID = null,
        string $wrapperTag = null,
        bool $parentTag = true,
        bool $activeClassOnLink = false
    ) : Menu
    {
        $newMenu = Menu::new();

        if ($uniqueSubmenuID) {
            $class .= ' collapse';
            $parentClass = 'nav-item';
            $attrs = [
                'id' => $uniqueSubmenuID
            ];
        }

        if ($class)
            $newMenu->addClass($class);

        if ($parentClass)
            $newMenu->addParentClass($parentClass);

        if ($attrs)
            $newMenu->setAttributes($attrs);

        if ($wrapperTag)
            $newMenu->setWrapperTag($wrapperTag);

        if (!$parentTag)
            $newMenu->withoutParentTag(false);

        if ($activeClassOnLink)
            $newMenu->setActiveClassOnLink();

        return $newMenu;
    }

    /**
     * @param string $route
     * @param string $title
     * @param string $icon
     * @param string $class
     * @param string $parentClass
     * @param array $attrs
     * @param string|null $uniqueSubmenuID
     * @return Link
     */
    public static function createLink(
        string $route,
        string $title,
        string $icon,
        string $class = 'nav-link',
        string $parentClass = 'nav-item',
        array $attrs = [],
        string $uniqueSubmenuID = null
    ) : Link
    {
        $attributes = [...$attrs];

        $submenuAttrs = [
            'data-toggle' => 'collapse',
            'aria-expanded' => 'false',
            'aria-controls' => $uniqueSubmenuID,
            'href' => '#' . $uniqueSubmenuID
        ];

        if ($uniqueSubmenuID)
            $attributes = [...$attributes ,...$submenuAttrs];


        return Link::to($route, $icon . $title)
            ->addClass($class)
            ->addParentClass($parentClass)
            ->setAttributes($attributes);
    }

    /**
     * @param array $submenuLinksArray
     * @return Menu
     */
    public static function buildSubmenuList(
        array $submenuLinksArray = []
    ) : Menu
    {
        $submenuList = self::newMenu(
            class: 'nav flex-column sub-menu',
            attrs: [
                'style' => 'list-style-type: none;'
            ]
        );

        foreach ($submenuLinksArray as $key => $submenuLink) {
            if (
                !isset($submenuLink['rule']) ||
                (!empty($submenuLink['rule']) && auth()->user()->can($submenuLink['rule']))
            ) {
                $submenuList->add(
                    self::createLink(
                        route: $submenuLink['url'],
                        title: $submenuLink['title'],
                        icon: $submenuLink['icon'],
                        class: $submenuLink['class'] ?? 'nav-link',
                        parentClass: $key == 0 ? $submenuLink['parentClass'] ?? 'nav-item' : 'nav-item',
                        attrs: $submenuLink['attrs'] ?? []
                    )
                );
            }
        }

        return $submenuList;
    }
}