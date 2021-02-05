<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Oglasi</title>

	<script src="<?= asset('js/jquery.js')?>"></script>
</head>
<body>
	<div>
		<input id="ad-search" type="text" />
		<div id="ad-search-results"></div>
	</div>

	<form id="ad-form" type="post" action="<?= url('send-mail') ?>">
		<div>
			<label>Naslov oglasa</label>
			<input name="ad_title" type="text" />
		</div>
		<div>
			<label>Grad</label>
			<input name="city" type="text" />
		</div>
		<div>
			<label>Deo grada</label>
			<input name="hood" type="text" />
		</div>
		<div>
			<label>Ulica</label>
			<input name="street" type="text" />
		</div>
		<div>
			<label>Cena</label>
			<input name="price" type="text" />
		</div>
		<div>
			<label>Kvadratura</label>
			<input name="surface" type="text" />
		</div>
		<div>
			<label>Tip nekretnine</label>
			<input name="type" type="text" />
		</div>
		<div>
			<label>E-mail</label>
			<input name="email" type="text" />
		</div>
		<div>
			<label>Komentar</label>
			<textarea name="comment" rows="4" cols="50"></textarea>
		</div>
		
		<input type="submit" value="Pošalji"/>
	</form>

	<script>

		class Search {
			static MIN_LENGTH		= 4
			static DEBOUNCE			= 400;
			static RESULT_TEMPLATE	= `
				<div class="search-result" data-key="{key}">
					<span>{ad_title}</span>
					<span>{city}</span>
				</div>
			`;

			constructor(form, input, container) {
				this.form		= form;
				this.input		= $(input);
				this.container	= $(container);
				this.timeout	= null;
				this.results	= [];
				this.search		= this.search.bind(this);

				this.input.on('input', this.search);
			}

			async search(event) {
				this.value = $(event.target).val();

				if (this.value.length < Search.MIN_LENGTH) {
					return;
				}

				await this.debounce();

				this.results = await this.getResults();

				this.clearResults();
				this.renderResults();
				this.initializeResults();
			}

			async debounce() {
				if (this.timeout !== null) {
					clearTimeout(this.timeout);
				}

				return new Promise((resolve, reject) => {
					this.timeout = setTimeout(resolve, Search.DEBOUNCE);
				})
			}

			async getResults() {
				return new Promise((resolve, reject) => {
					$.ajax({
						url: '<?= url('get-ads') ?>',
						method: 'POST',
						data: { title: this.value },
						success: (response) => {
							if (typeof(response) === 'string') {
								response = JSON.parse(response);
							}

							resolve(response);
						},
						error: (response) => {
							reject(response);
						}
					});
				})
			}

			clearResults() {
				$('.search-result').off('click');

				this.container.empty();
			}

			renderResults() {
				let html = '';

				for (let key in this.results) {
					let record		= this.results[key];
					let template	= Search.RESULT_TEMPLATE.replace(`{key}`, key);

					for (let name in record) {
						template = template.replace(`{${name}}`, record[name]);
					}

					html += template;
				}

				this.container.html(html);
			}

			initializeResults() {
				const _this = this;

				$('.search-result').on('click', function() {
					let key		= $(this).data('key');
					let record	= _this.results[key]
					
					_this.form.autocomplete(record);
				});
			}
		}

		class Form {
			constructor(form) {
				this.form	= $(form);
				this.fields	= {};

				this.getFields();
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
			}

			getFields() {
				let fields = this.form.find('[name]');

				for (let i = 0; i < fields.length; i++) {
					let field	= $(fields[i]);
					let name	= field.attr('name');

					this.fields[name] = field;
				}
			}
		}

		$(document).ready(() => {
			const form		= new Form('#ad-form');
			const search	= new Search(form, '#ad-search', '#ad-search-results');
		});
	</script>
</body>
</html>