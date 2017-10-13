<?php

namespace AcmeBundle\Tests\Validator\Constraints;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Validator\Constraints\IsNotClosingDay;
use AppBundle\Validator\Constraints\IsNotClosingDayValidator;
use DateTime;


class IsNotClosingDayValidatorTest extends KernelTestCase
{

    public function closingDayDataProvider(){

        return [
            ['2018-01-14 00:00:00'], // Sunday
            ['2018-01-16 00:00:00']  // Tuesday
        ];
    }

    public function openDayDataProvider(){

        return [
            ['2018-01-15 00:00:00'], //Monday
            ['2018-01-17 00:00:00'], //Wednesday
            ['2018-01-18 00:00:00'], //Thursday
            ['2018-01-19 00:00:00'], //Friday
            ['2018-01-20 00:00:00']  //Saturday
        ];

    }

    /**
     * Configure a IsNotClosingDayValidator.
     *
     * @param string $expectedMessage The expected message on a validation violation, if any.
     *
     * @return AppBundle\Validator\Constraints\IsNotClosingDayValidator
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
        $validator = new IsNotClosingDayValidator();
        $validator->initialize($context);

        // return the SomeConstraintValidator
        return $validator;
    }

    /**
     * On vérifie que les jours de fermeture sont interceptées par le validator
     * @dataProvider closingDayDataProvider
     */
    public function testOnClosingDay($date)
    {
        $testDate = new DateTime($date);
        $constraint = new IsNotClosingDay();
        $validator = $this->configureValidator($constraint->message);

        $validator->validate($testDate, $constraint);
    }
    /**
     * On vérifie qu'une date valide passe la validator.
     * @dataProvider openDayDataProvider
     */
    public function testOnOpenDay($date)
    {
        $testDate = new DateTime($date);
        $constraint = new IsNotClosingDay();
        $validator = $this->configureValidator();

        $validator->validate($testDate, $constraint);
    }
}