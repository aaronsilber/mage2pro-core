<?php
namespace Df\PaypalClone;
use Magento\Sales\Model\Order\Creditmemo as CM;
use Magento\Sales\Model\Order\Payment as OP;
/**
 * 2016-08-30
 * @see \Dfe\SecurePay\Refund
 * @method Method m()
 */
abstract class Refund extends \Df\Payment\Operation {
	/**
	 * 2016-08-30
	 * @override
	 * @see \Df\Payment\Operation::amountFromDocument()
	 * @used-by \Df\Payment\Operation::amount()
	 * @return float
	 */
	final protected function amountFromDocument() {return $this->cm()->getGrandTotal();}

	/**
	 * 2016-08-30
	 * @return CM
	 */
	final protected function cm() {return $this->payment()->getCreditmemo();}
}