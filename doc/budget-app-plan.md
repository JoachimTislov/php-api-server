# Development plan

An open-source budget application for personal use,


## Phase 1 - MVP

1. Setup authentication/login
    - Support 2FA with email
2. Create dashboard
    - budget overview
3. User profile
    - theme (dark/light)
    - yearly and monthly income
    - expected monthly expenses
    - currency (NOK, EUR, USD)

## Features

- User authentication
    - 2FA
- User profile
    - 2FA
    - highly customizable settings
        - overview layout
        - preferred language
- CRUD operations for entities: budgets, categories and transactions (manual entries)
- Auto-categorization of transactions based on description/merchant
- Alert/notification when exceeding budget
    - Through email if preferred, otherwise just display in the app
- Export data to CSV/PDF
- Multi-currency support
- Multi-language support (Norwegian and English)
- Integration with banks (PSD2) to fetch transactions automatically
- Integration with grocery business apps to fetch transactions automatically
- Responsive design - usable on both desktop and mobile

## 2FA

- Email
- Phone number
- [Norwegian BankID](https://www.bankid.no/en/)
- [Fast integration with Signicat](https://www.signicat.com/no/blogg/hvordan-raskt-integrere-med-norsk-bankid)
- Bitwarden authenticater app
- [FIDO2](https://www.microsoft.com/en-us/security/business/security-101/what-is-fido2)
    - [Auth0](https://auth0.com/docs/secure/multi-factor-authentication)
- [OTP - One Time Password (TOTP - Time-based One-Time Password)](https://www.thalesgroup.com/en/markets/digital-identity-and-security/technology/otp)

## Ui/Frontend - design/layout

- Landing page
- Login and Register
- Home/Dashboard
    - Spending visualization - Graphs/Charts
        - progress bars
    - Overview of total amount of money in the bank
    - Optionally include debt - Display diff in accordance with the budget
- Budget view - CRUD operations
    - Add manual entry (transaction)
- Profile

## Db

Try to only store user login information, budget and manual entries.

Data from bank and other third-party services should not be stored in the DB. And rather retrieved on demand.

### tables

- User
    - name/username/email
    - password
    - yearly and monthly income
    - expected monthly expenses
    - currency (NOK, EUR, USD)
    - preferred language (nb, en)
- Budget
    - name
    - time period (monthly, yearly)
    - total amount
    - currency (determind by owner's settings)
- Category
    - name
    - expected monthly expense
    - type ?
- Transaction, consider adding a mapping table for common transactions, to avoid duplicates
    - amount
    - date
    - description (nullable)
    - merchant (nullable)
    - type (expense, income)

### Relations

- User - one to many - Budgets
- Budget - many to many - Categories
- Category - one to many - Transactions

## Integrations

In order to automate the tedious task of adding transactions manually, we need to integrate with banks and other third-party services.

### Banks

- [SpareBank 1](https://developer.sparebank1.no/#/documentation/gettingstarted)
    - [PSD2 og Open Banking](https://www.sor.no/felles/info/open-banking/)
- [Nordea](https://developer.nordeaopenbanking.com/apiReference/premium?api=Authentication%20API&version=2)
- [DNB](https://developer.dnb.no/)
- [Bank Norwegian](https://developer.banknorwegian.com/documentation/openbanking/index.html)

Consider using [Aritma's](https://www.aritma.com/no/produkter/open-finance-platform) [API](https://developer.aritma.com/), since it is integrated with over 130 banks in Norway. Its kind of an overkill for this project. Both Nordea and SpareBank 1 have good developer APIs.

### Grocery business apps

- [Rema](https://www.rema.no/rema-appen/)
- [Coop-member](https://secure.coop.no/bli-medlem)
    - [Coop-mega](https://www.coop.no/medlem/medlemsfordeler/coop-appen)
    - [Obs](https://www.obs.no/nyheter/coop-medlemsapp-alle-tilbud-samlet-pa-ett-sted)
- [Trumf](https://www.trumf.no/bli-medlem)
    - [Kiwi](https://kiwi.no/fordeler/kiwi-pluss-appen)
    - [Meny](https://meny.no/kundefordeler/meny-app/)
    - [Spar](https://spar.no/full-oversikt-med-spar-appen)
    - [Joker](https://joker.no/bli-medlem)

## Similar applications

- [Endute](https://endute.com/)
- [Spiir](https://spiir.com/)

## Inspiration

- [Tryggmat](https://www.tryggmat.app/)
- [conta](https://conta.no/)

## Resources

- Read about [Finanstilsynets regulations](https://www.finanstilsynet.no/regelverk/), "Bankar" and "Opplysningsfullmektigar"
- [Open Banking APIs](https://stripe.com/en-no/resources/more/open-banking-apis-explained-what-they-are-and-how-they-work)
- PSD2
    - [European central bank](https://www.ecb.europa.eu/press/intro/mip-online/2018/html/1803_revisedpsd.en.html)
    - [Wikipedia](https://en.wikipedia.org/wiki/Payment_Services_Directive)
    - [finansnorge](https://www.finansnorge.no/tema/avtaler-normer-og-regler/psd2/)
- [AISP](https://compello.com/ordbok/aisp)
- [PISP](https://compello.com/ordbok/pisp)
