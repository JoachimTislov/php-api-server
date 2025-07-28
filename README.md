# PHP API server

The server is designed as a multi-purpose environment, intended to support more than one application or service. It is expected to be hosted on a web hosting platform, which imposes certain software limitations; consequently, PHP has been selected as the primary development language to ensure compatibility. The main application deployed will be a personal budget app, supplemented by additional minor API integrations for other functionalities. This usage scope may evolve over time to accommodate future requirements.

Note: This application currently integrates — or will integrate — with the SpareBank 1 Personal Client API for individual experimentation. It is not intended for public or third-party use at this stage.

Public use may be considered in the future through enrollment as a Third-Party Provider (TPP) via [SpareBank 1 Open Banking Developer Enrollment](https://openbanking.sor.no/developer/enrollment), in accordance with PSD2 regulations.

This application is developed for the Norwegian users, utilizing Norwegian banking APIs and complying with applicable regulations. Expansion to other countries may be considered in the future.

## Goal and Objectives

- Gain practical experience working with real-world APIs, relevant laws, and regulatory requirements
- Develop proficiency in PHP, Laravel, Tailwind CSS, and related technologies
- Achieve a deeper understanding of software development practices including testing, architecture, and AI integration
- Explore and establish best practices for leveraging AI in the development process
- Build a successful personal budget application with potential to support third-party users
- Ultimately deliver a fully-featured project suitable for commercial deployment

## API Usage

This application integrates with the SpareBank 1 Personal Client API exclusively to retrieve and display transaction data associated with individual user accounts. The application does not initiate, authorize, or perform any payment transactions or financial transfers on behalf of users.

All API interactions are limited to read-only access for the purpose of budget tracking and financial overview. No operations involving fund transfers, payments, or account modifications are conducted through this application.

## Links

- [Sparebank Open APIs](https://www.sparebank1.no/nb/bank/bedrift/open-api.html)
- [Openbanking Developer Enrollment](https://openbanking.sor.no/developer/enrollment)
- [Nordea API market](https://www.nordea.no/bedrift/vare-produkter/nettbank-og-konto/apimarket.html#faq=Vanlige-sporsmal-om-API-er+462745)

**Legal**

- [Norwegian Financial Supervisory Authority (Finanstilsynet)](https://www.finanstilsynet.no/en/)
- [Norwegian Personal Data Act (Personopplysningsloven)](https://lovdata.no/dokument/NL/lov/2018-06-15-38)
- [EU Payment Services Directive 2 (PSD2)](https://ec.europa.eu/info/business-economy-euro/banking-and-finance/consumer-finance-and-payments/payment-services/payment-services-directive-psd2_en)

## TODOs

- Use global variables for strings to avoid destructive typos
- Add Tailwindcss
- Nvim php plugins
- Setup database for development
- Fix undefined app issues

## Development setup

Install [php](https://www.php.net/) and [composer](https://getcomposer.org/download/)

[Laraval docs](https://laravel.com/docs/)

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

### Migration cmd

```php
php artisan migrate
```

