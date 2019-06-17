<?php
namespace App\GraphQL\Resolver;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

Class UserResolver implements ResolverInterface, AliasedInterface
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
    public function resolve(Argument $arg) :User
    {
        return $this->em->find(User::class, $arg['id']);
    }
    public function email(User $user) :string
    {
        return $user->getEmail();
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
            'resolve' => 'User'
        ];
    }


}