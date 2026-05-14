<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'matricola_fk', 'codice_cliente_fk', 'password_changed'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Relazioni Legacy ─────────────────────────────────────

    public function dipendente()
    {
        return $this->belongsTo(Dipendente::class, 'matricola_fk', 'Matricola');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'codice_cliente_fk', 'CodiceCliente');
    }

    // ── Helper Ruoli ─────────────────────────────────────────

    // ── Helper Ruoli Basati su Reparto ────────────────────────
    
    public function getEffectiveRoleAttribute(): string
    {
        // Se l'utente è il SuperAdmin, permettiamo al Role Switcher di comandare
        if ($this->email === 'admin@azienda.it') {
            return $this->role;
        }

        $dipendente = $this->dipendente;
        if (!$dipendente) return $this->role; // Fallback legacy

        return match ($dipendente->IDReparto_FK) {
            5 => 'admin',
            6 => 'sales',
            4 => 'logistics',
            default => 'customer',
        };
    }

    public function getRoleLevelAttribute(): int
    {
        $dipendente = $this->dipendente;
        if (!$dipendente) return 1;

        return $dipendente->ruolo ? (int)$dipendente->ruolo->Livello : 1;
    }

    public function isManager(): bool
    {
        $dipendente = $this->dipendente;
        if (!$dipendente) return false;

        // È manager se è responsabile ufficiale del reparto OPPURE se ha un ruolo di livello alto (>= 3)
        $reparto = $dipendente->reparto;
        $isResponsabile = $reparto && $reparto->IDResponsabile_FK === $dipendente->Matricola;
        
        return $isResponsabile || $this->role_level >= 3;
    }

    public function isAdmin(): bool
    {
        return $this->effective_role === 'admin';
    }

    public function isSales(): bool
    {
        return $this->effective_role === 'sales';
    }

    public function isLogistics(): bool
    {
        return $this->effective_role === 'logistics';
    }

    public function isStaff(): bool
    {
        return in_array($this->effective_role, ['logistics', 'sales', 'manager']);
    }



    public function isCustomer(): bool
    {
        return $this->effective_role === 'customer';
    }
}
