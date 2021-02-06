class Search {
	static MIN_LENGTH		= 4
	static DEBOUNCE			= 400;
	static RESULT_TEMPLATE	= `
		<a class="search-result list-group-item list-group-item-action" data-key="{key}">
			{ad_title}, {city}
		</a>
	`;

	constructor(form, input, container, url) {
		this.form		= form;
		this.input		= $(input);
		this.container	= $(container);
		this.url		= url;
		this.timeout	= null;
		this.results	= [];
		this.search		= this.search.bind(this);

		this.input.on('input', this.search);
	}

	async search(event) {
		this.value = $(event.target).val();

		if (this.value.length < Search.MIN_LENGTH) {
			this.clearResults();

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
				url: this.url,
				type: 'POST',
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
			let record	= _this.results[key];

			_this.clearResults();
			_this.input.val('');
			_this.form.autocomplete(record);
		});
	}
}