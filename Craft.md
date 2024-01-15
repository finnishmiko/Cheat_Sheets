# Craft CMS

Folder:

- `cpresources` - consider this as a cache and add it to .gitignore

## Editable admin area

There are few cases where you need to temporarily open the admin area in production for editing:

1) With SEO-plugin there was a case where settings were not applied to yaml-files, but the setting could not be changed without admin edit rights.
2) To get plugin lisence key to point correct domain it needs to be saved in admin area. Key can be set with yaml-files, but then the domain in lisence server is wrong.

## Command line commands

```sh
php craft help

php craft clear-caches/all
```

F.ex. Generate security key or app_id:

```sh
php craft setup/security-key
php craft setup/app-id
```

Craft update

```sh
# Check updates
php craft update

# Update to specific version
php craft update craftcms/cms:3.5.17
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

### Two queries where second continues where first left

```twig
{# Query first four entries #}
{% set firstEntries = craft.entries().section('sectionName')
	.relatedTo(filterQuery)
	.search(search)
	.orderBy('postDate DESC')
	.limit(4).all() %}

{# Get the rest of the articles #}
{% set timeAtom = (firstEntries is defined and firstEntries|length ? (firstEntries|last.postDate|atom) : now|atom) %}

{% set secondQuery = craft.entries().section('sectionName')
	.relatedTo(filterQuery)
	.search(search)
	.postDate('<' ~ (timeAtom))
	.orderBy('postDate DESC')
	.limit(6)
%}
{% paginate secondQuery as pageInfo, secondEntries %}
	

```