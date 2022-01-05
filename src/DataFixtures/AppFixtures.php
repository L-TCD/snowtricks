<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Message;
use App\Entity\Category;
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
		
		// Admin + 5 Users
		$users = [];

		$admin = new User();
		$hashAdmin = $this->encoder->hashPassword($admin, $_ENV['OWNER_PASSWORD']);

		$admin
			->setEmail($_ENV['OWNER_EMAIL'])
			->setUsername($_ENV['OWNER_USERNAME'])
			->setPassword($hashAdmin)
			->setRoles(['ROLE_ADMIN']);

		$users[] = $admin;

		$manager->persist($admin);

		for ($u = 0; $u < 5; $u++) {
			$user = new User();

			$hash = $this->encoder->hashPassword($user, "password");

			$user
				->setEmail($faker->email())
				->setUsername($faker->userName())
				->setPassword($hash);

			$users[] = $user;

			$manager->persist($user);
		}

		// 5 Categories
		for ($c = 0; $c < 5; $c++) {
			$category = new Category;

			$category
				->setName(ucfirst($faker->words(mt_rand(2, 4), true)))
				->setSlug(strtolower($this->slugger->slug($category->getName())));

			// between 0 and 20 tricks by category
			for ($t = 0; $t < (mt_rand(0, 20)); $t++) {
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
				$category->addTrick($trick);
				$manager->persist($category);

				// between 1 and 5 images by trick
				for ($i = 1; $i <= mt_rand(1, 5); $i++) {
					$image = new Image();
					$image->setFilename('fig.jpg')
						->setName(ucfirst($faker->words(mt_rand(1, 5), true)))
						->setTrick($trick);

					$manager->persist($image);
				}
		
				// between 0 and 5 videos by trick
				for ($v = 1; $v < mt_rand(1, 5); $v++) {
					$video = new Video();
					$video->setVideoUrl('https://www.youtube.com/embed/monyw0mnLZg')
						->setName(ucfirst($faker->words(mt_rand(1, 5), true)))
						->setTrick($trick);

					$manager->persist($video);
				}

				// between 0 and 20 message by trick
				for ($m = 0; $m < mt_rand(0, 20); $m++) {
					$message = new Message;
					$message
						->setContent($faker->paragraph(1, 3))
						->setCreatedAt($faker->dateTimeBetween($trick->getCreatedAt()))
						->setTrick($trick)
						->setUser($faker->randomElement($users));

					$random3 = mt_rand(0, 5);
					if ($random3 < 1) {
						$message->setUpdatedAt($faker->dateTimeBetween($message->getCreatedAt()));
					}
	
					$manager->persist($message);
				}		
			}
		}
		$manager->flush();
	}
}