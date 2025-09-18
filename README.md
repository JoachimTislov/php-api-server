# PHP API server

Designed as a multi-purpose environment, intended to support multiple services. PHP has been selected as the primary development language to ensure compatibility with the web hosting platform. The complete application will be mainly a personal budget app supplemented by additional minor API integrations for other functionalities. This usage scope may evolve over time to accommodate future requirements.

Note: This application is not intended for public or third-party use at this stage, currently only for individual experimentation. Public use may be considered in the future through enrollment as a Third-Party Provider (TPP) via [SpareBank 1 Open Banking Developer Enrollment](https://openbanking.sor.no/developer/enrollment), in accordance with PSD2 regulations.

This application is developed for the Norwegian users, by utilizing Norwegian banking APIs and complying with applicable regulations. Expansion to other countries may be considered in the future.

## Goal and Objectives

- Gain practical experience working with real-world APIs, relevant laws, and regulatory requirements
- Develop proficiency in PHP, Laravel, Tailwind CSS, and related technologies
- Achieve a deeper understanding of software development practices including testing, architecture, and AI integration
- Explore and establish best practices for leveraging AI in the development process
- Build a successful personal budget application with potential to support third-party users
- Ultimately deliver a fully-featured project suitable for commercial deployment

## API Usage

This application **will in the near future** integrate with the SpareBank 1 Personal Client API to retrieve and display transaction data associated with individual user accounts. All API interactions are limited to read-only access for the purpose of budget tracking and financial overview. No operations involving fund transfers, payments, or account modifications are conducted through this application. It does not initiate, authorize, or perform any payment transactions or financial transfers on behalf of users.

## Developers

View [developers overview](./doc/dev.md)

## Links

- [Openbanking Developer Enrollment](https://openbanking.sor.no/developer/enrollment)

**APIs**

- [Sparebank Open APIs](https://www.sparebank1.no/nb/bank/bedrift/open-api.html)
- [Nordea API market](https://www.nordea.no/bedrift/vare-produkter/nettbank-og-konto/apimarket.html#faq=Vanlige-sporsmal-om-API-er+462745)

**Legal**

- [Norwegian Financial Supervisory Authority (Finanstilsynet)](https://www.finanstilsynet.no/en/)
- [Norwegian Personal Data Act (Personopplysningsloven)](https://lovdata.no/dokument/NL/lov/2018-06-15-38)
- [EU Payment Services Directive 2 (PSD2)](https://ec.europa.eu/info/business-economy-euro/banking-and-finance/consumer-finance-and-payments/payment-services/payment-services-directive-psd2_en)
