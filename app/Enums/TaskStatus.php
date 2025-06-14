<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';//util
    case IN_PROGRESS = 'in_progress';
    case SUBMITTED = 'submitted';//util
    case APPROVED = 'approved';//util
    case REJECTED = 'rejected'; //util
    case NEEDS_REVISION = 'needs_revision'; // util
    // case COMPLETED = 'completed';
        public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getTransitions(string $currentStatus): array
    {
        return match($currentStatus) {
            self::PENDING->value => [self::IN_PROGRESS->value],
            self::IN_PROGRESS->value => [self::SUBMITTED->value],
            self::SUBMITTED->value => [self::APPROVED->value, self::REJECTED->value, self::NEEDS_REVISION->value], // Añadido NEEDS_REVISION
            self::REJECTED->value => [self::IN_PROGRESS->value],
            self::APPROVED->value => [],
            self::NEEDS_REVISION->value => [self::IN_PROGRESS->value], // Añadido transición desde NEEDS_REVISION
            default => []
        };
    }

    public static function isValidTransition(string $from, string $to): bool
    {
        return in_array($to, self::getTransitions($from));
    }

    // Método para obtener un nombre legible (opcional, si no quieres usar ->name)
    public function name(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::IN_PROGRESS => 'En Progreso',
            self::SUBMITTED => 'Enviado para Revisión',
            self::APPROVED => 'Aprobado',
            self::REJECTED => 'Rechazado',
            self::NEEDS_REVISION => 'Necesita Revisión',
            // self::COMPLETED => 'Completado', // utilizado
        };
    }

}//✅ NO se conecta directamente a la base de datos. Es solo una representación lógica de los estados.