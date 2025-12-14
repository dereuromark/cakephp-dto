<h1>Generate from JSON/Structure</h1>

<p>With this admin backend you can generate the DTO schema files from</p>
<ul>
	<li><a href="https://json-schema.org/overview/what-is-jsonschema" target="_blank">JSON Schema</a> definition</li>
	<li>Example JSON data, ideally as complete as possible</li>
</ul>

<p>Using example data is probably the most useful option, as it provides a chance to take an existing often huge and deeply nested
(assoc) array and transform it into a DTO moving forward.</p>

<h2>Select your input</h2>
<ul>
	<li>
		<?php echo $this->Html->link('Schema', ['action' => 'schema', '?' => ['type' => \PhpCollective\Dto\Importer\Parser\SchemaParser::NAME]]); ?>
	</li>
	<li>
		<?php echo $this->Html->link('Example data', ['action' => 'schema', '?' => ['type' => \PhpCollective\Dto\Importer\Parser\DataParser::NAME]]); ?>
	</li>
	<li>
		or let it <?php echo $this->Html->link('auto-detect', ['action' => 'schema']); ?>
	</li>
</ul>
