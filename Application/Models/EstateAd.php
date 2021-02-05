<?php

namespace Application\Models;

use System\Model;

class EstateAd extends Model
{
	protected $table		= 'estate_ad';
	protected $primaryKey	= 'id_ad';

	public function getByTitle($title, $limit = 10)
	{
		return EstateAd::where('estate_ad.deleted', '=', 0)
			->whereLike('estate_ad.ad_title', "%{$title}%")
			->select([
				'id_ad',
				'ad_title',
				'street',
				'price',
				'surface',
				'city_languages.name AS city',
				'hood.name AS hood',
				'estate_type_languages.name AS type'
			])
			->leftJoin('city_languages')->on('estate_ad.id_city', '=', 'city_languages.id_city')
			->leftJoin('hood')->on('estate_ad.id_hood', '=', 'hood.id_hood')
			->leftJoin('`estate_type_languages` ')->on('estate_ad.id_type', '=', '`estate_type_languages` .id_type')
			->limit($limit)
			->get();
	}
}