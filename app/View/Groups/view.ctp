<!-- File: /app/View/Posts/view.ctp -->

<h1><?php echo h($group['Aro']['alias']); ?></h1>

<p><small>Parent: <?php echo ($group['Aro']['parent_id']) ?: "Root"; ?></small></p>

<p><?php echo h($group['Aro']['model']); ?></p>

<?php if (!empty($group['Aco'])) { ?>
	<div>
		<table>
			<thead>
				<tr>
					<th><?php echo __("Entry Alias"); ?></th>
				</tr>
			</thead>

			<tbody>

			</tbody>
		</table>
		<?php foreach ($group['Aco'] as $aco) { ?>
			<p><?php echo $aco['alias']; ?> - <?php echo $aco['Permission']['_create'] ?></p>
		<?php } ?>
	</div>
<?php } ?>