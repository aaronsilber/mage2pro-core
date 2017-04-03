<?php
use Df\Core\Exception as DFE;
use Df\Qa\Message\Failure\Exception as QE;
use Exception as E;
use Magento\Framework\DataObject;
use Magento\Framework\Logger\Monolog;
use Psr\Log\LoggerInterface as ILogger;

/**
 * @param int $levelsToSkip
 * Позволяет при записи стека вызовов пропустить несколько последних вызовов функций,
 * которые и так очевидны (например, вызов данной функции, вызов df_bt() и т.п.)
 */
function df_bt($levelsToSkip = 0) {
	/** @var array $bt */
	$bt = array_slice(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), $levelsToSkip);
	/** @var array $compactBT */
	$compactBT = [];
	/** @var int $traceLength */
	$traceLength = count($bt);
	/**
	 * 2015-07-23
	 * 1) Удаляем часть файлового пути до корневой папки Magento.
	 * 2) Заменяем разделитель папок на унифицированный.
	 */
	/** @var string $bp */
	$bp = BP . DS;
	/** @var bool $nonStandardDS */
	$nonStandardDS = DS !== '/';
	for ($traceIndex = 0; $traceIndex < $traceLength; $traceIndex++) {
		/** @var array $currentState */
		$currentState = dfa($bt, $traceIndex);
		/** @var array(string => string) $nextState */
		$nextState = dfa($bt, 1 + $traceIndex, []);
		/** @var string $file */
		$file = str_replace($bp, '', dfa($currentState, 'file'));
		if ($nonStandardDS) {
			$file = df_path_n($file);
		}
		$compactBT[]= [
			'File' => $file
			,'Line' => dfa($currentState, 'line')
			,'Caller' => !$nextState ? '' : df_cc_method($nextState)
			,'Callee' => !$currentState ? '' : df_cc_method($currentState)
		];
	}
	df_report('bt-{date}-{time}.log', print_r($compactBT, true));
}

/**
 * @param DataObject|mixed[]|mixed|E $v
 * @param array(string => mixed) $context [optional]
 */
function df_log($v, array $context = []) {
	df_log_l($v);
	df_sentry(null, $v, $context);
}

/**
 * 2017-01-11
 * @used-by df_log()
 * @used-by \Df\Payment\W\Handler::log()
 * @param DataObject|mixed[]|mixed|E $v
 */
function df_log_l($v) {
	if ($v instanceof E) {
		QE::i([QE::P__EXCEPTION => $v, QE::P__SHOW_CODE_CONTEXT => true])->log();
	}
	else {
		$v = df_dump($v);
		/** @var ILogger|Monolog $logger */
		$logger = df_o(ILogger::class);
		$logger->debug($v);
	}
}

/**
 * 2017-04-03
 * @used-by df_bt()
 * @used-by dfp_log_l()
 * @used-by \Df\Core\Text\Regex::throwInternalError()
 * @used-by \Df\Core\Text\Regex::throwNotMatch()
 * @used-by \Df\Qa\Message::log()
 * @param string $name
 * @param string $message
 */
function df_report($name, $message) {df_file_write(df_file_name(BP . '/var/log', $name), $message);}