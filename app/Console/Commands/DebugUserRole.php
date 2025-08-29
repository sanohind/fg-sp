<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class DebugUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:user-role {username?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug user role information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');
        
        if ($username) {
            $user = User::where('username', $username)->first();
            if (!$user) {
                $this->error("User dengan username '$username' tidak ditemukan");
                return 1;
            }
            $this->debugUser($user);
        } else {
            $this->info("=== Debug Semua User ===");
            $users = User::with('role')->get();
            foreach ($users as $user) {
                $this->debugUser($user);
                $this->line('');
            }
        }
        
        $this->info("=== Debug Roles ===");
        $roles = Role::all();
        foreach ($roles as $role) {
            $this->line("Role ID: {$role->id}, Name: '{$role->role_name}'");
        }
        
        return 0;
    }
    
    private function debugUser(User $user)
    {
        $this->line("=== User: {$user->username} ===");
        $this->line("User ID: {$user->id}");
        $this->line("Username: {$user->username}");
        $this->line("Name: {$user->name}");
        $this->line("Role ID: {$user->role_id}");
        $this->line("Role Loaded: " . ($user->relationLoaded('role') ? 'Yes' : 'No'));
        
        if ($user->role) {
            $this->line("Role Name: '{$user->role->role_name}'");
            $this->line("Role ID from Relationship: {$user->role->id}");
        } else {
            $this->line("Role: NULL");
        }
        
        // Check direct database query
        $directRole = Role::find($user->role_id);
        if ($directRole) {
            $this->line("Direct DB Role: '{$directRole->role_name}'");
        } else {
            $this->line("Direct DB Role: NULL");
        }
    }
}
