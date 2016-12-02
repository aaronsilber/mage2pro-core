<?php
namespace Df\Sso\Install;
abstract class Data extends \Df\Framework\Install\Data {
	/**
	 * 2016-06-05
	 * @used-by \Df\Sso\Install\Data::attribute()
	 * @return string
	 */
	abstract protected function labelPrefix();

	/**
	 * 2016-12-02
	 * @override
	 * @see \Df\Framework\Install::_process()
	 * @used-by \Df\Framework\Install::process()
	 * @return void
	 */
	protected function _process() {
		if ($this->isInitial()) {
			$this->attribute(Schema::fIdC($this), 'User ID');
		}
	}

	/**
	 * 2015-10-10
	 * @param string $name
	 * @param string $label
	 * @return void
	 */
	final protected function attribute($name, $label) {
		/** @var int $ordering */
		static $ordering = 1000;
		df_eav_setup()->addAttribute('customer', $name, [
			'type' => 'static',
			'label' => "{$this->labelPrefix()} $label",
			'input' => 'text',
			'sort_order' => $ordering,
			'position' => $ordering++,
			'visible' => false,
			'system' => false,
			'required' => false
		]);
		/** @var int $attributeId */
		$attributeId = df_first(df_fetch_col('eav_attribute', 'attribute_id', 'attribute_code', $name));
		df_conn()->insert(df_table('customer_form_attribute'), [
			'form_code' => 'adminhtml_customer', 'attribute_id' => $attributeId
		]);
	}
}