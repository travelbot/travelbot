/**
 * Nette Framework - simple form validation.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 * @link       http://nette.org
 */

var nette = nette || { };

nette.getValue = function(elem) {
	if (!elem) {
		return null;

	} else if (!elem.nodeName) { // radio
		for (var i = 0, len = elem.length; i < len; i++) {
			if (elem[i].checked) {
				return elem[i].value;
			}
		}
		return null;

	} else if (elem.nodeName.toLowerCase() === 'select') {
		var index = elem.selectedIndex, options = elem.options;

		if (index < 0) {
			return null;

		} else if (elem.type === 'select-one') {
			return options[index].value;
		}

		for (var i = 0, values = [], len = options.length; i < len; i++) {
			if (options[i].selected) {
				values.push(options[i].value);
			}
		}
		return values;

	} else if (elem.type === 'checkbox') {
		return elem.checked;

	} else if (elem.type === 'radio') {
		return nette.getValue(elem.form.elements[elem.name]);

	} else {
		return elem.value.replace(/^\s+|\s+$/g, '');
	}
};


nette.validateControl = function(elem, rules, onlyCheck) {
	rules = rules || eval('[' + (elem.getAttribute('data-nette-rules') || '') + ']');
	for (var id in rules) {
		var rule = rules[id], op = rule.op.match(/(~)?([^?]+)/);
		rule.neg = op[1];
		rule.op = op[2];
		rule.condition = !!rule.rules;
		var el = rule.control ? elem.form.elements[rule.control] : elem;

		var success = nette.validateRule(el, rule.op, rule.arg);
		if (success === null) continue;
		if (rule.neg) success = !success;

		if (rule.condition && success) {
			if (!nette.validateControl(elem, rule.rules, onlyCheck)) {
				return false;
			}
		} else if (!rule.condition && !success) {
			if (el.disabled) continue;
			if (!onlyCheck) {
				nette.addError(el, rule.msg.replace('%value', nette.getValue(el)));
			}
			return false;
		}
	}
	return true;
};


nette.validateForm = function(sender) {
	var form = sender.form || sender;
	if (form['nette-submittedBy'] && form.elements[form['nette-submittedBy']] && form.elements[form['nette-submittedBy']].getAttribute('formnovalidate') !== null) {
		return true;
	}
	for (var i = 0; i < form.elements.length; i++) {
		var elem = form.elements[i];
		if (!(elem.nodeName.toLowerCase() in {input:1, select:1, textarea:1}) || (elem.type in {hidden:1, submit:1, image:1, reset: 1}) || elem.disabled || elem.readonly) {
			continue;
		}
		if (!nette.validateControl(elem)) {
			return false;
		}
	}
	return true;
};


nette.addError = function(elem, message) {
	if (elem.focus) {
		elem.focus();
	}
	if (message) {
		alert(message);
	}
};


nette.validateRule = function(elem, op, arg) {
	var val = nette.getValue(elem);

	if (elem.getAttribute) {
		if (val === elem.getAttribute('data-nette-empty-value')) val = null;
	}

	switch (op) {
	case ':filled':
		return val !== '' && val !== false && val !== null;

	case ':valid':
		return nette.validateControl(elem, null, true);

	case ':equal':
		arg = arg instanceof Array ? arg : [arg];
		for (var i in arg) {
			if (val == (arg[i].control ? nette.getValue(elem.form.elements[arg[i].control]) : arg[i])) return true;
		}
		return false;

	case ':minLength':
		return val.length >= arg;

	case ':maxLength':
		return val.length <= arg;

	case ':length':
		if (typeof arg !== 'object') {
			arg = [arg, arg];
		}
		return (arg[0] === null || val.length >= arg[0]) && (arg[1] === null || val.length <= arg[1]);

	case ':email':
		return /^[^@\s]+@[^@\s]+\.[a-z]{2,10}$/i.test(val);

	case ':url':
		return /^.+\.[a-z]{2,6}(\/.*)?$/i.test(val);

	case ':regexp':
		var parts = arg.match(/^\/(.*)\/([imu]*)$/);
		if (parts) try {
			return (new RegExp(parts[1], parts[2].replace('u', ''))).test(val);
		} catch (e) {}
		return;

	case ':integer':
		return /^-?[0-9]+$/.test(val);

	case ':float':
		return /^-?[0-9]*[.,]?[0-9]+$/.test(val);

	case ':range':
		return (arg[0] === null || parseFloat(val) >= arg[0]) && (arg[1] === null || parseFloat(val) <= arg[1]);

	case ':submitted':
		return elem.form['nette-submittedBy'] === elem.name;
	}
	return null;
};


nette.toggleForm = function(form) {
	for (var i = 0; i < form.elements.length; i++) {
		if (form.elements[i].nodeName.toLowerCase() in {input:1, select:1, textarea:1, button:1}) {
			nette.toggleControl(form.elements[i]);
		}
	}
};


nette.toggleControl = function(elem, rules, firsttime) {
	rules = rules || eval('[' + (elem.getAttribute('data-nette-rules') || '') + ']');
	var has = false;
	for (var id in rules) {
		var rule = rules[id], op = rule.op.match(/(~)?([^?]+)/);
		rule.neg = op[1];
		rule.op = op[2];
		rule.condition = !!rule.rules;
		if (!rule.condition) continue;

		var el = rule.control ? elem.form.elements[rule.control] : elem;
		var success = nette.validateRule(el, rule.op, rule.arg);
		if (success === null) continue;
		if (rule.neg) success = !success;

		if (nette.toggleControl(elem, rule.rules, firsttime) || rule.toggle) {
			has = true;
			if (firsttime) {
				if (!el.nodeName) { // radio
					for (var i in el) {
						el[i].onclick = function() { nette.toggleForm(elem.form); };
					}
				} else if (el.nodeName.toLowerCase() === 'select') {
					el.onchange = function() { nette.toggleForm(elem.form); };
				} else {
					el.onclick = function() { nette.toggleForm(elem.form); };
				}
			}
			for (var id in rule.toggle || []) {
				nette.toggle(id, success ? rule.toggle[id] : !rule.toggle[id]);
			}
		}
	}
	return has;
};


nette.toggle = function(id, visible) {
	var elem = document.getElementById(id);
	if (elem) {
		elem.style.display = visible ? "" : "none";
	}
};


nette.initForm = function(form) {
	form.onsubmit = function() {
		return nette.validateForm(form);
	};

	form.onclick = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		form['nette-submittedBy'] = (target.type in {submit:1, image:1}) ? target.name : null;
	};

	for (var i = 0; i < form.elements.length; i++) {
		nette.toggleControl(form.elements[i], null, true);
	}

	if (/MSIE/.exec(navigator.userAgent)) {
		var labels = {};
		for (i = 0, elms = form.getElementsByTagName('label'); i < elms.length; i++) {
			labels[elms[i].htmlFor] = elms[i];
		}

		for (i = 0, elms = form.getElementsByTagName('select'); i < elms.length; i++) {
			elms[i].onmousewheel = function() { return false };	// prevents accidental change in IE
			if (labels[elms[i].htmlId]) {
				labels[elms[i].htmlId].onclick = function() { document.getElementById(this.htmlFor).focus(); return false }; // prevents deselect in IE 5 - 6
			}
		}
	}
};


(function(){
	var init = function() {
		for (var i = 0; i < document.forms.length; i++) nette.initForm(document.forms[i]);
	};
	typeof jQuery === 'function' ? jQuery(init) : window.onload = init;
})();
