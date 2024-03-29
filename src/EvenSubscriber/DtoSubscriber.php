<?php

namespace App\EvenSubscriber;

use App\Event\AfterDtoCreatedEvent;
use App\Service\ServiceException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoSubscriber implements EventSubscriberInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function doSomethingElse(AfterDtoCreatedEvent $event)
    {
        dd('doing something else');
    }

    public static function getSubscribedEvents(): array
    {
        return [
          AfterDtoCreatedEvent::NAME => [
              ['validateDto', 1],
          ]
        ];
    }

    public function validateDto(AfterDtoCreatedEvent $event): void
    {
        $dto = $event->getDto();

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new ServiceException(422, 'Validation failed');
        }
    }
}