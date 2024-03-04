<h1>Generate DTO Schema from JSON/Structure</h1>

<div class="float-end">
	<?php echo $this->Html->link('Restart', ['action' => 'schema', '?' => $this->request->getQuery()]); ?>
</div>

<p>Enter your JSON data/structure (example API result or JSON schema):</p>

<?php if (!empty($result)) { ?>
	<pre><?php echo h(implode("\n\n", $result)); ?></pre>

<?php } elseif (!empty($schema)) { ?>

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
			<?php echo $this->Form->hidden('dto.' . $dtoName . '.' . $fieldName . '.required', ['value' => $details['required']]); ?>
			</li>
		<?php } ?>
		</ul>
	<?php } ?>

	<?php echo $this->Form->hidden('namespace'); ?>

	<?php echo $this->Form->button('Generate'); ?>
	<?php echo $this->Form->end(); ?>

<?php } else { ?>

	<?php echo $this->Form->create(); ?>

	<?php echo $this->Form->control('input', ['type' => 'textarea', 'rows' => 20]); ?>
	<?php echo $this->Form->control('type', ['type' => 'select', 'options' => \CakeDto\Importer\Parser\Config::typeLabels(), 'empty' => ['' => 'Auto-Detect']]); ?>
	<?php echo $this->Form->control('namespace', ['type' => 'text', 'placeholder' => 'Optional namespace prefix']); ?>

	<?php echo $this->Form->button('Parse'); ?>
	<?php echo $this->Form->end(); ?>

<?php } ?>
