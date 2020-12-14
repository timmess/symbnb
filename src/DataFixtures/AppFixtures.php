<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(PersistenceObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Tim')
                  ->setLastName('Messaoudene')
                  ->setEmail('aqme_sk8@hotmail.fr')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('http://placehold.it/100x100')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // Nous gérons les Users
        $users = [];
        $genres = ['male', 'female'];

        for ($i=1; $i < 10; $i++) { 
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $picturesId = $faker->numberBetween(1, 99) . '.jpg';

            // Condition ternaire (Même chose que if/else)
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $picturesId;

            $hash = $this->encoder->encodePassword($user, 'password');
            
            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->Lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }




        // Nous gérons les annonces
        for ($i=1; $i <= 30 ; $i++) { 
            $ad = new Ad();

            $title = $faker->sentence();
            // $coverImage = $faker->imageUrl(1000,350);
            $coverImage = "http://placehold.it/1000x350";
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
               ->setCoverImage($coverImage)
               ->setIntrodution($introduction)
               ->setContent($content)
               ->setPrice(mt_rand(40, 200))
               ->setRooms(mt_rand(1, 5))
               ->setAuthor($user);

            for ($j=1; $j <= mt_rand(2, 5); $j++) { 
                $image = new Image();

                // $image->setUrl($faker->imageUrl())
                $image->setUrl("http://placehold.it/1000x350")
                      ->setCaption($faker->sentence())
                      ->setAd($ad);

                $manager->persist($image);
            }
            $manager->persist($ad);
        }
    $manager->flush();
    }
}
