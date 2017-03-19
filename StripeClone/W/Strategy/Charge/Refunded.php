<?php
namespace Df\StripeClone\W\Strategy\Charge;
use Df\Sales\Model\Order as DfOrder;
use Df\StripeClone\Method as M;
use Df\StripeClone\W\IRefund;
use Df\StripeClone\W\Handler;
// 2017-01-07
final class Refunded extends \Df\StripeClone\W\Strategy\Charge {
	/**
	 * 2017-01-07
	 * @override
	 * @see \Df\StripeClone\W\Strategy::_handle()
	 * @used-by \Df\StripeClone\W\Strategy::::handle()
	 * @return void
	 */
	protected function _handle() {
		/** @var Handler|IRefund $h */
		$h = df_ar($this->h(), IRefund::class);
		// 2017-01-18
		// Переводить здесь размер платежа из копеек (формата платёжной системы)
		// в рубли (формат Magento) не нужно: это делает dfp_refund().
		$this->resultSet((dfp_container_has($this->op(), M::II_TRANS, $h->eTransId()) ? null :
			dfp_refund($this->op() ,df_invoice_by_trans($this->o(), $h->nav()->pid()), $h->amount())
		) ?: 'skipped');
	}
}