<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');
// HTMLHelper::_('behavior.modal', 'a.modal_jform_contenthistory');

$captchaEnabled = false;
$captchaSet = $this->params->get('captcha', Factory::getApplication()->get('captcha', '0'));

foreach (JPluginHelper::getPlugin('captcha') as $plugin)
{
	if ($captchaSet === $plugin->name)
	{
		$captchaEnabled = true;
		break;
	}
}

// Create shortcut to parameters.
$params = $this->state->get('params');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'weblink.cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			Joomla.submitform(task);
		}
	}
</script>
<div class="edit<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>
	<form action="<?php echo Route::_('index.php?option=com_weblinks&view=form&w_id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">

		<?php echo $this->form->renderField('title'); ?>
		<?php echo $this->form->renderField('alias'); ?>
		<?php echo $this->form->renderField('catid'); ?>
		<?php echo $this->form->renderField('url'); ?>
		<?php echo $this->form->renderField('tags'); ?>

		<?php if ($params->get('save_history', 0)) : ?>
			<?php echo $this->form->renderField('version_note'); ?>
		<?php endif; ?>

		<?php if ($this->user->authorise('core.edit.state', 'com_weblinks.weblink')) : ?>
			<?php echo $this->form->renderField('state'); ?>
		<?php endif; ?>
		<?php echo $this->form->renderField('language'); ?>
		<?php echo $this->form->renderField('description'); ?>
		
		<hr class="hr-condensed" />
		
		<?php if ($captchaEnabled) : ?>
			<div class="btn-group">
				<?php echo $this->form->renderField('captcha'); ?>
			</div>
		<?php endif; ?>

		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('weblink.save')">
					<span class="icon-ok"></span> <?php echo Text::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('weblink.cancel')">
					<span class="icon-cancel"></span> <?php echo Text::_('JCANCEL') ?>
				</button>
			</div>
			<?php if ($params->get('save_history', 0)) : ?>
				<div class="btn-group">
					<?php echo $this->form->getInput('contenthistory'); ?>
				</div>
			<?php endif; ?>
		</div>

		<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
		<input type="hidden" name="task" value="" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</form>
</div>
