### lib-changelog-parser

Parses and dumps `CHANGELOG.md` file written according to https://keepachangelog.com/en/1.0.0/ specifications for programmatic manipulation.

## Parsing:
Suppose you have a changelog:
```markdown
# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 1.0.1
### Added
- `php-generator:symfony-bundle` added to `phar`

## 1.0.0
### Added
- support of something.
- another feature added.
### Removed
- In particular class some method was removed.
### Changed
- Changed how things are parsed in parser.
```

You can now parse it:
```php
$parser = new ChangelogParser(new ValueExtractor(new ParserConfiguration()));

$changelog = $parser->parse(file_get_contents($pathToChangelog));
print_r($changelog);
```

Internal structure of parsed result:
```text
Changelog Object
    [header:Changelog:private] => # Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).
    [versions] => Array
        [0] => VersionInfo Object
            [version] => 1.0.1
            [date] => 
            [changeEntries] => Array
                [0] => ChangeEntry Object
                    [changeType] => Added
                    [changeDetails] => Array
                        [0] => ChangeDetails Object
                            [description] => `php-generator:symfony-bundle` added to `phar`
        [1] => VersionInfo Object
            [version] => 1.0.0
            [date] => 
            [changeEntries] => Array
                [0] => ChangeEntry Object
                    [changeType] => Added
                    [changeDetails] => Array
                        [0] => ChangeDetails Object
                            [description] => support of something.
                        [1] => ChangeDetails Object
                            [description] => another feature added.
                [1] => ChangeEntry Object
                    [changeType] => Removed
                    [changeDetails] => Array
                        [0] => ChangeDetails Object
                            [description] => In particular class some method was removed.
                [2] => ChangeEntry Object
                    [changeType] => Changed
                    [changeDetails] => Array
                        [0] => ChangeDetails Object
                            [description] => Changed how things are parsed in parser.
```
