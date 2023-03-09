<?php

namespace Avery;

class ImageLabel implements LabelInterface
{
  private $row;
  private $col;
  private $src;

  public function __construct(int $row, int $col, string $src)
  {
    $this->row = $row;
    $this->col = $col;
    $this->src = $src;
  }

  public function render($pdf, array $dimensions): bool
  {
    [$x, $y, $w, $h] = $dimensions;

    $pdf->Image($this->src, $x, $y, $w, $h);

    return true;
  }

  public function GetRow(): int
  {
    return $this->row;
  }

  public function GetCol(): int
  {
    return $this->col;
  }
}
