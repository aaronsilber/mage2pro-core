// 2016-07-16
define([
	'./mixin', 'df', 'jquery', 'ko', 'Magento_Payment/js/view/payment/cc-form'
], function(mixin, df, $, ko, parent) {'use strict'; return parent.extend(df.o.merge(mixin, {
	/**
	 * 2016-08-26
	 * Возвращает строку из 2 последних цифр суммы платежа.
	 * @returns {String}
	 */
	amoutLast2: df.c(function() {return df.money.last2(this.dfc.grandTotal());}),
	defaults: {
		df: {
			card: {
				expirationMonth: 'expirationMonth'
				,expirationYear: 'expirationYear'
				,number: 'number'
				,verification: 'verification'
			},
			// 2016-08-06
			// @used-by mage2pro/core/Payment/view/frontend/web/template/item.html
			formTemplate: 'Df_Payment/card'
		}
	},
	dfCardExpirationMonth: function() {return this.dfInputValueByData(this.df.card.expirationMonth);},
	dfCardExpirationYear: function() {return this.dfInputValueByData(this.df.card.expirationYear);},
	dfCardNumber: function() {return this.dfInputValueByData(this.df.card.number);},
	dfCardVerification: function() {return this.dfInputValueByData(this.df.card.verification);},
	/**
	 * 2016-08-04
	 * @param {String} value
	 * @returns {String}
	 */
	dfInputValueByData: function(value) {return this.dfFormElementByAttr('data', value).val();},
	/**
	 * 2016-08-04
	 * @override
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Payment/view/frontend/web/js/view/payment/cc-form.js#L98-L104
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L203-L208
	 * @returns {String}
	 */
	getCode: function() {return this.item.method;},
	/**
	 * 2016-08-06
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/js/view/payment/mixin.js
	 * @used-by getData()
	 * @returns {Object}
	 */
	dfData: function() {return {token: this.token};},
	/**
	 * 2016-08-16
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/js/view/payment/mixin.js
	 * @used-by dfFormCssClassesS()
	 * @returns {String[]}
	 */
	dfFormCssClasses: function() {return mixin.dfFormCssClasses.call(this).concat(['df-card']);},
	/**
	 * 2016-08-23
	 * @return {Object}
	*/
	initialize: function() {
		this._super();
		mixin.initialize.apply(this);
		this.savedCards = this.config('savedCards');
		this.hasSavedCards = !!this.savedCards.length;
		this.newCardId = 'new';
		this.currentCard = ko.observable(!this.hasSavedCards ? this.newCardId : this.savedCards[0].id);
		this.isNewCardChosen = ko.computed(function() {
			return this.newCardId === this.currentCard();
		}, this);
		return this;
	},
	/**
	 * 2016-08-26
	 * http://stackoverflow.com/a/6002276
	 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/getFullYear
	 */
	prefillWithAFutureData: function() {
		this.creditCardExpMonth(7);
		this.creditCardExpYear(1 + new Date().getFullYear());
	},
	/**
	 * 2016-08-06
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/js/view/payment/mixin.js
	 * @return {Boolean}
	*/
	validate: function() {
		/** @type {Boolean} */
		var result = !this.isNewCardChosen() || !!this.selectedCardType();
		if (!result) {
			this.showErrorMessage('It looks like you have entered an incorrect bank card number.');
		}
		return result && this._super();
	}
}));});
