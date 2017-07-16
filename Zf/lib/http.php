<?php
use Zend_Http_Client as C;
/**
 * 2017-07-16
 * @used-by df_github_request()
 * @used-by df_oro_get_list()
 * @used-by \Df\API\Client::__construct()
 * @used-by \Df\OAuth\App::requestToken()
 * @used-by \Dfe\BlackbaudNetCommunity\Url::check()
 * @used-by \Dfe\Dynamics365\T\OAuth::discovery()
 * @used-by \Dfe\SecurePay\Refund::process
 * @used-by
 * @param string|null $url [optional]
 * @param array(string => mixed)|null $config [optional]
 * @return C
 */
function df_zf_http($url = null, $config = []) {
	$result = new C($url, $config + ['timeout' => 120]); /** @var C $result */
	return !df_check_https($url) || !df_contains($url, 'localhost') ? $result :
		$result->setAdapter((new \Zend_Http_Client_Adapter_Socket)->setStreamContext([
			'ssl' => ['allow_self_signed' => true, 'verify_peer' => false]
		]))
	;
}

/**
 * 2017-07-01
 * @used-by \Dfe\Dynamics365\API\Facade::p()
 * @param C $c
 * @return string
 */
function df_zf_http_last_req(C $c) {
	/** @var string $s */
	$s = $c->getLastRequest();
	/**
	 * 2017-07-13
	 * @see \Zend_Http_Client_Adapter_Socket::write():
	 *
	 *	foreach ($headers as $k => $v) {
	 * 		if (is_string($k)) $v = ucfirst($k) . ": $v";
	 * 		$request .= "$v\r\n";
	 *	}
	 * https://github.com/magento/zf1/blob/1.13.1/library/Zend/Http/Client/Adapter/Socket.php#L282-L285
	 *
	 * 	$request .= "\r\n" . $body;
	 * https://github.com/magento/zf1/blob/1.13.1/library/Zend/Http/Client/Adapter/Socket.php#L291
	 * @var string[] $sA
	 */
	$sA = explode("\r\n\r\n", $s);
	/** @var string[] $a */
	$a = df_clean(df_explode_n($sA[0]));
	return df_cc_n(array_merge([df_first($a)], array_map(function($s) {
		if (df_starts_with($s, $b = 'Authorization:')) {
			$s = "$b <...>";
		}
		return $s;
	}, df_sort_names(df_tail($a)))), df_tail($sA));
}