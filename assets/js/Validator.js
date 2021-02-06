class Validator {
	static RULES_SEPARATOR	= '|';
	static ARGS_SEPARATOR	= ',';

	constructor(form) {
		this.form	= $(form);
		this.fields	= this.form.find('[data-rules]');
		this.rules	= {};

		this.prepare();

		const _this = this;

		this.fields.on('click', function() {
			_this.onFieldClick($(this));
		});
	}

	run() {
		let valid = true;

		this.clearErrors();

		for (let name in this.rules) {
			let rules = this.rules[name];

			for (let i = 0; i < rules.length; i++) {
				let instance	= rules[i].instance;
				let result		= instance.test(rules[i].args);

				valid &&= result;

				if (!result) {
					instance.invalidate();

					break;
				}
			}
		}

		return valid;
	}

	clearErrors() {
		this.fields.removeClass('is-invalid');
	}

	prepare() {
		for (let i = 0; i < this.fields.length; i++) {
			let field	= $(this.fields[i]);
			let name	= field.attr('name');
			let rules	= field.data('rules').split(Validator.RULES_SEPARATOR);

			rules.forEach((rule) => {
				let args	= [];
				let matches = rule
					.replace(/\s+/g, '', rule)
					.match(/(?<rule>[A-Z_-]+)\((?<args>[A-Z0-9,_-]+)\)/i);

				if (matches) {
					rule = matches.groups['rule'];
					args = matches.groups['args'].split(Validator.ARGS_SEPARATOR);
				}

				let Rule = rule
					.charAt(0)
					.toUpperCase() + rule.slice(1);

				try {
					Rule = Function(`return ${Rule}`)();

					if (this.rules[name] === void(0)) {
						this.rules[name] = [];
					}

					this.rules[name].push({
						instance: new Rule(field),
						args: args
					});
				}
				catch (exception) {
					console.warn(`Validator error: invalid rule ${Rule}`)
				}
			});
		}
	}

	onFieldClick(field) {
		field.removeClass('is-invalid');
	}
}

/**
 * Validation classes
 */
class Rule {
	constructor(field) {
		this.field = $(field);
	}

	invalidate() {
		this.field.addClass('is-invalid');

		let tooltip = this.field.siblings('.invalid-tooltip');

		tooltip.text(this.message());
	}

	test(...args) { return false; }
	message() { return ''; }
}

class Required extends Rule
{
	test() {
		let value = this.field.val() || this.field.text();

		return (value !== '');
	}

	message() {
		return 'Polje je neophodno.';
	}
}

class Email extends Rule
{
	test() {
		let value = this.field.val() || this.field.text();

		return !!value.match(/(.+)@(.+){2,}\.(.+){2,}/);
	}

	message() {
		return 'Unesite validnu email adresu.';
	}
}

class Numeric extends Rule
{
	test() {
		let value = this.field.val() || this.field.text();

		return !!value.match(/^([0-9]+)(\.[0-9]*)?$/);
	}

	message() {
		return 'Unesite validnu numeričku vrednost.';
	}
}