## Generating from JSON

Often times, you have an API result that you want to convert into PHP (transfer) objects.
In this case, you can either write them manually, or let a tool convert it for you.

### JSON into Schema
Navigate to

    /admin/cake-dto/generate

and select `Schema`.

Enter your JSON data structure and click `Parse`.

If you didn't select the input type, it will determine it based on the high level structure.
You can either use schema or example data.

#### Using JSON schema
This is usually a bit more precise as the schema defines the fields quite well.

It can sometimes have a field marked as "required", which is not the case.
You should remove this then in your final DTO schema.

#### Using example JSON data
Since we work with example data, we cannot infer too much about the overall data schema. As such:

- None of the fields are marked as required, since it does not know if other data might have it set or not set
- It cannot know the first high level DTO and names it "Object". You should rename it.
- Assoc arrays are assumed to be a nested DTO, whereas numeric indexes usually lead to a DTO collection to be determined.

Best to check and fine tune your DTO schema afterwards.

### JSON into PHP objects
Navigate to

    /admin/cake-dto/generate

and select `Objects`.

Enter your JSON example data and click `Parse`.

TODO
