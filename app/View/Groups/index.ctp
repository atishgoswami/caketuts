<!-- File: /app/View/Groups/index.ctp -->
<h1><?php echo __("Blog User Groups"); ?></h1>
<p><?php echo $this->Html->link('Add New Group', array('action' => 'add')); ?></p>
<table>
    <tr>
        <th>Id</th>
        <th>Group Name</th>
        <th>Parent</th>
        <th>Actions</th>
    </tr>
<!-- Here's where we loop through our $groups array, printing out post info -->

    <?php foreach ($groups as $group): ?>
    <tr>
        <td><?php echo $group['Aro']['id']; ?></td>
        <td>
            <?php
                echo $this->Html->link(
                    $group['Aro']['alias'],
                    array('action' => 'view', $group['Aro']['id'])
                );
            ?>
        </td>
        <td>
            <?php
                if (!empty($group['Aro']['parent_id'])) {
                    echo $groupList[$group['Aro']['parent_id']];
                } else {
                    echo "Null";
                }
            ?>
        </td>
        <td>
            <?php
                echo $this->Form->postLink(
                    'Delete',
                    array('action' => 'delete', $group['Aro']['id']),
                    array('confirm' => 'Are you sure?')
                );
            ?>
            <?php
                echo $this->Html->link(
                    'Edit', array('action' => 'edit', $group['Aro']['id'])
                );
            ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>