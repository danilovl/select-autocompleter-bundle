[![phpunit](https://github.com/danilovl/select-autocompleter-bundle/actions/workflows/phpunit.yml/badge.svg)](https://github.com/danilovl/select-autocompleter-bundle/actions/workflows/phpunit.yml)
[![downloads](https://img.shields.io/packagist/dt/danilovl/select-autocompleter-bundle)](https://packagist.org/packages/danilovl/select-autocompleter-bundle)
[![latest Stable Version](https://img.shields.io/packagist/v/danilovl/select-autocompleter-bundle)](https://packagist.org/packages/danilovl/select-autocompleter-bundle)
[![license](https://img.shields.io/packagist/l/danilovl/select-autocompleter-bundle)](https://packagist.org/packages/danilovl/select-autocompleter-bundle)

# SelectAutocompleterBundle #

## About ##

This Symfony bundle enables the popular [Select2](https://select2.github.io/) component to be used as a drop-in replacement for standard fields in a Symfony form.

The main feature of this bundle is that the list of choices is retrieved via a remote AJAX call.

![Alt text](/screenshot/autocompleter.gif?raw=true "Autocompleter example")

### Requirements

* PHP 8.5 or higher
* Symfony 8.0 or higher

### 1. Installation

Install `danilovl/select-autocompleter-bundle` package by Composer:

``` bash
composer require danilovl/select-autocompleter-bundle
```

``` php
<?php
// config/bundles.php

return [
    // ...
    Danilovl\SelectAutocompleterBundle\SelectAutocompleterBundle::class => ['all' => true]
];
```

### 2. Configuration

After installing the bundle, add this route to your routing configuration.

``` yaml
# config/routing.yaml

_danilovl_select_autocomopleter:
  resource: "@SelectAutocompleterBundle/Resources/config/routing.yaml"
  prefix:   /select-autocomplete
```

System default options for all autocompleters, which will be used when necessary.

```yaml
# danilovl/select-autocompleter-bundle/src/Resources/config/default.yaml

...
default:
  id_property: 'id'
  property: 'name'
  property_search_type: 'any'
  image_result_width: '100px'
  image_selection_width: '18px'
  widget: 'select2_v4'
  root_alias: 'e'
  limit: 10
  base_template: '@SelectAutocompleter/Form/versions.html.twig'
  role_prefix: 'ROLE_'
  select_option:
    delay: 1000
    theme: 'default'
    language: 'auto'
    width: 'resolve'
    amd_base: './'
    amd_language_base: './i18n/'
    cache: false
  cdn:
    auti: false
    script: 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js'
    link: 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css'
    language: 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/%%language%%.min.js'
  security:
    public_access: false
    voter: 'danilovl.select_autocompleter.voter.default'
    condition: 'and'
    role: []
  route:
    name: 'danilovl_select_autocomplete'
    parameters: []
    extra: []
```

List of available options that you can modify in your project.

These options will be applied to all autocompleters. For example:

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    widget: 'select2_v4'
    manager: null
    id_property: 'id'
    root_alias: 'e'
    property: 'name'
    property_search_type: 'any'
    image: 'image'
    image_result_width: '100px'
    image_selection_width: '18px'
    limit: 10
    base_template: '@SelectAutocompleter/Form/versions.html.twig'  
    cdn:
      auto: false
      link: 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css'
      script: 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js'
      language: 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/%%language%%.min.js'
    select_option:
      placeholder: "app.search_placeholder"
      delay: 1000
      minimum_input_length: 0
      maximum_input_length: 0
      minimum_results_for_search: 0
      maximum_selection_length: 0
      minimum_selection_length: 0
      multiple: false
      width: 'resolve'
      scroll_after_select: false
      select_on_close: false
      theme: 'custom'
      language: 'auto'
      amd_base: './'
      amd_language_base: './i18n/'
      cache: true
    security:
      public_access: false
      voter: 'danilovl.select_autocompleter.voter.default'
      role: 
        - ROLE_ADMIN
        - ROLE_API
      condition: 'or'
    to_string:
      format: "ID %%d: %%s"
      properties:
        - 'id'
        - 'name'
    where:
      - 'e.active = true'
    order_by:
      createdAt: 'ASC'
      uptadetAt: 'DESC'
    route:
      name: 'custom_select_autocomplete'
      parameters: []
      extra: []
```

### 3. Customization default options for all autocompleters

#### 3.1 Widget

By default, only one widget, `select2_v4`, is available.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    widget: 'select2_v4'
```
#### 3.2 Cdn

If you want to add default `select2.min.js`, `select2.min.css` and `i18n.js` files on page.

Links are defined in `default.yaml`.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    cdn:
      auto: true
```

You can also choose a specific script if, for example, you already have some scripts available on the page.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    cdn:
      link: auto
      script: auto
      language: auto
```

You can also define your own paths for script and CSS files.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    cdn:
      link: 'public/css/select2.min.css'
      script: 'public/js/select2.min.js'
      language: 'public/js/language.en.js'
```

#### 3.3 Select options

For customization, the following settings are available for the select option.

Text defined in `placeholder` will be translated by twig function `trans`.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    select_option:
      placeholder: "app.search_placeholder"
      delay: 1000
      minimum_input_length: 1
      maximum_input_length: 3
      minimum_results_for_search: 5
      maximum_selection_length: 0
      minimum_selection_length: 0
      multiple: true
      width: false
      scroll_after_select: false
      select_on_close: false
      theme: 'custom'
      language: 'en'
      amd_base: './'
      amd_language_base: './i18n/'
      cache: false
```

#### 3.4 toString

Simple `__toString` format.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    to_string:
      format: "ID %%d: %%s"
      properties:
        - 'id'
        - 'name'
```

If `to_string` option is `auto` then  `__toString()` method was called by Class.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    to_string:
      auto: true
```

#### 3.5 Where

Simple `where` condition.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    where:
      - 'e.active = true AND e.id > 100'
      - 'DATE_ADD(e.publishedAt, 10, "day") >= CURRENT_TIMESTAMP()'
      - 'e.totalAmount >= 50 AND e.totalAmount <= 15000'
      - 'e.prepared = 1 OR e.sent = 1'
      - 'e.id IN (1,2)'
```

Generated SQL query.

```sql
where (e.active = true AND e.id > 100) AND 
      (DATE_ADD(e.published_at, 10, "day") >= CURRENT_TIMESTAMP()) AND
      (e.total_amount >= 50 AND e.total_amount <= 15000) AND
      (e.prepared = 1 OR e.sent = 1)  AND
      (e.id IN (1,2))
```

#### 3.6 Order by

Order result by.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    order_by:
      createdAt: 'ASC'
      uptadetAt: 'DESC'
```

Generated SQL query.

```sql
ORDER BY e.created_at ASC, e.uptadet_at DESC
```

### 4. Configuring autocompleters

For `Doctrine ORM` you should use key `orm`. For `Doctrine ODM` you should use key `odm`.

The configuration is practically no different for `orm` or `odm`.

#### 4.1. ORM autocompleters
##### 4.1.1 Simple configuration

Simple configuration.

The `name` identifier will be duplicated with the prefix `orm` or `odm`, which can be used to identify autocompleters in forms.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'user'
      class: 'App:User'
      property: 'username'   
    
    - name: 'group'
      class: 'App:Group'
      property: 'name'   
      property_search_type: 'equal'
```

##### 4.1.2 Simple search

`start` is `LIKE 'search%'`

`any` is `LIKE '%search%'`

`end` is `LIKE 'search%'`

`equal` is `= 'search'`

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'group'
      class: 'App:Group'
      property: 'name' 
      search_simple:
        name: 'start'
        text: 'any'
        descrption: 'and'
```

##### 4.1.3 Custom search pattern

You can define a custom search pattern. Symbol `%` in yaml must be duplicate - `%%`.

You must use key word `$search` to insert search text into a pattern.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'group'
      class: 'App:Group'
      property: 'name' 
      search_pattern:
        name: 'group_$search%%'
        description: '%%$search%%'

    - name: 'product'
      class: 'App:Product'
      property: 'name' 
      search_pattern:
        price: 'EUR%%'
```

##### 4.1.4 toString

If `to_string` option `auto` is `true`, then `__toString()` method will be called by Class.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'group'
      class: 'App:Group'
      property: 'name' 
      to_string:
        auto: true
```
You could defined custom `__toString()` format, `sprintf` function will be called.

For each variables in `properties`, function `(string)` will be called.

Symbol `%` in yaml must be duplicate - `%%`.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'product'
      class: 'App:Product'
      property: 'name'
      to_string:
        format: "ID %%d: %%s %%d"
        properties:
          - 'id'
          - 'name'
          - 'price'
```

##### 4.1.5 Result ordering

You can add ordering.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'product'
      class: 'App:Product'
      property: 'name'
      order_by:
        createdAt: 'ASC'
        uptadetAt: 'DESC'
```

##### 4.1.6 Call repository method

If you want to use an existing repository method from your project, other parameters will be ignored.

Repository method should have `public` access and return `QueryBuilder` or `Builder`.

```yaml
# config/config.yaml

danilovl_select_autocompleter:
  orm:
    - name: 'product'
      class: 'App:Product'
      property: 'name'
      repository:
        method: 'createSearchQueryBuilder'
```

For entity `App:Product` will be found `Repository`, then method `createSearchQueryBuilder` will be called with `AutocompleterQuery` and `Config` as a parameters.

This means that all the search logic will be processed by the method you defined.

##### 4.1.7 Overriding `default_option`

You can override `default_option` for specific autocompleter.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'product'
      class: 'App:Product'
      id_property: 'id'
      property: 'name'
      widget: 'custom_widget'
      to_string:
        format: "ID %%d: %%s %%d"
        properties:
          - 'id'
          - 'name'
          - 'price'
      search_pattern:
        name: '%%$search%%'
        price: 'EUR%%'
      select_option:
        placeholder: "app.search_placeholder_product"
        delay: 1500
        minimum_input_length: 1
        maximum_input_length: 4
      security:
        public_access: false
        role:
          - 'ROLE_USER'
          - 'ROLE_ADMIN'  
```

### 5. Security

#### 5.1 By roles

You could restrict access to autocompleter by user roles.

Restrict access for all autocompleters.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    security: 
      role:
        - 'ROLE_USER'
        - 'ROLE_ADMIN'
```

You can user condition `or` or `and`
```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    security: 
      role:
        - 'ROLE_USER'
        - 'ROLE_ADMIN'
      condition: 'and'
```
If the user has no role `ROLE_USER` or `ROLE_ADMIN`, voter return `false`

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    security: 
      role:
        - 'ROLE_USER'
        - 'ROLE_ADMIN'
      condition: 'or'
```

If the user has at least one of `ROLE_USER` or `ROLE_ADMIN`, voter return `true`

Restrict access for some specific autocompleter.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'user'
      class: 'App:User'
      security: 
        role:
          - 'ROLE_USER'
          - 'ROLE_ADMIN'      
```

#### 5.2 By URL patterns

You can restrict access to autocompleters by securing URL patterns.

```yaml
# config/security.yaml

security:
   access_control:
        - { path: ^/(%app_locales%)/select-autocompleter/(\w+)/autocomplete, roles: [ROLE_AUTCOMOPLETER, ROLE_ADMIN] }
```

#### 5.3 By voter

You can create your own voter for autocompleters.

```php
<?php declare(strict_types=1);

namespace App\Security\Voter;

use Danilovl\SelectAutocompleterBundle\Constant\VoterSupportConstant;
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;

class CustomAutocompleterVoter extends Voter
{
    private const SUPPORTS = [
        VoterSupportConstant::GET_RESULT
    ];

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::SUPPORTS, true)) {
            return false;
        }

        if (!$subject instanceof AutocompleterInterface) {
            return false;
        }
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($attribute !== VoterSupportConstant::GET_DATA) {
            throw new LogicException('This code should not be reached!');
        }

        // custom logic    

        return true;
    }
}
```

Register new voter as a service.

```yaml
# config/security.yaml

...
services:
  app.voter.custom:
    class: App\Security\Voter\CustomAutocompleterVoter
    public: true
    arguments:
      - '@security.helper'
```

Set voter for all autocompleters.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    security: 
      voter: 'app.voter.custom'
```

Set voter for some specific autocompleter.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'user'
      class: 'App:User'
      security: 
        voter: 'app.voter.custom'
```

#### 5.4 Use `isGranted` method in custom autocompleter.

```php
<?php declare(strict_types=1);

namespace App\Autocompleter;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Item;
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Service\OrmAutocompleter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class CustomShopAutocompleter extends OrmAutocompleter
{
    private ShopRepository $shopRepository;

    public function __construct(
        ManagerRegistry $registry,
        AutocompleterConfigResolver $resolver,
        ShopRepository $shopRepository
    ) {
        parent::__construct($registry, $resolver);

        $this->shopRepository = $shopRepository;
    }

    public function isGranted(): int
    {
        return VoterInterface::ACCESS_ABSTAIN;
    }
}
```

### 6. Dependent select

Sometimes you need the options of a select element to be loaded or refreshed via AJAX based on the selection of another select element.

#### 6.1 OnyToMany

For example entity - `City` dependent on `Country` and `Region`.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'autocompleter-country'
      class: 'App:Country' 

    - name: 'autocompleter-region'
      class: 'App:Region'

    - name: 'autocompleter-city'
      class: 'App:City'
      dependent_selects:
        - name: 'dependent_on_country'
          parent_property: 'country'  

        - name: 'dependent_on_region'
          parent_property: 'region'
```

`parent_property` is the name of the variable in dependent class.

```php
<?php declare(strict_types=1);

namespace App\Entity;

#[ORM\Table(name: 'city')]
#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ORM\HasLifecycleCallbacks]
class City
{
    use IdTrait;
    use TimestampAbleTrait;
    use LocationTrait;

    #[ORM\Column(name: 'name', type: Types::STRING, nullable: false)]
    protected ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'cities')]
    #[ORM\JoinColumn(name: 'id_country', referencedColumnName: 'id', nullable: false)]
    protected ?Country $country = null;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'regions')]
    #[ORM\JoinColumn(name: 'id_region', referencedColumnName: 'id', nullable: false)]
    protected ?Region $region = null;
}
```

Parent and dependent fields should be in form together.

`parent_field` - name of master field in your `FormBuilder`.

```php
<?php declare(strict_types=1);

namespace App\Form;

use Danilovl\SelectAutocompleterBundle\Form\Type\AutocompleterType;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', AutocompleterType::class, [
                'autocompleter' => [
                    'name' => 'orm.country'
                ]
            ]) 
            ->add('cityByCountry', AutocompleterType::class, [
                'autocompleter' => [
                    'name' => 'orm.city',
                    'dependent_select' => [
                        'name' => 'dependent_on_country',
                        'parent_field' => 'cityByCountry'
                    ]
                ]
            ]) 
            ->add('region', AutocompleterType::class, [
                'autocompleter' => [
                    'name' => 'orm.region'
                ]
             ])   
            ->add('cityByRegion', AutocompleterType::class, [
                'autocompleter' => [
                    'name' => 'orm.city',
                    'dependent_select' => [
                        'name' => 'dependent_on_region',
                        'parent_field' => 'cityByRegion'
                    ]
                ]
            ]);
    }
}
```

#### 6.2 Simple ManyToMany

For example entity - `Tag` has many `Cheque`

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'cheque'
      class: 'App:Cheque'
      property: 'chequeNumber'

    - name: 'tag'
      class: 'App:Tag'
      dependent_selects:
        - name: 'cheques'
          parent_property: 'id'
          many_to_many:
             chequesAlies: 'e.cheques'
```

#### 6.3 Complicated ManyToMany

For example entity - `Work` dependent on `Firm` through custom entity `FirmWork`

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'firm'
      class: 'App:Firm'

    - name: 'work'
      class: 'App:Work'
      dependent_selects:
        - name: 'firms'
          parent_property: 'id'
          many_to_many:
            firmWork: 'e.firms'
            firm: 'firmWork.firm'
```

### 7. Route

#### 7.1 Redefine global route

You can redefine the global autocompleter route and add some extra route parameters.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    route:
      name: 'danilovl_select_autocomplete'
      parameters: []
      extra: []
```

#### 7.2 Redefine autocompleter route

You can redefine a specific autocompleter route and add extra route parameters.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'firm'
      class: 'App:Firm'
      route:
        name: 'danilovl_select_autocomplete_firm'
        parameters: 
          id: 200
          enable_migraiton: 'yes'
```

### 8. Usage

Simple configuration in form.

You should use `'name' => 'orm.shop'` for identification autocompleter.

```php
<?php declare(strict_types=1);

use Danilovl\SelectAutocompleterBundle\Form\Type\AutocompleterType;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shop', AutocompleterType::class, [
            'autocompleter' => [
                'name' => 'orm.shop'
            ],
            'required' => true,
            'constraints' => [
                new NotBlank
            ]
        ]);
    }
}
```

You can override `select_option`.

```php
<?php declare(strict_types=1);

use Danilovl\SelectAutocompleterBundle\Form\Type\AutocompleterType;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shop', AutocompleterType::class, [
            'autocompleter' => [
                'name' => 'orm.shop',
                'select_option' => [
                    'placeholder' => 'app.form_type_placeholder',
                    'delay' => 0,
                    'minimum_input_length' => 2
                ]
            ],
            'required' => true,
            'constraints' => [
                new NotBlank
            ]
        ]);
    }
}
```

If you need multi select than use `MultipleAutocompleterType`

```php
<?php declare(strict_types=1);

use Danilovl\SelectAutocompleterBundle\Form\Type\MultipleAutocompleterType;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shop', MultipleAutocompleterType::class, [
            'autocompleter' => [
                'name' => 'orm.shop',
                'select_option' => [
                    'multiple' => true
                ]
            ],
            'required' => true,
            'constraints' => [
                new NotBlank
            ]
        ]);
    }
}
```

### 9.  Custom Autocompleter

#### 9.1  Config method

You can create your own custom autocompleter.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  own:
    - name: 'customShop'
      class: 'App:Shop'
``` 

```php
<?php declare(strict_types=1);

namespace App\Autocompleter;

use Danilovl\SelectAutocompleterBundle\Service\OrmAutocompleter;

class CustomShopAutocompleter extends OrmAutocompleter
{
}
```

If the standard functionality is not enough, or if you want to reuse your existing code.

```php
<?php declare(strict_types=1);

namespace App\Autocompleter;

use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Item;
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Service\OrmAutocompleter;

class CustomShopAutocompleter extends OrmAutocompleter
{
    private ShopRepository $shopRepository;

    public function __construct(
        ManagerRegistry $registry,
        AutocompleterConfigResolver $resolver,
        ShopRepository $shopRepository
    ) {
        parent::__construct($registry, $resolver);

        $this->shopRepository = $shopRepository;
    }

    public function createQueryBuilder(): QueryBuilder
    {
        return $this->shopRepository->baseQueryBuilder();
    }

    protected function createAutocompleterQueryBuilder(AutocompleterQuery $query): QueryBuilder
    {
       return $this->shopRepository->queryBuilderFindNearestShopByName(
           $query->search,
           $this->getOffset($query),
           $this->config->limit,
           $query->extra
       );
    }

    public function transformObjectToItem($object): Item
    {
        $item = new Item;
        $item->option = $object->getIdentificator();
        $item->value = sprintf('%s (%s,%s)', $object->getName(), $object->getAddress(), $object->getCity()->getName());

        return $item;
    }
}
```

If you need additional parameters in the request, you can define an `extra` parameter that will be available in `AutocompleterQuery`.

Example url: `select-autocomplete/orm.customShop/autocomplete?extra[language]=en`.

```php
<?php declare(strict_types=1);

namespace App\Form;

use App\Autocompleter\CustomAutocompleter;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shop', CustomAutocompleter::class, [
            'autocompleter' => [
                'name' => 'orm.customShop',
                'route' => [
                    'extra' => [
                        'language' => 'en'
                    ]
                ]
            ],
            'required' => true,
            'constraints' => [
                new NotBlank
            ]
        ]);
    }
}
```

Then you must define new autocompleter service in you `services.yaml` with `danilovl_select_autocompleter.autocompleter` tag and `alias` name.

```yaml
app.autocompleter.custom:
  class: App\Autocompleter\CustomShopAutocompleter
  tags:
    - {name: 'danilovl.select_autocompleter.autocompleter', alias: 'own.customShop'}
```

#### 9.2  Attribute tag method(more preferable, less code in config)

You can use autocompleter attribute `AsAutocompleter` with require `alias` field.

```php
<?php declare(strict_types=1);

namespace App\Autocompleter;

use App\Entity\Shop;
use Danilovl\SelectAutocompleterBundle\Attribute\AsAutocompleter;

#[AsAutocompleter(alias: 'own.customShop')]
class CustomShopAutocompleter extends OrmAutocompleter
{
    public function getConfigOptions(): array
    {
        return [
            'class' => Shop::class
        ];
    }
}
```

Or you can use symfony service attribute `AutoconfigureTag` with require `name` and `alias` parameters.

```php
<?php declare(strict_types=1);

namespace App\Autocompleter;

use App\Entity\Shop;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'danilovl.select_autocompleter.autocompleter', attributes: ['alias' => 'own.customAutocompleter'])]
class CustomShopAutocompleter extends OrmAutocompleter
{
    public function getConfigOptions(): array
    {
        return [
            'class' => Shop::class
        ];
    }
}
```

### 10. Custom autocompleter widget template

Create your own custom autocompleter template that extends `versions.html.twig` and redefine the blocks you need.

```twig
{# templates/autocompleter/custom_widget_template.html.twig #}

{% extends '@SelectAutocompleter/Form/versions.html.twig' %}

{% block cdn %}
    {# new code #}
{% endblock %}

{% block style %}
    {# new code #}
{% endblock %}

{% block select_tag %}
    {# new code #}
{% endblock %}

{% block input %}
    {# new code #}
{% endblock %}

{% block options %}
    {# new code #}
{% endblock %}

{% block options_data %}
    {# new code #}
{% endblock %}

{% block options_ajax %}
    {# new code #}
{% endblock %}

{% block options_ajax_data %}
    {# new code #}
{% endblock %}

{% block initialize %}
    {# new code #}
{% endblock %}
 
{% block select_event_selecting %}
    {# new code #}
{% endblock %} 

{% block select_event_unselecting %}
    {# new code #}
{% endblock %}
 
{% block widget_NEW_WIDGET_NAME %}
    {# new code #}
{% endblock %}
```

Then you need to add the path for the new custom template to the configuration.

For all autocompleters.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    base_template: 'autocompleter/custom_widget_template.html.twig'     
```

Or for some specific autocompleter.

```yaml
# config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'user'
      class: 'App:User'
      property: 'username'   
      base_template: 'autocompleter/custom_widget_template.html.twig'  
```

## License

The SelectAutocompleterBundle is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
