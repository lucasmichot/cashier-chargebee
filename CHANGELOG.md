### v1.3.0-beta.1 (2025-10-27)
* * *

- Added `Entitlement` and related `Feature` support.

### v1.2.0
* * * 
- Added atrisan command to automatically add the webhook in Chargebee dashboard. 

### v1.1.0 (2025-05-19)
* * * 

- Simplify GitHub Actions using cleaner matrix syntax, #17.
- Add an .editorconfig for consistent editor formatting, #18.
- Normalize composer.json using ergebnis/composer-normalize, #19.
- Remove unused StyleCI configuration file, #20.
- Add Laravel Pint configuration and format codebase, #21.
- Bumped up the `chargebee-php` version to `v4.3.0` and updated the testcase accordingly.
- Added the script `composer lint` to lint the project using pint.
- Added a dev dependencies `laravel/pint`.

### v1.0.1 (2025-05-16)
- Bug Fix: Prevented ChargebeeClient instantiation if required config values (`CHARGEBEE_SITE` or `CHARGEBEE_API_KEY`) are missing, avoiding exceptions during package discovery or autoload.

### v1.0.0 (2025-04-16)

- Upgraded to `chargebee-php` version `v4.x.x` stable.
- removed minimum stability tag from the composer.

### v1.0.0-beta.2 (2025-04-10)

- Upgraded to `chargebee-php` version `v4.x.x`.
- Migrated codebase to be compatible with `chargebee-php v4.x.x`.
- Updated `minimum-stability` to `beta`.
