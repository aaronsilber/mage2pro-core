<?php
namespace Df\Payment\R;
use Df\Payment\Method;
use Magento\Sales\Model\Order\Payment\Transaction;
// 2016-07-09
// Портировал из Российской сборки Magento.
abstract class Response extends \Df\Core\O {
	/**
	 * 2016-07-09
	 * @used-by \Df\Payment\R\Response::validate()
	 * @return bool
	 */
	abstract protected function isSuccessful();

	/**
	 * 2016-07-09
	 * @used-by \Df\Payment\R\Response::validate()
	 * @return string
	 */
	abstract protected function messageKey();

	/**
	 * 2016-07-09
	 * @used-by \Df\Payment\R\Response::requestId()
	 * @return string
	 */
	abstract protected function requestIdKey();

	/**
	 * 2016-07-10
	 * @used-by \Df\Payment\R\Response::signatureProvided()
	 * @return string
	 */
	abstract protected function signatureKey();

	/**
	 * 2016-07-10
	 * @return Report
	 */
	public function report() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = Report::ic(
				df_convention_same_folder($this, 'Report', Report::class), $this
			);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-10
	 * @used-by
	 * @return array(string => mixed)
	 */
	public function requestParams() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = $this->requestInfo();
			unset($this->{__METHOD__}[Method::TRANSACTION_PARAM__URL]);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-10
	 * @return string
	 */
	public function requestUrl() {return dfa( $this->requestInfo(), Method::TRANSACTION_PARAM__URL);}

	/**
	 * 2016-07-09
	 * @return void
	 * @throws \Exception
	 */
	public function validate() {
		if (!$this->isSuccessful()) {
			$this->throwException($this->message());
		}
		$this->validateSignature();
	}

	/**
	 * 2016-07-09
	 * @used-by \Df\Payment\R\Response::validate()
	 * @return void
	 * @throws \Exception
	 */
	private function validateSignature() {
		/** @var string $expected */
		$expected = $this->signer()->sign();
		/** @var string $provided */
		$provided = $this->signatureProvided();
		if ($expected !== $provided) {
			$this->throwException(
				"Invalid signature.\nExpected: «%s».\nProvided: «%s».", $expected, $provided
			);
		}
	}

	/**
	 * 2016-07-10
	 * @param Exception|string $message
	 * @return void
	 * @throws Exception
	 */
	protected function throwException($message) {
		/** @var Exception $exception */
		if ($message instanceof Exception) {
			$exception = $message;
		}
		else {
			/** @var mixed[] $arguments */
			$arguments = func_get_args();
			/** @var string $exceptionClass */
			$exceptionClass = $this->exceptionC();
			/** @var Exception $exception */
			$exception = new $exceptionClass(df_format($arguments), $this);
		}
		df_error($exception);
	}

	/**
	 * 2016-07-10
	 * @return string
	 */
	private function exceptionC() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = df_convention_same_folder($this, 'Exception', Exception::class);
			df_assert_is($this->{__METHOD__}, Method::class);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-10
	 * @return string
	 */
	private function message() {return $this[$this->messageKey()];}

	/**
	 * 2016-07-10
	 * @return string
	 */
	private function methodC() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = df_convention($this, 'Method');
			df_assert_is($this->{__METHOD__}, Method::class);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-09
	 * @return string
	 */
	private function requestId() {return $this[$this->requestIdKey()];}

	/**
	 * 2016-07-10
	 * @return string
	 */
	private function requestIdL() {
		if (!isset($this->{__METHOD__})) {
			/** @uses \Df\Payment\Method::transactionIdG2L() */
			$this->{__METHOD__} = call_user_func([$this->methodC(), 'transactionIdG2L']);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-10
	 * @return array(string => mixed)
	 */
	private function requestInfo() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = $this->transaction()->getAdditionalInformation(
				Transaction::RAW_DETAILS
			);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-10
	 * @return Signer
	 */
	private function signer() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = df_create(
				df_convention_same_folder($this, 'Signer'), $this->getData()
			);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-10
	 * @return string
	 */
	private function signatureProvided() {return $this[$this->signatureKey()];}

	/**
	 * 2016-07-10
	 * @return Transaction
	 */
	private function transaction() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = df_load(Transaction::class, $this->requestIdL());
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-09
	 * http://php.net/manual/en/function.get-called-class.php#115790
	 * @param array(string => mixed) $params
	 * @return self
	 */
	public static function i(array $params) {return df_create(static::class, $params);}
}