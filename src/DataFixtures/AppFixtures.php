<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Tenant;
use App\Entity\TenantUserServiceProvider;
use App\Entity\User;
use App\Entity\Comment;
use App\Security\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{

    private $passwordEncoder;

    private $faker;

    private const TENANT = [
        [
            'name' => 'RedHouse',
            'type'=> 'dates'
        ],
        [
            'name' => 'WhiteHouse',
            'type' => 'friend zone'
        ],
        [
            'name' => 'GreenHouse',
            'type' => 'friendly'
        ],
        [
            'name' => 'PurpleHouse',
            'type' => 'zone'
        ],
        [
            'name' => 'OutDate',
            'type' => 'Good vibe'
        ]
    ];

    private const USER = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'name' => 'Ricardo Rabeto',
            'password' => 'admin@123',
            'roles' => [USER::ROLE_SUPERADMIN],
            "enabled" => true
        ],
        [
            'username' => 'john_doe',
            'email' => 'johndoe@blog.com',
            'name' => 'John Doe',
            'password' => 'admin@123',
            'roles' => [USER::ROLE_ADMIN],
            "enabled" => true

        ],
        [
            'username' => 'rob_smith',
            'email' => 'robsmith@blog.com',
            'name' => 'Rob Smith',
            'password' => 'admin@123',
            'roles' => [USER::ROLE_WRITER],
            "enabled" => true

        ],
        [
            'username' => 'jenny_rowling',
            'email' => 'jenny@blog.com',
            'name' => 'Jenny Rowling',
            'password' => 'admin@123',
            'roles' => [USER::ROLE_WRITER],
            "enabled" => true

        ],
        [
            'username' => 'han_solo',
            'email' => 'solo@blog.com',
            'name' => 'Han Solo',
            'password' => 'admin@123',
            'roles' => [USER::ROLE_EDITOR],
            "enabled" => false

        ],
        [
            'username' => 'jedi_knight',
            'email' => 'jedi@blog.com',
            'name' => 'Jedi Knight',
            'password' => 'admin@123',
            'roles' => [USER::ROLE_COMMENTATOR],
            "enabled" => true

        ]
    ];
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
        $this->tokenGenerator = $tokenGenerator;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->LoadBlogPosts($manager);
        $this->loadComments($manager);
        $this->LoadTenants($manager);
        $this->LoadServiceProviders($manager);


    }
    public function LoadServiceProviders(ObjectManager $manager){

        $roles = [TenantUserServiceProvider::ROLE_ALL, TenantUserServiceProvider::ROLE_LUX, TenantUserServiceProvider::ROLE_SIMPLE];
        for($i=0; $i < rand(25,40); $i ++){
            $randomUser = self::USER[rand(0, 5)];
            $randomUserReference = $this->getReference('user_'. $randomUser['username']);

            $randomTenant = self::TENANT[rand(0,4)];
            $randomTenantReference = $this->getReference('tenant_'.$randomTenant['name']);
            $serviceProvider = new TenantUserServiceProvider();
            $serviceProvider->setTenantId($randomTenantReference->getId());
            $serviceProvider->setUserId($randomUserReference->getId());
            $serviceProvider->setRole($roles[rand(0,2)]);
            $serviceProvider->setRating(rand(1,5));

            $manager->persist($serviceProvider);
        }

        $manager->flush();
    }
    public function LoadTenants(ObjectManager $manager)
    {
        foreach (self::TENANT as $tenant){
            $tnt = new Tenant();
            $tnt->setName($tenant['name']);
            $tnt->setType($tenant['type']);
            $this->addReference('tenant_' . $tenant['name'], $tnt);

            $manager->persist($tnt);
        }

        $manager->flush();

    }
    public function LoadBlogPosts(ObjectManager $manager)
    {
        for($i = 0; $i < 100; $i++){
            $blogPost = new BlogPost;
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setContent($this->faker->realText());

            $authorReference = $this->RandomUser($blogPost);
            $blogPost->setAuthor($authorReference);
            $blogPost->setSlug($this->faker->slug);
            
            $this->setReference("blog_post_$i", $blogPost);
            $manager->persist($blogPost);
        }

        $manager->flush();
    
    }
    
    public function loadComments(ObjectManager $manager)
    {
        for($i = 0; $i < 100; $i++){
            for($j=0; $j < rand(1, 10); $j++){
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear);

                $authorReference = $this->RandomUser($comment);

                $comment->setAuthor($authorReference);
                $comment->setBlogPost($this->getReference("blog_post_$i"));

                $manager->persist($comment);
            }
        }
        $manager->flush();
    }
    
    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USER as $userField){
            $user = new User();
            $user->setUsername($userField['username']);
            $user->setEmail($userField['email']);
            $user->setName($userField['name']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userField['password']
            ));

            $user->setRoles($userField['roles']);
            $user->setEnabled($userField['enabled']);

            if(!$userField['enabled']){
                $user->setConfirmationToken(
                    $this->tokenGenerator->getRandomSecureToken()
                );
            }

            $this->addReference('user_' . $userField['username'], $user);

            $manager->persist($user);

        }

        $manager->flush();


            
    }/**
 * @return User
 */public function RandomUser($entity): User
    {
        $randomUser = self::USER[rand(0, 5)];


        if($entity instanceof BlogPost && !count(
            array_intersect($randomUser['roles'],
                [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_WRITER]))){
            return $this->RandomUser($entity);
        }

        if($entity instanceof Comment && !count(
                array_intersect($randomUser['roles'],
                    [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_WRITER, User::ROLE_COMMENTATOR]))){
            return $this->RandomUser($entity);
        }
        if($entity instanceof Tenant && !count(
                array_intersect($randomUser['roles'],
                    [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_WRITER, User::ROLE_COMMENTATOR]))){
            return $this->RandomUser($entity);
        }

        return $this->getReference( "user_" . $randomUser['username']);
    }
}

