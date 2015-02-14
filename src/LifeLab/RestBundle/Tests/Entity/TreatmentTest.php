<?php
namespace LifeLab\RestBundle\Tests\Entity;

use LifeLab\RestBundle\Entity\Intake;
use LifeLab\RestBundle\Entity\Treatment;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TreatmentControllerTest extends WebTestCase {

	private function getTreatment($startDate, $duration, $frequency) {
		$treatment = new Treatment();
		$treatment->setDate($startDate);
		$treatment->setDuration($duration);
		$treatment->setFrequency($frequency);
		return $treatment;
	}

	public function testNumberOfComputedExpectedIntakes() {
		$startDate = new \DateTime();
		$duration = 2;
		$frequency = 23;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$numberOfIntakes = ((24 * $duration) % $frequency) + 1;
		$intakes = $treatment->computeExpectedIntakes();
		$this->assertEquals($numberOfIntakes, count($intakes));
	}

	public function testNumberOfComputedExpectedIntakesForZeroDuration() {
		$startDate = new \DateTime();
		$duration = 0;
		$frequency = 23;		
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$this->assertEquals(0, count($intakes));
	}

	public function testNumberOfComputedExpectedIntakesForNegativeDuration() {
		$startDate = new \DateTime();
		$duration = -1;
		$frequency = 23;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$this->assertEquals(0, count($intakes));
	}

	public function testNumberOfComputedExpectedIntakesForOver24Duration() {
		$startDate = new \DateTime();
		$duration = -3;
		$frequency = 25;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$this->assertEquals(0, count($intakes));
	}

	public function testRegularityOfComputedExpectedIntakesWithEvenFrequency() {
		$startDate = new \DateTime();
		$startDate->setTime(0, 0, 0);
		$duration = 1;
		$frequency = 2;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$time = clone $startDate;
		foreach ($intakes as $intake) {
			$diff = $time->diff($intake->getTime());
			$hoursElpased = intval($diff->format('%d'), 10) * 24 + intval($diff->format('%h'), 10);
			$this->assertEquals(0, $hoursElpased % $frequency);
		}	
	}

	public function testTimesOfComputedExpectedIntakesWithEvenFrequency() {
		$startDate = new \DateTime();
		$startDate->setTime(0, 0, 0);
		$duration = 1;
		$frequency = 3;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$time = clone $startDate;
		$interval = new \DateInterval('PT' . $frequency . 'H');
		foreach ($intakes as $intake) { 
			$this->assertEquals($time, $intake->getTime());
			$time->add($interval);
		}
	}

	public function testRegularityOfComputedExpectedIntakesWithOddFrequency() {
		$startDate = new \DateTime();
		$startDate->setTime(0, 0, 0);
		$duration = 1;
		$frequency = 3;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$time = clone $startDate;
		foreach ($intakes as $intake) {
			$diff = $time->diff($intake->getTime());
			$hoursElpased = intval($diff->format('%d'), 10) * 24 + intval($diff->format('%h'), 10);
			$this->assertEquals(0, $hoursElpased % $frequency);
		}	
	}

	public function testTimesOfComputedExpectedIntakesWithOddFrequency() {
		$startDate = new \DateTime();
		$startDate->setTime(0, 0, 0);
		$duration = 10;
		$frequency = 13;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$time = clone $startDate;
		$interval = new \DateInterval('PT' . $frequency . 'H');
		foreach ($intakes as $intake) { 
			$this->assertEquals($time, $intake->getTime());
			$time->add($interval);
		}
	}

	public function testRegularityOfComputedExpectedIntakesWithEvenFrequencyOver24Hours() {
		$startDate = new \DateTime();
		$startDate->setTime(0, 0, 0);
		$duration = 3;
		$frequency = 26;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$time = clone $startDate;
		foreach ($intakes as $intake) {
			$diff = $time->diff($intake->getTime());
			$hoursElpased = intval($diff->format('%d'), 10) * 24 + intval($diff->format('%h'), 10);
			$this->assertEquals(0, $hoursElpased % $frequency);
		}	
	}

	public function testTimesOfComputedExpectedIntakesWithEvenFrequencyOver24Hours() {
		$startDate = new \DateTime();
		$startDate->setTime(0, 0, 0);
		$duration = 3;
		$frequency = 26;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$time = clone $startDate;
		$interval = new \DateInterval('PT' . $frequency . 'H');
		foreach ($intakes as $intake) { 
			$this->assertEquals($time, $intake->getTime());
			$time->add($interval);
		}
	}

	public function testRegularityOfComputedExpectedIntakesWithOddFrequencyOver24Hours() {
		$startDate = new \DateTime();
		$startDate->setTime(0, 0, 0);
		$duration = 3;
		$frequency = 25;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$time = clone $startDate;
		foreach ($intakes as $intake) {
			$diff = $time->diff($intake->getTime());
			$hoursElpased = intval($diff->format('%d'), 10) * 24 + intval($diff->format('%h'), 10);
			$this->assertEquals(0, $hoursElpased % $frequency);
		}	
	}

	public function testTimesOfComputedExpectedIntakesWithOddFrequencyOver24Hours() {
		$startDate = new \DateTime();
		$startDate->setTime(0, 0, 0);
		$duration = 3;
		$frequency = 25;
		$treatment = $this->getTreatment($startDate, $duration, $frequency);
		$intakes = $treatment->computeExpectedIntakes();
		$time = clone $startDate;
		$interval = new \DateInterval('PT' . $frequency . 'H');
		foreach ($intakes as $intake) { 
			$this->assertEquals($time, $intake->getTime());
			$time->add($interval);
		}
	}
}