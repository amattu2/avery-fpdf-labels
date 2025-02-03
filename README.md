# Introduction

This is a PHP project to implement support for generating [Avery](https://www.avery.com/templates) label templates using [FPDF](http://fpdf.org/).

# Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Then run

```console
$ composer require amattu2/avery-fpdf-labels
```

## Usage

See the [documentation](docs/index.md).

## Supported Templates

- Avery Rectangle Labels (1" x 2-5/8") (Use `Avery5160`)

  `5160`, `5260`, `5520`, `5620`, `5630`, `5660`, `5810`, `5960`, `5970`, `5971`, `5972`, `5979`, `5980`, `6240`, `6241`,
  `6460`, `6461`, `6476`, `6478`, `6479`, `6498`, `6521`, `6525`, `6526`, `6560`, `6585`, `6970`, `7660`, `7666`, `8160`,
  `8215`, `8250`, `8460`, `8620`, `8660`, `8810`, `8860`, `8920`, `9160`, `15160`, `15510`, `15660`, `15700`, `15960`,
  `16460`, `18160`, `18260`, `18660`, `22837`, `28660`, `32660`, `38260`, `45160`, `48160`, `48260`, `48360`, `48460`,
  `48860`, `48960`, `55160`, `55260`, `55360`, `58160`, `58260`, `58660`, `75160`, `80509`, `85560`, `88560`, `95915`,
  `Presta™ 94200`


- Avery Rectangle Labels (2" x 4") (Use `Avery5163`)

  `5163`, `5263`, `5523`, `5663`, `5784`, `5954`, `5956`, `5963`, `5964`, `5973`, `5973`, `5974`, `5976`, `5978`, `6427`,
  `6468`, `6477`, `6481`, `6522`, `6522`, `6527`, `6528`, `7663`, `8163`, `8253`, `8363`, `8463`, `8563`, `8663`, `8923`,
  `15163`, `15513`, `15563`, `15663`, `15702`, `18163`, `18663`, `18863`, `28663`, `38363`, `38863`, `48163`, `48263`,
  `48363`, `48463`, `48863`, `55163`, `55263`, `55363`, `55463`, `58163`, `58163`, `58263`, `60505`, `60525`, `85563`,
  `92102`, `95523`, `95910`, `95945`, `Presta™ 94207`


- Avery Name Badge Inserts (3" x 4") (Use `Avery5392`)

  `5384`, `5392`, `5393`, `74459`, `74536`, `74540`, `74541`, `78617`, `78619`


- Avery Print-to-the-Edge Square Labels (2") (Use `AveryPresta94107`)

  `22960`, `22806`, `22816`, `22853`, `22922`, `80510`, `22846`, `22930`, `22921`, `92114`, `Presta™ 94107`

### Upcoming

There are plans to support the following templates but if you have an urgent need for a specific template to be supported, open an issue.

- Avery 5162
- Avery 5195
- Avery 5816
- Avery 5817

Feel free to contribute to the development effort by submitting a pull request for any of the above templates or any other templates you would like to see supported.
