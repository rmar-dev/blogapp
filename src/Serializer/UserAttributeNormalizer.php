<?php
/**
 * Created by PhpStorm.
 * User: rmar2
 * Date: 27/03/2019
 * Time: 11:43
 */

namespace App\Serializer;


namespace App\Serializer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class UserAttributeNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{

    use SerializerAwareTrait;

    const USER_ATTRIBUTE_NORMALIZER_ALREADY_CALLED = "USER_ATTRIBUTE_NORMALIZER_ALREADY_CALLED";

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {

        $this->tokenStorage = $tokenStorage;
    }


    public function supportsNormalization($data, $format = null, array $context = [])
    {
        if(isset($context[self::USER_ATTRIBUTE_NORMALIZER_ALREADY_CALLED])){
            return false;
        }

        return $data instanceof User;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        if($this->isUserHimself($object)){
            $context['groups'][] = 'get-owner';
        }

        //Now continue with the normalization
        return $this->passOn($object, $format, $context);
    }

    private function isUserHimself($object)
    {
        return $object->getUsername() === $this->tokenStorage->getToken()->getUsername();
    }

    private function passOn($object, $format, array $context)
    {
        if(!$this->serializer instanceof  NormalizerInterface){
            throw new \LogicException(
                sprintf('Cannot normalize object "%s" because the injected serializer is not a normilizer.',
                $object
                )
            );
        }

        $context[self::USER_ATTRIBUTE_NORMALIZER_ALREADY_CALLED] = true;

        return $this->serializer->normalize($object, $format, $context);
    }

}