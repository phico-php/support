# Support

Support classes and functions for [Phico](https://github.com/phico-php/phico)

# Reference

`str()` Returns a new string instance

#### Convert json to string

```php
// optionally pass boolean as_array as the second argument
$json = str()->toJson($object, $as_array=false);
```

#### Decode json from string

```php
// optionally pass any flag constants as the second argument
$object = str()->fromJson($json, $flags = null);
```

#### Sanitise a string

```php
$clean = str()->sanitise($input);
```

#### split a string on capitals

```php
$str = str()->sanitise('ACapitalisedString);
// A Capitalised String
```

#### convert a string to camel case

```php
$str = str()->sanitise('This is Camel case);
// thisIsCamelCase
```

#### convert a string to kebab case

```php
$str = str()->sanitise('This is Kebab case);
// this-is-kebab-case
```

#### convert a string to pascal case

```php
$str = str()->sanitise('This is pascal case);
// This Is Pascal Case
```

#### convert a string to train case

```php
$str = str()->sanitise('This is train case);
// This-Is-Train-Case
```

## Issues

If you discover any bugs or issues in behaviour or performance please create an issue, and if you are able a pull request with a fix.

Please make sure to update any tests as appropriate.

For major changes, please open an issue first to discuss what you would like to change.

## License

[BSD-3-Clause](https://choosealicense.com/licenses/bsd-3-clause/)
