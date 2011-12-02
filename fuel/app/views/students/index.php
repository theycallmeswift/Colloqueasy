<h2 class="first">Listing Students</h2>

<?php if ($students): ?>
<table cellspacing="0">
  <tr>
    <th>Id</th>
    <th>First name</th>
    <th>Last name</th>
    <th>Email</th>
    <th>Gender</th>
    <th>Created at</th>
    <th>Updated at</th>
    <th></th>
  </tr>

  <?php foreach ($students as $student): ?> <tr>

    <td><?php echo $student->id; ?></td>
    <td><?php echo $student->first_name; ?></td>
    <td><?php echo $student->last_name; ?></td>
    <td><?php echo $student->email; ?></td>
    <td><?php echo $student->gender; ?></td>
    <td><?php echo $student->created_at; ?></td>
    <td><?php echo $student->updated_at; ?></td>
    <td>
      <?php echo Html::anchor('students/view/'.$student->id, 'View'); ?> |
      <?php echo Html::anchor('students/edit/'.$student->id, 'Edit'); ?> |
      <?php echo Html::anchor('students/delete/'.$student->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>   </td>
  </tr>
  <?php endforeach; ?></table>

<?php else: ?>
<p>No Entries.</p>

<?php endif; ?>
<br />

<?php echo Html::anchor('students/create', 'Add new Student'); ?>
