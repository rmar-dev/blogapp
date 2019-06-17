<?php
namespace App\GraphQL\Resolver;
use App\Entity\TenantUserServiceProvider;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

Class TenantUserServiceResolver implements ResolverInterface, AliasedInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function __invoke(ResolveInfo $info, $value, Argument $args)
    {
        $method = $info->fieldName;
        return $this->$method($value, $args);
    }

    public function resolve(Argument $arg)
    {
        $allUsers = $this->em->getRepository(TenantUserServiceProvider::class)->findBy(array("tenant_id" => $arg['id']));
        $allUsers = $this->getUsersById($allUsers);
        return ["user_collection" => $allUsers];
    }

    private function getUsersById ($listOfUsers){
        $users = [];
        foreach ($listOfUsers as $singleUser){
            $singleUser = $this->em->find(User::class, $singleUser->getUserId());
            array_push($users,$singleUser);
        }
        return $users;
    }

    /*public function posts(User $user, Argument $args) :Connection
   {
       $posts = $user->getPosts();
       $paginator = new Paginator(function ($offset, $limit) use ($posts) {
           return array_slice($posts, $offset, $limit ?? 10);
       });
       return $paginator->auto($args, count($posts));
   }
*/
    public static function getAliases()
    {
        return [
            'resolve' => 'res'
        ];
    }


}