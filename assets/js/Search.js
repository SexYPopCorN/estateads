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
		this.cache		= {};
		this.search		= this.search.bind(this);

		this.input.on('input', this.search);
	}

	async search(event) {
		this.phrase = $(event.target).val();

		this.reset();

		if (this.phrase.length < Search.MIN_LENGTH) {
			return;
		}

		$('.search-spinner').show();

		await this.debounce();
		await this.getResults();
		this.renderResults();

		$('.search-spinner').hide();
	}

	async debounce() {
		return new Promise((resolve) => {
			this.timeout = setTimeout(resolve, Search.DEBOUNCE);
		});
	}

	async getResults() {
		return new Promise((resolve, reject) => {
			let key = this.phrase.toLowerCase();

			if (this.cache[key] !== void(0)) {
				this.results = this.cache[key];

				console.info('Search::getResults() - loading from cache');

				return resolve();
			}

			console.info('Search::getResults() - sending request');

			$.ajax({
				url: this.url,
				type: 'POST',
				data: { title: key },
				success: (response) => {
					if (typeof(response) === 'string') {
						response = JSON.parse(response);
					}

					this.results = response;

					this.cacheResults();

					resolve();
				},
				error: (response) => {
					reject(response);
				}
			});
		})
	}

	reset() {
		if (this.timeout !== null) {
			clearTimeout(this.timeout);
		}

		$('.search-result').off('click');
		$('.search-spinner').hide();

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

		const _this = this;

		$('.search-result').on('click', function() {
			let key		= $(this).data('key');
			let record	= _this.results[key];

			_this.reset();
			_this.input.val('');
			_this.form.autocomplete(record);
		});
	}

	cacheResults() {
		let key = this.phrase.toLowerCase();

		if (this.cache[key] !== void(0)) {
			return;
		}

		this.cache[key] = this.results;
	}
}