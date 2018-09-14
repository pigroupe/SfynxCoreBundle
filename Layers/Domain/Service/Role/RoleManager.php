<?php
/**
 * This file is part of the <Core> project.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Service\Role
 * @since 2012-02-03
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\CoreBundle\Layers\Domain\Service\Role;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Sfynx\AuthBundle\Domain\Service\Role\Generalisation\RoleFactoryInterface;

/**
 * role factory.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Service\Role
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RoleManager
{
    /**
     * Gets all no authorize roles of an heritage of roles.
     * @param array $heritage
     * @param array $allRoles
     * @return array
     */
    public static function getNoAuthorizeRolesFromUser(array $heritage, array $allRoles): array
    {
        if ( (null === $heritage) || (\count($heritage) === 0) ) {
            return null;
        }
        if (($key = \array_search('ROLE_ALLOWED_TO_SWITCH', $allRoles)) !== false) {
            unset($allRoles[$key]);
        }
        $allRoles_authorized = \array_merge($heritage, self::getAllHeritageByRoles($allRoles, $heritage));
        $allRoles_no_authorized = \array_diff($allRoles, $allRoles_authorized);

        if ( (\count($allRoles_authorized) == 0)
            && (\count($allRoles_no_authorized) == 0)
        ) {
            return null;
        }

        $allRoles_authorized = \array_map(function($value) {
            return "is_granted('{$value}')";
        }, \array_values($allRoles_authorized));

        $script_or = \implode(' or ', $allRoles_authorized);

        $allRoles_no_authorized = \array_map(function($value) {
            return "not is_granted('{$value}')";
        }, \array_values($allRoles_no_authorized));
        $script_and = \implode(' and ', $allRoles_no_authorized);

        if (!empty($script_or) && !empty($script_and)) {
            $twig_if = "{{ \" {% if ({$script_or}) and $script_and  %} \" }}\n";
        } elseif (empty($script_or) && !empty($script_and)) {
            $twig_if = "{{ \" {% if $script_and  %} \" }}\n";
        } elseif (!empty($script_or) && empty($script_and)) {
            $twig_if = "{{ \" {% if ({$script_or}) %} \" }}\n";
        }
        $twig_endif = "{{ \" {% endif %}  \" }} \n";

        return [
            'autorized' => $allRoles_authorized,
            'no_authorized' => $allRoles_no_authorized,
            'script_or' => $script_or,
            'script_and' => $script_and,
            'twig_if' => $twig_if,
            'twig_endif' => $twig_endif
        ];
    }

    /**
     * @param array $hierarchy
     * @param array $userRoles
     * @return array
     */
    public static function getAllUserRoles(array $hierarchy, array $userRoles)
    {
        return \array_unique(\array_merge(
            self::getAllHeritageByRoles($hierarchy, self::getBestRoles($hierarchy, $userRoles)),
            $userRoles
        ));
    }

    /**
     * Gets the best role of all user roles.
     * @param array $hierarchy
     * @param array $rolesUser
     * @return mixed
     */
    public static function getUserBestRoles(array $hierarchy, array $rolesUser)
    {
        // we get the map of all roles.
        $roleMap = self::buildRoleMap($hierarchy);
        foreach ($roleMap as $role => $heritage) {
            if (\in_array($role, $rolesUser)) {
                $intersect = \array_intersect($heritage, $rolesUser);
                $rolesUser = \array_diff($rolesUser, $intersect);  // =  $rolesUser -  $intersect
            }
        }
        return \end($rolesUser);
    }

    /**
     * Gets the best roles of many of roles.
     * @param array $hierarchy
     * @param array|null $roles
     * @return array|null
     */
    public static function getBestRoles(array $hierarchy, ?array $roles)
    {
        if (null === $roles) {
            return null;
        }
        // we get the map of all roles.
        $roleMap = self::buildRoleMap($hierarchy);
        foreach ($roleMap as $role => $heritage) {
            if (\in_array($role, $roles)){
                $intersect = \array_intersect($heritage, $roles);
                $roles = \array_diff($roles, $intersect);  // =  $roles -  $intersect
            }
        }
        return $roles;
    }

    /**
     * Gets all heritage roles of many of roles.
     * @param array $hierarchy
     * @param array|null $roles
     * @return array|null
     */
    public static function getAllHeritageByRoles(array $hierarchy, ?array $roles)
    {
        if (null === $roles) {
            return null;
        }
        $results = [];
        // we get the map of all roles.
        $roleMap = self::buildRoleMap($hierarchy);
        foreach ($roles as $key => $role) {
            if (isset($roleMap[$role])) {
                $results = \array_unique(\array_merge($results, $roleMap[$role]));
            }
        }
        return $results;
    }

    /**
     * Sets the map of all roles.
     * @param array $hierarchy
     * @return array
     */
    public static function buildRoleMap(array $hierarchy)
    {
        $map = [];
        foreach ($hierarchy as $main => $roles) {
            $map[$main] = $roles;
            $visited = [];
            $additionalRoles = $roles;
            while ($role = \array_shift($additionalRoles)) {
                if (!isset($hierarchy[$role])) {
                    continue;
                }
                $visited[] = $role;
                $map[$main] = \array_unique(array_merge($map[$main], $hierarchy[$role]));
                $additionalRoles = \array_merge($additionalRoles, \array_diff($hierarchy[$role], $visited));
            }
            if (($key = \array_search($main, $map[$main])) !== false) {
                unset($map[$main][$key]);
            }
        }
        return $map;
    }
}
