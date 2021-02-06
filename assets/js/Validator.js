class Validator {
	constructor(form) {
		this.form	= $(form);
		this.fields	= this.form.find('[data-rules]');
		this.rules	= [];

		this.prepare();

		const _this = this;

		this.fields.on('click', function() {
			_this.onFieldClick($(this));
		});
	}

	run() {
		let valid = true;

		this.rules.forEach((rule) => {
			let caller	= rule.caller;
			let result	= caller.test(rule.args);

			valid &&= result;

			if (!result) {
				caller.invalidate();

				return;
			}
		});

		return valid;
	}

	prepare() {
		for (let i = 0; i < this.fields.length; i++) {
			let field	= $(this.fields[i]);
			let rules	= field.data('rules').split('|');

			rules.forEach((rule) => {
				let args	= [];
				let matches = rule
					.replace(/\s+/g, '', rule)
					.match(/(?<rule>[A-Z_-]+)\((?<args>[A-Z0-9,_-]+)\)/i);

				if (matches) {
					rule = matches.groups['rule'];
					args = matches.groups['args'].split(',');
				}

				let Rule = rule
					.charAt(0)
					.toUpperCase() + rule.slice(1);

				try {
					Rule = Function(`return ${Rule}`)();

					this.rules.push({
						caller: new Rule(field),
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

// Validation rules
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
		return 'Ovo polje je neophodno.';
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