<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface $encoder */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $model = new User();
            $model->setEmail("tester{$i}@gmail.com");
            $model->setRoles(['ROLE_API']);
            $model->setPassword($this->encoder->encodePassword($model, "tester{$i}"));
            $model->setApiToken(bin2hex(random_bytes(32)));
            $manager->persist($model);
        }

        $manager->flush();
    }
}
