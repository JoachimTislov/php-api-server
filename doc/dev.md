# Development

Expected environment is Linux (Arch Linux preferred)

Starting point [Vue template - starter kit](https://github.com/laravel/vue-starter-kit)

[Budget app plan](./doc/budget-app-plan.md)

## Development setup

- Install [php](https://www.php.net/) and [composer](https://getcomposer.org/download/)
- Formatter: [pretty-php](https://github.com/lkrms/pretty-php)
- LSP: [phpactor](https://github.com/phpactor/phpactor)

### Database
Production: [MariaDB](https://wiki.archlinux.org/title/MariaDB)
Development: [MariaDB](https://wiki.archlinux.org/title/MariaDB), [Sqlite](https://www.sqlite.org/index.html) or [in memory](https://laravel.com/docs/12.x/database#in-memory-sqlite-testing)

**Important to uncomment pdo_sqlite or pdo_mysql in /etc/php/php.ini, depending on your database choice**
Failure to do so will cause Laravel to throw a "can't find driver" error

### Commands

View [Makefile](./Makefile) for more details

## Best Practices

- Use global variables for strings to avoid destructive typos

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
- [shadcn-vue](https://www.shadcn-vue.com/)
- [TypeScript](https://www.typescriptlang.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Vite](https://vitejs.dev/)

View [Composer json](./composer.json) for more details
