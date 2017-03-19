<?php
namespace Df\Payment;
/**
 * 2016-12-25
 * @see \Df\Payment\Action\CustomerReturn
 * @see \Df\Payment\W\Action
 * @method \Df\Payment\Settings s()
 */
abstract class Action extends \Df\Framework\Action {
	/**
	 * 2016-12-25
	 * @used-by \Df\Payment\Action\CustomerReturn::execute()
	 * @return bool
	 */
	final protected function needLog() {return $this->s()->log();}
}