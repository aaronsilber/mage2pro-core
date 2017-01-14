<?php
namespace Df\Qa;
use Df\Zf\Validate\ArrayT as VArray;
use Df\Zf\Validate\Between as VBetween;
use Df\Zf\Validate\Boolean as VBoolean;
use Df\Zf\Validate\FloatT as VFloat;
use Df\Zf\Validate\IntT as VInt;
use Df\Zf\Validate\StringT as VString;
use Df\Zf\Validate\StringT\Iso2 as VIso2;
use \Exception as E;
use Zend_Validate_Interface as Vd;
final class Method {
	/**
	 * @param array $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return array
	 * @throws E
	 */
	public static function assertParamIsArray($v, $ord, $sl = 0) {return self::vp(
		VArray::s(), $v, $ord, ++$sl
	);}

	/**
	 * @param int|float $v
	 * @param int $ord
	 * @param int|float $min [optional]
	 * @param int|float $max [optional]
	 * @param int $sl [optional]
	 * @return int|float
	 * @throws E
	 */
	public static function assertParamIsBetween($v, $ord, $min = null, $max = null, $sl = 0) {return
		self::vp(VBetween::i($min, $max), $v, $ord, ++$sl)
	;}

	/**
	 * @param bool $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return bool
	 * @throws E
	 */
	public static function assertParamIsBoolean($v, $ord, $sl = 0) {return
		self::vp(VBoolean::s(), $v, $ord, ++$sl)
	;}

	/**
	 * @param float $v
	 * @param float $ord
	 * @param int $sl [optional]
	 * @return float
	 * @throws E
	 */
	public static function assertParamIsFloat($v, $ord, $sl = 0) {return
		self::vp(VFloat::s(), $v, $ord, ++$sl)
	;}

	/**
	 * @param int $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return int
	 * @throws E
	 */
	public static function assertParamIsInteger($v, $ord, $sl = 0) {return
		self::vp(VInt::s(), $v, $ord, ++$sl)
	;}

	/**
	 * @param string $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	public static function assertParamIsIso2($v, $ord, $sl = 0) {return
		self::vp(VIso2::s(), $v, $ord, ++$sl)
	;}

	/**
	 * @param string $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	public static function assertParamIsString($v, $ord, $sl = 0) {return
		self::vp(VString::s(), $v, $ord, ++$sl)
	;}

	/**
	 * @param array $v
	 * @param int $sl [optional]
	 * @return array
	 * @throws E
	 */
	public static function assertResultIsArray($v, $sl = 0) {return
		self::validateResult(VArray::s(), $v, ++$sl)
	;}

	/**
	 * @param int|float $v
	 * @param int|float $min [optional]
	 * @param int|float $max [optional]
	 * @param int $sl [optional]
	 * @return int|float
	 * @throws E
	 */
	public static function assertResultIsBetween($v, $min = null, $max = null, $sl = 0) {return
		self::validateResult(VBetween::i($min, $max), $v, ++$sl)
	;}

	/**
	 * @param bool $v
	 * @param int $sl [optional]
	 * @return bool
	 * @throws E
	 */
	public static function assertResultIsBoolean($v, $sl = 0) {return
		self::validateResult(VBoolean::s(), $v, ++$sl)
	;}

	/**
	 * @param float $v
	 * @param int $sl [optional]
	 * @return float
	 * @throws E
	 */
	public static function assertResultIsFloat($v, $sl = 0) {return
		self::validateResult(VFloat::s(), $v, ++$sl)
	;}

	/**
	 * @param int $v
	 * @param int $sl [optional]
	 * @return int
	 * @throws E
	 */
	public static function assertResultIsInteger($v, $sl = 0) {return
		self::validateResult(VInt::s(), $v, ++$sl)
	;}

	/**
	 * @param string $v
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	public static function assertResultIsIso2($v, $sl = 0) {return
		self::validateResult(VIso2::s(), $v, ++$sl)
	;}

	/**
	 * @param string $v
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	public static function assertResultIsString($v, $sl = 0) {return
		self::validateResult(VString::s(), $v, ++$sl)
	;}

	/**
	 * @param array $v
	 * @param int $sl [optional]
	 * @return array
	 * @throws E
	 */
	public static function assertValueIsArray($v, $sl = 0) {return
		self::validateValue(VArray::s(), $v, ++$sl)
	;}

	/**
	 * @param int|float $v
	 * @param int|float $min [optional]
	 * @param int|float $max [optional]
	 * @param int $sl [optional]
	 * @return int|float
	 * @throws E
	 */
	public static function assertValueIsBetween($v, $min = null, $max = null, $sl = 0) {return
		self::validateValue(VBetween::i($min, $max), $v, ++$sl)
	;}

	/**
	 * @param bool $v
	 * @param int $sl [optional]
	 * @return bool
	 * @throws E
	 */
	public static function assertValueIsBoolean($v, $sl = 0) {return
		self::validateResult(VBoolean::s(), $v, ++$sl)
	;}

	/**
	 * @param float $v
	 * @param int $sl [optional]
	 * @return float
	 * @throws E
	 */
	public static function assertValueIsFloat($v, $sl = 0) {return
		self::validateValue(VFloat::s(), $v, ++$sl)
	;}

	/**
	 * @param int $v
	 * @param int $sl [optional]
	 * @return int
	 * @throws E
	 */
	public static function assertValueIsInteger($v, $sl = 0) {return
		self::validateValue(VInt::s(), $v, ++$sl)
	;}

	/**
	 * @param string $v
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	public static function assertValueIsIso2($v, $sl = 0) {return
		self::validateValue(VIso2::s(), $v, ++$sl)
	;}

	/**
	 * @param string $v
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	public static function assertValueIsString($v, $sl = 0) {return
		self::validateValue(VString::s(), $v, ++$sl)
	;}

	/**
	 * @param string $method
	 * @param array $messages
	 * @param int $ord  zero-based
	 * @param int $sl
	 * @return void
	 */
	public static function raiseErrorParam($method, array $messages, $ord, $sl = 1) {
		/** @var \Df\Qa\State $state */
		$state = self::caller($sl);
		/** @var string $paramName */
		$paramName = 'Неизвестный параметр';
		if (!is_null($ord) && $state->method()) {
			/** @var \ReflectionParameter $methodParameter */
			$methodParameter = $state->methodParameter($ord);
			if ($methodParameter instanceof \ReflectionParameter) {
				$paramName = $methodParameter->getName();
			}
		}
		/** @var string $messagesS */
		$messagesS = df_cc_n($messages);
		self::throwException(
			"[{$state->methodName()}]"
			."\nThe argument «{$paramName}» is rejected by the «{$method}» validator."
			."\nThe diagnostic message:\n{$messagesS}\n\n"
			, $sl
		);
	}

	/**
	 * @param string $vd
	 * @param array $messages
	 * @param int $sl
	 * @return void
	 */
	public static function raiseErrorResult($vd, array $messages, $sl = 1) {
		/** @var string $messagesS */
		$messagesS = df_cc_n($messages);
		/** @var string $method */
		$method = self::caller($sl)->methodName();
		self::throwException(
			"[{$method}]\nA result of this method is rejected by the «{$vd}» validator."
			."\nThe diagnostic message:\n{$messagesS}\n\n"
			, $sl
		);
	}

	/**
	 * @param string $vd
	 * @param array $messages
	 * @param int $sl
	 * @return void
	 */
	public static function raiseErrorVariable($vd, array $messages, $sl = 1) {
		/** @var string $messagesS */
		$messagesS = df_cc_n($messages);
		/** @var string $method */
		$method = self::caller($sl)->methodName();
		self::throwException(
			"[{$method}]\nThe validator «{$vd}» has catched a variable with an invalid value."
			."\nThe diagnostic message:\n{$messagesS}\n\n"
			, $sl
		);
	}

	/**
	 * @param Vd $vd
	 * @param mixed $v
	 * @param int $sl
	 * @return mixed
	 * @throws E
	 */
	public static function validateResult(Vd $vd, $v, $sl = 1) {
		if (!$vd->isValid($v)) {
			self::raiseErrorResult(get_class($vd), $vd->getMessages(), ++$sl);
		}
		return $v;
	}

	/**
	 * @param Vd $vd
	 * @param mixed $v
	 * @param int $sl
	 * @return mixed
	 * @throws E
	 */
	public static function validateValue(Vd $vd, $v, $sl = 1) {
		if (!$vd->isValid($v)) {
			/** @var string $messagesS */
			$messagesS = df_cc_n($vd->getMessages());
			/** @var string $vdClass */
			$vdClass = get_class($vd);
			self::throwException(
				"The validator «{$vdClass}» has catched a variable with an invalid value."
				."\nThe diagnostic message:\n{$messagesS}"
				, $sl
			);
		}
		return $v;
	}

	/**
	 * 2017-01-12
	 * @used-by df_assert_string_not_empty()
	 * @used-by df_param_string_not_empty()
	 * @used-by df_result_string_not_empty()
	 */
	const NES = 'A non-empty string is required, but got an empty one.';

	/**
	 * Ообъект \Df\Qa\State конструируется на основе $sl + 2,
	 * потому что нам нужно вернуть название метода,
	 * который вызвал тот метод, который вызвал метод caller.
	 * @used-by raiseErrorParam()
	 * @used-by raiseErrorResult()
	 * @used-by raiseErrorVariable()
	 * @param int $offset [optional]
	 * @return \Df\Qa\State
	 */
	private static function caller($offset) {return \Df\Qa\State::i(
		debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3 + $offset)[2 + $offset]
	);}

	/**
	 * @param Vd $vd
	 * @param mixed $v
	 * @param int $ord
	 * @param int $sl
	 * @return mixed
	 * @throws E
	 */
	private static function vp(Vd $vd, $v, $ord, $sl = 1) {
		if (!$vd->isValid($v)) {
			self::raiseErrorParam(get_class($vd), $vd->getMessages(), $ord, ++$sl);
		}
		return $v;
	}

	/**
	 * @param string $message
	 * @param int $sl [optional]
	 * @throws E
	 * @return void
	 */
	private static function throwException($message, $sl = 0) {
		/**
		 * 2015-01-28
		 * Раньше тут стояло throw $e, что приводило к отображению на экране
		 * диагностического сообщения в неверной кодировке.
		 * @uses df_error() точнее: эта функция в режиме разработчика
		 * отсылает браузеру заголовок HTTP о требуемой кодировке.
		 */
		df_error(new \Exception($message, ++$sl));
	}
}