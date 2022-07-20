# Menu Generator for spatie/laravel-menu
This generator provides a support for spatie/laravel-menu with php8

# Example:
### Links:
```php
#serviceProvider:

view()->composer(['your.blade'], function () {
    app('your-instance-name')->add(
        LaravelSpatieMenuService::createLink(
            route: route('buildings.index'),
            title: 'Buildings',
            icon: '<i class="menu-icon fas fa-file-alt fa-3x"></i>'
        )
    );
});
```

### Submenus:
```php
view()->composer(['your.blade'], function () {

    $uniqueSubmenuID = 'ExampleID';

    //CREATE SUBMENU MAIN LINK
    $linkNew = LaravelSpatieMenuService::createLink(
        route: '#',
        title: '<span class="menu-label">ExampleLink</span>',
        icon: '<i class="menu-icon far fa-sitemap fa-3x"></i>',
        uniqueSubmenuID: $uniqueSubmenuID
    );

    //CREATE SUBMENU OBJECT
    //Create submenuList for submenuObj
    $submenuList = LaravelSpatieMenuService::buildSubmenuList(
        submenuLinksArray: [
            [
                'title' => 'Show All',
                'icon' => '<i class="far fa-eye"></i>',
                'url' => route('example.index'),
                'rule' => ['browse-example']
            ], [
                'title' => 'Add Company',
                'icon' => '<i class="far fa-plus"></i>',
                'url' => route('example.create'),
                'rule' => ['add-example']
            ], [
                'title' => 'Archive',
                'icon' => '<i class="far fa-cabinet-filing"></i>',
                'url' => route('example.archive'),
                'rule' => ['delete-example']
            ],
            ...
        ]
    );

    //Create submenuObj
    $submenuObj = LaravelSpatieMenuService::newMenu(
        uniqueSubmenuID: $uniqueSubmenuID,
        wrapperTag: 'div',
        parentTag: false,
        activeClassOnLink: true
    );

    //Attach submenuObj to menu instance
    app('your-instance-name')->submenu(
        $linkNew,
        $submenuObj->add($submenuList)
    );

});
```

### Blade 'your.blade'
```php 
{!! app('your-instance-name') !!}
```