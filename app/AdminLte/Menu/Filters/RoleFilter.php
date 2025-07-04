<?php

namespace App\AdminLte\Menu\Filters;

use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
  public function transform($item)
  {
    if (!isset($item['role'])) {
      return $item;
    }

    $user = Auth::user();
    $userRole = optional($user->role)->slug;

    // Bisa juga if in_array(role, user roles) kalau multi-role
    if (!in_array($userRole, $item['role'])) {
      return false; // Hapus item
    }

    return $item;
  }
}
