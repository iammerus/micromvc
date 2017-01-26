<?php
	namespace MicroPos\Core\Helpers;

	use MicroPos\Statistics\Calculator;
	use Statistics as Stats;
	use Faker\Factory as Faker;


	class Statistics
	{
		/**
		 * Check if the application has calculated any of the statistics yet
		 * @return bool
		 */
		public static function hasPreviousStatistics()
		{
			return Stats::all()->count() != 0;
		}

		public static function getCityName($id)
		{
			return \City::where('id', '=', $id)->first()->name;
		}

		/**
		 * Get the last calculated statistics
		 * @return mixed
		 */
		public static function getLastStatistics()
		{
			$data = Stats::all()->last();

			return json_decode( $data->data );
		}

		public static function calculate()
		{
			$data = [];


			$cities = \City::all()->toArray();

			foreach ($cities as $city) {
				$maleToFemale = Calculator::getMaleToFemaleRatio( $city['id'] );
				$population = Calculator::getPopulation( $city['id'] );
				$birthRate = Calculator::getBirthRate( $city['id'] );
				$deathRate = Calculator::getDeathRate( $city['id'] );
				$infantMortalityRate = Calculator::getInfantMortalityRate( $city['id'] );
				$ageDemographics = Calculator::getAgeDemographics( $city['id'] );

				$data['cities'][$city['id']] = [
					'maleToFemale'        => $maleToFemale ,
					'population'          => $population ,
					'birthRate'           => $birthRate ,
					'deathRate'           => $deathRate ,
					'infantMortalityRate' => $infantMortalityRate ,
					'ageDemographics'     => $ageDemographics ,
				];
			}


			$data['global'] = [
				'maleToFemale'        => Calculator::getMaleToFemaleRatio() ,
				'population'          => Calculator::getPopulation() ,
				'marriages'						=> Calculator::getMarriages() , 
				'divorces'						=> Calculator::getDivorces() ,
				'birthRate'           => Calculator::getBirthRate() ,
				'deathRate'           => Calculator::getDeathRate() ,
				'infantMortalityRate' => Calculator::getInfantMortalityRate() ,
				'ageDemographics'     => Calculator::getAgeDemographics() ,
			];

			$json = json_encode($data);

			$stat = new Stats();



			$stat->data = $json;

			$stat->save();
		}
	}
