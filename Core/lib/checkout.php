<?php
use Df\Checkout\Model\Session as DfSession;
use Magento\Checkout\Model\Session;
use Magento\Framework\Phrase;
/**
 * 2016-07-14
 * @param string|Phrase ...
 * @return void
 */
function df_checkout_error() {df_checkout_message(df_format(func_get_args()), false);}

/**
 * 2016-07-14
 * Сообщение показывается всего на 5 секунд, а затем скрывается: https://mage2.pro/t/1871
 * @param string|Phrase $text
 * @param bool $success
 * @return void
 */
function df_checkout_message($text, $success) {
	/** @var array(array(string => bool|Phrase)) $messages */
	$messages = df_checkout_session()->getMessagesDf();
	/**
	 * 2016-07-14
	 * @used-by https://github.com/mage2pro/core/blob/539a6c4/Checkout/view/frontend/web/js/messages.js?ts=4#L17
	 */
	$messages[]= ['text' => df_phrase($text), 'success' => $success];
	df_checkout_session()->setMessagesDf($messages);
}

/**
 * 2016-05-06
 * @return Session|DfSession
 */
function df_checkout_session() {return df_o(Session::class);}

/**
 * 2016-07-05
 * @return string
 */
function df_url_checkout_success() {return df_url('checkout/onepage/success');}

