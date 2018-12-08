# Benchmark Shell

Copy over the shell into the plugin's `src/Shell/` dir, or into the one in your app (But then fix `namespace CakeDto\Shell;` to `namespace App\Shell;`).

Then run it as 
```
bin/cake dto_benchmark arrays 10000

bin/cake dto_benchmark dtos 10000
```

Adjust the `10000` if needed.

## Better to use in real life
The results of doing the same operation again multiple times is often not too helpful.
So best to just using them in a project and see what kind of impact actual use cases make.
