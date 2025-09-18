# Development plan

An open-source budget application for personal use,

## Steps

1. Setup authentication/login
    - 2FA - email, sms
2. Create home page

## Layers

Each layer of the application should be isolated and do certain tasks... 

### UI/Frontend - design/layout

#### Pages

- Landing
- Login
- Home (overview of total amount of money in the bank - optionally include debt. Display diff in accordance with the budget)
- Budget view - CRUD operations
    - Add manual entry (transaction)
- Profile

### API

Multi

### DB

Primarly store user login information and as little numbers related to the bank a possible.

#### Concerns

What has to be stored in DB?
Which tables are needed?

### Integrations

To enable automatic calculations without user input are integrations with their bank(s) required.

#### Banks

- SpareBank 1
- Nordea


## Similar applications

- [Endute](https://endute.com/)
- [Spiir](https://spiir.com/)

