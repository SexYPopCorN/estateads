class Form {
	constructor(form) {
		this.form		= $(form);
		this.fields		= {};
		this.validator	= new Validator(form);
		this.onSubmit	= this.onSubmit.bind(this);

		this.getFields();

		this.form.on('submit', this.onSubmit);
	}
	
	autocomplete(record) {
		for (let name in record) {
			let value = record[name];
			let field = this.fields[name];

			if (field) {
				field
					.val(value)
					.text(value);
			}
		}

		this.validator.clearErrors();
	}

	getFields() {
		let fields = this.form.find('[name]');

		for (let i = 0; i < fields.length; i++) {
			let field	= $(fields[i]);
			let name	= field.attr('name');

			this.fields[name] = field;
		}
	}

	onSubmit(event) {
		event.preventDefault();

		if (!this.validator.run()) {
			return;
		}

		let serialized	= this.form.serializeArray();
		let data		= {};
		let start		= Date.now();

		serialized.forEach((record) => {
			data[record.name] = record.value;
		});

		$('.overlay').show();

		$.ajax({
			url: this.form.attr('action'),
			type: this.form.attr('method'),
			data: data,
			success: () => {
				let elapsed = Date.now() - start;

				setTimeout(() => {
					this.form.trigger('reset');
					$('.overlay').hide();
					toastr.success('Uspešno ste poslali upit');
				}, 500 - elapsed);
			},
			error: () => {
				toastr.error('Došlo je do greške na serveru');
			}
		});
	}
}