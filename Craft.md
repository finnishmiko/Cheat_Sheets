# Craft CMS

## Command line commands

```sh
php craft help
```

F.ex. Generate security key or app_id:

```sh
php craft setup/security-key
php craft setup/app-id
```
Commands for migration and project config after staging update:

```sh
./craft migrate/all
./craft project-config/apply --force
```

Rebuild search indexes:

```sh
php craft resave/entries --update-search-index
```
