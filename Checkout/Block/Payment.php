<?php
namespace Df\Checkout\Block;
use Magento\Framework\View\Element\AbstractBlock;
/**
 * 2016-08-17
 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
 * Цель этого блока — добавить на страницу оформления заказа JavaScript,
 * который донастроит внешний вид и поведение блока способов оплаты.
 * @used-by https://github.com/mage2pro/core/blob/2.3.3/Checkout/view/frontend/layout/checkout_index_index.xml#L14
 */
class Payment extends AbstractBlock {
	/**
	 * 2016-08-17
	 * 2017-04-04
	 * @uses Df_Checkout/payment
	 * https://github.com/mage2pro/core/blob/2.4.26/Checkout/view/frontend/web/payment.js
	 * @override
	 * @see AbstractBlock::_toHtml()
	 * @used-by \Magento\Framework\View\Element\AbstractBlock::toHtml()
	 * @return string
	 */
	final protected function _toHtml() {return df_x_magento_init(__CLASS__, 'payment');}
}


