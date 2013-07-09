<?php
/**
 * @package     JoomJunk.Shoutbox
 *
 * @copyright   Copyright (C) 2011 - 2013 JoomJunk. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Form Field class for JoomJunk.
 * Provides radio button inputs for the jQuery insertation in Joomla 2.5 only
 *
 * @package     JoomJunk.Shoutbox
 * @subpackage  Form
 * @since       2.0
 */
class JFormFieldjQuery extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.3.0
	 */
	protected $type = 'jQuery';

	/**
	 * Method to get the list field input markup.
	 *
	 * @return  string  The field input markup if version is less than Joomla 3.0, else text string.
	 *
	 * @since   1.3.0
	 */
	protected function getInput()
	{
		if(version_compare(JVERSION,'3.0.0','ge')) {
			return '<p>'.JText::_('JJ_SOCIAL_SLIDER_NOJQUERY_30').'</p>';
		} else {
			$html = array();
			$attr = '';

			// Initialize some field attributes.
			$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

			// To avoid user's confusion, readonly="true" should imply disabled="true".
			if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
			{
				$attr .= ' disabled="disabled"';
			}

			$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
			$attr .= $this->multiple ? ' multiple="multiple"' : '';
			$attr .= $this->required ? ' required="required" aria-required="true"' : '';

			// Initialize JavaScript field attributes.
			$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

			// Get the field options.
			$options = (array) $this->getOptions();

			// Create a read-only list (no name) with a hidden input to store the value.
			if ((string) $this->element['readonly'] == 'true')
			{
				$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
				$html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
			}
			// Create a regular list.
			else
			{
				$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
			}

			return implode($html);
		}
	}

	/**
	 * Method to get the field options for radio buttons.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.3.0
	 */
	protected function getOptions()
	{
		$options = array();

		foreach ($this->element->children() as $option)
		{

			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
				'select.option', (string) $option['value'],
				JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
				((string) $option['disabled'] == 'true')
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}