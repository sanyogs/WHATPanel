<?= $this->extend("layouts/users") ?>   

<?= $this->section("content") ?>

<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ $session = \Config\Services::session(); if($session->getFlashData('message')): ?>
<div class="alert alert-info alert-dismissible">
	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<?php //echo $session->getFlashData('message') ?>
</div>
<?php endif ?>
 <?php
	if(!is_array($data)) {
		echo $data;
	}
	?>
<section id="hosting-services-wrap">
	<div class="container px-0">
		<div class="hs-table-wrap">
			<div class="hs-table-overflow table-responsive">
				<?php
				$attributes = array('class' => 'bs-example form-horizontal');
				echo form_open(base_url().'servers/import/'.$id, $attributes); 
				?>
				<table class="hs-table">
					<tr>
						<th><?=lang('hd_lang.domain')?></th> 
                        <th><?=lang('hd_lang.username')?></th> 
                        <td><?=lang('hd_lang.email')?></td>
                        <td><?=lang('hd_lang.client')?></td>
                        <td><?=lang('hd_lang.package_name')?></td>
                        <td><?=lang('hd_lang.package')?></td>
                        <td><?=lang('hd_lang.server')?></td>
                        <td><?=lang('hd_lang.import')?></td>
					</tr>
					<?php
                    $data = (is_array($data)) ? $data : array();                    
                    foreach ($data as $acc) {
					?>
					<tr>
						<td><?=$acc['domain']?></td>
                        <td><?=(isset($acc['user'])) ? $acc['user'] : ''?></td>
                        <td><?=$acc['email']?></td>
                        <td><?=(isset($acc['client'])) ? $acc['client'] : '<span class="label label-default">'.lang('hd_lang.will_create')?>  						  </td>
                        <td><?=$acc['plan']?></td>
                        <td><?=(isset($acc['package'])) ? $acc['package'] : lang('hd_lang.not_found')?></td>    
                        <td><?=(isset($acc['server'])) ? $acc['server'] : ''?></td>   
						<td><?=(isset($acc['import']) && $acc['import'] == 1) ? '<input type="checkbox" checked name="'.$acc['domain'].'"': ''?></td>
					</tr>
					<?php }  ?>
					<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>    
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><button class="btn btn-success btn-sm btn-block" style="font-size: 1.6rem;background-color: #1912d3;border-radius: 0.5rem;padding: 0.8rem 1.2rem;display: flex;align-items: center;justify-content: center;gap: 10px;cursor: pointer;color:white !important;"><?=lang('hd_lang.import')?></button></td>                 
                    </tr>
					</form>
			<div class="hs-table-pagination">
				<div class="showingEntriesWrap">
					<p>Showing 1 of 57 entries</p>
				</div>
				<div class="hs-pagination-wrap">
					<ul class="hs-pagination">
						<li class="page-item">
							<a class="page-link" href="#">Previous</a>
						</li>
						<li class="page-item active">
							<a class="page-link" href="#">1</a>
						</li>
						<li class="page-item">
							<a class="page-link" href="#">2</a>
						</li>
						<li class="page-item">
							<a class="page-link" href="#">3</a>
						</li>
						<li class="page-item">
							<a class="page-link" href="#">Next</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
<?= $this->endSection() ?>   