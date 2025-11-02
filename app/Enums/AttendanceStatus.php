<?php

namespace App\Enums;

/**
 * Statuts stockés dans la table attendances (valeurs stables pour la compta).
 */
enum AttendanceStatus: string
{
  case WORKED = 'WORKED';
  case ABSENT = 'ABSENT';
  case REST = 'REST';
  case REPLACEMENT = 'REPLACEMENT';

  /**
   * Retourne l'AttendanceStatus correspondant à un statut planning (input).
   */
  public static function fromPlanningStatus(string $input): self
  {
    return match (strtolower($input)) {
      'present' => self::WORKED,
      'repos' => self::REST,
      'absent' => self::ABSENT,
      'permutation' => self::REPLACEMENT,
      default => self::WORKED,
    };
  }
}
