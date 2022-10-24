<?php

namespace App\Helpers;

use JetBrains\PhpStorm\Pure;

class Color
{
    protected readonly string $original;

    public function __construct(protected $color)
    {
        $this->original = $color;
    }

    #[Pure]
    public static function make($color): self {
        return new self($color);
    }

    public function get()
    {
        return $this->color;
    }

    public function getOriginal() : string
    {
        return $this->original;
    }

    public function lighten(int $by = 50) : self
    {
        $adjustPercent = $by / 100;

        $hexCode = ltrim($this->color, "#");

        if (strlen($hexCode) == 3) {
            $hexCode =
                $hexCode[0] .
                $hexCode[0] .
                $hexCode[1] .
                $hexCode[1] .
                $hexCode[2] .
                $hexCode[2];
        }

        $hexCode = array_map("hexdec", str_split($hexCode, 2));

        foreach ($hexCode as &$color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount = ceil($adjustableLimit * $adjustPercent);

            $color = str_pad(
                dechex($color + $adjustAmount),
                2,
                "0",
                STR_PAD_LEFT,
            );
        }

        return self::make("#" . implode($hexCode));
    }

    public function darken(int $by = 50) : self
    {
        return $this->lighten(-$by);
    }

    #[Pure]
    public function __toString(): string
    {
        return $this->get();
    }
}
