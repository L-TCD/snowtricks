<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Trick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
	protected $slugger;
	protected $encoder;

	public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $encoder)
	{
		$this->slugger = $slugger;
		$this->encoder = $encoder;
	}

    public function load(ObjectManager $manager): void
    {
		$faker = Factory::create('fr_FR');

		$admin = new User();

		$hash = $this->encoder->hashPassword($admin, "password");

		$admin
			->setEmail("admin@gmail.com")
			->setUsername($faker->name())
			->setPassword($hash);


		$manager->persist($admin);

		$users = [];

		for ($u = 0; $u < 5; $u++) {
			$user = new User();

			$hash = $this->encoder->hashPassword($user, "password");

			$user
				->setEmail("user$u@gmail.com")
				->setUsername($faker->name())
				->setPassword($hash);

			$users[] = $user;

			$manager->persist($user);
		}

		for ($t = 0; $t < 20; $t++) {
			$trick = new Trick;
			$trick
				->setName(ucfirst($faker->words(mt_rand(2, 4), true)))
				->setSlug(strtolower($this->slugger->slug($trick->getName())))
				->setCreatedAt($faker->dateTimeBetween('-3 weeks'));

                $random = mt_rand(0, 3);
                if ($random >= 1) {
					$trick->setDescription($faker->paragraphs(mt_rand(1, 3), true));
				}

                $random2 = mt_rand(0, 3);
                if ($random2 < 1) {
					$trick->setUpdateAt($faker->dateTimeBetween($trick->getCreatedAt()));
                }

	        $manager->persist($trick);
		}

        $manager->flush();
    }
}
