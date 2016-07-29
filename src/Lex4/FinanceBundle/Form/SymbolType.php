<?php

namespace Lex4\FinanceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class SymbolType
 * @package Lex4\FinanceBundle\Form
 */
class SymbolType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('symbol', null, ['label' => 'Symbol Id', 'attr' => ['placeholder' => 'Symbol Id']])
            ->add('name', null, ['label' => 'Name', 'attr' => [
                'placeholder' => 'Name symbol'
                ]])
            ->add('save', SubmitType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lex4\FinanceBundle\Entity\FinanceSymbols',
            'attr' => ['id' => $this->getName()],
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lex4_finance_symbols';
    }
}
