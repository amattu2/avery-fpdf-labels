<?php

namespace Avery;

/**
 * Label Type Interface
 */
interface LabelInterface {
  /**
   * LabelInterface constructor
   *
   * @param int|null $row
   * @param int|null $col
   * @param mixed ...$args
   */
  // public function __construct(int $row, int $col, ...$args);

  /**
   * Render the Label
   *
   * @param TCPDF|FPDF|FPDI|Fpdf\Fpdf $pdf to render to
   * @param array $dimensions [x1, y1, w, h, template]
   */
  public function render($pdf, array $dimensions) : bool;

  /**
   * Get the requested placement row
   *
   * @return integer|null $row
   */
  public function GetRow() : ?int;

  /**
   * Get the requested placement column
   *
   * @return integer|null $col
   */
  public function GetCol() : ?int;
}
