<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Estate Ads</title>

	<link rel="icon" href="data:;base64,=">
	<link rel="stylesheet" href="<?= asset('css/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?= asset('css/toastr.min.css')?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">

	<script src="<?= asset('js/jquery.js')?>"></script>
	<script src="<?= asset('js/bootstrap.min.js')?>"></script>
	<script src="<?= asset('js/toastr.min.js')?>"></script>
	<script src="<?= asset('js/Validator.js')?>"></script>
	<script src="<?= asset('js/Form.js')?>"></script>
	<script src="<?= asset('js/Search.js')?>"></script>


	<style>
		.card {
			border-radius: 4px;
			background: #fff;
			box-shadow: 0 6px 10px rgba(0, 0, 0, .08), 0 0 6px rgba(0, 0, 0, .05);
			transition: .3s transform cubic-bezier(.155, 1.105, .295, 1.12), .3s box-shadow, .3s -webkit-transform cubic-bezier(.155, 1.105, .295, 1.12);
			padding: 14px 80px 18px 36px;
			cursor: pointer;
		}

		.card:hover {
			transform: scale(1.05);
			box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
		}

		.overlay {
			background: #ffffff;
			color: #666666;
			position: fixed;
			height: 100%;
			width: 100%;
			z-index: 5000;
			top: 0;
			left: 0;
			float: left;
			text-align: center;
			padding-top: 25%;
			opacity: .80;
			display: none;
		}

		.spinner {
			margin: 0 auto;
			height: 64px;
			width: 64px;
			animation: rotate 0.8s infinite linear;
			border: 5px solid firebrick;
			border-right-color: transparent;
			border-radius: 50%;
		}

		@keyframes rotate {
			0% {
				transform: rotate(0deg);
			}

			100% {
				transform: rotate(360deg);
			}
		}
	</style>
</head>

<body>
	<div class="overlay">
		<div class="spinner"></div>
		<br />
		Molimo sačekajte
	</div>
	<div class="container bg-light">
		<div class="row mb-5">
		</div>
		<div class="row">
			<div class="col-sm-6">
				<h3>Pretraga</h3>
				<hr class="mt-2 mb-3" />
				<div>
					<div class="input-group rounded">
						<input id="ad-search" type="search" class="form-control rounded"
							placeholder="Pretraži po naslovu" aria-describedby="search-addon" />
						<span class="input-group-text border-0" id="search-addon">
							<i class="fas fa-search"></i>
						</span>
					</div>
					<div class="list-group d-grid">
						<div id="ad-search-results"></div>
						<div class="search-spinner spinner-border mt-2 mb-2 mx-auto" role="status" style="display: none;"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 position-relative">
				<h3>Oglas</h3>
				<hr class="mt-2 mb-3" />
				<form id="ad-form" method="post" action="<?= url('send-mail') ?>">
					<input type="text" name="id_ad" hidden>
					<div class="form-group row mb-5">
						<label class="col-sm-4 col-form-label">Naslov</label>
						<div class="col-sm-8">
							<input class="form-control" name="ad_title" type="text" data-rules="required" />
							<div class="invalid-tooltip"></div>
						</div>
					</div>
					<div class="form-group row mb-5">
						<label class="col-sm-4 col-form-label">Grad</label>
						<div class="col-sm-8">
							<input class="form-control" name="city" type="text" data-rules="required" />
							<div class="invalid-tooltip"></div>
						</div>
					</div>
					<div class="form-group row mb-5">
						<label class="col-sm-4 col-form-label">Deo grada</label>
						<div class="col-sm-8">
							<input class="form-control" name="hood" type="text" data-rules="required" />
							<div class="invalid-tooltip"></div>
						</div>
					</div>
					<div class="form-group row mb-5">
						<label class="col-sm-4 col-form-label">Ulica</label>
						<div class="col-sm-8">
							<input class="form-control" name="street" type="text" data-rules="required" />
							<div class="invalid-tooltip"></div>
						</div>
					</div>
					<div class="form-group row mb-5">
						<label class="col-sm-4 col-form-label">Cena</label>
						<div class="col-sm-8">
							<input class="form-control" name="price" type="text" data-rules="required|numeric" />
							<div class="invalid-tooltip"></div>
						</div>
					</div>
					<div class="form-group row mb-5">
						<label class="col-sm-4 col-form-label">Kvadratura</label>
						<div class="col-sm-8">
							<input class="form-control" name="surface" type="text" data-rules="required|numeric" />
							<div class="invalid-tooltip"></div>
						</div>
					</div>
					<div class="form-group row mb-5">
						<label class="col-sm-4 col-form-label">Tip nekretnine</label>
						<div class="col-sm-8">
							<input class="form-control" name="type" type="text" data-rules="required" />
							<div class="invalid-tooltip"></div>
						</div>
					</div>
					<div class="form-group row mb-5">
						<label class="col-sm-4 col-form-label">Email</label>
						<div class="col-sm-8">
							<input class="form-control" name="email" type="text" data-rules="required|email" />
							<div class="invalid-tooltip"></div>
						</div>
					</div>
					<div class="form-group row mb-5">
						<label class="col-sm-4 col-form-label">Komentar</label>
						<div class="col-sm-8">
							<textarea class="form-control" name="comment" rows="3" cols="50"></textarea>
							<div class="invalid-tooltip"></div>
						</div>
					</div>

					<div class="col-xs-12">
						<div class="text-right">
							<button type="submit" class="btn btn-primary pull-right">Pošalji</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<script>
			$(document).ready(() => {
				const form = new Form('#ad-form');
				const search = new Search(
					form,
					'#ad-search',
					'#ad-search-results',
					`<?= url('get-ads') ?>`
				);
			});
		</script>
</body>

</html>