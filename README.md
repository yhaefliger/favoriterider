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
```sh
composer install
npm install
npm run watch
```

To use browsersync on watch run be sure to have your local host configured as the proxy configuration in ```webpack.config.js```
```
proxy: 'prestadev.local',
```

Sample vscode workspace configuration
```json
{
	"folders": [
		{
			"path": "/var/www/html/prestadev"
		}
	],
	"settings": {
		"php.executablePath": "/usr/bin/php7.3",
		"php.validate.executablePath": "/usr/bin/php7.3",
		"search.exclude": {
			"**/var/cache": true
		},
		"intelephense.environment.phpVersion": "7.3.30"
	},
	"tasks": {
		"version": "2.0.0",
		"tasks": [
			{
				"label": "Run PHPStan (favorite rider module)",
				"type": "shell",
				"command": "cd modules/favoriterider && _PS_ROOT_DIR_=/${workspaceFolder} vendor/bin/phpstan analyse --configuration=tests/phpstan/phpstan.neon",
				"problemMatcher": []
			},
			{
				"label": "Run PHP-CS-Fixer (favorite rider module)",
				"type": "shell",
				"command": "cd modules/favoriterider && vendor/bin/php-cs-fixer fix",
				"problemMatcher": []
			}
		]
	},
	"launch": {
		"version": "0.2.0",
		"configurations": [
			{
				"name": "Listen for Xdebug",
				"type": "php",
				"request": "launch",
				"port": 9003
			}
		]
	}
}
```

### Production builds
```sh
composer install --no-dev --optimize-autoloader
npm install
npm run build
```