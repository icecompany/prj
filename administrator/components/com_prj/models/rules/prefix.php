<?php
use Joomla\CMS\Form\FormRule;
defined('_JEXEC') or die;

class JFormRulePrefix extends FormRule
{
    protected $regex = '^[А-Яа-яA-Za-z\.\s-]{0,15}$';
}