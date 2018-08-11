<?php
/**
 * Created by PhpStorm.
 * User: Fred
 * Date: 11/08/2018
 * Time: 21:18
 */

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;


class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException){
        $content = '<h1>Access denied</h1><h3>Your present login doesn\'t give you enough privileges</h3>';
        return new Response($content, 403);
    }

}