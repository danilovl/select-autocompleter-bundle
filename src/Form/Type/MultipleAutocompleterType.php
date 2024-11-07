<?php declare(strict_types=1);

namespace Danilovl\SelectAutocompleterBundle\Form\Type;

use Danilovl\SelectAutocompleterBundle\Form\Loader\LazyChoiceLoaderFactory;
use Danilovl\SelectAutocompleterBundle\Interfaces\AutocompleterContainerInterface;
use Danilovl\SelectAutocompleterBundle\Resolver\Form\AutocompleterTypeResolver;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\{
    Options,
    OptionsResolver
};
use Twig\Environment;

class MultipleAutocompleterType extends AutocompleterType
{
    public function __construct(
        AutocompleterContainerInterface $autocompleterContainer,
        AutocompleterTypeResolver $autocompleterTypeResolver,
        Environment $environment,
        private readonly LazyChoiceLoaderFactory $choiceLoaderFactory
    ) {
        parent::__construct(
            $autocompleterContainer,
            $autocompleterTypeResolver,
            $environment,
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'multiple' => true,
            'choice_loader' => function (Options $options): ChoiceLoaderInterface {
                $autocompleterName = $options['autocompleter']['name'];

                return $this->choiceLoaderFactory->createLazyChoiceLoader($autocompleterName);
            }
        ]);

        parent::configureOptions($resolver);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
