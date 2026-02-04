## Generating from JSON

Often times, you have an API result that you want to convert into PHP (transfer) objects.
In this case, you can either write them manually, or let a tool convert it for you.

### Web User Interface

The plugin provides a web-based interface for generating DTO definitions from JSON data. This is particularly useful for quickly converting API responses or existing data structures into DTO schemas.

**Accessing the UI:**
Navigate to `/admin/cake-dto/generate` in your browser. This route is provided by the plugin and includes:

- **Index page**: Overview of available generation tools
- **Schema page**: Interactive JSON parser and schema builder

The web UI allows you to:
- Paste JSON data directly into the interface
- Choose between JSON Schema format or example JSON data
- Parse the data into DTO XML schema definitions
- Copy the generated schema for use in your `config/dto.xml` or `config/dto/` files

### JSON into Schema
From the web UI at `/admin/cake-dto/generate`, select `Schema`.

Enter your JSON data and click `Parse`.

If you didn't select the input type, it will determine it based on the high level structure.
You can either use schema or example data.

#### Using JSON schema
This uses [JSON Schema](https://json-schema.org/overview/what-is-jsonschema) standard.
It is usually a bit more precise as the schema defines the fields quite well.

Depending on the API, a field can sometimes be marked as "required" which should not be the case.
You should remove this then in your final DTO schema.

#### Using example JSON data
Since we work with example data, we cannot infer too much about the overall data schema. As such:

- None of the fields are marked as required, since it does not know if other data might have it set or not set
- It cannot know the first high level DTO and names it "Object". You should rename it.
- Assoc arrays are assumed to be a nested DTO, whereas numeric indexes usually lead to a DTO collection to be determined.

Best to check and fine tune your DTO schema afterwards.

### Tips and useful notes

#### Associative collections
You want to look into associative collections. In some cases you can - after parsing -
  adjust the schema field definitions to set a "key" for the associative array/object collection.

Right now, it looks for the following (string) keys to use for it:
- 'slug'
- 'login'
- 'name'

You can customize this list using `'CakeDto.assocKeyFields'` Configure key.
They should be of type `string`.

Example:
```xml
<field name="labels" type="Label[]" singular="label" associative="true" key="name"/>
```
This provides then also `has/get` methods for the specific (singular) collection item.

#### Existing schema files
If you are looking for some inspiration or maybe schemas for existing APIs, check out
- [github.com/SchemaStore/schemastore/tree/master/src/schemas/json](https://github.com/SchemaStore/schemastore/tree/master/src/schemas/json)

#### Limitations

The importer can resolve local `$ref` pointers (e.g. `#/definitions/...`).
External file `$ref` pointers require a base path and are not supported through the web UI yet.
Also:
- fields must not be prefixed with `_`
- field names are currently expected to be under_scored. Other types might work, but are not fully tested.
