<div class="modal-dialog my-modal">
    <div class="modal-content">
            <?php if ($type == 'hosting') : ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?=lang('hd_lang.hosting_account')?></h4>
        </div>
        <div class="modal-body">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->hosting_company?>
                    </span>
                    <?=lang('hd_lang.hosting_company')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->hostname?>
                    </span>
                    <?=lang('hd_lang.hostname')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->account_username?>
                    </span>
                    <?=lang('hd_lang.account_username')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->account_password?>
                    </span>
                    <?=lang('hd_lang.account_password')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->port?>
                    </span>
                    <?=lang('hd_lang.port')?>
                </li>
            </ul>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
        </div>
            <?php endif; ?>
            <?php if ($type == 'bank') : ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?=lang('hd_lang.bank_account')?></h4>
        </div>
        <div class="modal-body">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->bank?>
                    </span>
                    <?=lang('hd_lang.bank')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->bic?>
                    </span>
                    SWIFT/BIC
                </li>
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->sortcode?>
                    </span>
                    Sort Code
                </li>
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->account_holder?>
                    </span>
                    <?=lang('hd_lang.account_holder')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->account?>
                    </span>
                    <?=lang('hd_lang.account')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right">
                        <?=$client->iban?>
                    </span>
                    IBAN
                </li>
            </ul>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
        </div>
            <?php endif; ?>
</div>
</div>