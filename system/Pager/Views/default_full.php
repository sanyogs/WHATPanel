<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('hd_lang.Pager.pageNavigation') ?>">
	<ul class="pagination">
		<?php if ($pager->hasPrevious()) : ?>
			<li>
				<a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('hd_lang.Pager.first') ?>">
					<span aria-hidden="true"><?= lang('hd_lang.Pager.first') ?></span>
				</a>
			</li>
			<li>
				<a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('hd_lang.Pager.previous') ?>">
					<span aria-hidden="true"><?= lang('hd_lang.Pager.previous') ?></span>
				</a>
			</li>
		<?php endif ?>

		<?php foreach ($pager->links() as $link) : ?>
			<li <?= $link['active'] ? 'class="active"' : '' ?>>
				<a href="<?= $link['uri'] ?>">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<li>
				<a href="<?= $pager->getNext() ?>" aria-label="<?= lang('hd_lang.Pager.next') ?>">
					<span aria-hidden="true"><?= lang('hd_lang.Pager.next') ?></span>
				</a>
			</li>
			<li>
				<a href="<?= $pager->getLast() ?>" aria-label="<?= lang('hd_lang.Pager.last') ?>">
					<span aria-hidden="true"><?= lang('hd_lang.Pager.last') ?></span>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>
