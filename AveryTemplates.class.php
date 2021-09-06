<?php
/*
  Produced 2019-2021
  By https://amattu.com/github
  Copy Alec M.
  License GNU Affero General Public License v3.0
*/

// Class namespace
namespace amattu;

// Exception Classes
class BadValueException extends \Exception {}
class InvalidStateException extends \Exception {}

/*
  Avery label interface
 */
interface LabelInterface {
  /**
   * Add a single label to the PDF
   *
   * @param array $lines an array of label liens
   * @param integer $row optional desired insert row
   * @param integer $col optional diesired intert column
   * @throws TypeError
   * @throws BadValueException
   * @author Alec M. <https://amattu.com>
   * @date 2021-09-05T13:49:28-040
   */
  public function add(array $lines, int $row = 0, int $col = 0) : void;

  /**
   * Build the completed PDF with labels
   *
   * NOTE:
   *   (1) To save resources, no PDF is built until
   *   this function is called.
   *
   * @return void
   * @throws InvalidStateException
   * @author Alec M. <https://amattu.com>
   * @date 2021-09-05T13:50:58-040
   */
  public function build() : void;
}

/*
 A Avery 5160 label PDF
 */
class Avery_5160 extends \FPDF implements LabelInterface {
  /**
   * A private flag for toggling debug options
   *
   * @var int|bool
   */
  protected const DEBUG = 1;

  /**
   * Represents current PDF state
   *
   * @var int
   */
  protected $open_state = 1;

  /**
   * Holds the labels
   *
   * @var array
   */
  protected $labels = Array();

  /**
   * PDF top margin
   *
   * @var int
   */
  protected $top = 13;

  /**
   * PDF left margin
   *
   * @var int
   */
  protected $left = 5;

  /**
   * Represents the PDF column width
   *
   * @var int
   */
  public const COLUMN_WIDTH = 66.5;

  /**
   * Represents the PDF maximum number of labels
   *
   * @var int
   */
  public const MAX_LABEL_LINES = 4;

  /**
   * PDF maximum number of columns
   *
   * @var int
   */
  public const COLUMNS = 3;

  /**
   * PDF maximum number of rows
   *
   * @var int
   */
  public const ROWS = 10;

  /**
   * {@inheritdoc}
   */
  public function add(array $lines, int $row = -1, int $col = -1) : void
  {
    // Checks
    if (empty($lines)) {
      throw new BadValueException("Cannot add a empty label to PDF");
    }
    if (count($lines) > Avery_5160::MAX_LABEL_LINES) {
      throw new BadValueException("Cannot add a label with more than {Avery_5160::MAX_LABEL_LINES} lines to PDF");
    }
    if ($row < -1 || ($row + 1) > Avery_5160::ROWS) {
      throw new BadValueException("Cannot add a label to that row");
    }
    if ($col < -1 || ($col + 1) > Avery_5160::COLUMNS) {
      throw new BadValueException("Cannot add a label to that column");
    }

    // Append
    $this->labels[] = Array(
      array_pad($lines, 4, " "),
      $row,
      $col
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() : void
  {
    // Checks
    if ($this->open_state !== 1) {
      throw new InvalidStateException("Attempt to build onto an existing PDF");
    }

    // FPDF Configuration
    $this->SetTopMargin($this->top);
    $this->SetAutoPageBreak(false);

    // Variables
    $bottom = $this->GetPageHeight() - $this->top;
    $right = $this->GetPageWidth() - $this->left;
    $config_row_height = (($bottom - $this->top) / Avery_5160::ROWS);
    $config_items_per_page = Avery_5160::ROWS * Avery_5160::COLUMNS;
    $current_row = 0;
    $current_col = 0;
    $current_page = 0;
    $current_item_count = 0;

    // Loop
    foreach ($this->labels as $item) {
      // Check page overflow
      if ($current_item_count++ > $config_items_per_page) {
        $this->AddPage("P", "Letter");
        $current_item_count = 1;
        $current_page++;
        $current_col = 0;
        $current_row = 0;
      }

      // Check row overflow
      if ($current_row >= Avery_5160::ROWS) {
        $current_col++;
        $current_row = 0;
      }

      // Check column overflow
      if ($current_col >= Avery_5160::COLUMNS) {
        $this->AddPage("P", "Letter");
        $current_item_count = 1;
        $current_page++;
        $current_col = 0;
        $current_row = 0;
      }

      // Check label position request
      if ($item[1] > Avery_5160::ROWS || $item[1] < 0) {
        $item[1] = $current_row++;
      }
      if ($item[2] > Avery_5160::COLUMNS || $item[2] < 0) {
        $item[2] = $current_col;
      }

      // Build Item
      $this->setY(($item[1] > 0 ? $this->top + ($config_row_height * $item[1]) + 2 : $this->top + 2));
      $this->setX(($item[2] > 0 ? $this->left + (Avery_5160::COLUMN_WIDTH * $item[2]) + (3 * $item[2]) : $this->left));
      $this->MultiCell(Avery_5160::COLUMN_WIDTH, ($config_row_height / 4.5), implode($item[0], "\n"), Avery_5160::DEBUG, "C");
    }

    // Close PDF
    $this->open_state = 0;
  }
}
