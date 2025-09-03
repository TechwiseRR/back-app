<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:admin-role {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test la logique de vérification du rôle admin pour un utilisateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Utilisateur avec l'email {$email} non trouvé");
            return 1;
        }
        
        $this->info("=== Informations de l'utilisateur ===");
        $this->info("ID: {$user->id}");
        $this->info("Email: {$user->email}");
        $this->info("Username: {$user->username}");
        $this->info("roleId: {$user->roleId}");
        
        if ($user->role) {
            $this->info("=== Informations du rôle ===");
            $this->info("Role ID: {$user->role->id}");
            $this->info("Role Name: {$user->role->roleName}");
            $this->info("Role Rank: {$user->role->rank}");
            $this->info("Permission ID: {$user->role->permissionId}");
            
            $this->info("=== Test isAdmin() ===");
            $isAdmin = $user->isAdmin();
            $this->info("isAdmin(): " . ($isAdmin ? 'true' : 'false'));
        } else {
            $this->error("Aucun rôle trouvé pour cet utilisateur");
        }
        
        return 0;
    }
} 