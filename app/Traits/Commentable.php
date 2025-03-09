<?php

namespace App\Traits;

trait Commentable
{

  public function commentsCount()
  {
    $comments = $this->comments;
    $count = count($comments);
    foreach ($comments as $nestetComment) {
      $count += count($nestetComment->comments);
      foreach ($nestetComment->comments as $lastComment)
        $count += count($lastComment->comments);
    }
    return $count;
  }
}
