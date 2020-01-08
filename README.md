# SelectAutocompleterBundle #

## About ##

This is a Symfony bundle which enables the popular [Select2](https://select2.github.io/) component to be used as a drop-in replacement for a standard fields on a Symfony form.

The main feature of this bundle is that the list of choices is retrieved via a remote ajax call.

### Requirements 

  * PHP 7.3.0 or higher
  * Symfony 4.4 or higher

### 1. Installation

Install `danilovl/select-autocompleter-bundle` package by Composer:
 
``` bash
$ composer require danilovl/select-autocompleter-bundle
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

After installing the bundle, add this route to your routing:

``` yaml
# app/config/routing.yaml

_danilovl_select_autocomopleter:
  resource: "@SelectAutocompleterBundle/Resources/config/routing.yaml"
  prefix:   /select-autocomplete
```

System default options for all autocompleters, which will be used if necessary.

```yaml
# danilovl/select-autocompleter-bundle/src/Resources/config/default.yaml

...
default:
  id_property: 'id'
  property: 'name'
  widget: 'select2_v4'
  root_alias: 'e'
  limit: 10
  base_template: '@SelectAutocompleter/Form/versions.html.twig'
  role_prefix: 'ROLE_'
  select_option:
    delay: 1000
    theme: 'default'
    language: 'en'
    width: 'resolve'
    amd_base: './'
    amd_language_base: './i18n/'
    cache: false
  cdn:
    script: 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js'
    link: 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css'
  security:
    voter: 'danilovl_select_autocompleter.voter.default'
```

List of available options which you can change in you project.

This options will be applied for all autocompleters. For example: 

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    widget: 'select2_v4'
    manager: null
    id_property: 'id'
    root_alias: 'e'
    property: 'name'
    limit: 10
    base_template: '@SelectAutocompleter/Form/versions.html.twig'  
    cdn:
      auto: false
      link: 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css'
      script: 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js'
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
      language: 'en'
      amd_base: './'
      amd_language_base: './i18n/'
      cache: true
    security:
      voter: 'danilovl.select_autocompleter.voter.default'
      role: []
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
```

### 3. Customization default options for all autcompleters

#### 3.1 Widget

By default only one widget `select2_v4` is available.

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    widget: 'select2_v4'
```
#### 3.2 Cdn

If you want to add default `select2.min.js` and `select2.min.css` files on page.

Links are defined in `default.yaml`.

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    cdn:
      auto: true
```

Or you can defined you own path for script and css files.

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    cdn:
      link: 'public/css/select2.min.css'
      script: 'public/js/select2.min.js'
```

#### 3.3 Select options

For customization select is available following settings. 

Text defined in `placeholder` will be translated by twig function `truns`.

```yaml
# app/config/config.yaml

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
# app/config/config.yaml

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
# app/config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    to_string:
      auto: true
```

#### 3.5 Where

Simple `where` condition. 

```yaml
# app/config/config.yaml

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
# app/config/config.yaml

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

### 3. Configuring autocompleters

For `Doctrine ORM` you should use key `orm`. For `Doctrine ODM` you should use key `odm`.    

The configuration is practically no different for `orm` or `odm`.

#### 3.1. ORM autocompleters
##### 3.1.1 Simple configuration

Simple configuration.

Identifier `name` will be duplicated with prefix type `orm.` or `odm.`, which can be used for identification autocompleters in forms.

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'user'
      class: 'App:User'
      property: 'username'   
    
    - name: 'group'
      class: 'App:Group'
      property: 'name'   
```

##### 3.1.2 Simple search

`start` is `LIKE 'search%'`

`any` is `LIKE '%search%'`

`end` is `LIKE 'search%'`

```yaml
# app/config/config.yaml

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

##### 3.1.3 Custom search pattern

You can defined custom search pattern. Symbol `%` in yaml must be duplicate - `%%`.

You must use key word `$search` to insert search text into a pattern.

```yaml
# app/config/config.yaml

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

##### 3.1.4 toString

If `to_string` option `auto` is `true`, then `__toString()` method will be called by Class.

```yaml
# app/config/config.yaml

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
# app/config/config.yaml

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

##### 3.1.5 Result ordering

You can add ordering.

```yaml
# app/config/config.yaml

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

##### 3.1.6 Call repository method

If you want to use a existing repository method from you project. Other parameters will be ignored. 

Repository method should have `public` access and return `QueryBuilder` or `Builder`.

```yaml
# app/config/config.yaml

danilovl_select_autocompleter:
  orm:
    - name: 'product'
      class: 'App:Product'
      property: 'name'
      repository:
        method: 'createSearchQueryBuilder'
```

For entity `App:Product` will be found `Repository`, then method `createSearchQueryBuilder` will be called with `AutocompleterQuery` and `Config` as a parameters.

This means that all the search logic will be processing by the method which you defined.

##### 3.1.7 Overriding `default_option`

You can override `default_option` for specific autocompleter.

```yaml
# app/config/config.yaml

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
        role:
          - 'ROLE_USER'
          - 'ROLE_ADMIN'  
```

### 4. Security

#### 4.1 By roles

You could restrict access to autcompleter by user roles.

Restrict access for all autocompleters.

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    security: 
      role:
        - 'ROLE_USER'
        - 'ROLE_ADMIN'
```

Restrict access for some specific autocompleter.

```yaml
# app/config/config.yaml

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

#### 4.2 By URL patterns

You could restrict access to autocompleters by securing URL patterns

```yaml
# app/config/security.yaml

security:
   access_control:
        - { path: ^/(%app_locales%)/select-autocompleter/(\w+)/autocomplete, roles: [ROLE_AUTCOMOPLETER, ROLE_ADMIN] }
```

#### 4.2 By voter

You could create you own voter for autcompleters.

```php
<?php declare(strict_types=1);

namespace App\Security\Voter;

use Danilovl\SelectAutocompleterBundle\Constant\VoterSupportConstant;
use Danilovl\SelectAutocompleterBundle\Services\Interfaces\AutocompleterInterface;
use LogicException;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class CustomAutocompleterVoter extends Voter
{
    private const SUPPORTS = [
        VoterSupportConstant::GET_RESULT
    ];

    /**
     * @var Security
     */
    private $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, self::SUPPORTS, true)) {
            return false;
        }

        if (!$subject instanceof AutocompleterInterface) {
            return false;
        }
    }

    /**
     * @param string $attribute
     * @param AutocompleterInterface $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if ($attribute === VoterSupportConstant::GET_RESULT) {
            // custom logic            
        }

        throw new LogicException('This code should not be reached!');
    }
}
```

Register new voter as a service.

```yaml
# app/config/security.yaml

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
# app/config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    security: 
      voter: 'app.voter.custom'
```

Set voter for some specific autocompleter.

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'user'
      class: 'App:User'
      security: 
        voter: 'app.voter.custom'
```

### 5. Dependent select

Sometimes you need options of a select will be loaded/refreshed by ajax based on selection of another select.

#### 5.1 OnyToMany

For example entity - `City` dependent on `Country` and `Region`.

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'autocompleter.country'
      class: 'App:Country' 

    - name: 'autocompleter.region'
      class: 'App:Region'

    - name: 'autocompleter.city'
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

//use code

/**
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\CityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class City
{
    use IdTrait;
    use TimestampAbleTrait;
    use LocationTrait;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var Country|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="cities")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_country", nullable=false, referencedColumnName="id")
     * })
     */
    protected $country;

    /**
     * @var Region|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="regions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_region", nullable=false, referencedColumnName="id")
     * })
     */
    protected $region;

    //other code
}
```

Parent and dependent fields should be in form together.

`parent_field` - name of master field in your `FormBuilder`.

```php
<?php declare(strict_types=1);

namespace App\Form;

// ...
use Danilovl\SelectAutocompleterBundle\Form\Type\AutocompleterType;

class CityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', AutocompleterType::class, [
                'autocompleter' => [
                    'name' => 'country'
                ]
            ]) 
            ->add('cityByCountry', AutocompleterType::class, [
                'autocompleter' => [
                    'name' => 'city',
                    'dependent_select' => [
                        'name' => 'dependent_on_country',
                        'parent_field' => 'cityByCountry'
                    ]
                ]
            ]) 
            ->add('region', AutocompleterType::class, [
                'autocompleter' => [
                    'name' => 'region'
                ]
             ])   
            ->add('cityByRegion', AutocompleterType::class, [
                'autocompleter' => [
                    'name' => 'city',
                    'dependent_select' => [
                        'name' => 'dependent_on_region',
                        'parent_field' => 'cityByRegion'
                    ]
                ]
            ]);
    }
}
```

#### 5.2 Simple ManyToMany

For example entity - `Tag` has many `Cheque` 

```yaml
# app/config/config.yaml

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

#### 5.3 Complicated ManyToMany

For example entity - `Work` dependent on `Firm` through custom entity `FirmWork`

```yaml
# app/config/config.yaml

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
### 6. Using 

Simple configuration in form.

You can use `'name' => 'shop'` or `'name' => 'orm.shop'` for identification autocompleter.

```php
<?php declare(strict_types=1);

// ...
use Danilovl\SelectAutocompleterBundle\Form\Type\AutocompleterType;

class CityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shop', AutocompleterType::class, [
            'autocompleter' => [
                'name' => 'shop'
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

// ...
use Danilovl\SelectAutocompleterBundle\Form\Type\AutocompleterType;

class CityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shop', AutocompleterType::class, [
            'autocompleter' => [
                'name' => 'shop',
                'multiple' => true,
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

### 7.  Custom Autocompleter

You can create your own custom autocompleter .
 
```php
<?php declare(strict_types=1);

namespace App\Autocompleter;

// ...
use Danilovl\SelectAutocompleterBundle\Constant\{
    OrderByConstant,
    SearchConstant
};
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Services\OrmAutocompleter;

class CustomAutocompleter extends OrmAutocompleter
{
    /**
     * @param ManagerRegistry $registry
     * @param AutocompleterConfigResolver $resolver
     */
    public function __construct(
        ManagerRegistry $registry,
        AutocompleterConfigResolver $resolver
    ) {
        parent::__construct($registry, $resolver);

        $this->addConfig([
            'class' => Shop::class,
            'name' => 'custom',
            'id_property' => 'id',
            'to_string' => [
                'format' => '%d %s',
                'properties' => ['id', 'name']
            ],
            'order_by' => [
                'id' => OrderByConstant::ASC
            ],
            'search_simple' => [
                'name' => SearchConstant::START,
                'description' => SearchConstant::ANY
            ],
            'select_option' => [
               'placeholder' => 'app.form_type_placeholder'
            ],
            'security' => [
               'role' => [
                    'ROLE_USER',
                    'ROLE_ADMIN'
                ]
            ]
        ]);
    }
}

```

If the standard functionality is not enough, or you want to reuse you existing code.
   
```php
<?php declare(strict_types=1);

namespace App\Autocompleter;

// ...
use Danilovl\SelectAutocompleterBundle\Model\Autocompleter\AutocompleterQuery;
use Danilovl\SelectAutocompleterBundle\Model\SelectDataFormat\Item;
use Danilovl\SelectAutocompleterBundle\Resolver\Config\AutocompleterConfigResolver;
use Danilovl\SelectAutocompleterBundle\Services\OrmAutocompleter;

class CustomAutocompleter extends OrmAutocompleter
{
    /**
     * @var ShopRepository
     */
    private $shopRepository;

    /**
     * @param ManagerRegistry $registry
     * @param AutocompleterConfigResolver $resolver
     * @param ShopRepository $shopRepository
     */
    public function __construct(
        ManagerRegistry $registry,
        AutocompleterConfigResolver $resolver,
        ShopRepository $shopRepository
    ) {
        parent::__construct($registry, $resolver);

        $this->addConfig([
            'class' => Shop::class,
            'name' => 'custom'
        ]);

        $this->shopRepository = $shopRepository;
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        return $this->shopRepository->baseQueryBuilder();
    }

    /**
     * @param AutocompleterQuery $query
     * @return QueryBuilder
     */
    protected function createAutocompleterQueryBuilder(AutocompleterQuery $query): QueryBuilder
    {
       return $this->shopRepository->queryBuilderFindNearestShopByName(
           $query->search,
           $this->getOffset($query),
           $this->config->limit,
           $query->extra
       );
    }

    /**
     * @param Shop $object
     * @return Item
     */
    public function transformObjectToItem($object): Item
    {
        $item = new Item;
        $item->id = $object->getIdentificator();
        $item->text = sprintf('%s (%s,%s)', $object->getName(), $object->getAddress(), $object->getCity()->getName());

        return $item;
    }
}

```

If you need additional parameters in request you can defined `extra` parameter which will be available in `AutocompleterQuery`.

```php
<?php declare(strict_types=1);

namespace App\Form;

// ...
use App\Autocompleter\CustomAutocompleter;

class CityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder->add('shop', CustomAutocompleter::class, [
           'autocompleter' => [
               'name' => 'shop',
               'extra' => [
                   'language' => 'en'
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

Then you must defined new autocompleter service in you `services.yaml` with `danilovl_select_autocompleter.autocompleter` tag and `alias` name.

```yaml
app.autocompleter.custom:
  class: App\Autocompleter\CustomAutocompleter
  tags:
    - {name: 'danilovl_select_autocompleter.autocompleter', alias: 'custom'}
```

#### 8. Custom autocompleter widget template

Create you own custom autocompleter template which extends `versions.html.twig` and redefine the blocks you need.
 
```twig
{# templates/autocompleter/custom_widget_template.html.twig #}

{% extends '@SelectAutocompleter/Form/versions.html.twig' %}

{% block cdn %}
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
 
{% block widget_NEW_WIDGET_NAME %}
    {# new code #}
{% endblock %}
```
    
Then you need to add path for new custom template to config.

For all autocompleters.  

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  default_option:
    base_template: 'autocompleter/custom_widget_template.html.twig'     
```

Or for some specific autocompleter.

```yaml
# app/config/config.yaml

...
danilovl_select_autocompleter:
  orm:
    - name: 'user'
      class: 'App:User'
      property: 'username'   
      base_template: 'autocompleter/custom_widget_template.html.twig'  
```