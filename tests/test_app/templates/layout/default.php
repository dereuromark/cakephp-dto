<!DOCTYPE HTML>
<html>
<head>
</head>
<body>

	<section class="content">

		<div class="row messages">
			<?= $this->Flash->render() ?>
		</div>

		<div class="row">
			<?= $this->fetch('content') ?>
		</div>

	</section>
</body>
</html>
