<html>

<head>
</head>

<body>
	<div>
		<h4>Oglas</h4>
		<table>
			<tr>
				<td>Naslov:</td>
				<td>
					<?= $ad_title ?? '' ?>
				</td>
			</tr>
			<tr>
				<td>Grad:</td>
				<td>
					<?= $city ?? '' ?>
				</td>
			</tr>
			<tr>
				<td>Deo grada:</td>
				<td>
					<?= $hood ?? '' ?>
				</td>
			</tr>
			<tr>
				<td>Ulica:</td>
				<td>
					<?= $street ?? '' ?>
				</td>
			</tr>
			<tr>
				<td>Cena:</td>
				<td>
					<?= $price ?? '' ?>
				</td>
			</tr>
			<tr>
				<td>Kvadratura:</td>
				<td>
					<?= $surface ?? '' ?>
				</td>
			</tr>
			<tr>
				<td>Tip nekretnine:</td>
				<td>
					<?= $type ?? '' ?>
				</td>
			</tr>
		</table>
	</div>
	<div>
		<h4>Komentar</h4>
		<?php
		if (strlen($comment) === 0)
		{
			$comment = 'Korisnik nije uneo komentar.';
		}
	?>

		<?= $comment ?>
	</div>
</body>

</html>