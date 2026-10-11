# Commands and CI

Run commands from the application root. Plugin loading registers both commands.

## Initialize XML definitions

```sh
bin/cake dto init
bin/cake dto init Customer
bin/cake dto init --plugin MyPlugin
```

Without a name, the command creates `config/dto.xml`. With `Customer`, it creates `config/dto/Customer.dto.xml`. `--plugin` selects that plugin's config directory. The initializer always creates XML, even when you configured another engine; create that engine's definition files manually.

Existing files are refused. `--force` replaces the existing definition with an empty XML scaffold.

## Generate classes

```sh
bin/cake dto generate
bin/cake dto generate --confirm
bin/cake dto generate --plugin MyPlugin
bin/cake dto generate --namespace MyApp
```

| Option | Short | Behavior |
| --- | --- | --- |
| `--dry-run` | `-d` | Report pending changes without writing files or creating directories. |
| `--confirm` | `-c` | Lint files that are written with the current PHP interpreter. |
| `--force` | `-f` | Write every definition, including unchanged classes. This mode skips obsolete-file removal. |
| `--plugin` | `-p` | Read the plugin's definitions and write into its `src/Dto/` directory unless `CakeDto.srcPath` overrides the output. |
| `--namespace` | `-n` | Override the namespace used for generated classes. |
| `--verbose` | `-v` | Show skipped files and differences; this is CakePHP's console verbosity option. |

Normally, generation removes classes that no longer have definitions. Do not keep handwritten classes with the generated suffix in the output directory. The default suffix is `Dto`.

`--confirm` checks written files only. Use `--force --confirm` to check unchanged files too. `--dry-run --confirm` does not lint because no files are written.

## Exit status

| Status | Meaning |
| --- | --- |
| `0` | Success; a dry run found no changes. Ordinary successful generation returns this status even when files changed. |
| `1` | Generation or PHP syntax checking failed. |
| `2` | A dry run found additions, modifications or removals. The generator also uses this status when its `verbose` option is explicitly enabled. |

The CLI's `--verbose` controls console output; it does not set the generator's separate `verbose` option.

## CI and deployment

If generated classes are committed, verify them in CI:

```sh
bin/cake dto generate --dry-run
```

If generated files are ignored, generate them during CI and deployment before the application runs:

```sh
bin/cake dto generate --confirm
```

Composer scripts can expose the same commands:

```json
{
  "scripts": {
    "dto:generate": "bin/cake dto generate --confirm",
    "dto:check": "bin/cake dto generate --dry-run"
  }
}
```

Keep the generated classes available for static analysis of application code. Exclude generated files from style rules if needed; excluding them from analysis itself is a separate choice.
