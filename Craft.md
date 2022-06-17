# Craft CMS

Folder:

- `cpresources` - consider this as a cache and add it to .gitignore

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
# yaml --> database
./craft project-config/apply --force
```

Rebuild search indexes:

```sh
php craft resave/entries --update-search-index --section section_name
```

Rebuild yaml-files from database:

```sh
# database --> yaml
php craft project-config/rebuild
```

## Snippets

Get current entry's top level ancestor:

```twig
{% set rootEntry = entry.getAncestors().first() %}
```
