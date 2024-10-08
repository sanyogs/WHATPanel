<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>

<div class="container inner">
        <div class="row"> 
		<div class="login-box"> 
			<table>
				<tr>
					<td><?php echo form_label('Password', $password['id']); ?></td>
					<td><?php echo form_password($password); ?></td>
					<td class="text-danger"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></td>
				</tr>
			</table>
			<?php echo form_submit('cancel', 'Delete account'); ?>
			<?php echo form_close(); ?>
</div>
</div>
</div>