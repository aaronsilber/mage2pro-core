<?php
namespace Df\Payment\W;
use Df\Framework\Controller\Response;
use Df\Payment\W\Exception\Ignored;
/**
 * 2016-08-27
 * 2017-03-19
 * The class is not abstract anymore: it is used as the base for the following virtual types:
 * 1) AllPay:
 * 1a) \Dfe\AllPay\Controller\Confirm\Index
 * 1b) \Dfe\AllPay\Controller\Offline\Index
 * 2) Dragonpay: https://github.com/mage2pro/dragonpay/blob/0.1.2/etc/di.xml#L7
 * 3) Ginger Payments: https://github.com/mage2pro/ginger-payments/blob/0.4.1/etc/di.xml#L6
 * 4) iPay88: https://github.com/mage2pro/ipay88/blob/0.0.9/etc/di.xml#L13
 * 5) Iyzico: https://github.com/mage2pro/iyzico/blob/0.2.3/etc/di.xml#L6
 * 6) Kassa Compleet: https://github.com/mage2pro/kassa-compleet/blob/0.4.1/etc/di.xml#L6
 * 7) Moip: https://github.com/mage2pro/moip/blob/0.0.1/etc/di.xml#L6
 * 8) Omise: https://github.com/mage2pro/omise/blob/1.7.1/etc/di.xml#L7
 * 9) Paymill: https://github.com/mage2pro/paymill/blob/1.3.1/etc/di.xml#L6
 * 10) PostFinance: https://github.com/mage2pro/postfinance/blob/0.1.2/etc/di.xml#L7
 * 11) QIWI Wallet: https://github.com/mage2pro/qiwi/blob/0.3.0/etc/di.xml#L7
 * 12) Robokassa: https://github.com/mage2pro/robokassa/blob/0.0.4/etc/di.xml#L6
 * 13) SecurePay: https://github.com/mage2pro/securepay/blob/1.4.1/etc/di.xml#L6
 * 14) Stripe: https://github.com/mage2pro/stripe/blob/1.9.1/etc/di.xml#L6
 * 15) Yandex.Kassa: https://github.com/mage2pro/yandex-kassa/blob/0.2.1/etc/di.xml#L7
 */
class Action extends \Df\Payment\Action {
	/**
	 * 2016-08-27
	 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @used-by \Magento\Framework\App\Action\Action::dispatch():
	 * 		$result = $this->execute();
	 * https://github.com/magento/magento2/blob/2.2.0-RC1.8/lib/internal/Magento/Framework/App/Action/Action.php#L84-L125
	 * @return Response
	 */
	function execute() {
		$m = $this->m(); /** @var string $m */
		$f = null; /** @var F|null $f */
		$responder = null; /** @var Responder|null $responder */
		$result = null; /** @var Response $result */
		try {
			$f = F::s($m);
			$responder = $f->responder();
			$f->handler()->handle();
		}
		catch (Ignored $e) {
			$this->ignoredLog($e);
			$responder->setIgnored($e);
		}
		catch (\Exception $e) {
			df_log_e($e);
			df_sentry($m, $e);
			if ($e instanceof IEvent && $e->r()) {
				df_log_l($m, $e->r());
			}
			if ($responder) {
				$responder->setError($e);
			}
			else {
				$result = Responder::defaultError($e);
			}
		}
		$result = $result ?: $responder->get();
		if (df_my()) {
			df_log_l($m, $result->__toString(), 'response');
		}
		/**
		 * 2017-01-07
		 * Иначе мы можем получить сложнодиагностируемый сбой «Invalid return type».
		 * @see \Magento\Framework\App\Http::launch()
		 * https://github.com/magento/magento2/blob/2.1.3/lib/internal/Magento/Framework/App/Http.php#L137-L145
		 */
		return df_ar(df_response_sign($result), Response::class);
	}

	/**
	 * 2017-01-17
	 * 2017-02-01
	 * Отныне игнорируемые операции логирую только на своих серверах.
	 * Аналогично поступаю и с @see \Df\Payment\Method::action():
	 * @see \Df\StripeClone\Method::needLogActions()
	 * @used-by execute()
	 * @param Ignored $e
	 */
	private function ignoredLog(Ignored $e) {
		if (df_my()) {
			dfp_sentry_tags($m = $e->m()); /** @var string $m */
			$req = $e->event()->r(); /** @var array(string => mixed) $req */
			$ev = $e->event(); /** @var Event $ev */
			$label = $ev->tl(); /** @var string $label */
			df_sentry($m, "[{$e->mTitle()}] {$label}: ignored", ['extra' => $req]);
			df_log_l($m, $req, $ev->t());
		}
	}
}