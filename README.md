# MapperBundle  [![Build Status](https://travis-ci.com/BoShurik/mapper-bundle.svg?branch=master)](https://travis-ci.com/BoShurik/mapper-bundle)

Integrates [boshurik/mapper](https://github.com/BoShurik/mapper) to Symfony

## Usage

```bash
composer require boshurik/mapper-bundle
```

Bundle introduce two interfaces 
`BoShurik\MapperBundle\Mapper\Mapping\MappingInterface` and 
`BoShurik\MapperBundle\Mapper\Mapping\ReverseMappingInterface` 
for one-way and two-way mappings respectively

## Code generation

Code generation base on [PHP-Parser](https://github.com/nikic/PHP-Parser)

```bash
composer require nikic/php-parser
```

### Generate model

```bash
bin/console mapper:generate:model
```

### Generate mapping

```bash
bin/console mapper:generate:mapping
```