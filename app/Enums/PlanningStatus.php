<?php

namespace App\Enums;

/**
 * Statuts utilisés dans planning_agents (strings d'entrée).
 */
enum PlanningStatus: string
{
  case PRESENT = 'present';
  case ABSENT = 'absent';
  case REPOS = 'repos';
  case PERMUTATION = 'permutation';
}
