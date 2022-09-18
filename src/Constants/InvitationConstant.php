<?php
namespace App\Constants;

final class InvitationConstant
{
  public const PENDING = 'pending';
  public const ACCEPTED = 'accepted';
  public const DECLINED = 'declined';
  public const CANCELED = 'canceled';

  public const ALLOW = [
    self::ACCEPTED,
    self::DECLINED,
  ];
}
