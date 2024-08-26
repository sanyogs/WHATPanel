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
$pager->setSurroundCount(0);
?>
<nav>
	<ul class="pager">
		<li <?= $pager->hasPrevious() ? '' : 'class="disabled"' ?>>
			<a href="<?= $pager->getPrevious() ?? '#' ?>" aria-label="<?= lang('hd_lang.Pager.previous') ?>">
				<span aria-hidden="true"><?= lang('hd_lang.Pager.newer') ?></span>
			</a>
		</li>
		<li <?= $pager->hasNext() ? '' : 'class="disabled"' ?>>
			<a href="<?= $pager->getnext() ?? '#' ?>" aria-label="<?= lang('hd_lang.Pager.next') ?>">
				<span aria-hidden="true"><?= lang('hd_lang.Pager.older') ?></span>
			</a>
		</li>
	</ul>
</nav>
