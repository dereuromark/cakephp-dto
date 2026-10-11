---
layout: home
hero:
  name: CakePHP DTO
  text: Typed data between your application layers
  tagline: Define fields, generate PHP classes, and pass data through methods your IDE can check.
  image:
    src: /logo.svg
    alt: CakePHP DTO logo
  actions:
    - theme: brand
      text: Installation
      link: /guide/installation
    - theme: alt
      text: Build your first DTO
      link: /guide/getting-started
features:
  - title: Generated PHP classes
    details: Named getters, setters, field constants and type declarations come from your schema.
  - title: Mutable or immutable
    details: Choose setters for changing data or with methods that return a new DTO.
  - title: CakePHP integration
    details: Map entities, return JSON responses, validate inputs and import database schemas.
---

CakePHP DTO integrates [php-collective/dto](https://github.com/php-collective/dto) with CakePHP. A DTO carries data with named fields; it does not save records or replace ORM entities.

Start with one small DTO. The [beginner guide](/guide/getting-started) creates a working class without a database or admin interface.
