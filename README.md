# Test PrestaShop module

Test PrestaShop module developement (V1.7.7)

## Admin
Admin page with ability to create new models (riders)
Use of admin grid for data display / creation 

## Front
Frontend page with ability for visitors to vote for their favorite rider (count votes)
widget or hook ? to integrate the rendered component on any PrestaShop cms page

## Installation

PHP 7.3 / Node.js v12

### Local dev
```
composer install
npm install
npm run watch
```

### Production builds
```
composer install --no-dev --optimize-autoloader
npm run build
```