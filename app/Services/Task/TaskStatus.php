<?php
namespace App\Services\Task;

// final class TaskStatus
// {
//     public const PENDING = 'pending';
//     public const IN_PROGRESS = 'in_progress';
//     public const NEEDS_REVISION = 'needs_revision';
//     public const APPROVED = 'approved';
//     public const REJECTED = 'rejected';
//     public const COMPLETED = 'completed';

//     // Puedes agregar un método para validar estados si quieres
//     public static function isValid(string $status): bool
//     {
//         return in_array($status, [
//             self::PENDING,
//             self::IN_PROGRESS,
//             self::NEEDS_REVISION,
//             self::APPROVED,
//             self::REJECTED,
//             self::COMPLETED,
//         ]);
//     }
// }
// este archivo es lo mismo que enums, se puede eliminar y usar directamente enums