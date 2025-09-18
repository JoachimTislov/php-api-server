# Development

[Vue template - starter kit](https://github.com/laravel/vue-starter-kit)
[budget app plan](./doc/budget-app-plan.md)

The storage folder currently only contains .gitignore files ...

## TODOs

- Use global variables for strings to avoid destructive typos
- Add Tailwindcss
- Nvim php plugins
- Setup database for development
- Fix undefined app issues

## Development setup

- Install [php](https://www.php.net/) and [composer](https://getcomposer.org/download/)

View [Laraval docs](https://laravel.com/docs/)

Use [MariaDB](https://wiki.archlinux.org/title/MariaDB) or [AUR - mysql](https://aur.archlinux.org/packages/mysql)

### Env template:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

### Cmds

**migrate:**
```php
php artisan migrate
```

## Docs

- [Laravel 12.x docs](https://laravel.com/docs/12.x)

## Tech Stack

**Backend:**
- [PHP](https://www.php.net/)
- [Laravel](https://laravel.com/)
- [MySQL](https://www.mysql.com/)/[MariaDB](https://mariadb.org/)
- [Composer](https://getcomposer.org)
- [Artisan CLI](https://laravel.com/docs/12.x/artisan)
    - View [script](./artisan) for more details
- [PHPUnit](https://phpunit.de/)
- [inertiajs](https://inertiajs.com/)

**Frontend:**
- [Vue 3](https://vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Vite](https://vitejs.dev/)
- [shadcn-vue](https://www.shadcn-vue.com/)

View [Composer lock](./composer.lock) for more details

