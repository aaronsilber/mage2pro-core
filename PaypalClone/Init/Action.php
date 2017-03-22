<?php
namespace Df\PaypalClone\Init;
use Df\PaypalClone\Charge;
/**
 * 2017-03-21
 * @see \Dfe\AllPay\Init\Action
 * @see \Dfe\SecurePay\Init\Action
 * @method \Df\PaypalClone\Method m()
 */
abstract class Action extends \Df\Payment\Init\Action {
	/**
	 * 2017-03-21
	 * @override
	 * @see \Df\Payment\Init\Action::redirectParams()
	 * @used-by \Df\Payment\Init\Action::action()
	 * @return array(string => mixed)
	 */
	final protected function redirectParams() {return df_last($this->charge());}

	/**
	 * 2017-03-21
	 * @override
	 * @see \Df\Payment\Init\Action::transId()
	 * @used-by \Df\Payment\Init\Action::action()
	 * @used-by action()
	 * @return string|null
	 */
	final protected function transId() {return $this->m()->e2i(df_first($this->charge()));}

	/**
	 * 2017-03-21
	 * @used-by redirectParams()
	 * @used-by transId()
	 * @return array(string, array(string => mixed))
	 */
	private function charge() {return dfc($this, function() {return Charge::p($this->m());});}
}