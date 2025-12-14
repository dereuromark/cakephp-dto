<h1>Generate DTO Schema from JSON/Structure</h1>

<div class="float-end">
	<?php echo $this->Html->link('Restart', ['action' => 'schema', '?' => $this->request->getQuery()]); ?>
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

	<p>Enter your JSON data/structure (example API result or JSON schema):</p>

	<?php echo $this->Form->create(); ?>

	<?php
	$inputDefault = $this->request->getQuery('input', '');
	$compressed = $this->request->getQuery('compressed');
	?>
	<?php echo $this->Form->control('input', [
		'type' => 'textarea',
		'rows' => 20,
		'default' => $compressed ? '' : $inputDefault,
		'id' => 'input-field',
		'data-compressed' => $compressed ? $inputDefault : '',
	]); ?>
	<?php echo $this->Form->control('type', ['type' => 'select', 'options' => \PhpCollective\Dto\Importer\Parser\Config::typeLabels(), 'empty' => ['' => 'Auto-Detect'], 'default' => $this->request->getQuery('type')]); ?>
	<?php echo $this->Form->control('namespace', ['type' => 'text', 'placeholder' => 'Optional namespace prefix', 'label' => 'DTO namespace', 'default' => $this->request->getQuery('namespace')]); ?>

	<?php echo $this->Form->button('Parse'); ?>
	<?php echo $this->Form->end(); ?>

	<?php if ($compressed) { ?>
	<?php echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/pako/2.1.0/pako.min.js'); ?>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		var field = document.getElementById('input-field');
		var compressed = field.dataset.compressed;
		if (compressed) {
			try {
				var binary = atob(compressed);
				var bytes = new Uint8Array(binary.length);
				for (var i = 0; i < binary.length; i++) {
					bytes[i] = binary.charCodeAt(i);
				}
				var decompressed = pako.inflate(bytes, { to: 'string' });
				field.value = decompressed;
			} catch (e) {
				console.error('Failed to decompress:', e);
				field.value = compressed;
			}
		}
	});
	</script>
	<?php } ?>

<?php } ?>
