<?php
/**
 * @var \App\View\AppView $this
 * @var array<string> $tables
 * @var mixed $dto
 * @var mixed $format
 * @var mixed $namespace
 * @var mixed $result
 * @var mixed $schema
 */
?>
<h1>Generate DTO Schema from Database Tables</h1>

<div class="float-end">
	<?php echo $this->Html->link('Restart', ['action' => 'database']); ?>
</div>

<?php if (!empty($result)) { ?>

	<h2>Result</h2>

	<div class="mb-3">
		<?php
		$formats = ['php' => 'PHP', 'yaml' => 'YAML', 'xml' => 'XML', 'neon' => 'NEON'];
		foreach ($formats as $formatKey => $formatLabel) {
			echo $this->Form->create(null, ['style' => 'display: inline-block; margin-right: 5px;']);
			foreach ($dto as $dtoName => $fields) {
				foreach ($fields as $fieldName => $details) {
					if (is_array($details)) {
						foreach ($details as $detailKey => $detailValue) {
							echo $this->Form->hidden("dto.{$dtoName}.{$fieldName}.{$detailKey}", ['value' => $detailValue]);
						}
					} else {
						echo $this->Form->hidden("dto.{$dtoName}.{$fieldName}", ['value' => $details]);
					}
				}
			}
			echo $this->Form->hidden('namespace', ['value' => $namespace ?? '']);
			echo $this->Form->hidden('format', ['value' => $formatKey]);
			$btnClass = ($format ?? 'php') === $formatKey ? 'btn btn-primary btn-sm' : 'btn btn-outline-secondary btn-sm';
			echo $this->Form->button($formatLabel, ['class' => $btnClass]);
			echo $this->Form->end();
		}
		?>
	</div>

	<div class="position-relative">
		<button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="copy-btn" title="Copy to clipboard">
			Copy
		</button>
		<pre id="result-code" class="pt-5"><?php echo h($result); ?></pre>
	</div>
	<script>
	document.getElementById('copy-btn').addEventListener('click', function() {
		var code = document.getElementById('result-code').textContent;
		var btn = document.getElementById('copy-btn');

		function onSuccess() {
			btn.innerHTML = 'Copied!';
			setTimeout(function() {
				btn.innerHTML = 'Copy';
			}, 2000);
		}

		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(code).then(onSuccess);
		} else {
			var textarea = document.createElement('textarea');
			textarea.value = code;
			textarea.style.position = 'fixed';
			textarea.style.opacity = '0';
			document.body.appendChild(textarea);
			textarea.select();
			document.execCommand('copy');
			document.body.removeChild(textarea);
			onSuccess();
		}
	});
	</script>

<?php } elseif (!empty($schema)) { ?>

	<p>Define which DTOs and fields should be present</p>

	<?php echo $this->Form->create(); ?>

	<?php foreach ($schema as $dtoName => $fields) { ?>
		<?php echo $this->Form->control('dto.' . h($dtoName) . '._include', ['type' => 'checkbox', 'default' => true, 'label' => '<b>' . h($dtoName) . '</b>', 'escape' => false]); ?>
		<ul class="">
		<?php foreach ($fields as $fieldName => $details) { ?>
			<li class="list-unstyled">
			<?php
			$label = $fieldName;
			$label .= ' [' . $details['type'] .']';
			if (!empty($details['required'])) {
				$label.=' (required)';
			}
			?>
			<?php echo $this->Form->control('dto.' . $dtoName . '.' . $fieldName . '._include', ['type' => 'checkbox', 'default' => true, 'label' => $label]); ?>
			<?php echo $this->Form->hidden('dto.' . $dtoName . '.' . $fieldName . '.type', ['value' => $details['type']]); ?>
			<?php echo $this->Form->hidden('dto.' . $dtoName . '.' . $fieldName . '.singular', ['value' => $details['singular'] ?? null]); ?>
			<?php echo $this->Form->hidden('dto.' . $dtoName . '.' . $fieldName . '.associative', ['value' => $details['associative'] ?? null]); ?>
			<?php echo $this->Form->hidden('dto.' . $dtoName . '.' . $fieldName . '.required', ['value' => $details['required'] ?? null]); ?>
			</li>
		<?php } ?>
		</ul>
	<?php } ?>

	<?php echo $this->Form->hidden('namespace'); ?>

	<?php echo $this->Form->control('format', [
		'type' => 'select',
		'options' => [
			'php' => 'PHP',
			'yaml' => 'YAML',
			'xml' => 'XML',
			'neon' => 'NEON',
		],
		'default' => 'php',
		'label' => 'Output Format',
	]); ?>

	<?php echo $this->Form->button('Generate'); ?>
	<?php echo $this->Form->end(); ?>

<?php } else { ?>

	<p>Select the database tables you want to generate DTOs from:</p>

	<?php echo $this->Form->create(); ?>

	<div class="mb-3">
		<label>
			<input type="checkbox" id="select-all"> <b>Select All</b>
		</label>
	</div>

	<ul class="list-unstyled">
	<?php foreach ($tables as $table) { ?>
		<li>
			<?php echo $this->Form->control('tables.' . $table, [
				'type' => 'checkbox',
				'label' => $table,
				'class' => 'table-checkbox',
			]); ?>
		</li>
	<?php } ?>
	</ul>

	<?php echo $this->Form->control('namespace', ['type' => 'text', 'placeholder' => 'Optional namespace prefix', 'label' => 'DTO namespace']); ?>

	<?php echo $this->Form->button('Parse Selected Tables'); ?>
	<?php echo $this->Form->end(); ?>

	<script>
	document.getElementById('select-all').addEventListener('change', function() {
		var checkboxes = document.querySelectorAll('.table-checkbox');
		for (var i = 0; i < checkboxes.length; i++) {
			checkboxes[i].checked = this.checked;
		}
	});
	</script>

<?php } ?>
