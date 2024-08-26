<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'settings/add_custom_field',$attributes); ?>

          <div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('hd_lang.department')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="targetdept" class="form-control" required >
					<?php 
					$departments = $this -> db -> where(array('deptid >'=>'0')) -> get('departments') -> result();
					if (!empty($departments)) {
						foreach ($departments as $d): ?>
                                            <option value="<?=$d->deptid?>"><?=ucfirst($d->deptname)?></option>
					<?php endforeach; } ?>
					</select> 
					</div> 
				</div>
			</div>
<button type="submit" class="btn btn-sm btn-info"><?=lang('hd_lang.select_department')?></button>

</form>