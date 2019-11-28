<?php

namespace CakeDto\Shell;

use App\Dto\CarDto;
use CakeDto\TestSuite\DebugMemory;
use CakeDto\TestSuite\DebugTimer;
use Cake\Console\Shell;
use Cake\I18n\Number;

class DtoBenchmarkShell extends Shell {

	/**
	 * @param int|null $count
	 * @return int|null
	 */
	public function arrays($count = null) {
		$this->_start($count);

		$count = (int)$count ?: 1;
		for ($i = 0; $i < $count; $i++) {
			$this->_runArrays();
		}

		$this->_end();
	}

	/**
	 * @param int|null $count
	 * @return int|null
	 */
	public function dtos($count = null) {
		$this->_start($count);

		$count = (int)$count ?: 1;
		for ($i = 0; $i < $count; $i++) {
			$this->_runDtos();
		}

		$this->_end();
	}

	/**
	 * @return void
	 */
	protected function _runArrays() {
		$array = [];
		$array['foo']['bar'] = 'x';
		$array['baz']['bar'] = 'x';

		$x = $array['baz']['bar'];
	}

	/**
	 * @return void
	 */
	protected function _runDtos() {
		$car = new CarDto();
		$car2 = new CarDto();

		$x = $car2->getDistanceTravelled();
	}

	/**
	 * @param int|null $count
	 * @return void
	 */
	protected function _start($count) {
		$current = DebugMemory::getCurrent();
		$peak = DebugMemory::getPeak();

		$count = (int)$count ?: 1;

		$this->out('Memory: ' . Number::toReadableSize($current) . ' | Peak: ' . Number::toReadableSize($peak));

		$this->out('... Starting (' . $count . ' runs)');

		DebugTimer::start('dto');
	}

	/**
	 * @return void
	 */
	protected function _end() {
		DebugTimer::stop('dto');

		$this->out('... Stopped');

		$current = DebugMemory::getCurrent();
		$peak = DebugMemory::getPeak();

		$this->out('Memory: ' . Number::toReadableSize($current) . ' | Peak: ' . Number::toReadableSize($peak));

		$time = DebugTimer::elapsedTime('dto');
		$this->warn(Number::format($time) . 's');
	}

	/**
	 * @return \Cake\Console\ConsoleOptionParser
	 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser
			->addSubcommand('arrays')
			->addSubcommand('dtos')
			->addSubcommand('immutable');

		return $parser;
	}

}
