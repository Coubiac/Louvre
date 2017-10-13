<?php

namespace AcmeBundle\Tests\Validator\Constraints;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Validator\Constraints\IsNotHolliday;
use AppBundle\Validator\Constraints\IsNotHollidayValidator;
use DateTime;


/**
 * Exercises SomeConstraintValidator.
 */
class IsNotHollidayValidatorTest extends KernelTestCase
{

    public function HollidayDateDataProvider(){

        return [
            ['2018-01-01 00:00:00'], // New Years Day
            ['2018-05-01 00:00:00'], // French Labor Day
            ['2018-05-08 00:00:00'], // WW2 Victory
            ['2018-04-02 00:00:00'], // Easter Monday
            ['2018-05-21 00:00:00'], // Pentecot Monday
            ['2018-05-10 00:00:00'], // Ascent
            ['2018-07-14 00:00:00'], // French National day
            ['2018-08-15 00:00:00'], // Assomption
            ['2018-11-01 00:00:00'], // All soul day
            ['2018-11-11 00:00:00'], // WW1 Victory
            ['2018-12-25 00:00:00']  // Christmas
        ];
    }


    /**
     * Configure a IsNotHollidayValidator.
     *
     * @param string $expectedMessage The expected message on a validation violation, if any.
     *
     * @return AppBundle\Validator\Constraints\IsNotHollidayValidator
     */
    public function configureValidator($expectedMessage = null)
    {
        // mock the violation builder
        $builder = $this->getMockBuilder('Symfony\Component\Validator\Violation\ConstraintViolationBuilder')
            ->disableOriginalConstructor()
            ->setMethods(array('addViolation'))
            ->getMock()
        ;

        // mock the validator context
        $context = $this->getMockBuilder('Symfony\Component\Validator\Context\ExecutionContext')
            ->disableOriginalConstructor()
            ->setMethods(array('buildViolation'))
            ->getMock()
        ;

        if ($expectedMessage) {
            $builder->expects($this->once())
                ->method('addViolation')
            ;

            $context->expects($this->once())
                ->method('buildViolation')
                ->with($this->equalTo($expectedMessage))
                ->will($this->returnValue($builder))
            ;
        }
        else {
            $context->expects($this->never())
                ->method('buildViolation')
            ;
        }

        // initialize the validator with the mocked context
        $validator = new IsNotHollidayValidator();
        $validator->initialize($context);

        // return the SomeConstraintValidator
        return $validator;
    }

    /**
     * On vérifie que les Jours fériés sont rejetés
     * @dataProvider HollidayDateDataProvider
     */
    public function testDate($date)
    {
        $testDate = new DateTime($date);
        $constraint = new IsNotHolliday();
        $validator = $this->configureValidator($constraint->message);

        $validator->validate($testDate, $constraint);
    }

    /**
     * On vérifie qu'une date valide passe le validator.
     */
    public function testValidateOnValid()
    {
        $testDate = new DateTime('2018-01-02 00:00:00');
        $constraint = new IsNotHolliday();
        $validator = $this->configureValidator();

        $validator->validate($testDate, $constraint);
    }
}