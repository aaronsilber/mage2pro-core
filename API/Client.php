<?php
namespace Df\API;
use Df\API\Exception as E;
use Df\API\Response\Validator;
use Df\Core\Exception as DFE;
use Zend_Http_Client as C;
use Zend\Filter\FilterChain;
use Zend\Filter\FilterInterface as IFilter;
/**
 * 2017-07-05
 * @see \Df\Zoho\API\Client
 * @see \Dfe\Dynamics365\API\Client
 * @see \Dfe\Salesforce\API\Client
 */
abstract class Client {
	/**
	 * 2017-07-05
	 * @used-by __construct()
	 * @used-by p()
	 * @see \Df\ZohoBI\API\Client::uriBase()
	 * @see \Dfe\Dynamics365\API\Client::uriBase()
	 * @see \Dfe\Salesforce\API\Client::uriBase()
	 * @see \Dfe\ZohoCRM\API\Client::uriBase()
	 * @return string
	 */
	abstract protected function uriBase();

	/**
	 * 2017-07-02
	 * @used-by \Df\Zoho\API\Client::i()
	 * @used-by \Dfe\Dynamics365\API\Facade::metadata()
	 * @used-by \Dfe\Dynamics365\API\Facade::p()
	 * @param string $path
	 * @param array(string => mixed) $p [optional]
	 * @param string|null $method [optional]
	 * @throws DFE
	 */
	final function __construct($path, array $p = [], $method = null) {
		$this->_path = $path;
		$this->_c = new C;
		$this->_c->setMethod($method = $method ?: C::GET);
		$p += $this->commonParams($path);
		C::GET === $method ? $this->_c->setParameterGet($p) : $this->_c->setParameterPost($p);
		/**
		 * 2017-07-06
		 * @uses uriBase() is important here, because the rest cache key parameters can be the same
		 * for multiple APIs (e.g. for Zoho Books and Zoho Inventory).
		 * 2017-07-07
		 * @uses headers() is important here, because the response can depend on the HTTP headers
		 * (it is true for Microsoft Dynamics 365 and Zoho APIs,
		 * because the authentication token is passed through the headers).
		 */
		$this->_ckey = dfa_hash([$this->uriBase(), $path, $method, $p, $this->headers()]);
		$this->_filters = new FilterChain;
		$this->_construct();
	}

	/**
	 * 2017-06-30
	 * @used-by \Dfe\Dynamics365\API\Facade::p()
	 * @used-by \Dfe\ZohoBooks\API\R::p()
	 * @throws DFE
	 * @return string
	 */
	final function p() {return df_cache_get_simple($this->_ckey, function() {
		$c = $this->_c; /** @var C $c */
		$c->setConfig(['timeout' => 120]);
		$c->setHeaders($this->headers());
		$c->setUri("{$this->uriBase()}/$this->_path");
		try {
			$r = $this->_filters->filter($c->request()->getBody()); /** @var mixed $r */
			if ($validatorC = $this->responseValidatorC() /** @var string $validatorC */) {
				$validator = new $validatorC($r); /** @var Validator $validator */
				if (!$validator->valid()) {
					throw $validator;
				}
			}
		}
		catch (\Exception $e) {
			/** @var string $long */ /** @var string $short */
			list($long, $short) = $e instanceof E ? [$e->long(), $e->short()] : [null, df_ets($e)];
			$req = df_zf_http_last_req($c); /** @var string $req */
			$title = df_api_name($m = df_module_name($this)); /** @var string $m */ /** @var string $title */
			/** @var DFE $ex */
			$ex = df_error_create(
				"The «{$this->_path}» {$title} API request has failed: «{$short}».\n"
				.df_cc_kv(['The full error description' => $long, 'The full request' => $req])
			);
			df_log_l($m, $ex);
			df_sentry($m, $short, ['extra' => ['Request' => $req, 'Response' => $long]]);
			throw $ex;
		}
		return $r;
	});}

	/**
	 * 2017-07-06
	 * @used-by __construct()
	 * @see \Df\Zoho\API\Client::_construct()
	 * @see \Dfe\Dynamics365\API\Client\JSON::_construct()
	 * @see \Dfe\Salesforce\API\Client::_construct()
	 */
	protected function _construct() {}

	/**
	 * 2017-07-06
	 * @used-by addFilterJsonDecode()
	 * @param callable|IFilter $f
	 * @param int $priority
	 */
	final protected function addFilter($f, $priority = FilterChain::DEFAULT_PRIORITY) {
		$this->_filters->attach($f, $priority);
	}

	/**
	 * 2017-07-06
	 * @used-by \Df\Zoho\API\Client::_construct()
	 * @used-by \Dfe\Dynamics365\API\Client\JSON::_construct()
	 * @used-by \Dfe\Salesforce\API\Client::_construct()
	 */
	final protected function addFilterJsonDecode() {$this->addFilter('df_json_decode');}

	/**
	 * 2017-07-07
	 * Adds $f at the lowest priority (it will be applied after all other filters).
	 * Currently, it is not used anywhere.
	 * @param callable|IFilter $f
	 */
	final protected function appendFilter($f) {$this->_filters->attach(
		$f, df_zf_pq_min($this->_filters->getFilters()) - 1
	);}

	/**
	 * 2017-07-08
	 * @used-by __construct()
	 * @see \Df\ZohoBI\API\Client::commonParams()
	 * @param string $path
	 * @return array(string => mixed)
	 */
	protected function commonParams($path) {return [];}

	/**
	 * 2017-07-06
	 * @used-by p()
	 * @used-by \Df\Zoho\API\Client::_construct()
	 * @used-by \Dfe\Dynamics365\API\Client\JSON::_construct()
	 * @return FilterChain
	 */
	final protected function filters() {return dfc($this, function() {return new FilterChain;});}

	/**
	 * 2017-07-05
	 * @used-by __construct()
	 * @used-by p()
	 * @see \Df\ZohoBI\API\Client::headers()
	 * @see \Dfe\Dynamics365\API\Client::headers()
	 * @see \Dfe\Salesforce\API\Client::headers()
	 * @return array(string => string)
	 */
	protected function headers() {return [];}

	/**
	 * 2017-07-05 A descendant class can return null if it does not need to validate the responses.
	 * @used-by p()
	 * @see \Df\ZohoBI\API\Client::responseValidatorC()
	 * @see \Dfe\Dynamics365\API\Client\JSON::responseValidatorC()
	 * @return string
	 */
	protected function responseValidatorC() {return null;}

	/**
	 * 2017-07-02
	 * @used-by __construct()
	 * @used-by p()
	 * @var C
	 */
	private $_c;

	/**
	 * 2017-07-02
	 * @used-by __construct()
	 * @used-by p()
	 * @var string
	 */
	private $_ckey;

	/**
	 * 2017-07-06
	 * @used-by __construct()
	 * @used-by addFilter()
	 * @used-by p()
	 * @var FilterChain
	 */
	private $_filters;

	/**
	 * 2017-07-02
	 * @used-by __construct()
	 * @used-by p()
	 * @var string
	 */
	private $_path;
}