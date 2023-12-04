<?php

namespace App\Orchid\Layouts;

use App\Models\Image;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ImageLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = "images";

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make("Images")->render(function (Image $image) {
                $imagePath = $image->path ?? null;
                if ($imagePath) {
                    return "<img src='/storage/{$imagePath}' width='200' height='200' alt='Image'>";
                } else {
                    return "----------";
                }
            }),
        ];
    }
}
