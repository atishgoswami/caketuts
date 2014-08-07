<!-- File: /app/View/Posts/view.ctp -->

<h1><?php echo h($result['aro']['details']['alias']); ?></h1>
<?php if (!empty($result['aro']['parents'])) { ?>
	<p>
		<small>
		<span>Inherits:</span>
		<?php foreach ($result['aro']['parents'] as $key => $value) { ?>
			<?php
				echo $this->Html->link(
					$value,
					array(
			 		 'controller' => 'groups',
			 		 'action' => 'view',
			 		 $key
					)
			    );
			?>
		<?php } ?>
	 	</small>
	</p>

<?php } ?>

<?php if (!empty($result['permissions'])) { ?>
	<h1><?php echo __("Permission"); ?></h1>
	<div>
		<table>
			<thead>
				<tr>
					<th><?php echo __("Entry Alias"); ?></th>
					<th><?php echo __("Assigned To"); ?></th>
					<th><?php echo __("Authorization"); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($result['permissions'] as $perm) { ?>
					<tr>
						<td><?php echo $perm['Aco']['alias']; ?></td>
						<td>
							<?php
								echo $this->Html->link(
									$perm['Aro']['alias'],
									array(
							 		 'controller' => 'groups',
							 		 'action' => 'view',
							 		 $perm['Aro']['id']
									)
							    );
							?>
						</td>
						<td><?php echo ($perm['granted']) ? __("Allowed"):__("Denied"); ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php } ?>